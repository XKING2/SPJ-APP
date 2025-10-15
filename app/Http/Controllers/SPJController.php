<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Spj;
use App\Models\Pesanan;
use App\Models\Pemeriksaan;
use App\Models\Penerimaan;
use App\Models\Kwitansi;
use App\Models\User;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Log;

class SPJController extends Controller
{
    public function index()
    {
        $spjs = Spj::with(['pesanan', 'kwitansi', 'pemeriksaan', 'penerimaan'])->latest()->get();
        return view('users.pesanan', compact('spjs'));
    }

    public function create(Request $request)
    {
        $userId = Auth::id() ?? session('user_id'); // backup kalau Auth hilang

        if (!$userId) {
            return back()->withErrors(['auth' => 'User belum login']);
        }

        $spj = Spj::create([
            'status'         => 'draft',
            'pesanan_id'     => $request->pesanan_id,
            'kwitansi_id'    => $request->kwitansi_id,
            'penerimaan_id'  => $request->penerimaan_id,
            'pemeriksaan_id' => $request->pemeriksaan_id,
            'user_id'        => $userId, // ✅ integer ID user login
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
                'pemeriksaan'
            ])->findOrFail($id);

            $pesanan     = $spj->pesanan;
            $penerimaan  = $spj->penerimaan;
            $kwitansi    = $spj->kwitansi;
            $pemeriksaan = $spj->pemeriksaan;

            // Path file
            $templatePath = storage_path('app/public/Tamplate_SPJ.docx');
            $outputDocx   = storage_path("app/public/spj_preview_{$spj->id}.docx");
            $outputPdfDir = storage_path('app/public');
            $outputPdf    = "{$outputPdfDir}/spj_preview_{$spj->id}.pdf";

            if (!file_exists($templatePath)) {
                throw new Exception('Template SPJ tidak ditemukan.');
            }

            $template = new TemplateProcessor($templatePath);

            // 4️⃣ Set nilai dari database
            $template->setValue('no_rekening', $kwitansi->no_rekening ?? '-');
            $template->setValue('no_rekening_tujuan', $kwitansi->no_rekening_tujuan ?? '-');
            $template->setValue('nama_bank', $kwitansi->nama_bank ?? '-');
            $template->setValue('npwp', $kwitansi->npwp ?? '-');
            $template->setValue('telah_diterima_dari', $kwitansi->telah_diterima_dari ?? '-');
            $template->setValue('uang_terbilang', $kwitansi->uang_terbilang ?? '-');
            $template->setValue('pembayaran', $kwitansi->pembayaran ?? '-');
            $template->setValue('sub_kegiatan', $kwitansi->sub_kegiatan ?? '-');
            $template->setValue('jumlah_nominal', number_format($kwitansi->jumlah_nominal ?? 0));
            $template->setValue('penerima_kwitansi', $kwitansi->penerima_kwitansi ?? '-');
            $template->setValue('jabatan_penerima', $kwitansi->jabatan_penerima ?? '-');
            $template->setValue('subkegiatan', $kwitansi->pptk->subkegiatan ?? '-');
            $template->setValue('nama_pptk', $kwitansi->pptk->nama_pptk ?? '-');
            $template->setValue('jabatan_pptk', $kwitansi->pptk->jabatan_pptk ?? '-');
            $template->setValue('nip_pptk', $kwitansi->pptk->nip_pptk ?? '-');

            // Data Pesanan
            if ($pesanan) {
                $template->setValue('nama_pt',  $pesanan->nama_pt ?? '-');
                $template->setValue('no_surat', $pesanan->no_surat ?? '-');
                $template->setValue('alamat_pt', $pesanan->alamat_pt ?? '-');
                $template->setValue('nomor_tlp_pt', $pesanan->nomor_tlp_pt ?? '-');
                $template->setValue('tanggal_diterima', $pesanan->tanggal_diterima ?? '-');
                $template->setValue('surat_dibuat', $pesanan->surat_dibuat ?? '-');
            }

            // Pemeriksaan
            if ($pemeriksaan) {
                $template->setValue('hari_diterima', $pemeriksaan->hari_diterima ?? '-');
                $template->setValue('tanggals_diterima', $pemeriksaan->tanggals_diterima ?? '-');
                $template->setValue('bulan_diterima', $pemeriksaan->bulan_diterima ?? '-');
                $template->setValue('tahun_diterima', $pemeriksaan->tahun_diterima ?? '-');
                $template->setValue('nama_pihak_kedua', $pemeriksaan->nama_pihak_kedua ?? '-');
                $template->setValue('jabatan_pihak_kedua', $pemeriksaan->jabatan_pihak_kedua ?? '-');
                $template->setValue('alamat_pihak_kedua', $pemeriksaan->alamat_pihak_kedua ?? '-');
                $template->setValue('pekerjaan', $pemeriksaan->pekerjaan ?? '-');
            }

            // Data Penerimaan
            if ($penerimaan) {
                $template->setValue('subtotal', number_format($penerimaan->subtotal ?? 0));
                $template->setValue('ppn', number_format($penerimaan->ppn ?? 0));
                $template->setValue('grandtotal', number_format($penerimaan->grandtotal ?? 0));
                $template->setValue('dibulatkan', number_format($penerimaan->dibulatkan ?? 0));
                $template->setValue('terbilang', $penerimaan->terbilang ?? '-');
            }

            // 🔁 Ambil detail penerimaan barang
            $details = $penerimaan ? $penerimaan->details : collect();

            if ($details->count() > 0) {
                $template->cloneRow('nama_barang1', $details->count());
                foreach ($details as $i => $detail) {
                    $n = $i + 1;
                    $template->setValue("no1#{$n}", $n); // 👉 Tambahkan ini
                    $template->setValue("nama_barang1#{$n}", $detail->nama_barang ?? '-');
                    $template->setValue("jumlah1#{$n}", $detail->jumlah ?? '-');
                    $template->setValue("satuan1#{$n}", $detail->satuan ?? '-');
                    $template->setValue("harga_satuan1#{$n}", number_format($detail->harga_satuan ?? 0));
                    $template->setValue("subtotal1#{$n}", number_format($detail->total ?? 0));
                }

                // Serah Terima
                $template->cloneRow('nama_barang2', $details->count());
                foreach ($details as $i => $detail) {
                    $n = $i + 1;
                    $template->setValue("no2#{$n}", $n); // Tambahkan juga untuk tabel ke-2
                    $template->setValue("nama_barang2#{$n}", $detail->nama_barang ?? '-');
                    $template->setValue("jumlah2#{$n}", $detail->jumlah ?? '-');
                    $template->setValue("satuan2#{$n}", $detail->satuan ?? '-');
                    $template->setValue("harga_satuan2#{$n}", number_format($detail->harga_satuan ?? 0));
                    $template->setValue("subtotal2#{$n}", number_format($detail->total ?? 0));
                }

                $template->cloneRow('nama_barang3', $details->count());
                foreach ($details as $i => $detail) {
                    $n = $i + 1;
                    $template->setValue("no3#{$n}", $n); // Tambahkan juga untuk tabel ke-2
                    $template->setValue("nama_barang3#{$n}", $detail->nama_barang ?? '-');
                    $template->setValue("jumlah3#{$n}", $detail->jumlah ?? '-');
                    $template->setValue("satuan3#{$n}", $detail->satuan ?? '-');
                }

                
            }

            // Simpan Word
            $template->saveAs($outputDocx);

            // Konversi ke PDF
            $command = "soffice --headless --convert-to pdf --outdir " 
                . escapeshellarg($outputPdfDir) . " " 
                . escapeshellarg($outputDocx);
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new Exception("Gagal konversi ke PDF. Pastikan LibreOffice terinstal.");
            }

            // Pastikan file tersimpan di storage/public
            if (file_exists($outputPdf)) {
                Storage::disk('public')->putFileAs('spj_generated', new \Illuminate\Http\File($outputPdf), "spj_{$spj->id}.pdf");
            }

        } catch (Exception $e) {
            Log::error('Gagal generate SPJ otomatis: ' . $e->getMessage());
        }
    }

    public function preview($id)
    {
        $spj = Spj::with('pesanan')->findOrFail($id);

        // Lokasi file di storage
        $relativePath = "spj_preview_{$spj->id}.pdf";
        $pdfPath = storage_path("app/public/{$relativePath}");

        // Pastikan file-nya ada
        if (!file_exists($pdfPath)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }

        // Buat URL publik
        $fileUrl = asset("storage/{$relativePath}");

        return view('users.previewSPJ', compact('spj', 'fileUrl'));
    }

    public function submitToBendahara($id)
    {

    $spj = Spj::findOrFail($id);

    // Pastikan SPJ masih draft sebelum bisa diajukan
    if ($spj->status !== 'draft') {
        return redirect()->back()->with('error', 'SPJ ini sudah diajukan atau divalidasi.');
    }

    $spj->update(['status' => 'diajukan']);

    return redirect()->back()->with('success', 'SPJ berhasil diajukan ke Bendahara untuk diverifikasi.');

    }
    public function ajukanKasubag($id)
    {
        $spj = Spj::findOrFail($id);

        // Hanya bisa diajukan kalau sudah valid oleh Bendahara
        if ($spj->status !== 'valid') {
            return back()->with('error', 'SPJ belum divalidasi oleh Bendahara.');
        }

        // Update status2 agar menandakan SPJ telah diajukan ke Kasubag
        $spj->update([
            'status2' => 'diajukan',
        ]);

        return back()->with('success', 'SPJ berhasil diajukan ke Kasubag untuk validasi.');
    }

    public function cetak($id)
    {
        try {
            // Path file PDF hasil generate sebelumnya
            $pdfPath = storage_path("app/public/spj_preview_{$id}.pdf");

            // 🔍 Jika file belum ada, buat dulu
            if (!file_exists($pdfPath)) {
                // Panggil fungsi generateSPJDocument untuk membuatnya
                $this->generateSPJDocument($id);

                // Cek ulang apakah berhasil dibuat
                if (!file_exists($pdfPath)) {
                    return redirect()->back()->with('error', 'Gagal membuat file PDF SPJ.');
                }
            }

            // 🖨️ Kirim file sebagai download response
            return response()->download($pdfPath, "SPJ_{$id}.pdf")->deleteFileAfterSend(false);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencetak SPJ: ' . $e->getMessage());
        }
    }


}
