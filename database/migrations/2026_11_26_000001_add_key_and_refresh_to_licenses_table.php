<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->string('key')->nullable()->after('id');
            $table->timestamp('last_refreshed_at')->nullable()->after('token');
            $table->string('last_refresh_error')->nullable()->after('last_refreshed_at');
        });
    }

    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn(['key', 'last_refreshed_at', 'last_refresh_error']);
        });
    }
};
