<?php

namespace App\Services;

use App\Models\Tenants\Voucher;
use Exception;

class VoucherService
{
    public Voucher $voucher;

    private float $price;

    public function applyable(string $code, float $price): ?VoucherService
    {
        $today = today();
        /** @var Voucher $voucher */
        $voucher = Voucher::whereCode($code)
            ->first();
        if (! $voucher) {
            return null;
        }
        if ($voucher?->minimal_buying <= $price && $today->gte($voucher->start_date) && $today->lte($voucher->expired) && $voucher->kuota > 0) {
            $this->price = $price;
            $this->voucher = $voucher;

            return $this;
        }

        return null;
    }

    /**
     * Why the voucher cannot be applied to this price, or null when it can.
     * Mirrors the conditions in applyable().
     */
    public function rejectionReason(string $code, float $price): ?string
    {
        /** @var ?Voucher $voucher */
        $voucher = Voucher::whereCode($code)->first();
        $today = today();

        return match (true) {
            ! $voucher => __('Voucher not found.'),
            $today->lt($voucher->start_date) => __('Voucher is not active yet.'),
            $today->gt($voucher->expired) => __('Voucher has expired.'),
            $voucher->kuota <= 0 => __('Voucher quota is used up.'),
            $voucher->minimal_buying > $price => __('Voucher needs a minimum purchase of :amount.', [
                'amount' => price_format($voucher->minimal_buying),
            ]),
            default => null,
        };
    }

    public function calculate(): float
    {
        if (! $this->voucher) {
            throw new Exception('You can\'t use calculate before assign the voucher code');
        }
        $discount = 0;
        if ($this->voucher->type == 'percentage') {
            $discount = ($this->price * $this->voucher->nominal / 100);
        }

        if ($this->voucher->type == 'flat') {
            $discount = $this->voucher->nominal;
        }

        return $discount;
    }

    /**
     * Use up one of the voucher's quota. The decrement is a single conditional
     * UPDATE, so two cashiers selling the last voucher at the same time cannot
     * both succeed. Returns false when the quota is already gone.
     */
    public function reduceUsed(): bool
    {
        if (! $this->voucher) {
            throw new Exception('You can\'t use calculate before assign the voucher code');
        }

        $affected = Voucher::whereKey($this->voucher->id)
            ->where('kuota', '>', 0)
            ->decrement('kuota');

        $this->voucher->refresh();

        return $affected > 0;
    }
}
