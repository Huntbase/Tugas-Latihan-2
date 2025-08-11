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
        Schema::create('barang_dipindah', function (Blueprint $table) {
            $table->id('barang_dipindah_id');
            $table->foreignId('transfer_id')->constrained('transfers', 'transfers_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('barang_id')->constrained('produk', 'barang_id')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('banyaknya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_dipindah');
    }
};
