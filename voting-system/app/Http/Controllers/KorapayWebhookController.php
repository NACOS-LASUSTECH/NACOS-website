<?php

namespace App\Http\Controllers;

use App\Services\KorapayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KorapayWebhookController extends Controller
{
    public function __construct(
        protected KorapayService $korapayService,
    ) {}

    /**
     * Handle Korapay webhook events.
     */
    public function handle(Request $request)
    {
        $rawPayload = $request->getContent();
        $payload = json_decode($rawPayload, true);
        $signature = $request->header('x-korapay-signature', '');

        if (!$this->korapayService->validateWebhookSignature($payload, $signature)) {
            Log::warning('Korapay webhook: Invalid signature');
            return response('Invalid signature', 401);
        }

        if (empty($payload)) {
            Log::warning('Korapay webhook: Invalid payload');
            return response('Invalid payload', 400);
        }

        $this->korapayService->processWebhookEvent($payload);

        return response('OK', 200);
    }
}
