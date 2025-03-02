<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('code', 20)->unique();
            $table->string('license_plate')->unique();
            $table->string('vin')->nullable()->unique();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->string('color')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('engine_type')->nullable();
            $table->string('transmission')->nullable();
            $table->integer('mileage')->default(0);
            $table->string('mileage_unit')->default('km');
            $table->text('notes')->nullable();
            $table->string('status')->default('active');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // Add indexes
            $table->index('make');
            $table->index('model');
            $table->index('year');
            $table->index('status');
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
