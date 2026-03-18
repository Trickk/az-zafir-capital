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
       Schema::table('holdings', function (Blueprint $table) {

            $table->dropForeign(['gang_id']);

            $table->foreignId('gang_id')
                ->nullable()
                ->change();

            $table->foreign('gang_id')
                ->references('id')
                ->on('gangs')
                ->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('holdings', function (Blueprint $table) {
            //
        });
    }
};
