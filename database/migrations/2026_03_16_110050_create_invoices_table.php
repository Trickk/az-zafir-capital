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

            $table->foreignId('gang_id')
                ->constrained('gangs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('holding_id')
                ->constrained('holdings')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('invoice_number', 100)->unique();
            $table->string('internal_reference', 100)->nullable()->unique();

            $table->string('concept', 255);
            $table->text('description')->nullable();

            $table->decimal('gross_amount', 15, 2);

            $table->date('issued_at');
            $table->date('due_at')->nullable();

            $table->enum('status', [
                'draft',
                'pending',
                'reviewed',
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

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('gang_id');
            $table->index('holding_id');
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
