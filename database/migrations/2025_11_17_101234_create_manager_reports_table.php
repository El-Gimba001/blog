<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('manager_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emporia_id')->constrained('emporia')->onDelete('cascade');
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('total_profit', 12, 2)->default(0);
            $table->integer('transaction_count')->default(0);
            $table->json('sales_data');
            $table->boolean('is_reconstructed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('manager_reports');
    }
};