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
        // Super User
        User::create([
            'nama' => 'Wahyu2',
            'NIP' => '342242345',
            'password' => Hash::make('12345678'),
            'jabatan' => 'Junior Developers',
            'Alamat' => 'Jln.Batuyang Gang.pipit5b',
            'nomor_tlp' => '0812312412',
            'role' => 'superadmin',
        ]);

        // Admin Biasa
        User::create([
            'nama' => 'AriNindya',
            'NIP' => '3422423453',
            'password' => Hash::make('12345678'),
            'jabatan' => 'Junior Developers',
            'Alamat' => 'Jln.Batuyang Gang.pipit5b',
            'nomor_tlp' => '081234356',
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Ade',
            'NIP' => '34224234534',
            'password' => Hash::make('12345678'),
            'jabatan' => 'Junior Developers',
            'Alamat' => 'Jln.Batuyang Gang.pipit5b',
            'nomor_tlp' => '081234356',
            'role' => 'user',
        ]);
    }

}
