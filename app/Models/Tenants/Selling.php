<?php

namespace App\Models\Tenants;

use App\Traits\UseTimezoneAwareQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $member_id
 * @property string|null $customer_number
 * @property int|null $cash_drawer_id
 * @property string|null $note
 * @property float $fee
 * @property string $date
 * @property string $code
 * @property float $payed_money
 * @property float $money_changes
 * @property float $total_price
 * @property string $tax_price
 * @property float|null $total_cost
 * @property int $friend_price
 * @property int|null $payment_method_id
 * @property float $tax
 * @property float $total_qty
 * @property int $is_paid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\CashDrawer|null $cashDrawer
 * @property-read \App\Models\Tenants\Member|null $member
 * @property-read \App\Models\Tenants\PaymentMethod|null $paymentMethod
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\SellingDetail> $sellingDetails
 * @property-read int|null $selling_details_count
 * @property-read \App\Models\Tenants\User|null $user
 * @method static \Database\Factories\Tenants\SellingFactory factory($count = null, $state = [])
 * @method static Builder|Selling isNotPaid()
 * @method static Builder|Selling isPaid()
 * @method static Builder|Selling newModelQuery()
 * @method static Builder|Selling newQuery()
 * @method static Builder|Selling query()
 * @method static Builder|Selling timezoneBetween(string $column, array $dates)
 * @method static Builder|Selling whereCashDrawerId($value)
 * @method static Builder|Selling whereCode($value)
 * @method static Builder|Selling whereCreatedAt($value)
 * @method static Builder|Selling whereCustomerNumber($value)
 * @method static Builder|Selling whereDate($value)
 * @method static Builder|Selling whereFee($value)
 * @method static Builder|Selling whereFriendPrice($value)
 * @method static Builder|Selling whereId($value)
 * @method static Builder|Selling whereIsPaid($value)
 * @method static Builder|Selling whereMemberId($value)
 * @method static Builder|Selling whereMoneyChanges($value)
 * @method static Builder|Selling whereNote($value)
 * @method static Builder|Selling wherePayedMoney($value)
 * @method static Builder|Selling wherePaymentMethodId($value)
 * @method static Builder|Selling whereTax($value)
 * @method static Builder|Selling whereTaxPrice($value)
 * @method static Builder|Selling whereTotalCost($value)
 * @method static Builder|Selling whereTotalPrice($value)
 * @method static Builder|Selling whereTotalQty($value)
 * @method static Builder|Selling whereUpdatedAt($value)
 * @method static Builder|Selling whereUserId($value)
 * @mixin \Eloquent
 */
class Selling extends Model
{
    use HasFactory, UseTimezoneAwareQuery;

    protected $guarded = ['friend_price'];

    public function sellingDetails()
    {
        return $this->hasMany(SellingDetail::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashDrawer()
    {
        return $this->belongsTo(CashDrawer::class);
    }

    public function scopeIsPaid(Builder $builder): Builder
    {
        return $builder->where('is_paid', true);
    }

    public function scopeIsNotPaid(Builder $builder): Builder
    {
        return $builder->where('is_paid', false);
    }
}
