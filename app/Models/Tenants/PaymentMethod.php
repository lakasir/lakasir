<?php

namespace App\Models\Tenants;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperPaymentMethod
 */
class PaymentMethod extends Model
{
    use BelongsToCompany, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'is_cash' => 'boolean',
        'is_debit' => 'boolean',
        'is_credit' => 'boolean',
        'is_wallet' => 'boolean',
        'is_qris' => 'boolean',
    ];

    protected $appends = ['type', 'qris_image_url'];

    public const TYPE_CASH = 'cash';

    public const TYPE_QR = 'qr';

    public const TYPE_OTHER = 'other';

    public function icon(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => config('app.url').'/'.$value,
        );
    }

    /**
     * How a POS client should take this payment: cash (amount + change),
     * qr (show the merchant QRIS image) or other (simple confirmation).
     */
    public function type(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if ($this->is_qris) {
                    return self::TYPE_QR;
                }

                if ($this->is_cash && ! $this->is_credit && ! $this->is_debit && ! $this->is_wallet) {
                    return self::TYPE_CASH;
                }

                return self::TYPE_OTHER;
            },
        );
    }

    public function qrisImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->qris_image
                ? Storage::disk(config('filesystems.upload_disk'))->url($this->qris_image)
                : null,
        );
    }
}
