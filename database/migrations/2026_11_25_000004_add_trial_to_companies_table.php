<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Null means the shop is not on a trial: a self-hosted install is
            // free forever, and only lakasir-cloud provisioning sets a date.
            $table->timestamp('trial_ends_at')->nullable()->after('enabled');

            $table->string('provisioned_by', 20)->default('local')->after('trial_ends_at');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['trial_ends_at', 'provisioned_by']);
        });
    }
};
