<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, update existing 'Sales' role to 'sales_user'
        DB::table('users')->where('role', 'Sales')->update(['role' => 'sales_user']);
        
        // Then modify the column to use enum
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'administrator', 
                'manager', 
                'auditor', 
                'sales_user', 
                'store_manager'
            ])->default('sales_user')->change();
        });
    }

    public function down()
    {
        // Revert back to string
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('sales_user')->change();
        });
        
        // Revert role values if needed
        DB::table('users')->where('role', 'sales_user')->update(['role' => 'Sales']);
    }
};