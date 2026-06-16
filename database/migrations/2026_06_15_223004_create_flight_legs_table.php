<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_legs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->constrained()->cascadeOnDelete();
            $table->foreignId('aircraft_id')->constrained('aircraft')->restrictOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('origin', 10);
            $table->string('destination', 10);
            $table->dateTime('departure_at');
            $table->dateTime('arrival_at')->nullable();
            $table->string('visibility')->default('public');
            $table->string('status')->default('planned');
            $table->string('return_leg_resale')->default('blocked');
            $table->boolean('is_return_leg')->default(false);
            $table->foreignId('parent_leg_id')->nullable()->constrained('flight_legs')->nullOnDelete();
            $table->decimal('base_price', 12, 2)->nullable();
            $table->json('baggage_rules')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['departure_at', 'status']);
            $table->index(['origin', 'destination']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_legs');
    }
};
