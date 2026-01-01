<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add primary store assignment after role column
            $table->unsignedBigInteger('primary_store_id')->after('role')->nullable();
            $table->foreign('primary_store_id')->references('id')->on('emporia')->onDelete('set null');
            
            // Also add phone field for store managers
            $table->string('phone')->after('email')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['primary_store_id']);
            $table->dropColumn(['primary_store_id', 'phone']);
        });
    }
};