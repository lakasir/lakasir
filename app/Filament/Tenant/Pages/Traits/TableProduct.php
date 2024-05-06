<?php

namespace App\Filament\Tenant\Pages\Traits;

use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

trait TableProduct
{
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->with('stocks')
                    ->limit(12)
            )
            ->paginated(false)
            ->columns([
                Stack::make([
                    ImageColumn::make('hero_images')
                        ->alignCenter()
                        ->defaultImageUrl(url('https://cdn4.iconfinder.com/data/icons/picture-sharing-sites/32/No_Image-1024.png'))
                        ->extraAttributes([
                            'class' => 'py-0',
                        ])
                        ->extraImgAttributes([
                            'class' => 'mb-4 object-cover -mt-4 xl:w-[200px] md:w-[180px] w-[150px]',
                        ])
                        ->height(100),
                    TextColumn::make('selling_price')
                        ->color('primary')
                        ->money(Setting::get('currency', 'IDR'))
                        ->columnStart(0),
                    TextColumn::make('name')
                        ->size('lg')
                        ->extraAttributes([
                            'class' => 'font-bold',
                        ])
                        ->searchable(['sku', 'name']),
                    TextColumn::make('stock')
                        ->icon(
                            fn (Product $product) => $product->stock < Setting::get('minimum_stock_nofication', 0)
                                    ? 'heroicon-s-information-circle'
                                : ''
                        )
                        ->iconColor('danger')
                        ->extraAttributes([
                            'class' => 'font-bold',
                        ])
                        ->formatStateUsing(fn (Product $product) => 'Stock: '.$product->stock),
                ]),
            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 4,
            ])
            ->headerActionsPosition(HeaderActionsPosition::Bottom)
            ->filters([
                SelectFilter::make('Category')
                    ->searchable()
                    ->relationship('category', 'name'),
            ])
            ->searchPlaceholder(__('Search (SKU, name)'))
            ->actions([
                Action::make('add_item')
                    ->icon('heroicon-o-plus')
                    ->button()
                    ->form([
                        TextInput::make('stock')
                            ->extraAttributes([
                                'focus',
                            ])
                            ->rules([
                                function (Product $product) {
                                    return function (string $attribute, $value, Closure $fail) use ($product) {
                                        if (! $this->validateStock($product, $value)) {
                                            $fail('Stock is out');
                                        }
                                    };
                                },
                            ])
                            ->default(1),
                    ])
                    ->extraAttributes([
                        'class' => 'mr-auto',
                    ])
                    ->action(fn (Product $product, array $data) => $this->addCart($product, $data))
                    ->hiddenLabel(),
                Action::make('cart')
                    ->label(function (Product $product) {
                        return $product->CartItems()->first()?->qty ?? '';
                    })
                    ->color('white')
                    ->icon('heroicon-o-shopping-bag')
                    ->hidden(fn (Product $product) => ! $product->CartItems()->exists()),
            ]);
    }
}
