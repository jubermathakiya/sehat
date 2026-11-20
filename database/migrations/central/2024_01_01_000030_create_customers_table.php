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
            $table->string('tenant_code', 20)->unique();
            $table->string('clinic_name');
            $table->string('doctor_name');
            $table->string('email')->unique();
            $table->string('mobile', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('username')->unique();
            $table->string('password'); // hashed admin password snapshot for reset reference
            $table->enum('account_status', [
                'pending', 'provisioning', 'active', 'inactive', 'suspended', 'expired', 'failed',
            ])->default('pending');
            $table->foreignId('subscription_plan_id')->nullable()->constrained('subscription_plans')->nullOnDelete();
            $table->enum('billing_cycle', ['trial', 'monthly', 'quarterly', 'half_yearly', 'yearly'])->default('monthly');
            $table->date('subscription_start_date')->nullable();
            $table->date('subscription_expiry_date')->nullable();
            $table->unsignedInteger('grace_period_days')->default(0);
            $table->enum('subscription_status', [
                'scheduled', 'active', 'expired', 'cancelled', 'trial',
            ])->default('scheduled');
            $table->string('provisioning_status')->default('pending');
            $table->text('provisioning_error')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
