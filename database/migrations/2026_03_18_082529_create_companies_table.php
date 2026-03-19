<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('name', 180)->unique();
            $table->string('slug', 190)->unique();
            $table->string('legal_name', 220)->nullable();

            $table->enum('type', [
                'cultural',
                'logistics',
                'hospitality',
                'investment',
                'entertainment',
                'security',
                'technology',
                'trading',
                'other',
            ])->default('other');

            $table->string('country', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('address')->nullable();
            $table->string('tax_id', 100)->nullable();

            $table->text('description')->nullable();

            $table->string('logo_path')->nullable();
            $table->string('invoice_image_path')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
