<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Operator',
            'email' => 'operator@example.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
        ]);

        User::create([
            'name' => 'Operator 1',
            'email' => 'operator1@example.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
        ]);

        User::create([
            'name' => 'Operator 2',
            'email' => 'operator2@example.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
        ]);

        User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'kedudukan' => 'Ketua RW'
        ]);

        User::create([
            'name' => 'superadmin',
            'email' => 'superadmin2@example.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'kedudukan' => 'Wakil Ketua RW'
        ]);

    }
}
