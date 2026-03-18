<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
                ->constrained('invoices')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('gang_id')
                ->constrained('gangs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('holding_id')
                ->constrained('holdings')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('settlement_number', 100)->unique();

            $table->decimal('gross_amount', 15, 2);
            $table->decimal('commission_percent', 5, 2)->default(20.00);
            $table->decimal('commission_amount', 15, 2);
            $table->decimal('net_amount', 15, 2);

            $table->enum('status', [
                'pending',
                'processed',
                'released',
                'cancelled',
            ])->default('pending');

            $table->timestamp('processed_at')->nullable();
            $table->timestamp('released_at')->nullable();

            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('released_by')->nullable()->constrained('users')->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_id');
            $table->index('gang_id');
            $table->index('holding_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};
