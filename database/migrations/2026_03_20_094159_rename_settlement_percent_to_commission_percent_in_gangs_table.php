<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gangs', function (Blueprint $table) {
            $table->renameColumn('settlement_percent', 'commission_percent');
        });
    }

    public function down(): void
    {
        Schema::table('gangs', function (Blueprint $table) {
            $table->renameColumn('commission_percent', 'settlement_percent');
        });
    }
};
