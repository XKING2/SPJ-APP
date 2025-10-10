<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function create()
    {
        return view('admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:users,nip|max:255',
            'password' => 'required|max:255',
            'nama' => 'required|max:255',
            'jabatan' => 'required',
            'alamat' => 'required',
            'nomor_tlp' => 'required',
            'role' => 'required',
        ], [
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'password.required' => 'Password wajib diisi',
            'nomor_tlp.required' => 'Nomor telepon wajib diisi',
            'role.required' => 'Role wajib diisi',
        ]);

        User::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'alamat' => $request->alamat,
            'password' => $request->password,
            'nomor_tlp' => $request->nomor_tlp,
            'role' => $request->role,
        ]);



        return redirect()->route('showanggota')
                        ->with('success', 'Data anggota berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $anggota = User::findOrFail($id);
        return view('admins.edit', compact('anggota'));
    }

        public function update(Request $request, $id)
    {
        $anggota = User::findOrFail($id);

        $request->validate([
            'nip' => 'required|max:255|unique:users,nip,'.$anggota->id,
            'nama' => 'required|max:255',
            'jabatan' => 'required',
            'alamat' => 'required',
            'nomor_tlp' => 'required',
            'role' => 'required',
        ]);

        $data = [
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'alamat' => $request->alamat,
            'nomor_tlp' => $request->nomor_tlp,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $anggota->update($data);

        return redirect()->route('showanggota')
                        ->with('success', 'Data anggota berhasil diupdate!');
    }
    public function destroy($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->delete();

        return redirect()->route('showanggota')
                        ->with('success', 'Data anggota berhasil dihapus!');
    }
}
