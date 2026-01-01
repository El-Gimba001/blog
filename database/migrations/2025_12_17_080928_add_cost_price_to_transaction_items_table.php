<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            // SAFE: Check if column exists before adding
            if (!Schema::hasColumn('transaction_items', 'cost_price')) {
                $table->decimal('cost_price', 12, 2)
                      ->default(0)
                      ->after('unit_price');
                
                // Note: We'll handle profit calculation in the model
                // because profit column already exists
            }
        });
    }

    public function down()
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            // SAFE: Check before dropping
            if (Schema::hasColumn('transaction_items', 'cost_price')) {
                $table->dropColumn('cost_price');
            }
        });
    }
};