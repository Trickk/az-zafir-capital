<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gangs', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150)->unique();
            $table->string('slug', 160)->unique();
            $table->text('description')->nullable();

            $table->string('boss_name', 150)->nullable();
            $table->string('contact_discord', 150)->nullable();

            $table->decimal('dirty_balance', 15, 2)->default(0);
            $table->decimal('dirty_received_total', 15, 2)->default(0);
            $table->decimal('cleaned_total', 15, 2)->default(0);
            $table->decimal('commission_paid_total', 15, 2)->default(0);

            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gangs');
    }
};
