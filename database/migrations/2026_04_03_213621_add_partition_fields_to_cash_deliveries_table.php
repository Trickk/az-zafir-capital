<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cash_deliveries', function (Blueprint $table) {
            $table->decimal('matrix_fund_percent', 5, 2)->default(10.00)->after('amount');
            $table->decimal('management_fee_percent', 5, 2)->default(10.00)->after('matrix_fund_percent');
            $table->decimal('net_dirty_percent', 5, 2)->default(80.00)->after('management_fee_percent');

            $table->decimal('matrix_fund_amount', 15, 2)->default(0)->after('net_dirty_percent');
            $table->decimal('management_fee_amount', 15, 2)->default(0)->after('matrix_fund_amount');
            $table->decimal('net_dirty_amount', 15, 2)->default(0)->after('management_fee_amount');
        });
    }

    public function down(): void
    {
        Schema::table('cash_deliveries', function (Blueprint $table) {
            $table->dropColumn([
                'matrix_fund_percent',
                'management_fee_percent',
                'net_dirty_percent',
                'matrix_fund_amount',
                'management_fee_amount',
                'net_dirty_amount',
            ]);
        });
    }
};
