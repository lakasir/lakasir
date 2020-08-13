<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Item as ItemRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Item extends Controller
{
    public function __invoke(Request $request, $id): JsonResponse
    {
        $item = ( new ItemRepository )->find($id);

        return response()->json($item->last_price, 200);
    }
}
