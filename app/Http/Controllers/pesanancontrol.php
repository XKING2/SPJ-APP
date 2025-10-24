<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Penerimaan;
use App\Models\setting;
use App\Models\SPJ;
use App\Models\Kwitansi;

class PesananControl extends Controller
{
    public function create(Request $request)
    {
        $spj = SPJ::findOrFail($request->spj_id);
        $kwitansi = Kwitansi::findOrFail($request->kwitansi_id);
        return view('users.create.createpesanan', compact('spj', 'kwitansi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spj_id'             => 'required|exists:spjs,id',
            'kwitansi_id'        => 'required|exists:kwitansis,id',
            'no_surat'           => 'required|string|max:255',
            'nama_pt'            => 'required|string|max:255',
            'nomor_tlp_pt'       => 'required|numeric',
            'surat_dibuat'       => 'required|date',
            'tanggal_diterima'   => 'required|date',
            'alamat_pt'          => 'required|string|max:255',
            'items'              => 'required|array|min:1',
            'items.*.nama_barang'=> 'required|string',
            'items.*.jumlah'     => 'required|numeric',
        ]);

        // ✅ Buat pesanan utama
        $pesanan = Pesanan::create([
            'spj_id'          => $validated['spj_id'],
            'kwitansi_id'     => $validated['kwitansi_id'],
            'no_surat'        => $validated['no_surat'],
            'nama_pt'         => $validated['nama_pt'],
            'alamat_pt'       => $validated['alamat_pt'],
            'nomor_tlp_pt'    => $validated['nomor_tlp_pt'],
            'tanggal_diterima'=> $validated['tanggal_diterima'],
            'surat_dibuat'    => $validated['surat_dibuat'],
        ]);

        // ✅ Hubungkan pesanan ke SPJ
        $spj = SPJ::findOrFail($validated['spj_id']);
        $spj->update(['pesanan_id' => $pesanan->id]);

        // ✅ Simpan item menggunakan relasi
        foreach ($validated['items'] as $item) {
            $pesanan->items()->create([
                'nama_barang' => $item['nama_barang'],
                'jumlah'      => $item['jumlah'],
            ]);
        }

        return redirect()
            ->route('pemeriksaan.create', [
                'spj_id'     => $validated['spj_id'],
                'pesanan_id' => $pesanan->id
            ])
            ->with('success', 'Pesanan berhasil disimpan. Lanjut ke pemeriksaan.');
    }

        public function edit($id)
    {
        $pesanan = Pesanan::with(['items', 'spj'])->findOrFail($id);

        return view('users.update.updatepesanan', [
            'pesanan' => $pesanan,
            'spj'     => $pesanan->spj,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'no_surat'         => 'required|string|max:255',
            'nama_pt'          => 'required|string|max:255',
            'nomor_tlp_pt'     => 'required|numeric',
            'alamat_pt'        => 'required|string|max:255',
            'surat_dibuat'     => 'required|date',
            'tanggal_diterima' => 'required|date',

            'subtotal'     => 'nullable|numeric|min:0',
            'ppn'          => 'nullable|numeric|min:0',
            'grandtotal'   => 'nullable|numeric|min:0',
            'dibulatkan'   => 'nullable|numeric|min:0',

            'items'               => 'required|array|min:1',
            'items.*.id'          => 'nullable|integer|exists:pesanan_items,id',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.jumlah'      => 'required|numeric|min:1',
        ]);

        $pesanan = Pesanan::with(['items.penerimaanDetail'])->findOrFail($id);
        $ppnRate = Setting::where('key', 'ppn_rate')->value('value') ?? 10;

        /** 🔹 Sinkronisasi item */
        $formItemIds = collect($validated['items'])->pluck('id')->filter()->toArray();
        $itemsToDelete = $pesanan->items()->whereNotIn('id', $formItemIds)->get();

        foreach ($itemsToDelete as $item) {
            $item->delete();
        }

        /** 🔹 Update / Tambah item baru */
        foreach ($validated['items'] as $itemData) {
            if (!empty($itemData['id'])) {
                $item = $pesanan->items->firstWhere('id', $itemData['id']);
                if ($item) {
                    $item->update([
                        'nama_barang' => $itemData['nama_barang'],
                        'jumlah'      => $itemData['jumlah'],
                    ]);
                }
            } else {
                $pesanan->items()->create([
                    'nama_barang' => $itemData['nama_barang'],
                    'jumlah'      => $itemData['jumlah'],
                ]);
            }
        }

        /** 🔹 Reload data terbaru */
        $pesanan->load('items.penerimaanDetail');

        /** 🔹 Hitung total per item (jumlah × harga_satuan) */
        foreach ($pesanan->items as $item) {
            if ($item->penerimaanDetail) {
                $jumlah = $item->jumlah ?? 0;
                $harga  = $item->penerimaanDetail->harga_satuan ?? 0;
                $total  = $jumlah * $harga;

                $item->penerimaanDetail->update(['total' => $total]);
            }
        }

        /** 🔹 Hitung ulang subtotal, PPN, Grandtotal, Dibulatkan */
        $subtotal = $pesanan->items->sum(fn($i) => optional($i->penerimaanDetail)->total ?? 0);
        $ppnValue = $subtotal * ($ppnRate / 100);
        $grandtotal = $subtotal + $ppnValue;
        $dibulatkan = round($grandtotal);

        /** 🔹 Konversi ke terbilang */
        $terbilang = $this->terbilang($dibulatkan) . ' rupiah';

        /** 🔹 Update Pesanan utama */
        $pesanan->update([
            'no_surat'         => $validated['no_surat'],
            'nama_pt'          => $validated['nama_pt'],
            'alamat_pt'        => $validated['alamat_pt'],
            'nomor_tlp_pt'     => $validated['nomor_tlp_pt'],
            'surat_dibuat'     => $validated['surat_dibuat'],
            'tanggal_diterima' => $validated['tanggal_diterima'],
            'subtotal'         => $subtotal,
            'ppn'              => $ppnValue,
            'grandtotal'       => $grandtotal,
            'dibulatkan'       => $dibulatkan,
        ]);

        /** 🔹 Update Penerimaan */
        if ($penerimaan = Penerimaan::where('pesanan_id', $pesanan->id)->first()) {
            $penerimaan->update([
                'subtotal'   => $subtotal,
                'ppn'        => $ppnValue,
                'grandtotal' => $grandtotal,
                'dibulatkan' => $dibulatkan,
                'terbilang'  => $terbilang,
            ]);
        }

        /** 🔹 Regenerasi SPJ jika ada */
        if ($pesanan->spj_id) {
            app(\App\Http\Controllers\SPJController::class)->generateSPJDocument($pesanan->spj_id);
        }

        return redirect()
            ->route('pesanan')
            ->with('success', 'Pesanan, total, dan terbilang berhasil diperbarui.');
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

        // Hilangkan spasi ganda dan rapikan
        return trim(preg_replace('/\s+/', ' ', $hasil));
    }






    
}
