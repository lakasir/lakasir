<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QrisService
{
    private string $baseUrl = 'https://qris.interactive.co.id/restapi/qris/';
    private ?string $apiKey;
    private ?int $mId;

    public function __construct()
    {
        $this->apiKey = config('services.qris.api_key');
        $this->mId = config('services.qris.m_id');
    }

    /**
     * Check if QRIS service is properly configured
     *
     * @return bool
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->mId);
    }

    /**
     * Create a QRIS invoice
     *
     * @param string $transactionNumber
     * @param int $amount
     * @param string $useTip
     * @return array|null
     */
    public function createInvoice(string $transactionNumber, int $amount, string $useTip = 'no'): ?array
    {
        if (!$this->apiKey || !$this->mId) {
            Log::error('QRIS API credentials not configured');
            return null;
        }

        try {
            $response = Http::get($this->baseUrl . 'show_qris.php', [
                'do' => 'create-invoice',
                'apikey' => $this->apiKey,
                'mID' => $this->mId,
                'cliTrxNumber' => $transactionNumber,
                'cliTrxAmount' => $amount,
                'useTip' => $useTip,
            ]);

            if (!$response->successful()) {
                Log::error('QRIS Create Invoice API failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();

            if ($data['status'] === 'success') {
                return $data['data'];
            }

            Log::warning('QRIS Create Invoice failed', ['response' => $data]);
            return null;

        } catch (\Exception $e) {
            Log::error('QRIS Create Invoice exception', [
                'message' => $e->getMessage(),
                'transactionNumber' => $transactionNumber,
                'amount' => $amount,
            ]);
            return null;
        }
    }

    /**
     * Check QRIS invoice payment status
     *
     * @param int $invoiceId
     * @param int $amount
     * @param string $transactionDate
     * @return array|null
     */
    public function checkInvoiceStatus(int $invoiceId, int $amount, string $transactionDate): ?array
    {
        if (!$this->apiKey || !$this->mId) {
            Log::error('QRIS API credentials not configured');
            return null;
        }

        try {
            $response = Http::get($this->baseUrl . 'checkpaid_qris.php', [
                'do' => 'checkStatus',
                'apikey' => $this->apiKey,
                'mID' => $this->mId,
                'invid' => $invoiceId,
                'trxvalue' => $amount,
                'trxdate' => $transactionDate,
            ]);

            if (!$response->successful()) {
                Log::error('QRIS Check Status API failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }

            $data = $response->json();

            if ($data['status'] === 'success') {
                return $data['data'];
            }

            Log::warning('QRIS Check Status failed', ['response' => $data]);
            return null;

        } catch (\Exception $e) {
            Log::error('QRIS Check Status exception', [
                'message' => $e->getMessage(),
                'invoiceId' => $invoiceId,
                'amount' => $amount,
            ]);
            return null;
        }
    }
}
