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
        Schema::table('m_users', function (Blueprint $table) {
            if (Schema::hasColumn('m_users', 'role')) {
                // Hapus foreign key jika ada
                $table->dropForeign(['role']);
                $table->dropColumn('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('m_users', function (Blueprint $table) {
            $table->foreignId('role')->nullable()->constrained('roles')->nullOnDelete();
        });
    }
};
