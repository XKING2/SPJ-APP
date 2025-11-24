<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerimaan;
use App\Models\Pemeriksaan;
use App\Models\SPJ;
use App\Models\Setting;
use App\Models\nosurat;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\SPJController;
use App\Models\serahbarang;

class PenerimaanControl extends Controller
{
    public function create(Request $request)
    {
        $spj = SPJ::findOrFail($request->spj_id);
        $nosurat = nosurat::latest()->first();
        $pemeriksaan = Pemeriksaan::findOrFail($request->pemeriksaan_id);
        $serahbarang = serahbarang::findOrFail($request->id_serahbarang);
        $ppn_rate = Setting::where('key', 'ppn_rate')->first()->value;
        $pph_list = Setting::where('key', 'like', 'pph_%')->get();

        $pesananItems = $pemeriksaan->pesanan->items ?? [];

        return view('users.create.createpenerimaan', compact('spj', 'pemeriksaan', 'ppn_rate', 'pesananItems','serahbarang','nosurat','pph_list'));
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'id_serahbarang' => 'required|exists:serah_barang,id',
            'pesanan_id' => 'required|exists:pesanans,id',

            'no_surat' => 'required|string|max:255',
            'surat_dibuat' => 'required|date',
            'subtotal' => 'required|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
            'pph' => 'nullable|numeric|min:0',
            'grandtotal' => 'required|numeric|min:0',
            'dibulatkan' => 'nullable|numeric|min:0',
            'terbilang' => 'required|string|max:255',

            'barang' => 'required|array|min:1',
            'barang.*.nama_barang' => 'required|string|max:255',
            'barang.*.jumlah' => 'required|numeric|min:1',
            'barang.*.satuan' => 'required|string|max:50',
            'barang.*.harga_satuan' => 'required|numeric|min:0',
            'barang.*.total' => 'required|numeric|min:0',
        ]);

        $ppnRate = Setting::where('key', 'ppn_rate')->value('value') ?? 10;
        $ppnValue = $validated['ppn'] ?? ($validated['subtotal'] * ($ppnRate / 100));
        $grandtotal = $validated['grandtotal'] ?? ($validated['subtotal'] + $ppnValue);


        $penerimaan = Penerimaan::create([
            'spj_id' => $validated['spj_id'],
            'id_serahbarang' => $validated['id_serahbarang'],
            'pesanan_id' => $validated['pesanan_id'],
            'pekerjaan' => $request->pekerjaan,
            'no_surat' => $request->no_surat,
            'surat_dibuat' => $request->surat_dibuat,
            'nama_pihak_kedua' => $request->nama_pihak_kedua,
            'jabatan_pihak_kedua' => $request->jabatan_pihak_kedua,
            'surat_dibuat' => $validated['surat_dibuat'],
            'subtotal' => $validated['subtotal'],
            'ppn' => $ppnValue,
            'pph' => $validated['pph'] ?? 0,
            'grandtotal' => $grandtotal,
            'dibulatkan' => $validated['dibulatkan'],
            'terbilang' => $validated['terbilang'],
        ]);

        $penerimaan->details()->createMany(
            collect($validated['barang'])->map(function ($item) use ($validated) {
                $pesananItem = \App\Models\PesananItem::where('nama_barang', $item['nama_barang'])
                                ->where('pesanan_id', $validated['pesanan_id'])
                                ->first();

                return [
                    'pesanan_id'      => $validated['pesanan_id'],
                    'pesanan_item_id' => $pesananItem ? $pesananItem->id : null,
                    'satuan'          => $item['satuan'],
                    'harga_satuan'    => $item['harga_satuan'],
                    'total'           => $item['total'],
                ];
            })->toArray()
        );

        if ($penerimaan->spj) {
            app(SPJController::class)->generateSPJDocument($penerimaan->spj->id);
        }

        return redirect()
            ->route('reviewSPJ')
            ->with('success', 'Data penerimaan berhasil disimpan dan SPJ telah digenerate otomatis.');
    }



    public function edit(Request $request, $id)
    {
        $penerimaan = Penerimaan::with(['details', 'spj.pesanan.items', 'spj.pemeriksaan'])->findOrFail($id);

        $spj = $penerimaan->spj;
        $pemeriksaan = $spj->pemeriksaan;
        $pesanan = $spj->pesanan;
        $nosurat = nosurat::latest()->first();

        $barangList = $penerimaan->details->count() > 0
            ? $penerimaan->details
            : ($spj->pesanan->items ?? collect());

        return view('users.update.updatepenerimaan', compact('penerimaan', 'spj', 'pemeriksaan', 'pesanan', 'barangList','nosurat'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'spj_id'             => 'required|exists:spjs,id',
            'pemeriksaan_id'     => 'required|exists:pemeriksaans,id',
            'pesanan_id'         => 'required|exists:pesanans,id',

            'no_surat'           => 'required|string|max:255',
            'surat_dibuat'       => 'required|date',
            'subtotal'           => 'nullable|numeric|min:0',
            'ppn'                => 'nullable|numeric|min:0',
            'grandtotal'         => 'nullable|numeric|min:0',
            'dibulatkan'         => 'nullable|numeric|min:0',

            'barang'                   => 'required|array|min:1',
            'barang.*.id'              => 'nullable|integer|exists:penerimaan_details,id',
            'barang.*.nama_barang'     => 'required|string|max:255',
            'barang.*.jumlah'          => 'required|numeric|min:1',
            'barang.*.satuan'          => 'required|string|max:50',
            'barang.*.harga_satuan'    => 'required|numeric|min:0',
            'barang.*.total'           => 'nullable|numeric|min:0',
        ]);

        $penerimaan = Penerimaan::with(['details'])->findOrFail($id);
        $ppnRate = Setting::where('key', 'ppn_rate')->value('value') ?? 10;

        $formDetailIds = collect($validated['barang'])->pluck('id')->filter()->toArray();
        $detailsToDelete = $penerimaan->details()->whereNotIn('id', $formDetailIds)->get();

        foreach ($detailsToDelete as $detail) {
            $detail->delete();
        }

        foreach ($validated['barang'] as $barangData) {
            $jumlah = $barangData['jumlah'] ?? 0;
            $harga  = $barangData['harga_satuan'] ?? 0;
            $total  = $jumlah * $harga;

            if (!empty($barangData['id'])) {
                $detail = $penerimaan->details->firstWhere('id', $barangData['id']);
                if ($detail) {
                    $detail->update([
                        'satuan'       => $barangData['satuan'],
                        'harga_satuan' => $harga,
                        'total'        => $total,
                    ]);
                }
            } else {
                $pesananItem = \App\Models\PesananItem::where('nama_barang', $barangData['nama_barang'])
                                ->where('pesanan_id', $validated['pesanan_id'])
                                ->first();

                $penerimaan->details()->create([
                    'pesanan_id'      => $validated['pesanan_id'],
                    'pesanan_item_id' => $pesananItem ? $pesananItem->id : null,
                    'satuan'          => $barangData['satuan'],
                    'harga_satuan'    => $harga,
                    'total'           => $total,
                ]);
            }
        }

        $penerimaan->load('details');
        $subtotal = $penerimaan->details->sum('total');
        $ppnValue = $subtotal * ($ppnRate / 100);
        $grandtotal = $subtotal + $ppnValue;
        $dibulatkan = round($grandtotal);
        $terbilang = $this->terbilang($dibulatkan) . ' rupiah';

        $penerimaan->update([
            'spj_id'         => $validated['spj_id'],
            'pemeriksaan_id' => $validated['pemeriksaan_id'],
            'pesanan_id'     => $validated['pesanan_id'],
            'no_surat'       => $validated['no_surat'],
            'surat_dibuat'   => $validated['surat_dibuat'],
            'subtotal'       => $subtotal,
            'ppn'            => $ppnValue,
            'grandtotal'     => $grandtotal,
            'dibulatkan'     => $dibulatkan,
            'terbilang'      => $terbilang,
        ]);

        $spj = SPJ::find($penerimaan->spj_id);

        if (!$spj) {
            Log::error("SPJ dengan ID {$penerimaan->spj_id} tidak ditemukan saat update Kwitansi.");
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

        if ($penerimaan->spj) {
            app(\App\Http\Controllers\SPJController::class)->generateSPJDocument($penerimaan->spj->id);
        }

        return redirect()
            ->route('penerimaan')
            ->with('success', 'Data penerimaan berhasil diperbarui Dan Dokumen SPJ diperbaharui.');
    }

    private function terbilang($angka)
    {
        $angka = abs((int)$angka);
        $huruf = [
            "", "satu", "dua", "tiga", "empat", "lima",
            "enam", "tujuh", "delapan", "sembilan",
            "sepuluh", "sebelas"
        ];

        $hasil = "";

        if ($angka < 12) {
            $hasil = $huruf[$angka];
        } elseif ($angka < 20) {
            $hasil = $huruf[$angka - 10] . " belas";
        } elseif ($angka < 100) {
            $hasil = $this->terbilang(floor($angka / 10)) . " puluh " . $huruf[$angka % 10];
        } elseif ($angka < 200) {
            $hasil = "seratus " . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $hasil = $this->terbilang(floor($angka / 100)) . " ratus " . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $hasil = "seribu " . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $hasil = $this->terbilang(floor($angka / 1000)) . " ribu " . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $hasil = $this->terbilang(floor($angka / 1000000)) . " juta " . $this->terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $hasil = $this->terbilang(floor($angka / 1000000000)) . " miliar " . $this->terbilang($angka % 1000000000);
        } else {
            $hasil = $this->terbilang(floor($angka / 1000000000000)) . " triliun " . $this->terbilang($angka % 1000000000000);
        }

        return trim(preg_replace('/\s+/', ' ', $hasil));
    }






}
