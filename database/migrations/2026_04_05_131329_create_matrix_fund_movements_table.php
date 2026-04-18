<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matrix_fund_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gang_id')
                ->constrained('gangs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('matrix_fund_id')
                ->constrained('matrix_funds')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->enum('type', ['in', 'out', 'adjustment']);
            $table->decimal('amount', 15, 2);
            $table->string('concept', 150);
            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('gang_id');
            $table->index('matrix_fund_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matrix_fund_movements');
    }
};
