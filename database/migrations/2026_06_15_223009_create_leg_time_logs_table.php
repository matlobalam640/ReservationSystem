<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leg_time_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_leg_id')->constrained()->cascadeOnDelete();
            $table->dateTime('engine_start_at')->nullable();
            $table->dateTime('takeoff_at')->nullable();
            $table->dateTime('landing_at')->nullable();
            $table->dateTime('engine_shutdown_at')->nullable();
            $table->decimal('flight_time_hours', 8, 2)->nullable();
            $table->decimal('block_time_hours', 8, 2)->nullable();
            $table->string('billing_method')->nullable();
            $table->decimal('calculated_cost', 12, 2)->nullable();
            $table->decimal('fixed_route_cost', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leg_time_logs');
    }
};
