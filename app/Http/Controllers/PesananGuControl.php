<?php

namespace App\Http\Controllers;

use App\Models\kwitansi;
use App\Models\nosurat;
use App\Models\Pesanan;
use App\Models\plt;
use App\Models\SPJ;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PesananGuControl extends Controller
{
    public function creategu($spj_id)
    {
        $nosurat = nosurat::orderBy('id', 'desc')->get();
        $spj = SPJ::findOrFail($spj_id);
        $plts = plt::all();
        return view('users.SpjGu.creategu.createpesanangu', compact('spj','nosurat','plts'));
    }

    

    public function storeGu(Request $request)
    {
        $validated = $request->validate([
            'spj_id'             => 'required|exists:spjs,id',
            'nama_pt'            => 'required|string|max:255',
            'nomor_tlp_pt'       => ['required', 'string', 'regex:/^[0-9]{10,15}$/'],
            'surat_dibuat'       => 'required|date',
            'tanggal_diterima'   => 'required|date',
            'jumlah_nominal'     => 'required|numeric',
            'uang_terbilang'     => 'required|string',
            'bulan_diterima'     => 'required|string',
            'tahun_diterima'     => 'required|string',
            'alamat_pt'          => 'required|string|max:255',
            'items'              => 'required|array|min:1',
            'items.*.nama_barang'=> 'required|string',
            'items.*.jumlah'     => 'required|numeric',
            'no_surat'           => 'nullable|string',
        ]);

    
        // SIMPAN PESANAN
        $pesanan = Pesanan::create([
            'spj_id'          => $validated['spj_id'],
            'no_surat'        => $request->no_surat,
            'nama_pt'         => $validated['nama_pt'],
            'alamat_pt'       => $validated['alamat_pt'],
            'nomor_tlp_pt'    => $validated['nomor_tlp_pt'],
            'tanggal_diterima'=> $validated['tanggal_diterima'],
            'surat_dibuat'    => $validated['surat_dibuat'],
            'jumlah_nominal'  => $validated['jumlah_nominal'],
            'uang_terbilang'  => $validated['uang_terbilang'],
            'bulan_diterima'  => $validated['bulan_diterima'],
            'tahun_diterima'  => $validated['tahun_diterima'],
        ]);

        // UPDATE SPJ DENGAN PESANAN ID + TAHUN
        $spj = SPJ::findOrFail($validated['spj_id']);

        $tahun = Carbon::parse($validated['tanggal_diterima'])->year;

        $spj->update([
            'pesanan_id' => $pesanan->id,
            'tahun'      => $tahun,
        ]);

        // SIMPAN ITEM PESANAN
        foreach ($validated['items'] as $item) {
            $pesanan->items()->create([
                'nama_barang' => $item['nama_barang'],
                'jumlah'      => $item['jumlah'],
            ]);
        }

        

         return redirect()
            ->route('kwitansigu.create', [
                'spj_id'     => $validated['spj_id'],
            ])
            ->with('success', 'Pesanan berhasil Disimpan. Lanjut ke pemeriksaan.');
    }


    

    public function editGu($id)
    {
        $nosurat = Nosurat::orderBy('id', 'desc')->get();
        $pesanan = Pesanan::with(['items', 'spj'])->findOrFail($id);

        return view('users.SpjGu.updategu.updatepesanangu', [
            'pesanan'  => $pesanan,
            'spj'      => $pesanan->spj,
            'no_surat' => $pesanan->no_surat,
            'nosurat'  => $nosurat, 
        ]);
    }

    

    public function updateGu(Request $request, $id)
    {
        $validated = $request->validate([
            'no_surat'           => 'nullable|string',
            'nama_pt'          => 'required|string|max:255',
            'nomor_tlp_pt'     => 'required|numeric',
            'alamat_pt'        => 'required|string|max:255',
            'surat_dibuat'     => 'required|date',
            'tanggal_diterima' => 'required|date',
            'jumlah_nominal'   => 'required|numeric',
            'uang_terbilang'   => 'required|string|max:255',

            'items'               => 'required|array|min:1',
            'items.*.id'          => 'nullable|integer|exists:pesanan_items,id',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.jumlah'      => 'required|numeric|min:1',
        ]);

        
        $pesanan = Pesanan::with('items')->findOrFail($id);

        $formItemIds = collect($validated['items'])->pluck('id')->filter()->toArray();

        $itemsToDelete = $pesanan->items()
            ->whereNotIn('id', $formItemIds)
            ->get();

        foreach ($itemsToDelete as $item) {
            $item->delete();
        }

        foreach ($validated['items'] as $itemData) {

            if (!empty($itemData['id'])) {
                $item = $pesanan->items->firstWhere('id', $itemData['id']);
                if ($item) {
                    $item->update([
                        'nama_barang' => $itemData['nama_barang'],
                        'jumlah'      => $itemData['jumlah'],
                    ]);
                }
            } 

            else {
                $pesanan->items()->create([
                    'nama_barang' => $itemData['nama_barang'],
                    'jumlah'      => $itemData['jumlah'],
                ]);
            }
        }

        $pesanan->update([
            'no_surat'         => $request->no_surat,
            'nama_pt'          => $validated['nama_pt'],
            'alamat_pt'        => $validated['alamat_pt'],
            'nomor_tlp_pt'     => $validated['nomor_tlp_pt'],
            'surat_dibuat'     => $validated['surat_dibuat'],
            'tanggal_diterima' => $validated['tanggal_diterima'],
            'jumlah_nominal'   => $validated['jumlah_nominal'],
            'uang_terbilang'   => $validated['uang_terbilang'],
        ]);


        $spj = SPJ::find($pesanan->spj_id);

        if (!$spj) {
            return redirect()->back()->with('error', 'Data SPJ tidak ditemukan.');
        }

        $spj->feedbacks()->delete();

        if ($spj->status !== 'valid') {
            if ($spj->status === 'belum_valid') {
                $spj->status = 'draft';
            }
        }

        if ($spj->status2 !== 'valid') {
            if ($spj->status2 === 'belum_valid') {
                $spj->status2 = 'draft';
            }
        }

        $spj->resetNotifications();

        $spj->save();
        if ($pesanan->spj_id) {
            app(\App\Http\Controllers\SPJController::class)->generateSPJDocumentls($pesanan->spj_id);
        }
        
        return redirect()
            ->route('pesanangu')
            ->with('success', 'Pesanan & item berhasil diupdate dan SPJ diperbarui.');
    }
}
