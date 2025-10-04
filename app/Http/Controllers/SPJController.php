<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Models\Pesanan;
use App\Models\Penerimaan;
use App\Models\Kwitansi;
use App\Models\Spj;

class SPJController extends Controller
{

    public function showreviewSPJ()
    {
        return view('users.reviewSPJ');
    }
    public function showcetakSPJ()
    {
        return view('users.cetakSPJ');
    }
    public function preview($id)
    {
        // Ambil data dari DB (contoh dari pesanan + penerimaan + kwitansi)
        $pesanan    = Pesanan::with('items')->findOrFail($id);
        $penerimaan = Penerimaan::where('pesanan_id', $id)->first();
        $kwitansi   = Kwitansi::where('nama_pt', $pesanan->nama_pt)->first();

        // Load template Word
        $template = new TemplateProcessor(resource_path('storage/app/public/tamplate/Tamplate_SPJ.docx'));

        // Set value untuk placeholder
        $template->setValue('no_surat', $pesanan->no_surat);
        $template->setValue('nama_pt', $pesanan->nama_pt);
        $template->setValue('alamat_pt', $pesanan->alamat_pt);
        $template->setValue('tanggal_diterima', $pesanan->tanggal_diterima);
        $template->setValue('surat_dibuat', $pesanan->surat_dibuat);

        $template->setValue('nama_pihak_kedua', $penerimaan->nama_pihak_kedua ?? '-');
        $template->setValue('jabatan_pihak_kedua', $penerimaan->jabatan_pihak_kedua ?? '-');
        $template->setValue('subtotal', $penerimaan->subtotal ?? 0);
        $template->setValue('ppn', $penerimaan->ppn ?? 0);
        $template->setValue('grandtotal', $penerimaan->grandtotal ?? 0);
        $template->setValue('terbilang', $penerimaan->terbilang ?? '');

        $template->setValue('no_rekening', $kwitansi->no_rekening ?? '-');
        $template->setValue('nama_bank', $kwitansi->nama_bank ?? '-');
        $template->setValue('penerima_kwitansi', $kwitansi->penerima_kwitansi ?? '-');
        $template->setValue('jumlah_nominal', number_format($kwitansi->jumlah_nominal ?? 0));

        // Simpan sementara ke file Word
        $path = storage_path("app/public/spj_preview.docx");
        $template->saveAs($path);

        // Convert ke PDF (butuh LibreOffice atau DomPDF)
        // misalnya pakai DomPDF preview sederhana
        return response()->download($path)->deleteFileAfterSend(false);
    }
    public function review($id)
    {
        $spj = Spj::findOrFail($id);
        return view('users.reviewSPJ', compact('spj'));
    }

    public function download($id)
    {
        $spj = Spj::findOrFail($id);

        $template = new TemplateProcessor(resource_path('templates/Tamplate_SPJ.docx'));
        $template->setValue('no_surat', $spj->no_surat);
        $template->setValue('nama_pt', $spj->nama_pt);
        // isi semua field sesuai kebutuhan...

        $path = storage_path("app/public/spj_{$spj->id}.docx");
        $template->saveAs($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

}

