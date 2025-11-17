<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add emporia_id to products table
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('emporia_id')->after('id')->nullable()->constrained('emporia')->onDelete('cascade');
        });

        // Add emporia_id to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('emporia_id')->after('user_id')->nullable()->constrained('emporia')->onDelete('cascade');
            $table->enum('report_status', ['pending', 'audited', 'approved'])->default('pending')->after('status');
            $table->boolean('included_in_report')->default(false)->after('report_status');
        });

        // Add emporia_id to transaction_items table
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->foreignId('emporia_id')->after('id')->nullable()->constrained('emporia')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['emporia_id']);
            $table->dropColumn('emporia_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['emporia_id']);
            $table->dropColumn(['emporia_id', 'report_status', 'included_in_report']);
        });

        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign(['emporia_id']);
            $table->dropColumn('emporia_id');
        });
    }
};