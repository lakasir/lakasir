<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * 
 *
 * @property int $id
 * @property string|null $shop_name
 * @property string|null $shop_location
 * @property string $currency
 * @property string|null $business_type
 * @property string|null $other_business_type
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|About newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|About newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|About query()
 * @method static \Illuminate\Database\Eloquent\Builder|About whereBusinessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereOtherBusinessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereShopLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereShopName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAbout {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAdmin {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string|null $shop_name
 * @property string|null $shop_location
 * @property string $currency
 * @property string|null $business_type
 * @property string|null $other_business_type
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|About newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|About newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|About query()
 * @method static \Illuminate\Database\Eloquent\Builder|About whereBusinessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereOtherBusinessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereShopLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereShopName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|About whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAbout {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCart {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property float $qty
 * @property float|null $price
 * @property float $discount_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\User $cashier
 * @property-read mixed $discount_price_format
 * @property-read mixed $final_price_format
 * @property-read mixed $price_format_m_oney
 * @property-read mixed $hero_image
 * @property-read \App\Models\Tenants\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem cashier()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCartItem {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $opened_by
 * @property int|null $closed_by
 * @property float $cash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\User|null $closedBy
 * @property-read \App\Models\Tenants\User $openedBy
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer lastOpened()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer today()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereClosedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereOpenedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCashDrawer {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Product> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\Tenants\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCategory {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $member_id
 * @property int $selling_id
 * @property float $total_debt
 * @property float $rest_debt
 * @property string $due_date
 * @property string|null $last_billing_date
 * @property int|null $total_billing_via_whatsapp
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\DebtItem> $debtItems
 * @property-read int|null $debt_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\DebtPayment> $debtPayments
 * @property-read int|null $debt_payments_count
 * @property-read \App\Models\Tenants\Member $member
 * @property-read \App\Models\Tenants\Selling $selling
 * @method static \Illuminate\Database\Eloquent\Builder|Debt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Debt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Debt query()
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereLastBillingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereRestDebt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereSellingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereTotalBillingViaWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereTotalDebt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Debt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDebt {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int $debt_id
 * @property float $amount
 * @property float $price
 * @property float $subtotal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\Debt $debt
 * @property-read \App\Models\Tenants\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem whereDebtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDebtItem {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $payment_method_id
 * @property int $debt_id
 * @property float $amount
 * @property float $last_debt
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\Debt $debt
 * @property-read \App\Models\Tenants\PaymentMethod $paymentMethod
 * @property-read \App\Models\Tenants\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment whereDebtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment whereLastDebt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDebtPayment {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $identity_type
 * @property string|null $identity_number
 * @property string|null $joined_date
 * @property string $code
 * @property string|null $address
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Debt> $debts
 * @property-read int|null $debts_count
 * @method static \Database\Factories\Tenants\MemberFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereIdentityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereIdentityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereJoinedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Member whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMember {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string $data
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperNotification {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property bool $is_cash
 * @property bool $is_debit
 * @property bool $is_credit
 * @property bool $is_wallet
 * @property-read string|null $icon
 * @property string|null $waletable_type
 * @property int|null $waletable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIsCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIsCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIsDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereIsWallet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereWaletableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereWaletableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPaymentMethod {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $driver
 * @property string|null $port
 * @property string|null $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPrinter {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $sku
 * @property string|null $barcode
 * @property float $stock
 * @property int $is_non_stock
 * @property float $initial_price
 * @property float $selling_price
 * @property string $unit
 * @property string $type
 * @property string|null $hero_images
 * @property int $show
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Tenants\Category $category
 * @property-read mixed $expired_stock
 * @property-read mixed $has_expired_stock
 * @property-read mixed $hero_image
 * @property mixed $initial_price_calculate
 * @property-read mixed $net_profit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\SellingDetail> $sellingDetails
 * @property-read int|null $selling_details_count
 * @property mixed $selling_price_calculate
 * @property mixed $selling_price_label_calculate
 * @property mixed $stock_calculate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Stock> $stocks
 * @property-read int|null $stocks_count
 * @method static \Database\Factories\Tenants\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Product inActivate()
 * @method static \Illuminate\Database\Eloquent\Builder|Product nearestExpiredProduct()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product stockLatestCalculateIn()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHeroImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereInitialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsNonStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperProduct {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $url
 * @property string $size
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $path
 * @property-read \App\Models\Tenants\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage whereUrl($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperProductImage {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $phone
 * @property string|null $photo
 * @property string|null $address
 * @property string|null $locale
 * @property string|null $timezone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Profile whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperProfile {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $supplier_id
 * @property string $number
 * @property string|null $due_date
 * @property string|null $date
 * @property string|null $image
 * @property float $total_initial_price
 * @property float $total_selling_price
 * @property float $tax
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Stock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \App\Models\Tenants\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing query()
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereTotalInitialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereTotalSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPurchasing {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSecureInitialPrice {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $member_id
 * @property int|null $table_id
 * @property string|null $customer_number
 * @property int|null $cash_drawer_id
 * @property string|null $note
 * @property float $fee
 * @property string|null $voucher
 * @property float|null $discount_price
 * @property string $date
 * @property string $code
 * @property float $payed_money
 * @property float $money_changes
 * @property float $total_price
 * @property string $tax_price
 * @property float|null $total_cost
 * @property float $total_discount_per_item
 * @property int $friend_price
 * @property int|null $payment_method_id
 * @property float $tax
 * @property float $total_qty
 * @property int $is_paid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\CashDrawer|null $cashDrawer
 * @property-read mixed $grand_total_price
 * @property-read \App\Models\Tenants\Member|null $member
 * @property-read \App\Models\Tenants\PaymentMethod|null $paymentMethod
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\SellingDetail> $sellingDetails
 * @property-read int|null $selling_details_count
 * @property-read \App\Models\Tenants\Table|null $table
 * @property-read \App\Models\Tenants\User|null $user
 * @method static \Database\Factories\Tenants\SellingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Selling isNotPaid()
 * @method static \Illuminate\Database\Eloquent\Builder|Selling isPaid()
 * @method static \Illuminate\Database\Eloquent\Builder|Selling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Selling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Selling query()
 * @method static \Illuminate\Database\Eloquent\Builder|Selling timezoneBetween(string $column, array $dates)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereCashDrawerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereCustomerNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereFriendPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereMoneyChanges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling wherePayedMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereTableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereTaxPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereTotalDiscountPerItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereTotalQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Selling whereVoucher($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSelling {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $selling_id
 * @property int $product_id
 * @property float $price
 * @property float|null $cost
 * @property float $qty
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float $discount_price
 * @property-read \App\Models\Tenants\Product $product
 * @property-read \App\Models\Tenants\Selling $selling
 * @property-read mixed $total_price
 * @method static \Database\Factories\Tenants\SellingDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereSellingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSellingDetail {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereValue($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSetting {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int|null $purchasing_id
 * @property int $is_ready
 * @property float $stock
 * @property float $init_stock
 * @property float $initial_price
 * @property float|null $selling_price
 * @property string $type
 * @property string $date
 * @property string|null $expired
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\Product $product
 * @property-read \App\Models\Tenants\Purchasing|null $purchasing
 * @property-read mixed $total_initial_price
 * @property-read mixed $total_selling_price
 * @method static \Database\Factories\Tenants\StockFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Stock in()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock latestIn()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock out()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock product($product_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereInitStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereInitialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereIsReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock wherePurchasingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStock {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $status
 * @property string $number
 * @property string $pic
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\StockOpnameItem> $stockOpnameItems
 * @property-read int|null $stock_opname_items_count
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStockOpname {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int $stock_opname_id
 * @property string $adjustment_type
 * @property float $current_stock
 * @property float $amount
 * @property float $amount_after_adjustment
 * @property string|null $attachment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\Product $product
 * @property-read \App\Models\Tenants\StockOpname $stockOpname
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem whereAdjustmentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem whereAmountAfterAdjustment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem whereCurrentStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem whereStockOpnameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStockOpnameItem {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $phone_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSupplier {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $number
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Selling> $Sellings
 * @property-read int|null $sellings_count
 * @method static \Illuminate\Database\Eloquent\Builder|Table newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Table newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Table onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Table query()
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Table withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Table withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTable {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $original_name
 * @property string $url
 * @property string $mime_type
 * @property string $extension
 * @property string $size
 * @property string $path
 * @property string $disk
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile inUrl($url)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile whereUrl($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUploadedFile {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string|null $fcm_token
 * @property int $is_owner
 * @property string|null $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Tenants\CashDrawer|null $cashDrawer
 * @property-read mixed $cashier_name
 * @property-read mixed $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Tenants\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Tenants\SecureInitialPrice|null $secureInitialPrice
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Selling> $sellings
 * @property-read int|null $sellings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\Tenants\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User owner()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property float $nominal
 * @property int $kuota
 * @property string $start_date
 * @property string $expired
 * @property float $minimal_buying
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\Tenants\VoucherFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereKuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereMinimalBuying($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperVoucher {}
}

namespace App{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Stancl\Tenancy\Database\Models\Domain> $domains
 * @property-read int|null $domains_count
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant query()
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> get($columns = ['*'])
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> get($columns = ['*'])
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTenant {}
}

namespace App{
/**
 * 
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|TenantUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TenantUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TenantUser query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTenantUser {}
}

