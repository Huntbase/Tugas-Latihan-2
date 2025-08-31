<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserSeeder  extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'user_name' => 'Admin',
            'password' => Hash::make('admin'),
            'role' => 'admin'
        ]);

        User::create([
            'user_name' => 'Supervisor',
            'password' => Hash::make('Supervisor'),
            'role' => 'supervisor'
        ]);

        User::create([
            'user_name' => 'Staff',
            'password' => Hash::make('Supervisor'),
            'role' => 'staff'
        ]);
    }
}
