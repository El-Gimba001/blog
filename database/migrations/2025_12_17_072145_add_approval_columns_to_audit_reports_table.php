<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('audit_reports', function (Blueprint $table) {
            // Manager approval (after existing status)
            $table->enum('manager_approval_status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('status');
                  
            $table->unsignedBigInteger('manager_approved_by')->nullable()->after('manager_approval_status');
            $table->foreign('manager_approved_by')->references('id')->on('users');
            $table->timestamp('manager_approved_at')->nullable();
            $table->text('manager_comments')->nullable();
            
            // Admin approval  
            $table->enum('admin_approval_status', ['pending', 'approved', 'rejected'])
                  ->default('pending');
                  
            $table->unsignedBigInteger('admin_approved_by')->nullable();
            $table->foreign('admin_approved_by')->references('id')->on('users');
            $table->timestamp('admin_approved_at')->nullable();
            $table->text('admin_comments')->nullable();
        });
    }

    public function down()
    {
        Schema::table('audit_reports', function (Blueprint $table) {
            // Drop manager columns
            $table->dropForeign(['manager_approved_by']);
            $table->dropColumn([
                'manager_approval_status',
                'manager_approved_by',
                'manager_approved_at',
                'manager_comments'
            ]);
            
            // Drop admin columns
            $table->dropForeign(['admin_approved_by']);
            $table->dropColumn([
                'admin_approval_status',
                'admin_approved_by',
                'admin_approved_at',
                'admin_comments'
            ]);
        });
    }
};