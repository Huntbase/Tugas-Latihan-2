<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_items', function (Blueprint $table) {
            $table->id('transfer_item_id');

            $table->foreignId('transfer_id')
                ->constrained('transfers', 'transfer_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('barang_id')
                ->constrained('produk', 'barang_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedInteger('quantity'); // tidak boleh negatif

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_items');
    }
};
