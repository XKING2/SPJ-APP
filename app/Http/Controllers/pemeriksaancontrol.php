<?php

namespace App\Http\Controllers;

use App\Models\kwitansi;
use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use App\Models\SPJ;
use App\Models\Pesanan;
use App\Models\pihakkedua;
use App\Models\Plt;
use App\Models\nosurat;
use App\Models\pekerjaans;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

Carbon::setLocale('id');

class PemeriksaanControl extends Controller
{

    public function create(Request $request)
    {
    
        $spj = SPJ::findOrFail($request->spj_id);
        $pesanan = Pesanan::findOrFail($request->pesanan_id);
        $plts = Plt::all();
        $nosurat = Nosurat::orderBy('id', 'desc')->get();
        $keduas = pihakkedua::all();
        $kwitansi = Kwitansi::latest()->first();

        $tanggalPesanan = $pesanan->tanggal_diterima;

        // Pastikan nilai tanggal valid
        if (!$tanggalPesanan) {
            abort(400, 'Tanggal pesanan tidak ditemukan.');
        }

        $tanggal_diterima = Carbon::parse($tanggalPesanan);

        $hari = ucfirst($tanggal_diterima->translatedFormat('l')); 
        $tglAngka = (int)$tanggal_diterima->format('d');            
        $bulan = ucfirst($tanggal_diterima->translatedFormat('F')); 
        $tahunAngka = (int)$tanggal_diterima->format('Y');         

        $tglTeks = $this->angkaKeTeks($tglAngka);
        $tahunTeks = $this->angkaKeTeks($tahunAngka);

        $tanggalLengkap = "$hari, $tglTeks $bulan $tahunTeks";

        return view('users.SpjLs.create.createpemeriksaan', compact(
            'spj',
            'pesanan',
            'plts',
            'hari',
            'tglTeks',
            'bulan',
            'tahunTeks',
            'tanggalLengkap',
            'kwitansi',
            'keduas',
            'nosurat'
        ));
    }

    private function angkaKeTeks($angka)
    {
    
        $formatter = new \NumberFormatter("id", \NumberFormatter::SPELLOUT);

        $hasil = $formatter->format($angka);

        $hasil = trim(preg_replace('/\s+/', ' ', $hasil));

        return ucwords($hasil);
    }



   public function store(Request $request)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'pesanan_id' => 'required|exists:pesanans,id',
            'no_suratssss' => 'required|string|max:255',
            'hari_diterima' => 'required|string|max:50',
            'tanggals_diterima' => 'required|string|max:100',
            'bulan_diterima' => 'required|string|max:100',
            'tahun_diterima' => 'required|string|max:255',
            'nama_pihak_kedua' => 'required|string|max:255',
            'jabatan_pihak_kedua' => 'required|string|max:255',
            'alamat_pihak_kedua' => 'required|string',
            'pekerjaan' => 'required|string|max:255',
        ]);

        $pekerjaan = pekerjaans::firstOrCreate(
            [
                'pekerjaan' => trim($validated['pekerjaan']),
                'spj_id' => $validated['spj_id'],
            ],
            [
                // default lain jika perlu
                'kegiatan_id' => null,
            ]
        );


        $pemeriksaan = Pemeriksaan::create([
            'spj_id' => $validated['spj_id'],
            'pesanan_id' => $validated['pesanan_id'],
            'no_suratssss' => $validated['no_suratssss'],
            'hari_diterima' => $validated['hari_diterima'],
            'tanggals_diterima' => $validated['tanggals_diterima'],
            'bulan_diterima' => $validated['bulan_diterima'],
            'tahun_diterima' => $validated['tahun_diterima'],
            'nama_pihak_kedua' => $validated['nama_pihak_kedua'],
            'jabatan_pihak_kedua' => $validated['jabatan_pihak_kedua'],
            'alamat_pihak_kedua' => $validated['alamat_pihak_kedua'],
            'id_pekerjaan' => $pekerjaan->id, // ⬅️ KUNCI UTAMA
        ]);

        $spj = SPJ::findOrFail($validated['spj_id']);
        $spj->update(['pemeriksaan_id' => $pemeriksaan->id]);

        return redirect()
            ->route('serahbarang.create', [
                'spj_id' => $validated['spj_id'],
                'pemeriksaan_id' => $pemeriksaan->id
            ])
            ->with('success', 'Pemeriksaan berhasil. Lanjut ke penerimaan.');
    }


    public function edit($id)
    {
        // Ambil data pemeriksaan beserta relasi SPJ dan Pesanan
        $pemeriksaan = Pemeriksaan::with(['spj.pesanan'])->findOrFail($id);
        $spj = $pemeriksaan->spj;
        $pesanan = $spj ? $spj->pesanan : null;

        // Ambil data tambahan seperti di create()
        $plts = Plt::all();
        $keduas = pihakkedua::all();
        $nosurat = nosurat::latest()->first();

        // Tangani tanggal dari pesanan
        $tanggalPesanan = $pesanan->tanggal_diterima ?? null;

        if (!$tanggalPesanan) {
            $hari = $tglTeks = $bulan = $tahunTeks = $tanggalLengkap = '';
        } else {
            $tanggal_diterima = Carbon::parse($tanggalPesanan);
            $hari = ucfirst($tanggal_diterima->translatedFormat('l')); 
            $tglAngka = (int)$tanggal_diterima->format('d');            
            $bulan = ucfirst($tanggal_diterima->translatedFormat('F')); 
            $tahunAngka = (int)$tanggal_diterima->format('Y');         

            $tglTeks = $this->angkaKeTeks($tglAngka);
            $tahunTeks = $this->angkaKeTeks($tahunAngka);
            $tanggalLengkap = "$hari, $tglTeks $bulan $tahunTeks";
        }

        return view('users.update.updatepemeriksaan', compact(
            'pemeriksaan',
            'spj',
            'pesanan',
            'plts',
            'hari',
            'tglTeks',
            'bulan',
            'tahunTeks',
            'tanggalLengkap',
            'kwitansi',
            'keduas',
            'nosurat'
        ));
    }



    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'no_suratssss' => 'required|string|max:255',
            'hari_diterima' => 'required|string|max:50',
            'tanggals_diterima' => 'required|string|max:100',
            'bulan_diterima' => 'required|string|max:100',
            'tahun_diterima' => 'required|string|max:255',
            'nama_pihak_kedua' => 'required|string|max:255',
            'jabatan_pihak_kedua' => 'required|string|max:255',
            'alamat_pihak_kedua' => 'required|string',
            'pekerjaan' => 'required|string',
        ]);

        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->update($validated);

        $spj = SPJ::find($pemeriksaan->spj_id);

        if (!$spj) {
            Log::error("SPJ dengan ID {$pemeriksaan->spj_id} tidak ditemukan saat update Kwitansi.");
            return redirect()->back()->with('error', 'Data SPJ tidak ditemukan.');
        }

         // 🔹 Reset feedback
        $spj->feedbacks()->delete();

        // 🔹 Status 1
        if ($spj->status !== 'valid') {
            // hanya ubah jika BUKAN valid
            if ($spj->status === 'belum_valid') {
                $spj->status = 'draft';
            }
        }

        // 🔹 Status 2
        if ($spj->status2 !== 'valid') {
            // hanya ubah jika BUKAN valid
            if ($spj->status2 === 'belum_valid') {
                $spj->status2 = 'draft';
            }
        }
        // 🔹 Reset semua notification flag
        $spj->resetNotifications();

        $spj->save();

        Log::info("✅ SPJ #{$spj->id} berhasil diubah ke status: {$spj->status} / {$spj->status2}");
        if ($spj) {
            app(\App\Http\Controllers\SPJController::class)->generateSPJDocumentLs($spj->id);
        }

        return redirect()
            ->route('pemeriksaan')
            ->with('success', 'Data pemeriksaan berhasil diperbarui dan dokumen SPJ telah Perbaharui.');
    }
}
