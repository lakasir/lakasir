<?php

namespace App\Repositories;

use App\Abstracts\Repository as RepositoryAbstract;
use App\Models\Selling as SellingModel;
use App\Traits\HasParent;
use Illuminate\Http\Request;

class Selling extends RepositoryAbstract
{
    use HasParent;

    protected string $model = SellingModel::class;

    public function activity(Request $request)
    {
        $selling = $this->query()
                        ->when($request->search, function ($query)
                        {
                            return $query->where('number_transaction', 'LIKE', '%%'.$request->search.'%%');
                        })
                        ->where('user_id', auth()->id())
                        ->orderBy('transaction_date', 'desc')
                        ->limit(30)
                        ->get();

        return $selling;
    }

}
