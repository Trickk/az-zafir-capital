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
            $table->string('internal_reference', 100)->nullable()->unique();

            $table->string('gang_name_snapshot', 150);

            $table->string('company_name_snapshot', 180);
            $table->string('company_legal_name_snapshot', 220)->nullable();
            $table->string('company_type_snapshot', 120)->nullable();
            $table->string('company_country_snapshot', 120)->nullable();
            $table->string('company_city_snapshot', 120)->nullable();
            $table->string('company_address_snapshot')->nullable();
            $table->string('company_tax_id_snapshot', 100)->nullable();
            $table->string('company_logo_path_snapshot')->nullable();
            $table->string('company_invoice_image_path_snapshot')->nullable();

            $table->string('concept', 255);
            $table->text('description')->nullable();

            $table->decimal('gross_amount', 15, 2);
            $table->decimal('settlement_percent', 5, 2)->default(80.00);
            $table->decimal('commission_percent', 5, 2)->default(20.00);
            $table->decimal('commission_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);

            $table->date('issued_at');
            $table->date('due_at')->nullable();

            $table->enum('status', [
                'draft',
                'pending',
                'approved',
                'rejected',
                'paid',
                'cancelled',
            ])->default('draft');

            $table->boolean('is_generated_image')->default(false);
            $table->string('public_image_path')->nullable();
            $table->string('pdf_path')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_number');
            $table->index('status');
            $table->index('issued_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
