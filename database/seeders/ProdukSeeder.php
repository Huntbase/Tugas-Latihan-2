<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\db;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('produk')->insert([
            [
                'nama_barang' => 'semen 3 kaki',
                'category' => 'semen',
                'unit' => 30,
                'created_at' => now(),
            ],
            [
                'nama_barang' => 'semen 4 kaki',
                'category' => 'semen',
                'unit' => 30,
                'created_at' => now(),
            ],
            [
                'nama_barang' => 'semen 5 kaki',
                'category' => 'semen',
                'unit' => 30,
                'created_at' => now(),
            ]
        ]);
    }
}
