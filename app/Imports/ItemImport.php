<?php

namespace App\Imports;

use App\Repositories\Category;
use App\Repositories\Item;
use App\Repositories\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class ItemImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if ($key != 0) {
                $array = $this->createRequest($row->toArray());
                $request = new Request($array);
                $item = new Item();
                $item->create($request);
            }
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
                    $array['stock'] =  $row[$i] ?? 0;
                    break;
                case 2:
                    $string = Str::lower($row[$i]);
                    switch ($string) {
                        case 'ya':
                            $bool = true;
                            break;
                        case 'tidak':
                            $bool = false;
                            break;
                        case 'yes':
                            $bool = true;
                            break;
                        case 'no':
                            $bool = false;
                            break;
                        default:
                            $bool = false;
                            break;
                    }
                    $array['internal_production'] =  $bool;
                    break;
                case 3:
                    $category = new Category();
                    if (!$row[$i]) {
                        $category = $category->findByKeyArray(['umum'], 'name')->first();
                    } else {
                        $category = $category->getModel()::where('name', $row[$i])->firstOrCreate([
                            'name' => $row[$i]
                        ]);
                    }
                    $array['category_id'] =  $category->id;
                    break;
                case 4:
                    $unit = new Unit();
                    if (!$row[$i]) {
                        $unit = $unit->findByKeyArray(['pcs'], 'name')->first();
                    } else {
                        $unit = $unit->getModel()::where('name', $row[$i])->firstOrCreate([
                            'name' => $row[$i]
                        ]);
                    }
                    $array['unit_id'] =  $unit->id;
                    break;
                case 5:
                    // initial_price
                    $array['initial_price'] =  $row[$i] ?? 0;
                    break;
                case 6:
                    // selling_price
                    $array['selling_price'] =  $row[$i] ?? 0;
                    break;

                default:
                    break;
            }
        }

        return $array;
    }
}
