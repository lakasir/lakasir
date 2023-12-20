<?php

namespace App\Observers;

use App\Models\Tenants\Member;
use Illuminate\Support\Str;

class MemberObserver
{
    public function creating(Member $member)
    {
        /* TODO: fixing the iteration code <10-08-22, sheenazien8> */
        $members = Member::all();
        $lastCount = $members->count();
        $member->code = "CUS" . Str::of($lastCount + 1)->padLeft(4, 0)->value();
    }
}
