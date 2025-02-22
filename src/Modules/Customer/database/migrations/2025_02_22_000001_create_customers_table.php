<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('code', 20)->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('company_name')->nullable();
            $table->string('vat_number', 20)->nullable();
            $table->string('tax_office')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('country')->default('Cyprus');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Add indexes here instead of separate migration
            $table->index(['first_name', 'last_name']);
            $table->index('email');
            $table->index('phone');
            $table->index('mobile');
            $table->index('vat_number');
            $table->index('code');
            $table->index('is_active');
            $table->index('created_at');

            // Add full-text search index for better search performance
            $table->fullText(['first_name', 'last_name', 'email', 'company_name']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
