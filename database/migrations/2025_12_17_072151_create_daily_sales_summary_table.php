<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('daily_sales_summary', function (Blueprint $table) {
            $table->id();
            $table->date('sale_date');
            $table->unsignedBigInteger('store_id'); // emporia_id
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->integer('total_transactions')->default(0);
            $table->integer('total_products_sold')->default(0);
            $table->decimal('total_profit', 12, 2)->default(0);
            $table->decimal('avg_transaction_value', 10, 2)->default(0);
            $table->timestamps();
            
            // Foreign key
            $table->foreign('store_id')->references('id')->on('emporia')->onDelete('cascade');
            
            // Unique constraint and indexes
            $table->unique(['sale_date', 'store_id']);
            $table->index(['sale_date']);
            $table->index(['store_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_sales_summary');
    }
};