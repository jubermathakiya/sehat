<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans');
            $table->enum('billing_cycle', ['trial', 'monthly', 'quarterly', 'half_yearly', 'yearly']);
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->date('start_date');
            $table->date('expiry_date');
            $table->unsignedInteger('grace_period_days')->default(0);
            $table->enum('status', ['scheduled', 'active', 'expired', 'cancelled', 'trial'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('subscription_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('customer_subscription_id')->nullable()->constrained('customer_subscriptions')->nullOnDelete();
            $table->string('transaction_type'); // renew, assign, change_plan, discount, trial
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('account_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('super_admin_id')->nullable()->constrained('super_admins')->nullOnDelete();
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('meta')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('password_otps', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_code', 20)->nullable()->index();
            $table->string('email')->index();
            $table->string('otp', 10);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_otps');
        Schema::dropIfExists('account_logs');
        Schema::dropIfExists('subscription_transactions');
        Schema::dropIfExists('customer_subscriptions');
    }
};
