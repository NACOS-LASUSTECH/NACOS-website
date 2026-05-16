<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoteRequest;
use App\Models\Candidate;
use App\Models\Setting;
use App\Services\KorapayService;
use App\Services\PaystackService;
use App\Services\VotingService;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function __construct(
        protected VotingService $votingService,
        protected PaystackService $paystackService,
        protected KorapayService $korapayService,
    ) {}

    /**
     * Store a new vote and redirect to payment.
     */
    public function store(StoreVoteRequest $request)
    {
        $validated = $request->validated();
        $reference = $this->votingService->generateReference();

        $transaction = $this->votingService->createTransaction([
            'candidate_id' => $validated['candidate_id'],
            'reference' => $reference,
            'voter_name' => $validated['voter_name'],
            'voter_email' => $validated['voter_email'],
            'votes' => $validated['votes'],
            'payment_method' => $validated['payment_method'],
        ]);

        if (in_array($validated['payment_method'], ['paystack', 'korapay'], true)) {
            return $this->handleOnlinePayment($transaction, $validated);
        }

        // Bank transfer — show bank details page
        return redirect()->route('vote.bank-transfer', $transaction->reference);
    }

    /**
     * Handle Paystack payment initialization.
     */
    protected function handleOnlinePayment($transaction, $validated)
    {
        $paymentData = null;

        if ($transaction->payment_method === 'paystack') {
            $paymentData = $this->paystackService->initializeTransaction([
                'email' => $validated['voter_email'],
                'amount' => $transaction->amount,
                'reference' => $transaction->reference,
                'callback_url' => route('vote.callback'),
                'metadata' => [
                    'candidate_id' => $transaction->candidate_id,
                    'voter_name' => $validated['voter_name'],
                    'votes' => $transaction->votes,
                ],
            ]);
        }

        if ($transaction->payment_method === 'korapay') {
            $paymentData = $this->korapayService->initializeTransaction([
                'email' => $validated['voter_email'],
                'name' => $validated['voter_name'],
                'amount' => $transaction->amount,
                'reference' => $transaction->reference,
                'callback_url' => route('vote.callback'),
                'metadata' => [
                    'candidate_id' => $transaction->candidate_id,
                    'voter_name' => $validated['voter_name'],
                    'votes' => $transaction->votes,
                ],
            ]);
        }

        if (!$paymentData) {
            return back()->with('error', 'Unable to initialize payment. Please try again.');
        }

        $redirectUrl = $paymentData['authorization_url'] ?? $paymentData['checkout_url'] ?? null;
        if (!$redirectUrl) {
            return back()->with('error', 'Unable to initialize payment. Please try again.');
        }

        return redirect($redirectUrl);
    }

    /**
     * Handle online payment callback — verify payment from backend.
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('home')->with('error', 'Invalid payment reference.');
        }

        $transaction = \App\Models\VoteTransaction::where('reference', $reference)->first();

        if (!$transaction) {
            return redirect()->route('home')->with('error', 'Invalid payment reference.');
        }

        if ($transaction->payment_method === 'paystack') {
            // ALWAYS verify from backend — never trust frontend
            $paymentData = $this->paystackService->verifyTransaction($reference);

            if (!$paymentData || $paymentData['status'] !== 'success') {
                return redirect()->route('vote.failed')->with('error', 'Payment verification failed.');
            }

            if (!$transaction->isProcessed()) {
                $transaction->update([
                    'payment_provider_reference' => $paymentData['id'] ?? null,
                ]);

                $this->votingService->processSuccessfulPayment($transaction);
            }

            return redirect()->route('vote.success', $reference);
        }

        if ($transaction->payment_method === 'korapay') {
            $paymentData = $this->korapayService->verifyTransaction($reference);

            $status = $paymentData['status'] ?? null;
            if (!$paymentData || $status !== 'success') {
                return redirect()->route('vote.failed')->with('error', 'Payment verification failed.');
            }

            if (!$transaction->isProcessed()) {
                $transaction->update([
                    'payment_provider_reference' => $paymentData['id'] ?? null,
                ]);

                $this->votingService->processSuccessfulPayment($transaction);
            }

            return redirect()->route('vote.success', $reference);
        }

        return redirect()->route('vote.pending', $reference)
            ->with('success', 'Payment received. Confirmation will complete shortly.');
    }

    /**
     * Show bank transfer instructions page.
     */
    public function bankTransfer(string $reference)
    {
        $transaction = \App\Models\VoteTransaction::where('reference', $reference)
            ->with('candidate.category')
            ->firstOrFail();

        return view('vote.bank-transfer', compact('transaction'));
    }

    /**
     * Upload bank transfer receipt.
     */
    public function uploadReceipt(Request $request, string $reference)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $transaction = \App\Models\VoteTransaction::where('reference', $reference)->firstOrFail();

        $path = $request->file('receipt')->store('receipts', 'public');
        $this->votingService->uploadReceipt($transaction, $path);

        return redirect()->route('vote.pending', $reference)
            ->with('success', 'Receipt uploaded successfully. Your payment will be verified shortly.');
    }

    /**
     * Show payment success page.
     */
    public function success(string $reference)
    {
        $transaction = \App\Models\VoteTransaction::where('reference', $reference)
            ->with('candidate.category')
            ->firstOrFail();

        return view('vote.success', compact('transaction'));
    }

    /**
     * Show pending verification page.
     */
    public function pending(string $reference)
    {
        $transaction = \App\Models\VoteTransaction::where('reference', $reference)
            ->with('candidate.category')
            ->firstOrFail();

        return view('vote.pending', compact('transaction'));
    }

    /**
     * Show payment failed page.
     */
    public function failed()
    {
        return view('vote.failed');
    }

    /**
     * API: Calculate vote amount.
     */
    public function calculateAmount(Request $request)
    {
        $votes = (int) $request->input('votes', 1);
        $amount = $this->votingService->calculateAmountNaira($votes);

        return response()->json([
            'votes' => $votes,
            'amount' => $amount,
            'formatted' => '₦' . number_format($amount, 2),
        ]);
    }
}
