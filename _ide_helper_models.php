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
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAdmin {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|About newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|About newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|About query()
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
 * @property-read \App\Models\Tenants\User|null $cashier
 * @property-read mixed $discount_price_format
 * @property-read mixed $final_price_format
 * @property-read mixed $price_format_m_oney
 * @property-read mixed $hero_image
 * @property-read \App\Models\Tenants\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem cashier()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartItem query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCartItem {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \App\Models\Tenants\User|null $closedBy
 * @property-read \App\Models\Tenants\User|null $openedBy
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer lastOpened()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDrawer today()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCashDrawer {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Product> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\Tenants\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCategory {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\DebtItem> $debtItems
 * @property-read int|null $debt_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\DebtPayment> $debtPayments
 * @property-read int|null $debt_payments_count
 * @property-read \App\Models\Tenants\Member|null $member
 * @property-read \App\Models\Tenants\Selling|null $selling
 * @method static \Illuminate\Database\Eloquent\Builder|Debt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Debt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Debt query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDebt {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \App\Models\Tenants\Debt|null $debt
 * @property-read \App\Models\Tenants\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtItem query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDebtItem {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \App\Models\Tenants\Debt|null $debt
 * @property-read \App\Models\Tenants\PaymentMethod|null $paymentMethod
 * @property-read \App\Models\Tenants\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebtPayment query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDebtPayment {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Debt> $debts
 * @property-read int|null $debts_count
 * @method static \Database\Factories\Tenants\MemberFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMember {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperNotification {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read mixed $icon
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
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
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPrinter {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \App\Models\Tenants\Category|null $category
 * @property-read mixed $expired_stock
 * @property-read mixed $has_expired_stock
 * @property-read mixed $hero_image
 * @property mixed $hero_images
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
 * @property-read mixed $path
 * @property-read \App\Models\Tenants\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductImage query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperProductImage {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \App\Models\Tenants\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperProfile {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Stock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \App\Models\Tenants\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasing query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPurchasing {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \App\Models\Tenants\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecureInitialPrice query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSecureInitialPrice {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \App\Models\Tenants\CashDrawer|null $cashDrawer
 * @property-read mixed $grand_total_price
 * @property-read \App\Models\Tenants\Member|null $member
 * @property-read \App\Models\Tenants\PaymentMethod|null $paymentMethod
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\SellingDetail> $sellingDetails
 * @property-read int|null $selling_details_count
 * @property-read \App\Models\Tenants\User|null $user
 * @method static \Database\Factories\Tenants\SellingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Selling isNotPaid()
 * @method static \Illuminate\Database\Eloquent\Builder|Selling isPaid()
 * @method static \Illuminate\Database\Eloquent\Builder|Selling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Selling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Selling query()
 * @method static \Illuminate\Database\Eloquent\Builder|Selling timezoneBetween(string $column, array $dates)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSelling {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \App\Models\Tenants\Product|null $product
 * @property-read \App\Models\Tenants\Selling|null $selling
 * @property-read mixed $total_price
 * @method static \Database\Factories\Tenants\SellingDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingDetail query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSellingDetail {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSetting {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \App\Models\Tenants\Product|null $product
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
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStock {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\StockOpnameItem> $stockOpnameItems
 * @property-read int|null $stock_opname_items_count
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpname query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStockOpname {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property-read \App\Models\Tenants\Product|null $product
 * @property-read \App\Models\Tenants\StockOpname|null $stockOpname
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOpnameItem query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperStockOpnameItem {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSupplier {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile inUrl($url)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadedFile query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUploadedFile {}
}

namespace App\Models\Tenants{
/**
 * 
 *
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
 * @method static \Database\Factories\Tenants\VoucherFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher query()
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
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $data
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant whereUpdatedAt($value)
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
 * @property int $id
 * @property string $tenant_id
 * @property string|null $full_name
 * @property string $email
 * @property string|null $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TenantUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantUser whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantUser whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TenantUser whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTenantUser {}
}

