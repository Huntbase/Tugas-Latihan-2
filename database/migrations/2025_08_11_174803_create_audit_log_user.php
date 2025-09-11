<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_log_user', function (Blueprint $table) {
            $table->id('audit_log_user_id');
            $table->foreignId('user_id')->nullable()->constrained('m_users', 'user_id')->onDelete('set null')->onUpdate('cascade');
            $table->string('user_name')->nullable();
            $table->string('action');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_log_user');
    }
};
