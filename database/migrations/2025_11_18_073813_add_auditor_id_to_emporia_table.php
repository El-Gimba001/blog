<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuditorIdToEmporiaTable extends Migration
{
    public function up()
    {
        Schema::table('emporia', function (Blueprint $table) {
            $table->foreignId('auditor_id')->nullable()->after('manager_id')->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('emporia', function (Blueprint $table) {
            $table->dropForeign(['auditor_id']);
            $table->dropColumn('auditor_id');
        });
    }
}