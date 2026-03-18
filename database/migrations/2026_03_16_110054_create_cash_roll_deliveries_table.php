<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_roll_deliveries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gang_id')
                ->constrained('gangs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('holding_id')
                ->constrained('holdings')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('delivery_number', 100)->unique();

            $table->decimal('amount', 15, 2);
            $table->unsignedInteger('roll_count')->default(1);

            $table->enum('status', [
                'pending',
                'received',
                'verified',
                'cancelled',
            ])->default('received');

            $table->string('delivered_by', 150)->nullable();
            $table->string('received_by', 150)->nullable();

            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('received_at')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('gang_id');
            $table->index('holding_id');
            $table->index('status');
            $table->index('delivered_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_roll_deliveries');
    }
};
