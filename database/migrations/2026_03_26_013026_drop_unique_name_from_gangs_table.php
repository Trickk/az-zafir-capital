<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gangs', function (Blueprint $table) {
            $table->dropUnique('gangs_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('gangs', function (Blueprint $table) {
            $table->unique('name');
        });
    }
};
