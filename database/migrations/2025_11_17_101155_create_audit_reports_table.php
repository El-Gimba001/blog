<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emporia_id')->constrained('emporia')->onDelete('cascade');
            $table->foreignId('auditor_id')->constrained('users')->onDelete('cascade');
            $table->string('report_title');
            $table->text('findings');
            $table->decimal('amount_adjusted', 12, 2)->default(0);
            $table->decimal('profit_adjusted', 12, 2)->default(0);
            $table->json('affected_products')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('report_sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_reports');
    }
};