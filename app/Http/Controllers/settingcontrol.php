<?php

namespace App\Http\Controllers;

use App\Models\kegiatan;
use App\Models\pptk;
use Illuminate\Http\Request;
use App\Models\setting;
use App\Models\plt;

class settingcontrol extends Controller
{
    public function index()
    {
        $ppn = Setting::firstOrCreate(['key' => 'ppn_rate'], ['value' => 10]);
        return view('superadmins.setting.settingppn', compact('ppn'));
    }

    public function update(Request $request)
    {
        $request->validate(['ppn_rate' => 'required|numeric|min:0|max:100']);
        Setting::updateOrCreate(['key' => 'ppn_rate'], ['value' => $request->ppn_rate]);
        return back()->with('success', 'PPN berhasil diperbarui!');
    }


    public function showpptk(Request $request)
    {
        $search = $request->input('search');

        $query = Pptk::with('kegiatan'); // ✅ eager load biar gak N+1 query

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pptk', 'like', "%{$search}%")
                ->orWhere('jabatan_pptk', 'like', "%{$search}%")
                ->orWhere('nip_pptk', 'like', "%{$search}%")
                ->orWhereHas('kegiatan', function ($k) use ($search) {
                    $k->where('subkegiatan', 'like', "%{$search}%")
                        ->orWhere('kegiatan', 'like', "%{$search}%")
                        ->orWhere('program', 'like', "%{$search}%");
                });
            });
        }

        $pptks = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('superadmins.setting.settingpptk', compact('pptks'));
    }

    public function createpptk()
    {
        $pptks = Pptk::all();
        return view('superadmins.setting.createpptk', compact('pptks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program' => 'required|max:255',
            'kegiatan' => 'required|max:255',
            'subkegiatan' => 'required|max:255',
            'kasubag' => 'required|max:255',
        ]);

        // 🔹 Jika user pilih PPTK yang sudah ada
        if ($request->filled('pptk_id')) {
            $pptk = Pptk::findOrFail($request->pptk_id);
        } else {
            // 🔹 Validasi tambahan kalau buat PPTK baru
            $request->validate([
                'nama_pptk' => 'required|unique:pptk,nama_pptk|max:255',
                'nip_pptk' => 'required|max:255|unique:pptk,nip_pptk',
                'gol_pptk' => 'required|max:255',
                'jabatan_pptk' => 'required|max:255',
            ]);

            $pptk = Pptk::create([
                'nama_pptk' => $request->nama_pptk,
                'jabatan_pptk' => $request->jabatan_pptk,
                'nip_pptk' => $request->nip_pptk,
                'gol_pptk' => $request->gol_pptk,
            ]);
        }

        // 🔹 Tambahkan kegiatan baru untuk PPTK itu
        $pptk->kegiatan()->create([
            'program' => $request->program,
            'kegiatan' => $request->kegiatan,
            'subkegiatan' => $request->subkegiatan,
            'kasubag' => $request->kasubag,
        ]);

        return redirect()
            ->route('showpptk')
            ->with('success', 'Data kegiatan berhasil ditambahkan untuk PPTK ' . $pptk->nama_pptk . '!');
    }


    public function editpptk($id)
    {
        
        $pptk = Pptk::with('kegiatan')->findOrFail($id);

        return view('superadmins.setting.updatepptk', compact('pptk'));
    }

    public function updatepptk(Request $request, $id)
    {
        $pptk = Pptk::findOrFail($id);

        $validated = $request->validate([
            'nama_pptk'    => 'required|max:255|unique:pptk,nama_pptk,' . $id,
            'jabatan_pptk' => 'required|max:255',
            'nip_pptk'     => 'required|max:255|unique:pptk,nip_pptk,' . $id,
            'gol_pptk'     => 'required|max:255',
            'program'      => 'required|max:255',
            'kegiatan'     => 'required|max:255',
            'kasubag'      => 'required|max:255',
            'subkegiatan'  => 'required|array|min:1',
            'subkegiatan.*'=> 'required|string|max:255',
        ], [
            'nama_pptk.required'    => 'Nama wajib diisi',
            'nama_pptk.unique'      => 'Nama sudah terdaftar',
            'jabatan_pptk.required' => 'Jabatan wajib diisi',
            'nip_pptk.required'     => 'NIP wajib diisi',
            'nip_pptk.unique'       => 'NIP sudah terdaftar',
            'gol_pptk.required'     => 'Golongan wajib diisi',
            'program.required'      => 'Program wajib diisi',
            'kegiatan.required'     => 'Kegiatan wajib diisi',
            'kasubag.required'      => 'Kasubag wajib diisi',
            'subkegiatan.required'  => 'Minimal satu sub kegiatan wajib diisi',
        ]);

        // 🔹 Update data PPTK utama
        $pptk->update([
            'nama_pptk'    => $validated['nama_pptk'],
            'jabatan_pptk' => $validated['jabatan_pptk'],
            'nip_pptk'     => $validated['nip_pptk'],
            'gol_pptk'     => $validated['gol_pptk'],
        ]);

        // 🔹 Hapus semua kegiatan lama (opsional: kalau mau replace total)
        $pptk->kegiatan()->delete();

        // 🔹 Tambahkan ulang kegiatan baru (atau yang diperbarui)
        foreach ($validated['subkegiatan'] as $sub) {
            $pptk->kegiatan()->create([
                'program'     => $validated['program'],
                'kegiatan'    => $validated['kegiatan'],
                'subkegiatan' => $sub,
                'kasubag'     => $validated['kasubag'],
            ]);
        }

        return redirect()
            ->route('showpptk')
            ->with('success', 'Data PPTK dan kegiatan berhasil diperbarui!');
    }


    public function destroy($id)
    {
        $pptks = kegiatan::findOrFail($id);
        $pptks->delete();

        return redirect()->route('showpptk')
                        ->with('success', 'Data pptk berhasil dihapus!');
    }

    public function showplt(Request $request)
    {
        $search = $request->input('search');

        $query = plt::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pihak_pertama', 'like', "%{$search}%")
                ->orWhere('jabatan_pihak_pertama', 'like', "%{$search}%")
                ->orWhere('nip_pihak_pertama', 'like', "%{$search}%");
            });
        }

        $plts = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim ke view
        return view('superadmins.setting.settingplt', compact('plts'));
    }

    public function createplt()
    {
        return view('superadmins.setting.createplt');
    }

    public function storeplt(Request $request)
    {
        $request->validate([
            'nama_pihak_pertama' => 'required|max:255|:plt,nama_pihak_pertama',
            'jabatan_pihak_pertama' => 'required|max:255',
            'nip_pihak_pertama' => 'required|max:255',
            'gol_pihak_pertama' => 'required|max:255',
        ], [
            
            'nama_pihak_pertama.required' => 'Nama wajib diisi',
            'jabatan_pihak_pertama.required' => 'Jabatan wajib diisi',
            'gol_pihak_pertama.required' => 'Golongan wajib diisi',
            'nip_pihak_pertama.required' => 'NIP wajib diisi',
            'nip_pihak_pertama.unique' => 'NIP sudah terdaftar',
        ]);

        plt::create([
            'nama_pihak_pertama' => $request->nama_pihak_pertama,
            'jabatan_pihak_pertama' => $request->jabatan_pihak_pertama,
            'nip_pihak_pertama' => $request->nip_pihak_pertama,
            'gol_pihak_pertama' => $request->gol_pihak_pertama,
        ]);



        return redirect()->route('showplt')
                        ->with('success', 'Data Pihak Pertama berhasil ditambahkan!');
    }

    public function editplt($id)
    {
        $plt = plt::findOrFail($id);
        return view('superadmins.setting.updateplt', compact('plt'));
    }

    public function updateplt(Request $request, $id)
    {
        $plts = plt::findOrFail($id);
        $validated = $request->validate([
            'nama_pihak_pertama' => 'required|max:255|:plt,nama_pihak_pertama,' . $id,
            'jabatan_pihak_pertama'=> 'required|max:255',
            'nip_pihak_pertama'    => 'required|max:255|unique:plt,nip_pihak_pertama,' . $id,
        ], [
            'nama_pihak_pertama.required' => 'Nama wajib diisi',
            'jabatan_pihak_pertama.required' => 'Jabatan wajib diisi',
            'nip_pihak_pertama.required'   => 'NIP wajib diisi',
            'nip_pihak_pertama.unique'     => 'NIP sudah terdaftar',

        ]);

        // Gunakan array $validated untuk update
        $plts->update($validated);

        return redirect()->route('showplt')
                        ->with('success', 'Data Pihak Pertamax   berhasil diperbarui!');
    }

    public function destroyplt($id)
    {
        $plts = plt::findOrFail($id);
        $plts->delete();

        return redirect()->route('showplt')
                        ->with('success', 'Data Pihak Pertama berhasil dihapus!');
    }
}
