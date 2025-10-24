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
            'spj_id'    => null,
            'NIP'       => '1001',
            'password'  => Hash::make('password123'),
            'jabatan'   => 'Staff',
            'Alamat'    => 'Jl. Mawar No.3',
            'nomor_tlp' => '0812777',
            'role'      => 'Kasubag',
        ]);

         User::create([
            'nama'      => 'Ari Nindya',
            'spj_id'    => null,
            'NIP'       => '1002',
            'password'  => Hash::make('password123'),
            'jabatan'   => 'Staff',
            'Alamat'    => 'Jl. Mawar No.3',
            'nomor_tlp' => '0812777',
            'role'      => 'Bendahara',
        ]);

         User::create([
            'nama'      => 'Budhi',
            'spj_id'    => null,
            'NIP'       => '1003',
            'password'  => Hash::make('password123'),
            'jabatan'   => 'Staff',
            'Alamat'    => 'Jl. Mawar No.3',
            'nomor_tlp' => '0812777',
            'role'      => 'user',
        ]);
         User::create([
            'nama'      => 'Ade',
            'spj_id'    => null,
            'NIP'       => '1004',
            'password'  => Hash::make('password123'),
            'jabatan'   => 'Staff',
            'Alamat'    => 'Jl. Mawar No.3',
            'nomor_tlp' => '0812777',
            'role'      => 'user',
        ]);
    }

}
