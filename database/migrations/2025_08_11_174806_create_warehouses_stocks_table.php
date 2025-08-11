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
        Schema::create('warehouses_stocks', function (Blueprint $table) {
            $table->id('ware_stock_id');
            $table->foreignId('warehouse_id')->constrained('warehouses', 'warehouse_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('barang_id')->constrained('produk', 'barang_id')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('stock_quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses_stocks');
    }
};
