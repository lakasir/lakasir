<?php

namespace App\Models\Tenants;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrisTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_method_id',
        'qris_invoice_id',
        'qris_content',
        'qris_request_date',
        'qris_nmid',
        'amount',
        'transaction_number',
        'status',
        'cart_data',
        'expires_at',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'expires_at' => 'datetime',
        'qris_request_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }

    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }
}
