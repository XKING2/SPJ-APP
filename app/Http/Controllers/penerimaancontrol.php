<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerimaan;
use App\Models\Pemeriksaan;
use App\Models\SPJ;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\SPJController;

class PenerimaanControl extends Controller
{
    public function create(Request $request)
    {
        $spj = SPJ::findOrFail($request->spj_id);
        $pemeriksaan = Pemeriksaan::findOrFail($request->pemeriksaan_id);

        $ppnRate = Setting::where('key', 'ppn_rate')->value('value') ?? 10;

        // Ambil semua item pesanan untuk dipilih sebagai detail penerimaan
        $pesananItems = $pemeriksaan->pesanan->items ?? [];

        return view('users.create.createpenerimaan', compact('spj', 'pemeriksaan', 'ppnRate', 'pesananItems'));
    }

    public function store(Request $request)
    {
        // 🧠 Validasi input form
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'pemeriksaan_id' => 'required|exists:pemeriksaans,id',
            'pesanan_id' => 'required|exists:pesanans,id',

            'no_surat' => 'required|string|max:255',
            'surat_dibuat' => 'required|date',
            'subtotal' => 'required|numeric|min:0',
            'ppn' => 'nullable|numeric|min:0',
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

        // 💰 Ambil nilai PPN dari Setting
        $ppnRate = Setting::where('key', 'ppn_rate')->value('value') ?? 10;
        $ppnValue = $validated['ppn'] ?? ($validated['subtotal'] * ($ppnRate / 100));
        $grandtotal = $validated['grandtotal'] ?? ($validated['subtotal'] + $ppnValue);

        // 🧾 Buat entri utama di tabel penerimaans
        $penerimaan = Penerimaan::create([
            'spj_id' => $validated['spj_id'],
            'pemeriksaan_id' => $validated['pemeriksaan_id'],
            'pesanan_id' => $validated['pesanan_id'],
            'pekerjaan' => $request->pekerjaan,
            'no_surat' => $request->no_surat,
            'surat_dibuat' => $request->surat_dibuat,
            'nama_pihak_kedua' => $request->nama_pihak_kedua,
            'jabatan_pihak_kedua' => $request->jabatan_pihak_kedua,
            'surat_dibuat' => $validated['surat_dibuat'],
            'subtotal' => $validated['subtotal'],
            'ppn' => $ppnValue,
            'grandtotal' => $grandtotal,
            'dibulatkan' => $validated['dibulatkan'],
            'terbilang' => $validated['terbilang'],
        ]);

        // 📦 Simpan data detail barang via relasi
        $penerimaan->details()->createMany(
            collect($validated['barang'])->map(function ($item) use ($validated) {
                // Ambil data item dari PesananItem berdasarkan nama_barang
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


        // 🔁 Regenerasi dokumen SPJ jika ada
        if ($penerimaan->spj) {
            app(SPJController::class)->generateSPJDocument($penerimaan->spj->id);
        }

        // ✅ Redirect sukses
        return redirect()
            ->route('reviewSPJ')
            ->with('success', 'Data penerimaan berhasil disimpan dan SPJ telah digenerate otomatis.');
    }



    public function edit(Request $request, $id)
    {
        $penerimaan = Penerimaan::with(['details', 'spj.pesanan.items', 'spj.pemeriksaan'])->findOrFail($id);

        $spj = $penerimaan->spj;
        $pemeriksaan = $spj->pemeriksaan;
        $pesanan = $spj->pesanan; // ✅ Ambil langsung dari relasi SPJ, bukan dari request

        // Jika penerimaan belum punya detail, ambil dari pesanan items
        $barangList = $penerimaan->details->count() > 0
            ? $penerimaan->details
            : ($spj->pesanan->items ?? collect());

        return view('users.update.updatepenerimaan', compact('penerimaan', 'spj', 'pemeriksaan', 'pesanan', 'barangList'));
    }

    public function update(Request $request, $id)
    {
        // 🧠 Validasi input form
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

        // 🔍 Ambil data penerimaan lengkap beserta relasi
        $penerimaan = Penerimaan::with(['details'])->findOrFail($id);
        $ppnRate = Setting::where('key', 'ppn_rate')->value('value') ?? 10;

        /** 🔹 Hapus detail yang tidak ada di form */
        $formDetailIds = collect($validated['barang'])->pluck('id')->filter()->toArray();
        $detailsToDelete = $penerimaan->details()->whereNotIn('id', $formDetailIds)->get();

        foreach ($detailsToDelete as $detail) {
            $detail->delete();
        }

        /** 🔹 Update atau Tambah detail baru */
        foreach ($validated['barang'] as $barangData) {
            // Pastikan total dihitung ulang dari jumlah × harga
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
                // Cari ID item pesanan untuk menjaga relasi
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

        /** 🔹 Reload relasi setelah perubahan */
        $penerimaan->load('details');

        /** 🔹 Hitung subtotal dari kolom total */
        $subtotal = $penerimaan->details->sum('total');
        $ppnValue = $subtotal * ($ppnRate / 100);
        $grandtotal = $subtotal + $ppnValue;
        $dibulatkan = round($grandtotal);

        /** 🔹 Konversi ke terbilang */
        $terbilang = $this->terbilang($dibulatkan) . ' rupiah';

        /** 🔹 Update data utama penerimaan */
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

        /** 🔹 Regenerasi SPJ jika ada */
        if ($penerimaan->spj) {
            app(\App\Http\Controllers\SPJController::class)->generateSPJDocument($penerimaan->spj->id);
        }

        return redirect()
            ->route('penerimaan')
            ->with('success', 'Data penerimaan berhasil diperbarui.');
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
