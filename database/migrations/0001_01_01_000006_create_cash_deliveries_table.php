<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_deliveries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gang_id')
                ->constrained('gangs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('delivery_number', 100)->unique();

            $table->decimal('amount', 15, 2);

            $table->decimal('matrix_percent', 5, 2)->default(10.00);
            $table->decimal('commission_percent', 5, 2)->default(10.00);
            $table->decimal('operating_percent', 5, 2)->default(80.00);

            $table->decimal('matrix_amount', 15, 2)->default(0);
            $table->decimal('commission_amount', 15, 2)->default(0);
            $table->decimal('operating_amount', 15, 2)->default(0);

            $table->enum('status', [
                'pending',
                'received',
                'verified',
                'cancelled',
            ])->default('received');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('gang_id');
            $table->index('company_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_deliveries');
    }
};
