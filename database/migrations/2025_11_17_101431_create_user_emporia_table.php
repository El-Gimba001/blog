<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_emporia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('emporia_id')->constrained('emporia')->onDelete('cascade');
            $table->string('role');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            $table->unique(['user_id', 'emporia_id', 'role']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_emporia');
    }
};