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
        Schema::create('perpindahan_stock', function (Blueprint $table) {
            $table->id('move_stock_id');
            $table->foreignId('warehouse_id')->constrained('warehouses', 'warehouse_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('barang_id')->constrained('produk', 'barang_id')->onDelete('cascade')->onUpdate('cascade');
            $table->string('type');
            $table->integer('quantity');
            $table->unsignedBigInteger('reference_id');
            $table->string('dibuat_oleh');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perpindahan_stock');
    }
};
