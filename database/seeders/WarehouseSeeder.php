<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\db;


class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('warehouses')->insert([
            [
                'name_id' => 'Ciomas',
                'location_id' => 'semen',
                'description' => 'Someting Just Like this',
                'created_at' => now(),
            ],
            [
                'name_id' => 'Ciomas 2',
                'location_id' => 'semen',
                'description' => 'Someting Just Like this',
                'created_at' => now(),
            ],
            [
                'name_id' => 'Ciomas 3',
                'location_id' => 'semen',
                'description' => 'Someting Just Like this',
                'created_at' => now(),
            ]
        ]);
    }
}
