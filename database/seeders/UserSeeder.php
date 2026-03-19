<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name'     => 'Admin',
            'email'    => 'antonio.medina.88@gmail.com',
            'password' => bcrypt('Astr0lla69'),
        ]);

        DB::table('users')->insert([
            'name'     => 'Tanizen',
            'email'    => 'jsptanizen@gmail.com',
            'password' => bcrypt('WarriorsForLife'),
        ]);
    }
}
