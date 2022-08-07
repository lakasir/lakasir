<?php

namespace App\Observers;

use App\Models\Member;
use Illuminate\Support\Str;

class MemberObserver
{
    public function creating(Member $member)
    {
        $members = Member::all();
        $lastCount = $members->count();
        $member->code = "CUS" . Str::of($lastCount + 1)->padLeft(4, 0)->value();
    }
}
