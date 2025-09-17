<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_dipindah', function (Blueprint $table) {
            $table->id('barang_dipindah_id');

            $table->foreignId('transfer_id')
                ->constrained('transfers', 'transfer_id') // ✅ harus sama dengan PK
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('barang_id')
                ->constrained('produk', 'barang_id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->integer('banyaknya');
            $table->timestamps(); // tambahkan biar konsisten
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_dipindah');
    }
};
