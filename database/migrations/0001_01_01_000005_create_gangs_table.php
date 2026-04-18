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

            $table->string('gang_code', 50)->unique();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

            $table->string('name', 150);
            $table->string('slug', 160)->nullable();

            $table->text('description')->nullable();
            $table->string('boss_name', 150)->nullable();
            $table->string('contact_discord', 150)->nullable();

            $table->decimal('commission_percent', 5, 2)->default(10.00);
            $table->decimal('matrix_percent', 5, 2)->default(10.00);
            $table->decimal('operating_balance', 15, 2)->default(0);

            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->index('company_id');
            $table->index('status');
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gangs');
    }
};
