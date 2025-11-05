<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. SAVE10, DIWALI2025
            $table->string('name')->nullable(); // internal label (e.g. “New User Offer”)
            $table->text('description')->nullable();

            // Discount details
            $table->enum('type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('value', 10, 2); // amount or percentage
            $table->decimal('max_discount', 10, 2)->nullable(); // for percentage caps

            // Rules
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable(); // total uses allowed
            $table->integer('usage_limit_per_user')->nullable(); // per user limit
            $table->integer('used_count')->default(0);

            // Validity period
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
