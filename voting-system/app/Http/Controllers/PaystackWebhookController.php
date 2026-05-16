<?php

namespace App\Http\Controllers;

use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaystackWebhookController extends Controller
{
    public function __construct(
        protected PaystackService $paystackService,
    ) {}

    /**
     * Handle Paystack webhook events.
     *
     * @see https://paystack.com/docs/payments/webhooks
     */
    public function handle(Request $request)
    {
        // Get the raw payload and signature
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature', '');

        // Validate webhook signature — CRITICAL security check
        if (!$this->paystackService->validateWebhookSignature($payload, $signature)) {
            Log::warning('Paystack webhook: Invalid signature');
            return response('Invalid signature', 401);
        }

        // Decode the event
        $event = json_decode($payload, true);

        if (!$event) {
            Log::warning('Paystack webhook: Invalid JSON payload');
            return response('Invalid payload', 400);
        }

        // Return 200 immediately as Paystack recommends, then process
        // In production with queues, you'd dispatch a job here
        $this->paystackService->processWebhookEvent($event);

        return response('OK', 200);
    }
}
