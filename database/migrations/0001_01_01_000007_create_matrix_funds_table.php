<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matrix_funds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gang_id')
                ->unique()
                ->constrained('gangs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('total_in', 15, 2)->default(0);
            $table->decimal('total_out', 15, 2)->default(0);

            $table->timestamps();

            $table->index('gang_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matrix_funds');
    }
};
