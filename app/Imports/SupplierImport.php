<?php

namespace App\Imports;

use App\Repositories\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class SupplierImport implements OnEachRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function onRow(Row $row)
    {
        if ($row->getIndex() != 1) {
            $array = $this->createRequest($row->toArray());
            $request = new Request($array);
            $supplier = new Supplier();
            $supplier->create($request);
        }
    }

    private function createRequest(array $row)
    {
        $array = [];
        for ($i = 0; $i < count($row); $i++) {
            switch ($i) {
                case 0:
                    $array['name'] =  $row[$i];
                    break;
                case 1:
                    $array['code'] =  $row[$i];
                    break;
                case 2:
                    $array['shop_name'] =  $row[$i] ?? '-';
                    break;
                case 3:
                    $array['phone'] =  $row[$i] ?? '-';
                    break;
                case 4:
                    $array['address'] =  $row[$i] ?? '-';
                    break;
                default:
                    break;
            }
        }

        return $array;
    }
}
