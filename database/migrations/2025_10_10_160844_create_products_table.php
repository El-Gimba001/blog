<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('category')->nullable();
            $table->string('unit');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('cost_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('profit', 10, 2)->default(0)->after('selling_price');

            // If profit not yet added here, it's OK – we'll add it in another migration
            // $table->decimal('profit', 10, 2)->default(0);

            $table->string('reference')->unique()->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('products', function(Blueprint $table){
            $table->dropColumn('profit');
        });
    }
};

