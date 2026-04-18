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

            $table->string('company_code', 50)->unique();
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
            ])->default('investment');

            $table->string('country', 100)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('tax_id', 100)->nullable()->unique();
            $table->string('responsible_name', 150)->nullable();
            $table->text('description')->nullable();

            $table->string('logo_path', 255)->nullable();
            $table->string('invoice_image_path', 255)->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
