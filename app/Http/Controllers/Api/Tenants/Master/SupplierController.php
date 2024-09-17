<?php

namespace App\Http\Controllers\Api\Tenants\Master;

use App\Http\Controllers\Controller;
use App\Models\Tenants\Supplier;
use Lakasir\HasCrudAction\Abstracts\HasCrudActionAbstract;
use Lakasir\HasCrudAction\Interfaces\WithSimplePagination;

class SupplierController extends HasCrudActionAbstract implements WithSimplePagination
{
    public static string $model = Supplier::class;

    public function filter(): array
    {
        return [
            'email',
            'name',
            'phone_number',
            'address',
            'city',
            'country',
        ];
    }

    public static function rules($id): array
    {
        return [
            'phone_number' => "unique:suppliers,phone_number,{$id}",
            'email' => "unique:suppliers,email,{$id}",
            'name' => 'required',
        ];
    }

    public function response($record)
    {
        return $this->buildResponse()
            ->setData($record)
            ->present();
    }

    public function buildResponse()
    {
        return (new Controller())->buildResponse();
    }
}
