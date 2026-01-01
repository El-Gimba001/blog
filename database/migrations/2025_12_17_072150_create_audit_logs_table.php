<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action_type', 100); // login, sale, adjustment, delete
            $table->string('action', 255);
            $table->string('table_name', 100)->nullable();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['user_id']);
            $table->index(['created_at']);
            $table->index(['table_name', 'record_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};