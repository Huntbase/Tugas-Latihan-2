<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_code')->unique();

            $table->foreignId('barang_id')->constrained('produk', 'barang_id')->cascadeOnDelete();
            $table->foreignId('from_warehouse_id')->constrained('warehouses', 'warehouse_id')->cascadeOnDelete();
            $table->foreignId('to_warehouse_id')->constrained('warehouses', 'warehouse_id')->cascadeOnDelete();

            $table->unsignedInteger('quantity');

            // State machine: draft -> menunggu_approval -> disetujui -> dikirim -> diterima/ditolak
            $table->enum('status', ['draft', 'menunggu_approval', 'disetujui', 'dikirim', 'diterima', 'ditolak'])
                ->default('draft');

            $table->unsignedBigInteger('requested_by');
            $table->foreign('requested_by')->references('user_id')->on('m_users');

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('user_id')->on('m_users');

            $table->unsignedBigInteger('shipped_by')->nullable();
            $table->foreign('shipped_by')->references('user_id')->on('m_users');

            $table->unsignedBigInteger('received_by')->nullable();
            $table->foreign('received_by')->references('user_id')->on('m_users');

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('received_at')->nullable();

            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
