<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\kegiatan;
use App\Models\nosurat;
use App\Models\pihakkedua;
use App\Models\pptk;
use Illuminate\Http\Request;
use App\Models\setting;
use App\Models\plt;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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

        // 1️⃣ Ambil semua PPTK beserta kegiatan (pakai Eloquent)
        $pptks = Pptk::with('kegiatan')
            ->when($search, function ($query) use ($search) {
                $query->where('nama_pptk', 'like', "%{$search}%")
                    ->orWhere('idinjab_pptk', 'like', "%{$search}%")
                    ->orWhereHas('kegiatan', function ($q) use ($search) {
                        $q->where('subkegiatan', 'like', "%{$search}%")
                            ->orWhere('kegiatan', 'like', "%{$search}%")
                            ->orWhere('program', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->get();

        // 2️⃣ Gabungkan PPTK + setiap kegiatan jadi satu baris
        $combined = new Collection();
        foreach ($pptks as $pptk) {
            if ($pptk->kegiatan->isEmpty()) {
                $combined->push((object) [
                    'pptk_id' => $pptk->id,
                    'nama_pptk' => $pptk->nama_pptk,
                    'idinjab_pptk' => $pptk->idinjab_pptk,
                    'subkegiatan' => '-',
                    'kegiatan_id' => null,
                ]);
            } else {
                foreach ($pptk->kegiatan as $kegiatan) {
                    $combined->push((object) [
                        'pptk_id' => $pptk->id,
                        'nama_pptk' => $pptk->nama_pptk,
                        'idinjab_pptk' => $pptk->idinjab_pptk,
                        'subkegiatan' => $kegiatan->subkegiatan,
                        'kegiatan_id' => $kegiatan->id,
                    ]);
                }
            }
        }

        // 3️⃣ Pagination manual 10 per halaman
        $perPage = 10;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $items = $combined->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator(
            $items,
            $combined->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->ajax()) {
            return view('superadmins.setting._pptk_table', ['pptks' => $paginated])->render();
        }

        return view('superadmins.setting.settingpptk', ['pptks' => $paginated]);
    }


    public function createpptk()
    {
        $users = User::all();
        $pptks = Pptk::all();
        return view('superadmins.setting.createpptk', compact('pptks','users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program' => 'required|max:255',
            'kegiatan' => 'required|max:255',
            'subkegiatan' => 'required|max:255',
        ]);

  
        if ($request->filled('pptk_id')) {
            $pptk = Pptk::findOrFail($request->pptk_id);
        } else {
        
            $request->validate([
                'nama_pptk' => 'required|unique:pptk,nama_pptk|max:255',
                'nip_pptk' => 'required|max:255|unique:pptk,nip_pptk',
                'idinjab_pptk' => 'required|max:255',
            ]);

            $pptk = Pptk::create([
                'nama_pptk' => $request->nama_pptk,
                'nip_pptk' => $request->nip_pptk,
                'idinjab_pptk' => $request->idinjab_pptk,
            ]);
        }

        $pptk->kegiatan()->create([
            'program' => $request->program,
            'kegiatan' => $request->kegiatan,
            'subkegiatan' => $request->subkegiatan,
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

        // ✅ Validasi dasar PPTK
        $validated = $request->validate([
            'nama_pptk'   => 'required|max:255|unique:pptk,nama_pptk,' . $id,
            'nip_pptk'    => 'required|max:255|unique:pptk,nip_pptk,' . $id,
            'gol_pptk'    => 'required|max:255',
            'program'     => 'required|max:255',
            'kegiatan'    => 'required|max:255',
            'subkegiatan' => 'required|array|min:1',
            'subkegiatan.*' => 'required|string|max:255',
        ]);

        // ✅ Update PPTK utama
        $pptk->update([
            'nama_pptk' => $validated['nama_pptk'],
            'nip_pptk'  => $validated['nip_pptk'],
            'gol_pptk'  => $validated['gol_pptk'],
        ]);

        // ✅ Tambahkan ulang semua subkegiatan baru
        foreach ($validated['subkegiatan'] as $sub) {
            $pptk->kegiatan()->create([
                'program'     => $validated['program'],
                'kegiatan'    => $validated['kegiatan'],
                'subkegiatan' => $sub,
            ]);
        }

        return redirect()
            ->route('showpptk')
            ->with('success', 'Data PPTK & Kegiatan berhasil diperbarui!');
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

        $search = $request->input('search');

        $query = pihakkedua::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pihak_kedua', 'like', "%{$search}%")
                ->orWhere('jabatan_pihak_kedua', 'like', "%{$search}%")
                ->orWhere('nip_pihak_kedua', 'like', "%{$search}%");
            });
        }

        $keduas = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim ke view
        return view('superadmins.setting.settingplt', compact('keduas','plts'));
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


    public function shownosurat(Request $request)
    {

       $search = $request->input('search');

        $query = nosurat::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('no_awal', 'like', "%{$search}%")
                ->orWhere('nama_dinas', 'like', "%{$search}%")
                ->orWhere('tahun', 'like', "%{$search}%");
            });
        }

        $no_surats = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim ke view
        return view('superadmins.setting.settingnosurat', compact('no_surats'));
    }

    public function createnosurat()
    {
        return view('superadmins.setting.createnosurat');
    }

    public function storenosurat(Request $request)
    {
        $request->validate([
            'no_awal' => 'required|max:255',
            'nama_dinas' => 'required|max:255',
            'tahun' => 'required|max:255',
        ]);

        nosurat::create([
                'no_awal' => $request->no_awal,
                'nama_dinas' => $request->nama_dinas,
                'tahun' => $request->tahun,
            ]);

        return redirect()->route('shownosurat')
        ->with('success', 'Data Nomor Surat berhasil ditambahkan!');
    }


    public function editnosurat($id)
    {
        
        $nosurats = nosurat::findOrFail($id);

        return view('superadmins.setting.updatenosurat', compact('nosurats'));
    }

    public function updatenosurat(Request $request, $id)
    {
        $nosurats = nosurat::findOrFail($id);

        $request->validate([
            'no_awal' => 'required|max:255',
            'nama_dinas' => 'required|max:255',
            'tahun' => 'required|max:255',
        ]);

        // 🔹 Update data PPTK utama
        $nosurats->update([
            'no_awal'    => $request['no_awal'],
            'nama_dinas' => $request['nama_dinas'],
            'tahun'     => $request['tahun'],
        ]);

        return redirect()
            ->route('shownosurat')
            ->with('success', 'Data Nomor Surat  berhasil diperbarui!');
    }


    public function destroynosurat($id)
    {
        $pptks = nosurat::findOrFail($id);
        $pptks->delete();

        return redirect()->route('shownosurat')
                        ->with('success', 'Data Nomor Surat berhasil dihapus!');
    }

    public function createkedua()
    {
        return view('superadmins.setting.createphkkedua');
    }

    public function storekedua(Request $request)
    {
        $request->validate([
            'nama_pihak_kedua' => 'required|max:255|:pihak_kedua,nama_pihak_kedua',
            'jabatan_pihak_kedua' => 'required|max:255',
            'nip_pihak_kedua' => 'required|max:255',
            'gol_pihak_kedua' => 'required|max:255',
        ]);

        pihakkedua::create([
                'nama_pihak_kedua' => $request->nama_pihak_kedua,
                'jabatan_pihak_kedua' => $request->jabatan_pihak_kedua,
                'nip_pihak_kedua' => $request->nip_pihak_kedua,
                'gol_pihak_kedua' => $request->gol_pihak_kedua,
            ]);

        return redirect()->route('showplt')
        ->with('success', 'Data Nomor Surat berhasil ditambahkan!');
    }


    public function editkedua($id)
    {
        
        $keduas = pihakkedua::findOrFail($id);

        return view('superadmins.setting.updatephkkedua', compact('keduas'));
    }

    public function updatekedua(Request $request, $id)
    {
        $keduas = pihakkedua::findOrFail($id);

        $request->validate([
            'nama_pihak_kedua' => 'required|max:255|:pihak_kedua,nama_pihak_kedua',
            'jabatan_pihak_kedua' => 'required|max:255',
            'nip_pihak_kedua' => 'required|max:255',
            'gol_pihak_kedua' => 'required|max:255',
        ]);

        // 🔹 Update data PPTK utama
        $keduas->update([
            'nama_pihak_kedua'    => $request['nama_pihak_kedua'],
            'jabatan_pihak_kedua' => $request['jabatan_pihak_kedua'],
            'nip_pihak_kedua'     => $request['nip_pihak_kedua'],
            'gol_pihak_kedua'     => $request['gol_pihak_kedua'],
        ]);

        return redirect()
            ->route('showplt')
            ->with('success', 'Data Nomor Surat  berhasil diperbarui!');
    }


    public function destroykedua($id)
    {
        $keduas = pihakkedua::findOrFail($id);
        $keduas->delete();

        return redirect()->route('showplt')
                        ->with('success', 'Data Nomor Surat berhasil dihapus!');
    }
}
