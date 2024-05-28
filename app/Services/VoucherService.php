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

    public function reduceUsed()
    {
        if (! $this->voucher) {
            throw new Exception('You can\'t use calculate before assign the voucher code');
        }

        $this->voucher->update([
            'kuota' => $this->voucher->kuota - 1,
        ]);
    }
}
