<?php

namespace App\Http\Controllers;

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

        $query = pptk::query();

        // Filter pencarian (berdasarkan nama, jabatan, nip, dsb)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pptk', 'like', "%{$search}%")
                ->orWhere('jabatan_pptk', 'like', "%{$search}%")
                ->orWhere('subkegiatan', 'like', "%{$search}%")
                ->orWhere('nip_pptk', 'like', "%{$search}%");
            });
        }

        // Urutkan dari yang terbaru
        $pptks = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim ke view
        return view('superadmins.setting.settingpptk', compact('pptks'));
    }

    public function createpptk()
    {
        return view('superadmins.setting.createpptk');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pptk' => 'required|unique:pptk,nama_pptk|max:255',
            'jabatan_pptk' => 'required|max:255',
            'nip_pptk' => 'required|max:255',
            'subkegiatan' => 'required',
        ], [
            
            'nama_pptk.required' => 'Nama wajib diisi',
            'jabatan_pptk.required' => 'Jabatan wajib diisi',
            'nip_pptk.required' => 'NIP wajib diisi',
            'nip_pptk.unique' => 'NIP sudah terdaftar',
            'subkegiatan.required' => 'Alamat wajib diisi',
        ]);

        pptk::create([
            'nama_pptk' => $request->nama_pptk,
            'jabatan_pptk' => $request->jabatan_pptk,
            'nip_pptk' => $request->nip_pptk,
            'subkegiatan' => $request->subkegiatan,
        ]);



        return redirect()->route('showpptk')
                        ->with('success', 'Data PPTK berhasil ditambahkan!');
    }

    public function editpptk($id)
    {
        $pptk = pptk::findOrFail($id);
        return view('superadmins.setting.updatepptk', compact('pptk'));
    }

    public function updatepptk(Request $request, $id)
    {
        $pptk = pptk::findOrFail($id);

        // Validasi — untuk rule unique kita kecualikan id yang sedang diupdate
        $validated = $request->validate([
            'nama_pptk'   => 'required|max:255|pptk,nama_pptk,' . $id,
            'jabatan_pptk'=> 'required|max:255',
            'nip_pptk'    => 'required|max:255|unique:pptk,nip_pptk,' . $id,
            'subkegiatan' => 'required',
        ], [
            'nama_pptk.required' => 'Nama wajib diisi',
            'nama_pptk.unique'   => 'Nama sudah terdaftar',
            'jabatan_pptk.required' => 'Jabatan wajib diisi',
            'nip_pptk.required'   => 'NIP wajib diisi',
            'nip_pptk.unique'     => 'NIP sudah terdaftar',
            'subkegiatan.required'=> 'Sub kegiatan wajib diisi',
        ]);

        // Gunakan array $validated untuk update
        $pptk->update($validated);

        return redirect()->route('showpptk')
                        ->with('success', 'Data PPTK berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pptks = pptk::findOrFail($id);
        $pptks->delete();

        return redirect()->route('showpptk')
                        ->with('success', 'Data pptk berhasil dihapus!');
    }

    public function showplt(Request $request)
    {
        $search = $request->input('search');

        $query = plt::query();

        // Filter pencarian (berdasarkan nama, jabatan, nip, dsb)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pihak_pertama', 'like', "%{$search}%")
                ->orWhere('jabatan_pihak_pertama', 'like', "%{$search}%")
                ->orWhere('nip_pihak_pertama', 'like', "%{$search}%");
            });
        }

        // Urutkan dari yang terbaru
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

        // Validasi — untuk rule unique kita kecualikan id yang sedang diupdate
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
