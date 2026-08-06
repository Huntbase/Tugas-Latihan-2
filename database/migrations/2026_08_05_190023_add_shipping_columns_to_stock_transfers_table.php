<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('shipped_by')->nullable()->after('approved_by');
            $table->timestamp('shipped_at')->nullable()->after('approved_at');

            $table->unsignedBigInteger('received_by')->nullable()->after('shipped_at');
            $table->timestamp('received_at')->nullable()->after('shipped_at');

            $table->foreign('shipped_by')->references('user_id')->on('m_users');
            $table->foreign('received_by')->references('user_id')->on('m_users');
        });
    }

    public function down(): void
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropForeign(['shipped_by']);
            $table->dropForeign(['received_by']);
            $table->dropColumn(['shipped_by', 'shipped_at', 'received_by', 'received_at']);
        });
    }
};
