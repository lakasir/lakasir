<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ownership belongs to the membership, not the person: in one box serving
     * many customers the same login can belong to several shops, and a global
     * flag made an owner of one shop an owner of every shop they joined.
     */
    public function up(): void
    {
        Schema::table('user_companies', function (Blueprint $table) {
            $table->boolean('is_owner')->default(false)->after('company_id');
        });

        $owners = DB::table('users')->where('is_owner', true)->pluck('id');

        foreach ($owners as $userId) {
            DB::table('user_companies')
                ->where('user_id', $userId)
                ->update(['is_owner' => true]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_owner');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_owner')->default(false);
        });

        $owners = DB::table('user_companies')->where('is_owner', true)->pluck('user_id')->unique();

        foreach ($owners as $userId) {
            DB::table('users')->where('id', $userId)->update(['is_owner' => true]);
        }

        Schema::table('user_companies', function (Blueprint $table) {
            $table->dropColumn('is_owner');
        });
    }
};
