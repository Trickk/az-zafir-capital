<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->string('invoice_number', 100)->unique();

            $table->foreignId('gang_id')
                ->constrained('gangs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('gang_name_snapshot', 150);
            $table->string('company_name_snapshot', 180);
            $table->string('company_legal_name_snapshot', 220)->nullable();
            $table->string('company_tax_id_snapshot', 100)->nullable();
            $table->string('company_responsible_name_snapshot', 150)->nullable();

            $table->string('concept', 180);
            $table->text('description')->nullable();

            $table->decimal('amount', 15, 2);

            $table->enum('status', [
                'draft',
                'issued',
                'paid',
                'cancelled',
            ])->default('issued');

            $table->dateTime('issued_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('pdf_path', 255)->nullable();
            $table->string('image_path', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('gang_id');
            $table->index('company_id');
            $table->index('status');
            $table->index('issued_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
