<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'luisyi1998@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        $admin->assignRole('admin');

        $customer = User::create([
            'name' => 'customer',
            'email' => 'delectuslab@gmail.com',
            'password' => Hash::make('123456'),
        ]);

        $customer->assignRole('customer');
    }
}
