<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_transaction_id')->nullable()->constrained('transactions')->nullOnDelete(); // For refunds
            
            // Transaction identifiers
            $table->string('transaction_number')->unique(); // TXN20251104XXXX
            $table->string('gateway_transaction_id')->nullable()->index(); // Payment gateway's ID
            
            // Payment details
            $table->enum('payment_method', ['cod', 'card', 'upi', 'wallet', 'paypal', 'net_banking'])->default('cod');
            $table->enum('type', ['payment', 'refund', 'partial_refund'])->default('payment');
            $table->enum('status', ['pending', 'processing', 'success', 'failed', 'cancelled', 'refunded'])->default('pending');
            
            // Financial details
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->decimal('gateway_fee', 10, 2)->default(0);
            
            // Payment gateway details
            $table->string('gateway')->nullable(); // razorpay, stripe, paypal, etc.
            $table->text('gateway_response')->nullable();
            
            // Card/Bank details (masked for security)
            $table->string('card_last_four')->nullable();
            $table->string('card_brand')->nullable(); // visa, mastercard, etc.
            $table->string('bank_name')->nullable();
            
            // Additional info
            $table->string('ip_address', 45)->nullable();
            $table->text('notes')->nullable();
            $table->text('failure_reason')->nullable();
            
            // Timestamps
            $table->timestamp('attempted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('order_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};