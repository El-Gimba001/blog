<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_ledger', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->enum('payment_type', ['Cash', 'POS', 'Transfer', 'Debt']);
            $table->unsignedBigInteger('reference_id')->nullable(); // transaction_id
            $table->decimal('debit', 12, 2)->default(0);
            $table->decimal('credit', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('reference_id')->references('id')->on('transactions')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['customer_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_ledger');
    }
};