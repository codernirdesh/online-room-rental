<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EsewaPaymentService
{
    protected string $merchantCode;
    protected string $baseUrl;

    public function __construct()
    {
        // Prefer settings from DB, fallback to config
        $this->merchantCode = \App\Models\Setting::get('esewa_merchant_code') ?? config('esewa.merchant_code', 'EPAYTEST');
        $environment = \App\Models\Setting::get('esewa_environment') ?? config('esewa.environment', 'testing');
        $this->baseUrl = config("esewa.urls.{$environment}");
    }

    /**
     * Get the eSewa payment form URL.
     */
    public function getPaymentUrl(): string
    {
        return $this->baseUrl . '/api/epay/main/v2/form';
    }

    /**
     * Build the payment form data for eSewa.
     */
    public function buildPaymentData(float $amount, string $transactionUuid, string $productCode = null): array
    {
        $productCode = $productCode ?? $this->merchantCode;

        $signatureData = "total_amount={$amount},transaction_uuid={$transactionUuid},product_code={$productCode}";
        $signature = $this->generateSignature($signatureData);

        return [
            'amount' => $amount,
            'tax_amount' => 0,
            'total_amount' => $amount,
            'transaction_uuid' => $transactionUuid,
            'product_code' => $productCode,
            'product_service_charge' => 0,
            'product_delivery_charge' => 0,
            'success_url' => url(config('esewa.success_url')),
            'failure_url' => url(config('esewa.failure_url')),
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
            'signature' => $signature,
        ];
    }

    /**
     * Verify the eSewa payment response.
     */
    public function verifyPayment(string $encodedData): ?array
    {
        try {
            $decodedData = json_decode(base64_decode($encodedData), true);

            if (!$decodedData) {
                Log::error('eSewa: Failed to decode payment response data');
                return null;
            }

            Log::info('eSewa: Payment response decoded', $decodedData);

            // Verify the transaction status via eSewa's status check API
            $statusUrl = $this->baseUrl . '/api/epay/transaction/status/';

            $response = Http::get($statusUrl, [
                'product_code' => $decodedData['product_code'] ?? $this->merchantCode,
                'total_amount' => $decodedData['total_amount'] ?? 0,
                'transaction_uuid' => $decodedData['transaction_uuid'] ?? '',
            ]);

            if ($response->successful()) {
                $statusData = $response->json();
                Log::info('eSewa: Transaction status response', $statusData);

                if (isset($statusData['status']) && $statusData['status'] === 'COMPLETE') {
                    return [
                        'transaction_code' => $decodedData['transaction_code'] ?? null,
                        'transaction_uuid' => $decodedData['transaction_uuid'] ?? null,
                        'total_amount' => $decodedData['total_amount'] ?? null,
                        'product_code' => $decodedData['product_code'] ?? null,
                        'status' => $statusData['status'],
                        'ref_id' => $statusData['ref_id'] ?? null,
                    ];
                }
            }

            Log::warning('eSewa: Transaction verification failed', [
                'decoded_data' => $decodedData,
                'status_response' => $response->json() ?? null,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('eSewa: Payment verification exception', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Generate HMAC SHA256 signature for eSewa.
     */
    protected function generateSignature(string $data): string
    {
        $environment = \App\Models\Setting::get('esewa_environment') ?? config('esewa.environment', 'testing');

        // For testing environment, eSewa uses a known test secret key
        if ($environment === 'testing') {
            $secret = '8gBm/:&EnhH.1/q';
        } else {
            $secret = $this->merchantCode;
        }

        $hash = hash_hmac('sha256', $data, $secret, true);
        return base64_encode($hash);
    }

    /**
     * Check if eSewa payments are enabled in settings.
     */
    public static function isEnabled(): bool
    {
        return \App\Models\Setting::get('esewa_enabled') === '1';
    }
}
