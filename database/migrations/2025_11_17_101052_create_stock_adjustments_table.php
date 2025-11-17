<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emporia_id')->constrained('emporia')->onDelete('cascade');
            $table->foreignId('auditor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('old_quantity', 10, 2);
            $table->decimal('new_quantity', 10, 2);
            $table->decimal('adjustment', 10, 2);
            $table->text('reason');
            $table->decimal('amount_impact', 12, 2);
            $table->decimal('profit_impact', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_adjustments');
    }
};