<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_service_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->date('service_date');
            $table->string('service_type');
            $table->text('description');
            $table->integer('mileage');
            $table->string('mileage_unit')->default('km');
            $table->string('technician_name')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('status')->default('completed');
            $table->text('notes')->nullable();
            $table->foreignId('work_order_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes
            $table->index('vehicle_id');
            $table->index('service_date');
            $table->index('service_type');
            $table->index('status');
            $table->index('work_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_service_history');
    }
};
