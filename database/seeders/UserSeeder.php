<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
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

        $adminRole = Role::where('role_name', 'Admin')->firstOrFail();
        $supervisorRole = Role::where('role_name', 'Supervisor')->firstOrFail();
        $staffRole = Role::where('role_name', 'Staff')->firstOrFail();
        User::whereNull('role_id')->update(['role_id' => $staffRole->id]);

        User::create([
            'user_name' => 'Admin',
            'password' => Hash::make('admin'),
            'role_id' => $adminRole->id
        ]);

        User::create([
            'user_name' => 'Supervisor',
            'password' => Hash::make('Supervisor'),
            'role_id' => $supervisorRole->id
        ]);

        User::create([
            'user_name' => 'Staff',
            'password' => Hash::make('staff'),
            'role_id' => $staffRole->id
        ]);
    }
}
