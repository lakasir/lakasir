<?php

namespace App\Events;

use App\Models\Tenants\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class RecalculateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param  Collection<int|string, Product>|Product  $products
     * @return void
     */
    public function __construct(public Collection|Product $products, public array $data)
    {
    }
}
