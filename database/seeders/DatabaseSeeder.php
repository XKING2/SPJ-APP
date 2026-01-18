<?php

namespace Database\Seeders;

use App\Models\pihakkedua;
use App\Models\plt;
use App\Models\setting;
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



        plt::create([
            'nama_pihak_pertama'     => 'I Gede Daging, SSTP.,M.Si',
            'jabatan_pihak_pertama'  => 'Kepala Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Gianyar',
            'nip_pihak_pertama'      => '197709021997111001',
            'gol_pihak_pertama'      => 'Pembina Tingkat I (IV/b)',

        ]);

        pihakkedua::create([
            'nama_pihak_kedua'     => 'I Dewa Gede Putra,S.sos',
            'jabatan_pihak_kedua'  => 'Pengurus Barang Pengguna pada Dinas Pemberdayaan Masyarakat dan Desa Kabupaten Gianyar',
            'nip_pihak_kedua'      => '-',
            'gol_pihak_kedua'      => '-',

        ]);

        setting::create([
            'key'                  => 'ppn_rate',
            'value'                => '11',

        ]);

        setting::create([
            'key'                  => 'pph_22',
            'value'                => '1.5',

        ]);

        setting::create([
            'key'                  => 'pph_23',
            'value'                => '2',

        ]);
    }

}
