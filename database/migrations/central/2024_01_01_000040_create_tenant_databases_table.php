<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_databases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('db_name')->unique();
            $table->string('db_host')->default('127.0.0.1');
            $table->unsignedInteger('db_port')->default(3306);
            $table->string('db_username');
            $table->text('db_password'); // encrypted
            $table->enum('status', ['pending', 'ready', 'failed'])->default('pending');
            $table->timestamp('migrated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_databases');
    }
};
