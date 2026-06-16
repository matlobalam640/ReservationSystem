<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_passengers', function (Blueprint $table) {
            $table->decimal('actual_weight_kg', 8, 2)->nullable()->after('baggage_weight_kg');
            $table->decimal('actual_baggage_weight_kg', 8, 2)->nullable()->after('actual_weight_kg');
            $table->timestamp('checked_in_at')->nullable()->after('notes');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('agency_id')->nullable()->after('email')->constrained()->nullOnDelete();
            $table->foreignId('passenger_id')->nullable()->after('agency_id')->constrained()->nullOnDelete();
        });

        Schema::create('add_ons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('weight_kg', 8, 2)->default(0);
            $table->string('visibility')->default('public');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('booking_add_ons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('add_on_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('invoice_type');
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agency_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->string('status')->default('unpaid');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->restrictOnDelete();
            $table->string('payment_method');
            $table->decimal('amount', 12, 2);
            $table->string('reference')->nullable();
            $table->timestamp('paid_at');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('commission_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('agency_id')->nullable()->constrained()->nullOnDelete();
            $table->string('channel')->nullable();
            $table->string('split_type')->default('fixed');
            $table->decimal('hero_amount', 12, 2)->default(0);
            $table->decimal('agency_amount', 12, 2)->default(0);
            $table->unsignedSmallInteger('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('commission_ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->restrictOnDelete();
            $table->foreignId('agency_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('hero_amount', 12, 2)->default(0);
            $table->decimal('agency_amount', 12, 2)->default(0);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('credit_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account_number')->unique();
            $table->foreignId('account_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('credit_limit', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('credit_account_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('prepaid_hour_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->decimal('hours_purchased', 10, 2)->default(0);
            $table->decimal('hours_used', 10, 2)->default(0);
            $table->decimal('flight_hour_rate', 12, 2)->nullable();
            $table->decimal('ground_hour_rate', 12, 2)->nullable();
            $table->decimal('ferry_hour_rate', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('hour_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prepaid_hour_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flight_leg_id')->nullable()->constrained()->nullOnDelete();
            $table->string('usage_type')->default('flight');
            $table->decimal('hours', 8, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('operator_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained()->restrictOnDelete();
            $table->string('invoice_reference');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('total_hours', 10, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('reconciliation_discrepancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_invoice_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->decimal('internal_value', 12, 2)->nullable();
            $table->decimal('operator_value', 12, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('resolved')->default(false);
            $table->timestamps();
        });

        Schema::create('medevac_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_leg_id')->constrained()->cascadeOnDelete();
            $table->string('patient_name');
            $table->text('condition')->nullable();
            $table->text('vitals')->nullable();
            $table->string('pickup_location')->nullable();
            $table->string('dropoff_location')->nullable();
            $table->string('category')->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('cargo_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_leg_id')->constrained()->cascadeOnDelete();
            $table->string('client_name');
            $table->decimal('weight_kg', 10, 2);
            $table->string('origin', 10);
            $table->string('destination', 10);
            $table->decimal('invoice_amount', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('staff_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->decimal('per_flight_rate', 12, 2)->nullable();
            $table->decimal('per_leg_rate', 12, 2)->nullable();
            $table->decimal('per_day_rate', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('staff_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flight_leg_id')->constrained()->cascadeOnDelete();
            $table->date('assignment_date');
            $table->decimal('calculated_pay', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('ground_handlers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('default_rate', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('leg_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_leg_id')->constrained()->cascadeOnDelete();
            $table->string('cost_type');
            $table->string('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });

        Schema::create('hero_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->nullable()->constrained()->nullOnDelete();
            $table->string('member_code')->unique();
            $table->string('plan_level');
            $table->string('member_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('covered_members')->nullable();
            $table->timestamps();
        });

        Schema::create('membership_benefit_rules', function (Blueprint $table) {
            $table->id();
            $table->string('plan_level');
            $table->string('benefit_type');
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->decimal('fixed_discount', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->constrained()->cascadeOnDelete();
            $table->integer('points')->default(0);
            $table->string('source')->nullable();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
        Schema::dropIfExists('membership_benefit_rules');
        Schema::dropIfExists('hero_memberships');
        Schema::dropIfExists('leg_costs');
        Schema::dropIfExists('ground_handlers');
        Schema::dropIfExists('staff_assignments');
        Schema::dropIfExists('staff_members');
        Schema::dropIfExists('cargo_shipments');
        Schema::dropIfExists('medevac_cases');
        Schema::dropIfExists('reconciliation_discrepancies');
        Schema::dropIfExists('operator_invoices');
        Schema::dropIfExists('hour_usage_logs');
        Schema::dropIfExists('prepaid_hour_accounts');
        Schema::dropIfExists('credit_account_entries');
        Schema::dropIfExists('credit_accounts');
        Schema::dropIfExists('commission_ledger');
        Schema::dropIfExists('commission_rules');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('booking_add_ons');
        Schema::dropIfExists('add_ons');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('passenger_id');
            $table->dropConstrainedForeignId('agency_id');
        });

        Schema::table('booking_passengers', function (Blueprint $table) {
            $table->dropColumn(['actual_weight_kg', 'actual_baggage_weight_kg', 'checked_in_at']);
        });
    }
};
