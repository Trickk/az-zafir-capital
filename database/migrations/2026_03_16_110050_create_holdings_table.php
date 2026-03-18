<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holdings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('gang_id')
                ->constrained('gangs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('name', 180)->unique();
            $table->string('slug', 190)->unique();
            $table->string('legal_name', 220)->nullable();

            $table->string('sector', 150)->nullable();
            $table->string('contact_name', 150)->nullable();
            $table->string('contact_phone', 80)->nullable();
            $table->string('contact_email', 180)->nullable();

            $table->unsignedTinyInteger('trust_level')->default(1);
            $table->decimal('default_commission_percent', 5, 2)->default(20.00);

            $table->decimal('dirty_balance', 15, 2)->default(0);
            $table->decimal('cleaned_total', 15, 2)->default(0);
            $table->decimal('commission_paid_total', 15, 2)->default(0);

            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('gang_id');
            $table->index('slug');
            $table->index('trust_level');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holdings');
    }
};
