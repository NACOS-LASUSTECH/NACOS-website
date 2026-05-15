<?php

namespace App\Services;

use App\Models\VoteTransaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    protected string $secretKey;
    protected string $publicKey;
    protected string $paymentUrl;

    public function __construct()
    {
        $this->secretKey = config('paystack.secret_key');
        $this->publicKey = config('paystack.public_key');
        $this->paymentUrl = config('paystack.payment_url');
    }

    /**
     * Initialize a Paystack transaction.
     *
     * @param array $data [email, amount (kobo), reference, callback_url, metadata]
     * @return array|null Authorization URL data or null on failure
     */
    public function initializeTransaction(array $data): ?array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->paymentUrl}/transaction/initialize", [
                    'email' => $data['email'],
                    'amount' => $data['amount'], // Amount in kobo
                    'reference' => $data['reference'],
                    'callback_url' => $data['callback_url'],
                    'metadata' => $data['metadata'] ?? [],
                    'currency' => 'NGN',
                ]);

            $result = $response->json();

            if ($result['status'] ?? false) {
                return $result['data'];
            }

            Log::error('Paystack initialization failed', ['response' => $result]);
            return null;

        } catch (\Exception $e) {
            Log::error('Paystack initialization exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Verify a Paystack transaction by reference.
     * NEVER trust frontend — always verify from backend.
     *
     * @see https://paystack.com/docs/payments/verify-payments/
     */
    public function verifyTransaction(string $reference): ?array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->paymentUrl}/transaction/verify/{$reference}");

            $result = $response->json();

            if ($result['status'] ?? false) {
                return $result['data'];
            }

            Log::error('Paystack verification failed', [
                'reference' => $reference,
                'response' => $result,
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('Paystack verification exception', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Validate Paystack webhook signature.
     * Uses HMAC SHA-512 as per Paystack docs.
     *
     * @see https://paystack.com/docs/payments/webhooks
     */
    public function validateWebhookSignature(string $payload, string $signature): bool
    {
        $computedSignature = hash_hmac('sha512', $payload, $this->secretKey);
        return hash_equals($computedSignature, $signature);
    }

    /**
     * Get the public key for frontend usage.
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * Process a webhook event payload.
     *
     * @return bool Whether the event was processed successfully
     */
    public function processWebhookEvent(array $event): bool
    {
        $eventType = $event['event'] ?? null;

        if ($eventType !== 'charge.success') {
            Log::info('Paystack webhook: ignoring event type', ['type' => $eventType]);
            return true; // Not an error, just not relevant
        }

        $data = $event['data'] ?? [];
        $reference = $data['reference'] ?? null;

        if (!$reference) {
            Log::warning('Paystack webhook: missing reference');
            return false;
        }

        // Find the transaction
        $transaction = VoteTransaction::where('reference', $reference)->first();

        if (!$transaction) {
            Log::warning('Paystack webhook: transaction not found', ['reference' => $reference]);
            return false;
        }

        // Idempotency: skip if already processed
        if ($transaction->isProcessed()) {
            Log::info('Paystack webhook: transaction already processed', ['reference' => $reference]);
            return true;
        }

        // Verify the amount matches
        $paidAmount = $data['amount'] ?? 0;
        if ((int) $paidAmount !== (int) $transaction->amount) {
            Log::warning('Paystack webhook: amount mismatch', [
                'reference' => $reference,
                'expected' => $transaction->amount,
                'received' => $paidAmount,
            ]);
            return false;
        }

        // Update provider reference
        $transaction->update([
            'payment_provider_reference' => $data['id'] ?? null,
        ]);

        // Process the payment (add votes)
        $votingService = app(VotingService::class);
        return $votingService->processSuccessfulPayment($transaction);
    }
}
