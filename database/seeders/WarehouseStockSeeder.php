<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseStockSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('warehouse_stocks')->insert([
            [
                'warehouse_id'   => 1,
                'barang_id'      => 1,
                'stock_quantity' => 100,
                'created_at' => now(),
            ],
            [
                'warehouse_id'   => 1,
                'barang_id'      => 2,
                'stock_quantity' => 50,
                'created_at' => now(),
            ],
            [
                'warehouse_id'   => 2,
                'barang_id'      => 1,
                'stock_quantity' => 200,
                'created_at' => now(),
            ],
            [
                'warehouse_id'   => 2,
                'barang_id'      => 3,
                'stock_quantity' => 75,
                'created_at' => now(),
            ],
        ]);
    }
}
