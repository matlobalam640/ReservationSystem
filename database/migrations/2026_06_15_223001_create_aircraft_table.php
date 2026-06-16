<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aircraft', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained()->cascadeOnDelete();
            $table->string('tail_number')->unique();
            $table->string('aircraft_type');
            $table->unsignedSmallInteger('seat_capacity')->default(6);
            $table->decimal('max_weight_kg', 10, 2)->nullable();
            $table->string('billing_type')->default('hourly');
            $table->decimal('hourly_rate', 12, 2)->nullable();
            $table->decimal('minimum_monthly_hours', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aircraft');
    }
};
