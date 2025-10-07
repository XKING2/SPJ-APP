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
            'nama'      => 'User Biasa2',
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
