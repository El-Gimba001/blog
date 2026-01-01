<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // SAFE: Check if columns exist before adding
            
            if (!Schema::hasColumn('transactions', 'hold_reason')) {
                $table->text('hold_reason')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('transactions', 'held_by')) {
                $table->unsignedBigInteger('held_by')->nullable();
                $table->foreign('held_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('transactions', 'held_at')) {
                $table->timestamp('held_at')->nullable();
            }
            
            if (!Schema::hasColumn('transactions', 'released_by')) {
                $table->unsignedBigInteger('released_by')->nullable();
                $table->foreign('released_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('transactions', 'released_at')) {
                $table->timestamp('released_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // SAFE: Check before dropping
            
            if (Schema::hasColumn('transactions', 'released_by')) {
                $table->dropForeign(['released_by']);
            }
            
            if (Schema::hasColumn('transactions', 'held_by')) {
                $table->dropForeign(['held_by']);
            }
            
            $columns = ['hold_reason', 'held_by', 'held_at', 'released_by', 'released_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};