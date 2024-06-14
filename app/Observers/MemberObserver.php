<?php

namespace App\Observers;

use App\Models\Tenants\Member;

class MemberObserver
{
    public function creating(Member $member)
    {
        $members = Member::all();
        $lastCount = $members->count();
        $lastMember = Member::orderBy('code', 'desc')->first();
        if ($lastMember) {
            $lastCount = (int) substr($lastMember->code, 3);
        } else {
            $lastCount = 0;
        }

        if (! $member->code) {
            // Generate the new customer code
            $member->code = 'CUS'.str_pad($lastCount + 1, 4, '0', STR_PAD_LEFT);
        }
    }
}
