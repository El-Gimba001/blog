<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('emporia', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->string('code')->unique();
            $table->foreignId('administrator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('emporia');
    }
};