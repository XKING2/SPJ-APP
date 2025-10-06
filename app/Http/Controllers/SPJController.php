<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spj;
use App\Models\Pesanan;
use App\Models\Pemeriksaan;
use App\Models\Penerimaan;
use App\Models\Kwitansi;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Exception;



class SPJController extends Controller
{
    public function index()
    {
        $spjs = Spj::with(['pesanan', 'kwitansi', 'pemeriksaan', 'penerimaan'])->latest()->get();
        return view('users.pesanan', compact('spjs'));
    }

    public function create(Request $request)
    {
        // 1️⃣ Buat SPJ
        $spj = Spj::create([
            'status'         => 'draft',
            'pesanan_id'     => $request->pesanan_id,
            'kwitansi_id'    => $request->kwitansi_id,
            'penerimaan_id'  => $request->penerimaan_id,
            'pemeriksaan_id' => $request->pemeriksaan_id,
        ]);

        // 2️⃣ Update relasi agar sinkron
        if ($request->pesanan_id) {
            Pesanan::where('id', $request->pesanan_id)
                ->update(['spj_id' => $spj->id]);
        }

        if ($request->kwitansi_id) {
            Kwitansi::where('id', $request->kwitansi_id)
                ->update(['spj_id' => $spj->id]);
        }

        if ($request->penerimaan_id) {
            Penerimaan::where('id', $request->penerimaan_id)
                ->update(['spj_id' => $spj->id]);
        }

        if ($request->pemeriksaan_id) {
            Pemeriksaan::where('id', $request->pemeriksaan_id)
                ->update(['spj_id' => $spj->id]);
        }

        // 3️⃣ Simpan ID SPJ di session
        session(['current_spj_id' => $spj->id]);

        // 4️⃣ Redirect ke halaman selanjutnya, misal ke form Kwitansi
        return redirect()->route('kwitansi.create', ['spj_id' => $spj->id]);
    }

    public function review($spj_id)
    {
        $spj = Spj::with(['pesanan', 'kwitansi', 'pemeriksaan', 'penerimaan'])->findOrFail($spj_id);
        return view('users.reviewSPJ', compact('spj'));
    }




public function preview($id)
    {
        try {
            // 1️⃣ Ambil data lengkap SPJ dan relasinya
            $spj = Spj::with([
                'pesanan.items',
                'penerimaan',
                'kwitansi',
                'pemeriksaan'
            ])->findOrFail($id);

            $pesanan     = $spj->pesanan;
            $penerimaan  = $spj->penerimaan;
            $kwitansi    = $spj->kwitansi;
            $pemeriksaan = $spj->pemeriksaan;

            // 2️⃣ Path file
            $templatePath = storage_path('app/public/Tamplate_SPJ.docx');
            $outputDocx   = storage_path("app/public/spj_preview_{$spj->id}.docx");
            $outputPdfDir = storage_path('app/public');
            $outputPdf    = "{$outputPdfDir}/spj_preview_{$spj->id}.pdf";

            if (!file_exists($templatePath)) {
                return back()->with('error', 'Template tidak ditemukan.');
            }

            // 3️⃣ Load template Word
            $template = new TemplateProcessor($templatePath);

            // 4️⃣ Set nilai dari database (pakai nama placeholder yang sesuai di DOCX)
            $template->setValue('${{no_rekening}}', $kwitansi->no_rekening ?? '-');
            $template->setValue('${{nama_pt}}', $kwitansi->nama_pt ?? $pesanan->nama_pt ?? '-');
            $template->setValue('${{no_rekening_tujuan}}', $kwitansi->no_rekening_tujuan ?? '-');
            $template->setValue('${{nama_bank}}', $kwitansi->nama_bank ?? '-');
            $template->setValue('${{npwp}}', $kwitansi->npwp ?? '-');
            $template->setValue('${{telah_diterima_dari}}', $kwitansi->telah_diterima_dari ?? '-');
            $template->setValue('${{uang_terbilang}}', $kwitansi->uang_terbilang ?? '-');
            $template->setValue('${{pembayaran}}', $kwitansi->pembayaran ?? '-');
            $template->setValue('${{sub_kegiatan}}', $kwitansi->sub_kegiatan ?? '-');
            $template->setValue('${{jumlah_nominal}}', number_format($kwitansi->jumlah_nominal ?? 0));
            $template->setValue('${{penerima_kwitansi}}', $kwitansi->penerima_kwitansi ?? '-');
            $template->setValue('${{jabatan_penerima}}', $kwitansi->jabatan_penerima ?? '-');

            // Data Pesanan
            if ($pesanan) {
                $template->setValue('${{no_surat}}', $pesanan->no_surat ?? '-');
                $template->setValue('${{alamat_pt}}', $pesanan->alamat_pt ?? '-');
                $template->setValue('${{nomor_tlp_pt}}', $pesanan->nomor_tlp_pt ?? '-');
                $template->setValue('${{tanggal_diterima}}', $pesanan->tanggal_diterima ?? '-');
                $template->setValue('${{surat_dibuat}}', $pesanan->surat_dibuat ?? '-');
            }

            // Pemeriksaan
            if ($pemeriksaan) {
                $template->setValue('${{hari_diterima}}', $pemeriksaan->hari_diterima ?? '-');
                $template->setValue('${{bulan_diterima}}', $pemeriksaan->bulan_diterima ?? '-');
                $template->setValue('${{tahun_diterima}}', $pemeriksaan->tahun_diterima ?? '-');
                $template->setValue('${{nama_pihak_kedua}}', $pemeriksaan->nama_pihak_kedua ?? '-');
                $template->setValue('${{jabatan_pihak_kedua}}', $pemeriksaan->jabatan_pihak_kedua ?? '-');
                $template->setValue('${{alamat_pihak_kedua}}', $pemeriksaan->alamat_pihak_kedua ?? '-');
                $template->setValue('${{pekerjaan}}', $pemeriksaan->pekerjaan ?? '-');
            }

            // Penerimaan
            if ($penerimaan) {
                $template->setValue('${{subtotal}}', number_format($penerimaan->subtotal ?? 0));
                $template->setValue('${{ppn}}', number_format($penerimaan->ppn ?? 0));
                $template->setValue('${{grandtotal}}', number_format($penerimaan->grandtotal ?? 0));
                $template->setValue('${{dibulatkan}}', number_format($penerimaan->dibulatkan ?? 0));
                $template->setValue('${{terbilang}}', $penerimaan->terbilang ?? '-');
            }

            // Item Barang (ambil hanya item pertama untuk contoh)
            if ($pesanan && $pesanan->items->count() > 0) {
                $item = $pesanan->items->first();
                $template->setValue('${{nama_barang}}', $item->nama_barang ?? '-');
                $template->setValue('${{jumlah}}', $item->jumlah ?? '-');
                $template->setValue('${{satuan}}', $item->satuan ?? '-');
                $template->setValue('${{harga_satuan}}', $item->harga_satuan ?? '-');
            }

            // 5️⃣ Simpan file hasil Word
            $template->saveAs($outputDocx);

            // 6️⃣ Konversi ke PDF menggunakan LibreOffice (headless)
            $command = "soffice --headless --convert-to pdf --outdir " . escapeshellarg($outputPdfDir) . " " . escapeshellarg($outputDocx);
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new Exception("Gagal konversi ke PDF. Pastikan LibreOffice terinstal di server.");
            }

            // 7️⃣ Tampilkan hasil PDF langsung ke browser
            return response()->file($outputPdf);

        } catch (Exception $e) {
            return back()->with('error', 'Gagal membuat preview: ' . $e->getMessage());
        }
    }



}
