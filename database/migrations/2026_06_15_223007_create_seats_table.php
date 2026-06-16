<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_leg_id')->constrained()->cascadeOnDelete();
            $table->string('seat_number', 10);
            $table->string('seat_type')->default('passenger');
            $table->boolean('is_available')->default(true);
            $table->decimal('max_weight_kg', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['flight_leg_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
