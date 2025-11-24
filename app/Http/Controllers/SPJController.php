<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Spj;
use App\Models\Pesanan;
use App\Models\Pemeriksaan;
use App\Models\Penerimaan;
use App\Models\Kwitansi;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use App\Models\spjfeedback;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class SPJController extends Controller
{
    public function index()
    {
        $spjs = Spj::with(['pesanan', 'kwitansi', 'pemeriksaan', 'penerimaan'])->latest()->get();
        return view('users.pesanan', compact('spjs'));
    }

    public function create(Request $request)
    {
        $userId = Auth::id() ?? session('user_id'); 

        if (!$userId) {
            return back()->withErrors(['auth' => 'User belum login']);
        }

        $spj = Spj::create([
            'status'         => 'draft',
            'pesanan_id'     => $request->pesanan_id,
            'kwitansi_id'    => $request->kwitansi_id,
            'penerimaan_id'  => $request->penerimaan_id,
            'pemeriksaan_id' => $request->pemeriksaan_id,
            'user_id'        => $userId,
            'kegiatan_id'    => $request->kegiatan_id,
        ]);

        if ($request->pesanan_id) {
            Pesanan::where('id', $request->pesanan_id)->update(['spj_id' => $spj->id]);
        }
        if ($request->kwitansi_id) {
            Kwitansi::where('id', $request->kwitansi_id)->update(['spj_id' => $spj->id]);
        }
        if ($request->penerimaan_id) {
            Penerimaan::where('id', $request->penerimaan_id)->update(['spj_id' => $spj->id]);
        }
        if ($request->pemeriksaan_id) {
            Pemeriksaan::where('id', $request->pemeriksaan_id)->update(['spj_id' => $spj->id]);
        }

        session(['current_spj_id' => $spj->id]);

        return redirect()
            ->route('kwitansi.create', ['spj_id' => $spj->id])
            ->with('success', "SPJ berhasil dibuat untuk user ID: {$userId}");
    }


    public function review($spj_id)
    {
        $spj = Spj::with(['pesanan', 'kwitansi', 'pemeriksaan', 'penerimaan'])->findOrFail($spj_id);
        return view('users.reviewSPJ', compact('spj'));
    }

    

    public function generateSPJDocument($id)
    {
        try {
            $spj = Spj::with([
                'pesanan.items',
                'penerimaan.details',
                'kwitansi.pptk',
                'pemeriksaan',
                'serah_barang.plt',
                'serah_barang.pihak_kedua',
                'kwitansi.kegiatan'
            ])->findOrFail($id);

            $pesanan     = $spj->pesanan;
            $penerimaan  = $spj->penerimaan;
            $kwitansi    = $spj->kwitansi;
            $pemeriksaan = $spj->pemeriksaan;
            $serahbarang = $spj->serah_barang;

            $templatePath = storage_path('app/public/Tamplate_SPJ.docx');
            $outputDocx   = storage_path("app/public/spj_preview_{$spj->id}.docx");
            $outputPdfDir = storage_path('app/public');
            $outputPdf    = "{$outputPdfDir}/spj_preview_{$spj->id}.pdf";

            if (!file_exists($templatePath)) {
                throw new Exception('Template SPJ tidak ditemukan.');
            }

            $template = new TemplateProcessor($templatePath);

            $empty = fn($v) => $v ?? '';
            

            $template->setValue('no_rekening', $kwitansi->no_rekening);
            $template->setValue('no_rekening_tujuan', $kwitansi->no_rekening_tujuan);
            $template->setValue('nama_bank', $kwitansi->nama_bank);
            $template->setValue('npwp', $kwitansi->npwp);
            $template->setValue('telah_diterima_dari', $kwitansi->telah_diterima_dari);
            $template->setValue('uang_terbilang',$penerimaan->terbilang);
            $template->setValue('pembayaran', $kwitansi->pembayaran);
            $template->setValue('sub_kegiatan', $kwitansi->kegiatan->subkegiatan);
            $template->setValue('jumlah_nominal', number_format($penerimaan->grandtotal ?? 0));
            $template->setValue('penerima_kwitansi', $kwitansi->penerima_kwitansi);
            $template->setValue('jabatan_penerima', $kwitansi->jabatan_penerima);
            $template->setValue('nama_pptk', $kwitansi->pptk->nama_pptk);
            $template->setValue('jabatan_pptk', $kwitansi->pptk->gol_pptk);
            $template->setValue('nip_pptk', $kwitansi->pptk->nip_pptk);


            
            if ($pesanan) {
                $template->setValue('nama_pt',  $pesanan->nama_pt);
                $template->setValue('no_surat', $pesanan->no_surat);
                $template->setValue('alamat_pt', $pesanan->alamat_pt);
                $template->setValue('nomor_tlp_pt', $pesanan->nomor_tlp_pt);

                // 🔥 Format tanggal ke DD-MM-YYYY sebelum masuk template
                $template->setValue('tanggal_diterima', 
                    Carbon::parse($pesanan->tanggal_diterima)->format('d-m-Y')
                );

                $template->setValue('surat_dibuat', 
                    Carbon::parse($pesanan->surat_dibuat)->format('d-m-Y')
                );
            }

            
            if ($pemeriksaan) {
                $template->setValue('hari_diterima', $pemeriksaan->hari_diterima);
                $template->setValue('tanggals_diterima', $pemeriksaan->tanggals_diterima);
                $template->setValue('bulan_diterima', $pemeriksaan->bulan_diterima);
                $template->setValue('tahun_diterima', $pemeriksaan->tahun_diterima);
                $template->setValue('nama_pihak_kedua', $pemeriksaan->nama_pihak_kedua);
                $template->setValue('jabatan_pihak_kedua', $pemeriksaan->jabatan_pihak_kedua);
                $template->setValue('alamat_pihak_kedua', $pemeriksaan->alamat_pihak_kedua);
                $template->setValue('pekerjaan', $pemeriksaan->pekerjaan);
                
            }

            if ($serahbarang) {
                $template->setValue('nama_pertama', $serahbarang->plt->nama_pihak_pertama);
                $template->setValue('nip_pertama', $serahbarang->plt->nip_pihak_pertama);
                $template->setValue('gol_pertama', $serahbarang->plt->gol_pihak_pertama);
                $template->setValue('jab_pertama', $serahbarang->plt->jabatan_pihak_pertama);
                $template->setValue('nama_pengelola', $serahbarang->pihak_kedua->nama_pihak_kedua);
                $template->setValue('nip_pengelola', $serahbarang->pihak_kedua->nip_pihak_kedua);
                $template->setValue('gol_pengelola', $serahbarang->pihak_kedua->gol_pihak_kedua);
                $template->setValue('jabatan_pengelola', $serahbarang->pihak_kedua->jabatan_pihak_kedua);
            }

           
            if ($penerimaan) {
                $template->setValue('subtotal', number_format($penerimaan->subtotal ?? 0));
                $template->setValue('ppn', number_format($penerimaan->ppn ?? 0));
                $template->setValue('grandtotal', number_format($penerimaan->grandtotal ?? 0));
                $template->setValue('dibulatkan', number_format($penerimaan->dibulatkan ?? 0));
                $template->setValue('terbilang', $penerimaan->terbilang);
                $template->setValue('pph', $penerimaan->pph);
            }

            
            $details = $spj->penerimaan && $spj->penerimaan->details
            ? $spj->penerimaan->details->load('pesananItem') 
            : collect();

        if ($details->count() > 0) {
            
            $template->cloneRow('nama_barang1', $details->count());
            foreach ($details as $i => $detail) {
                $n = $i + 1;
                $item = $detail->pesananItem; 

                $template->setValue("no1#{$n}", $n);
                $template->setValue("nama_barang1#{$n}", $item->nama_barang ?? '-');
                $template->setValue("jumlah1#{$n}", $item->jumlah ?? '-');
                $template->setValue("satuan1#{$n}", $detail->satuan ?? '-');
                $template->setValue("harga_satuan1#{$n}", number_format($detail->harga_satuan ?? 0, 0, ',', '.'));
                $template->setValue("total1#{$n}", number_format($detail->total ?? 0, 0, ',', '.'));
            }

    
            $template->cloneRow('nama_barang2', $details->count());
            foreach ($details as $i => $detail) {
                $n = $i + 1;
                $item = $detail->pesananItem;

                $template->setValue("no2#{$n}", $n);
                $template->setValue("nama_barang2#{$n}", $item->nama_barang ?? '-');
                $template->setValue("jumlah2#{$n}", $item->jumlah ?? '-');
                $template->setValue("satuan2#{$n}", $detail->satuan ?? '-');
                $template->setValue("harga_satuan2#{$n}", number_format($detail->harga_satuan ?? 0, 0, ',', '.'));
                $template->setValue("total2#{$n}", number_format($detail->total ?? 0, 0, ',', '.'));
            }

          
            $template->cloneRow('nama_barang3', $details->count());
            foreach ($details as $i => $detail) {
                $n = $i + 1;
                $item = $detail->pesananItem;

                $template->setValue("no3#{$n}", $n);
                $template->setValue("nama_barang3#{$n}", $item->nama_barang ?? '-');
                $template->setValue("jumlah3#{$n}", $item->jumlah ?? '-');
                $template->setValue("satuan3#{$n}", $detail->satuan ?? '-');
            }
        } else {
            
            $template->setValue('nama_barang1', '-');
            $template->setValue('jumlah1', '-');
            $template->setValue('satuan1', '-');
            $template->setValue('harga_satuan1', '-');
            $template->setValue('subtotal1', '-');
        }



            
            $template->saveAs($outputDocx);

            
            $command = "soffice --headless --convert-to pdf --outdir " 
                . escapeshellarg($outputPdfDir) . " " 
                . escapeshellarg($outputDocx);
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new Exception("Gagal konversi ke PDF. Pastikan LibreOffice terinstal.");
            }

            
            if (file_exists($outputPdf)) {
                Storage::disk('public')->putFileAs('spj_generated', new \Illuminate\Http\File($outputPdf), "spj_{$spj->id}.pdf");
            }

        } catch (Exception $e) {
            Log::error('Gagal generate SPJ otomatis: ' . $e->getMessage());
        }
    }

    public function preview($id)
    {
        $spj = Spj::with(['pesanan', 'feedbacks'])->findOrFail($id);

        
        if ($spj->status === 'belum_valid' || $spj->status2 === 'belum_valid') {
            $relativePath = "spj_marked/spj_revisi_{$spj->id}.pdf";
        } else {
            $relativePath = "spj_generated/spj_{$spj->id}.pdf";
        }

        $pdfPath = storage_path("app/public/{$relativePath}");

        if (!file_exists($pdfPath)) {
            
            $fallbackPath = storage_path("app/public/spj_generated/spj_{$spj->id}.pdf");
            if (file_exists($fallbackPath)) {
                $pdfPath = $fallbackPath;
                $relativePath = "spj_generated/spj_{$spj->id}.pdf";
            } else {
                return back()->with('error', 'File PDF tidak ditemukan.');
            }
        }

        $fileUrl = asset("storage/{$relativePath}");
        return view('users.previewSPJ', compact('spj', 'fileUrl'));
    }


    public function submitToBendahara($id)
    {
        $spj = Spj::findOrFail($id);

        
        if (!in_array($spj->status, ['draft'])) {
            return redirect()->back()->with('error', 'SPJ ini sudah diajukan atau divalidasi.');
        }

        $spj->update(['status' => 'diajukan']);

        return redirect()->back()->with('success', 'SPJ berhasil diajukan ke Bendahara untuk diverifikasi.');
    }

    public function ajukanKasubag($id)
    {
        $spj = Spj::findOrFail($id);

        
        if ($spj->status !== 'valid') {
            return back()->with('error', 'SPJ belum divalidasi oleh Bendahara.');
        }

        
        $spj->update([
            'status2' => 'diajukan',
        ]);

        return back()->with('success', 'SPJ berhasil diajukan ke Kasubag untuk validasi.');
    }

    public function cetak($id)
    {
        try {
            
            $pdfPath = storage_path("app/public/spj_preview_{$id}.pdf");

            
            if (!file_exists($pdfPath)) {
                
                $this->generateSPJDocument($id);

                
                if (!file_exists($pdfPath)) {
                    return redirect()->back()->with('error', 'Gagal membuat file PDF SPJ.');
                }
            }

            
            return response()->download($pdfPath, "SPJ_{$id}.pdf")->deleteFileAfterSend(false);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencetak SPJ: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $spj = Spj::findOrFail($id);

        
        if ($spj->pesanan_id) {
            \App\Models\Pesanan::where('id', $spj->pesanan_id)->delete();
        }

        if ($spj->penerimaan_id) {
            \App\Models\Penerimaan::where('id', $spj->penerimaan_id)->delete();
        }

        if ($spj->pemeriksaan_id) {
            \App\Models\Pemeriksaan::where('id', $spj->pemeriksaan_id)->delete();
        }

        if ($spj->kwitansi_id) {
            \App\Models\Kwitansi::where('id', $spj->kwitansi_id)->delete();
        }
        
        $spj->delete();

        return redirect()->back()->with('success', 'SPJ dan seluruh data terkait berhasil dihapus.');
    }





}
