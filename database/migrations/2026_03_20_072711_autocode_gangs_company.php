<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('company_code', 50)->nullable()->unique()->after('id');
        });

        Schema::table('gangs', function (Blueprint $table) {
            $table->string('gang_code', 50)->nullable()->unique()->after('id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->string('gang_code_snapshot', 50)->nullable()->after('gang_name_snapshot');
            $table->string('company_code_snapshot', 50)->nullable()->after('company_name_snapshot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['company_code']);
            $table->dropColumn('company_code');
        });

        Schema::table('gangs', function (Blueprint $table) {
            $table->dropUnique(['gang_code']);
            $table->dropColumn('gang_code');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'gang_code_snapshot',
                'company_code_snapshot',
            ]);
        });
    }
};
