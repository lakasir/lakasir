<?php

namespace Database\Seeders;

use App\Constants\Role;
use App\Models\Company;
use App\Models\Tenants\About;
use App\Models\Tenants\Category;
use App\Models\Tenants\Member;
use App\Models\Tenants\Product;
use App\Models\Tenants\Selling;
use App\Models\Tenants\SellingDetail;
use App\Models\Tenants\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Demo data for one shop: enough to open the POS, ring up a sale and see a
 * report. Run with `php artisan db:seed --class=DemoSeeder`. It reuses the
 * first company and leaves an existing shop's data alone on a second run.
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $company = $this->company();

        $company->makeCurrent();

        $this->owner($company);
        $this->about();
        $this->permissions();

        $category = Category::firstOrCreate(['name' => 'UMUM']);
        $products = $this->products($category);
        $members = $this->members();

        $this->sales($products, $members);

        $this->command->info("Seeded company #{$company->id} ({$company->name})");
    }

    private function company(): Company
    {
        return Company::firstOrCreate(
            ['name' => 'Toko Demo'],
            ['domain' => null],
        );
    }

    private function owner(Company $company): User
    {
        $user = User::firstOrCreate(
            ['email' => 'owner@lakasir.test'],
            [
                'name' => 'Pemilik Toko',
                'password' => bcrypt('password'),
            ],
        );

        $user->companies()->syncWithoutDetaching([$company->id => ['is_owner' => true]]);

        return $user;
    }

    private function about(): void
    {
        About::firstOrCreate(
            ['shop_name' => 'Toko Demo'],
            [
                'shop_location' => 'Jl. Contoh No. 1, Jakarta',
                'business_type' => 'retail',
            ],
        );
    }

    private function permissions(): void
    {
        Artisan::call('db:seed', ['--class' => 'PermissionSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'PaymentMethodSeeder', '--force' => true]);

        User::where('email', 'owner@lakasir.test')->first()?->assignRole(Role::admin);
    }

    /**
     * @return array<int, Product>
     */
    private function products(Category $category): array
    {
        $catalogue = [
            ['Kopi Susu', 12000, 18000, 50],
            ['Roti Bakar', 8000, 14000, 30],
            ['Air Mineral', 3000, 5000, 100],
            ['Teh Manis', 2000, 6000, 80],
            ['Nasi Goreng', 10000, 20000, 25],
        ];

        $products = [];

        foreach ($catalogue as [$name, $cost, $price, $stock]) {
            $product = Product::firstOrCreate(
                ['name' => $name],
                [
                    'category_id' => $category->id,
                    'initial_price' => $cost,
                    'selling_price' => $price,
                    'stock' => $stock,
                    'is_non_stock' => false,
                    'type' => 'product',
                    'show' => true,
                ],
            );

            $products[] = $product;
        }

        return $products;
    }

    /**
     * @return array<int, Member>
     */
    private function members(): array
    {
        return Member::factory()->count(3)->create()->all();
    }

    /**
     * Three completed cash sales, one per member, so the reports have a window
     * to page.
     *
     * @param  array<int, Product>  $products
     * @param  array<int, Member>  $members
     */
    private function sales(array $products, array $members): void
    {
        if (Selling::exists()) {
            return;
        }

        foreach ($members as $index => $member) {
            $product = $products[$index % count($products)];
            $qty = 2;
            $total = $product->selling_price * $qty;

            $selling = Selling::create([
                'member_id' => $member->id,
                'date' => now()->subDays($index)->toDateString(),
                'payed_money' => $total,
                'money_changes' => 0,
                'total_price' => $total,
                'total_qty' => $qty,
                'total_cost' => $product->initial_price * $qty,
                'tax_price' => 0,
                'discount_price' => 0,
                'total_discount_per_item' => 0,
                'is_paid' => true,
            ]);

            SellingDetail::create([
                'selling_id' => $selling->id,
                'product_id' => $product->id,
                'qty' => $qty,
                'price' => $product->selling_price,
                'cost' => $product->initial_price,
                'discount_price' => 0,
            ]);
        }
    }
}
