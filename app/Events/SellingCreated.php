<?php

namespace App\Events;

use App\Models\Tenants\Selling;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SellingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Selling $selling, public array $data)
    {
    }
}
