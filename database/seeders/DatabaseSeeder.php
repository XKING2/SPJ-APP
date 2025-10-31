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
            'idinjab'    => '22140102007300000010002',
            'jabatan_atasan' => 'Bos Besar',
            'role'      => 'Kasubag',
            'status'      => 'PNS',
        ]);

        User::create([
            'nama'      => 'Ari Nindya',
            'NIP'       => '1002',
            'password'  => Hash::make('password123'),
            'jabatan'   => 'Staff',
            'idinjab'    => '22140102007300000010003',
            'jabatan_atasan' => 'Bos Besar',
            'role'      => 'Bendahara',
            'status'      => 'PNS',
        ]);

        User::create([
            'nama'      => 'Budhi',
            'NIP'       => '1003',
            'password'  => Hash::make('password123'),
            'jabatan'   => 'Staff',
            'idinjab'    => '22140102007300000010004',
            'jabatan_atasan' => 'Bos Besar',
            'role'      => 'users',
            'status'      => 'PNS',
        ]);

        User::create([
            'nama'      => 'Ade',
            'NIP'       => '1004',
            'password'  => Hash::make('password123'),
            'jabatan'   => 'Staff',
            'idinjab'    => '22140102007300000010000',
            'jabatan_atasan' => 'Bos Besar',
            'role'      => 'users',
            'status'      => 'PNS',
        ]);
    }

}
