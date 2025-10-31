<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use App\Models\SPJ;
use App\Models\Pesanan;
use App\Models\Plt;
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

        return view('users.create.createpemeriksaan', compact(
            'spj',
            'pesanan',
            'plts',
            'hari',
            'tglTeks',
            'bulan',
            'tahunTeks',
            'tanggalLengkap'
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
            'id_plt' => 'required|exists:plt,id',
            'pesanan_id' => 'required|exists:pesanans,id',
            'hari_diterima' => 'required|string|max:50',
            'tanggals_diterima' => 'required|string|max:100',
            'bulan_diterima' => 'required|string|max:100',
            'tahun_diterima' => 'required|string|max:255',
            'nama_pihak_kedua' => 'required|string|max:255',
            'jabatan_pihak_kedua' => 'required|string|max:255',
            'alamat_pihak_kedua' => 'required|string',
            'pekerjaan' => 'required|string',
        ]);

        $pemeriksaan = Pemeriksaan::create($validated);

        
        $spj = SPJ::findOrFail($validated['spj_id']);
        $spj->update(['pemeriksaan_id' => $pemeriksaan->id]);

        return redirect()
            ->route('penerimaan.create', [
                'spj_id' => $validated['spj_id'],
                'pemeriksaan_id' => $pemeriksaan->id
            ])
            ->with('success', 'Pemeriksaan berhasil. Lanjut ke penerimaan.');
    }


    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::with('spj')->findOrFail($id);
        $spj = SPJ::find($pemeriksaan->spj_id);
        $pesanan = $spj ? $spj->pesanan : null;
        $plts = Plt::all();

        if ($pesanan && $pesanan->tanggal) {
            $tanggal = Carbon::parse($pesanan->tanggal);
            $hari = ucfirst($tanggal->translatedFormat('l'));
            $tglTeks = $this->angkaKeTeks((int) $tanggal->format('d'));
            $bulan = ucfirst($tanggal->translatedFormat('F'));
            $tahunTeks = $this->angkaKeTeks((int) $tanggal->format('Y'));
            $tanggalLengkap = "$hari, $tglTeks $bulan $tahunTeks";
        } else {
            $hari = $tglTeks = $bulan = $tahunTeks = $tanggalLengkap = '';
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
            'tanggalLengkap'
        ));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_plt' => 'required|exists:plt,id',
            'hari_diterima'      => 'required|string|max:50',
            'tanggals_diterima'  => 'required|string|max:50',
            'bulan_diterima'     => 'required|string|max:50',
            'tahun_diterima'     => 'required|string|max:50',
            'nama_pihak_kedua'   => 'required|string|max:255',
            'jabatan_pihak_kedua'=> 'required|string|max:255',
            'alamat_pihak_kedua' => 'required|string|max:255',
            'pekerjaan'          => 'required|string|max:255',
        ]);

        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->update($validated);

        $spj = SPJ::find($pemeriksaan->spj_id);

        if (!$spj) {
            Log::error("SPJ dengan ID {$pemeriksaan->spj_id} tidak ditemukan saat update Kwitansi.");
            return redirect()->back()->with('error', 'Data SPJ tidak ditemukan.');
        }

        if ($spj->status === 'belum_valid') {
            $spj->status = 'draft';
        }
        if ($spj->status2 === 'belum_valid') {
            $spj->status2 = 'draft';
        }

        $spj->save();

        Log::info("✅ SPJ #{$spj->id} berhasil diubah ke status: {$spj->status} / {$spj->status2}");
        if ($spj) {
            app(\App\Http\Controllers\SPJController::class)->generateSPJDocument($spj->id);
        }

        return redirect()
            ->route('pemeriksaan')
            ->with('success', 'Data pemeriksaan berhasil diperbarui dan dokumen SPJ telah Perbaharui.');
    }
}
