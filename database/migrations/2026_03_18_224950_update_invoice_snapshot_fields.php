<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'company_responsible_snapshot')) {
                $table->dropColumn('company_responsible_snapshot');
            }

            $table->string('invoice_customer_name', 180)->nullable()->after('company_invoice_image_path_snapshot');
            $table->string('invoice_state_id', 120)->nullable()->after('invoice_customer_name');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('company_responsible_snapshot', 150)->nullable();
            $table->dropColumn(['invoice_customer_name', 'invoice_state_id']);
        });
    }
};
