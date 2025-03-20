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
 * @property string|null $business_type
 * @property string|null $other_business_type
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|About newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|About newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|About query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|About whereBusinessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|About whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|About whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|About whereOtherBusinessType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|About wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|About whereShopLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|About whereShopName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|About whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAbout {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
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
 * @property int|null $price_unit_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\User $cashier
 * @property-read mixed $discount_price_format
 * @property-read mixed $final_price_format
 * @property-read mixed $price_format_m_oney
 * @property-read mixed $hero_image
 * @property-read \App\Models\Tenants\PriceUnit|null $priceUnit
 * @property-read \App\Models\Tenants\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem cashier()
 * @method static \Database\Factories\Tenants\CartItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem wherePriceUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CartItem whereUserId($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashDrawer lastOpened()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashDrawer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashDrawer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashDrawer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashDrawer today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashDrawer whereCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashDrawer whereClosedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashDrawer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashDrawer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashDrawer whereOpenedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CashDrawer whereUpdatedAt($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
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
 * @property int|null $completed_at
 * @property string $file_name
 * @property string $file_path
 * @property string $importer
 * @property int $processed_rows
 * @property int $total_rows
 * @property int $successful_rows
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Filament\Actions\Imports\Models\FailedImportRow> $failedRows
 * @property-read int|null $failed_rows_count
 * @property-read \App\Models\Tenants\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereImporter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereProcessedRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereSuccessfulRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereTotalRows($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Import whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperImport {}
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Receivable> $receivables
 * @property-read int|null $receivables_count
 * @method static \Database\Factories\Tenants\MemberFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereIdentityNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereIdentityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereJoinedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereUpdatedAt($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
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
 * @property string|null $icon
 * @property string|null $waletable_type
 * @property int|null $waletable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIsCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIsCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIsDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIsWallet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereWaletableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereWaletableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod withoutTrashed()
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
 * @property int $product_id
 * @property float $selling_price
 * @property float $stock
 * @property string $unit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\Product $product
 * @method static \Database\Factories\Tenants\PriceUnitFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceUnit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPriceUnit {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Printer whereUpdatedAt($value)
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\PriceUnit> $priceUnits
 * @property-read int|null $price_units_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\SellingDetail> $sellingDetails
 * @property-read int|null $selling_details_count
 * @property mixed $selling_price_calculate
 * @property mixed $selling_price_label_calculate
 * @property mixed $stock_calculate
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Stock> $stocks
 * @property-read int|null $stocks_count
 * @method static \Database\Factories\Tenants\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product inActivate()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product nearestExpiredProduct()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product stockLatestCalculateIn()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHeroImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereInitialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsNonStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product withoutTrashed()
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereUrl($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profile whereUserId($value)
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
 * @property int|null $payment_method_id
 * @property int $supplier_id
 * @property int|null $user_id
 * @property string $number
 * @property string|null $due_date
 * @property string|null $approved_at
 * @property string|null $date
 * @property string|null $image
 * @property float $total_initial_price
 * @property float $total_selling_price
 * @property float $tax
 * @property string $status
 * @property int $payment_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\PaymentMethod|null $paymentMethod
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\Stock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \App\Models\Tenants\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereTotalInitialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereTotalSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchasing whereUserId($value)
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
 * @property int $member_id
 * @property int $selling_id
 * @property float $total_receivable
 * @property float $rest_receivable
 * @property string $due_date
 * @property string|null $last_billing_date
 * @property int|null $total_billing_via_whatsapp
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\Member $member
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\ReceivableItem> $receivableItems
 * @property-read int|null $receivable_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\ReceivablePayment> $receivablePayments
 * @property-read int|null $receivable_payments_count
 * @property-read \App\Models\Tenants\Selling $selling
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable whereLastBillingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable whereRestReceivable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable whereSellingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable whereTotalBillingViaWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable whereTotalReceivable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receivable whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReceivable {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property int $receivable_id
 * @property float $amount
 * @property float $price
 * @property float $subtotal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\Product $product
 * @property-read \App\Models\Tenants\Receivable $receivable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivableItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivableItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivableItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivableItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivableItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivableItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivableItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivableItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivableItem whereReceivableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivableItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivableItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReceivableItem {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $payment_method_id
 * @property int $receivable_id
 * @property float $amount
 * @property float $last_receivable
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\PaymentMethod $paymentMethod
 * @property-read \App\Models\Tenants\Receivable $receivable
 * @property-read \App\Models\Tenants\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment whereLastReceivable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment whereReceivableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceivablePayment whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperReceivablePayment {}
}

namespace App\Models\Tenants{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $mobilePermissions
 * @property-read int|null $mobile_permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\User> $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $webPermissions
 * @property-read int|null $web_permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role withoutPermission($permissions)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRole {}
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecureInitialPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecureInitialPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecureInitialPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecureInitialPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecureInitialPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecureInitialPrice wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecureInitialPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecureInitialPrice whereUserId($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling isNotPaid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling isPaid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling timezoneBetween(string $column, array $dates)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereCashDrawerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereCustomerNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereFriendPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereMoneyChanges($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling wherePayedMoney($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereTableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereTaxPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereTotalDiscountPerItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereTotalQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Selling whereVoucher($value)
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
 * @property int|null $price_unit_id
 * @property-read mixed $price_per_unit
 * @property-read \App\Models\Tenants\Product $product
 * @property-read \App\Models\Tenants\Selling $selling
 * @property-read mixed $total_price
 * @method static \Database\Factories\Tenants\SellingDetailFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail wherePriceUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail whereSellingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellingDetail whereUpdatedAt($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereValue($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock in()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock latestIn()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock out()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock product($product_id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereInitStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereInitialPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereIsReady($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock wherePurchasingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stock whereUpdatedAt($value)
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
 * @property int $user_id
 * @property string $status
 * @property string $number
 * @property string $pic
 * @property string $date
 * @property string|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tenants\StockOpnameItem> $stockOpnameItems
 * @property-read int|null $stock_opname_items_count
 * @property-read \App\Models\Tenants\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpname whereUserId($value)
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
 * @property float $actual_stock
 * @property float $missing_stock
 * @property string|null $attachment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenants\Product $product
 * @property-read \App\Models\Tenants\StockOpname $stockOpname
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem whereActualStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem whereAdjustmentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem whereCurrentStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem whereMissingStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem whereStockOpnameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOpnameItem whereUpdatedAt($value)
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
 * @property string|null $contact_name
 * @property string|null $email
 * @property string|null $address
 * @property string|null $city
 * @property string|null $country
 * @property string|null $postal_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier withoutTrashed()
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table withoutTrashed()
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile inUrl($url)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile whereOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UploadedFile whereUrl($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User owner()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereKuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereMinimalBuying($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereNominal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereUpdatedAt($value)
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

