<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        User::create([
            'nama'      => 'Wahyu Aditya',
            'NIP'       => '1001',
            'password'  => Hash::make('password123'),
            'jabatan'   => 'Staff',
            'idinjab'    => '2301010564',
            'jabatan_atasan' => 'Staff IT',
            'role'      => 'Kasubag',
            'status'      => 'PNS',
        ]);
        User::create([
            'nama'      => 'Ade',
            'NIP'       => '1002',
            'password'  => Hash::make('password123'),
            'jabatan'   => 'Staff',
            'idinjab'    => '2301010564',
            'jabatan_atasan' => 'Staff IT',
            'role'      => 'Kasubag',
            'status'      => 'PNS',
        ]);
        User::create([
            'nama'      => 'Ari Nindya',
            'NIP'       => '1003',
            'password'  => Hash::make('password123'),
            'jabatan'   => 'Staff',
            'idinjab'    => '2301010564',
            'jabatan_atasan' => 'Staff IT',
            'role'      => 'Bendahara',
            'status'      => 'PNS',
        ]);
        User::create([
            'nama'      => 'I Gede Wahyu Aditya',
            'NIP'       => '1004',
            'password'  => Hash::make('password123'),
            'jabatan'   => 'Staff',
            'idinjab'    => '2301010564',
            'jabatan_atasan' => 'Staff IT',
            'role'      => 'users',
            'status'      => 'PNS',
        ]);
    }

}
