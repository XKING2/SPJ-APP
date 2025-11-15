<?php

namespace App\Http\Controllers;

use App\Models\kegiatan;
use App\Models\User;
use App\Models\Kasubag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function create()
    {
        $subKegiatans = Kegiatan::all();
        return view('superadmins.create', compact('subKegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:users,nip|max:255',
            'password' => 'required|max:255',
            'nama' => 'required|max:255',
            'jabatan' => 'required',
            'idinjab' => 'required',
            'jabatan_atasan' => 'required|max:255',
            'role' => 'required',
            'status' => 'required',
        ], [
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'idinjab.required' => 'Idinjab wajib diisi',
            'password.required' => 'Password wajib diisi',
            'jabatan_atasan.required' => 'Jabatan Atasan Langsung wajib diisi',
            'role.required' => 'Role wajib diisi',
            'status.required' => 'Status wajib diisi',
        ]);

        $user = User::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'idinjab' => $request->idinjab,
            'password' => Hash::make($request->password),
            'jabatan_atasan' => $request->jabatan_atasan,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('showanggota')
            ->with('success', 'Data anggota berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $anggota = User::findOrFail($id);
        return view('superadmins.edit', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $anggota = User::findOrFail($id);

        $request->validate([
            'nip' => 'required|max:255|unique:users,nip,' . $anggota->id,
            'nama' => 'required|max:255',
            'jabatan' => 'required',
            'idinjab' => 'required',
            'jabatan_atasan' => 'required|max:255',
            'role' => 'required',
            'status' => 'required',
            'password' => 'nullable|max:255',
        ], [
            'nip.required' => 'NIP wajib diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'nama.required' => 'Nama wajib diisi',
            'jabatan.required' => 'Jabatan wajib diisi',
            'idinjab.required' => 'Idinjab wajib diisi',
            'jabatan_atasan.required' => 'Jabatan Atasan Langsung wajib diisi',
            'role.required' => 'Role wajib diisi',
            'status.required' => 'Status wajib diisi',
        ]);

        $data = [
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'idinjab' => $request->idinjab,
            'jabatan_atasan' => $request->jabatan_atasan,
            'role' => $request->role,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $anggota->update($data);

        return redirect()
            ->route('showanggota')
            ->with('success', 'Data anggota berhasil diperbarui!');
    }
    
    public function destroy($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->delete();

        return redirect()->route('showanggota')
                        ->with('success', 'Data anggota berhasil dihapus!');
    }
}
