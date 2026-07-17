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
            $table->string('transfer_code')->unique(); // e.g. TRF-20250712-0001

            // produk PK is barang_id
            $table->foreignId('barang_id')->constrained('produk', 'barang_id')->cascadeOnDelete();

            // warehouses PK is warehouse_id
            $table->foreignId('from_warehouse_id')->constrained('warehouses', 'warehouse_id')->cascadeOnDelete();
            $table->foreignId('to_warehouse_id')->constrained('warehouses', 'warehouse_id')->cascadeOnDelete();

            $table->unsignedInteger('quantity');

            // pending   -> just created, stock not yet moved
            // completed -> approved & received, stock has moved
            // rejected  -> denied, stock not moved
            // cancelled -> cancelled by requester before approval
            $table->enum('status', ['pending', 'completed', 'rejected', 'cancelled'])
                ->default('pending');

            // users table is actually named m_users, PK is user_id
            $table->unsignedBigInteger('requested_by');
            $table->foreign('requested_by')->references('user_id')->on('m_users');

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('user_id')->on('m_users');

            $table->timestamp('approved_at')->nullable();
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
