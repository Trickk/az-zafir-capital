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
       Schema::table('invoices', function (Blueprint $table) {
            $table->string('company_logo_path_snapshot', 255)->nullable()->after('company_responsible_name_snapshot');
            $table->string('company_invoice_image_path_snapshot', 255)->nullable()->after('company_logo_path_snapshot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
