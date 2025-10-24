<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spj;
use App\Models\spj_feedbacks;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use DOMDocument;
use Exception;

class spjresponcontrol extends Controller
{
    public function store(Request $request, $spjId)
    {
        // ✅ 1. Validasi — dukung multiple feedback (array)
        $request->validate([
            'field_name' => 'required|array|min:1',
            'field_name.*' => 'required|string|max:255',
            'message' => 'required|array|min:1',
            'message.*' => 'required|string|max:1000',
        ]);

        Log::info('=== [SPJ PROCESS START - XML INLINE HIGHLIGHT VERSION] ===');
        Log::info('User: ' . (Auth::id() ?? 'guest') . ' | SPJ ID: ' . $spjId);

        try {
            $spj = Spj::with(['penerimaan.details.pesananItem', 'pemeriksaan.plt', 'kwitansi.pptk', 'pesanan'])
                ->findOrFail($spjId);

            // ✅ 2. Simpan semua feedback (multiple input)
            foreach ($request->field_name as $index => $field) {
                $message = $request->message[$index] ?? null;
                if (!$field || !$message) continue;

                spj_feedbacks::create([
                    'spj_id' => $spj->id,
                    'user_id' => Auth::id(),
                    'field_name' => $field,
                    'message' => $message,
                    'role' => Auth::user()->role ?? 'admin',
                ]);

                Log::info("Feedback tersimpan: field={$field}, message={$message}");
            }

            // ✅ 3. Ambil semua field yang bermasalah
            $errorFields = spj_feedbacks::where('spj_id', $spj->id)
                ->pluck('field_name')->unique()->toArray();
            Log::info('Field yang bermasalah: ' . implode(', ', $errorFields));

            // ✅ 4. Pastikan template tersedia
            $templatePath = storage_path('app/public/Tamplate_SPJ.docx');
            if (!file_exists($templatePath)) {
                Log::error('Template tidak ditemukan: ' . $templatePath);
                return response()->json(['success' => false, 'message' => 'Template SPJ tidak ditemukan.'], 500);
            }

            // ✅ 5. Siapkan direktori output
            $outputDir = storage_path('app/public/spj_marked');
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
                Log::info("Folder spj_marked dibuat di: {$outputDir}");
            }

            $outputDocx = "{$outputDir}/spj_revisi_{$spj->id}.docx";
            $outputPdf  = "{$outputDir}/spj_revisi_{$spj->id}.pdf";

            // ✅ 6. Load template DOCX
            $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

            // Helper untuk menandai field yang error
            $setVal = function ($key, $value) use (&$template, $errorFields) {
                $val = $value ?? '-';
                if (in_array($key, $errorFields)) {
                    $val = "__HIGHLIGHT_START__{$val}__HIGHLIGHT_END__";
                    Log::info("Menandai field $key untuk highlight (token ditambahkan).");
                }
                $template->setValue($key, $val);
            };

            // ✅ 7. Ambil semua relasi
            $kwitansi = $spj->kwitansi;
            $penerimaan = $spj->penerimaan;
            $pemeriksaan = $spj->pemeriksaan;
            $pesanan = $spj->pesanan;

            // ✅ 8. Isi KWITANSI
            $setVal('no_rekening', $kwitansi->no_rekening ?? '-');
            $setVal('no_rekening_tujuan', $kwitansi->no_rekening_tujuan ?? '-');
            $setVal('nama_bank', $kwitansi->nama_bank ?? '-');
            $setVal('npwp', $kwitansi->npwp ?? '-');
            $setVal('telah_diterima_dari', $kwitansi->telah_diterima_dari ?? '-');
            $setVal('uang_terbilang', $kwitansi->uang_terbilang ?? '-');
            $setVal('pembayaran', $kwitansi->pembayaran ?? '-');
            $setVal('sub_kegiatan', $kwitansi->sub_kegiatan ?? '-');
            $setVal('jumlah_nominal', number_format($kwitansi->jumlah_nominal ?? 0));
            $setVal('penerima_kwitansi', $kwitansi->penerima_kwitansi ?? '-');
            $setVal('jabatan_penerima', $kwitansi->jabatan_penerima ?? '-');

            if ($kwitansi && $kwitansi->pptk) {
                $setVal('subkegiatan', $kwitansi->pptk->subkegiatan ?? '-');
                $setVal('nama_pptk', $kwitansi->pptk->nama_pptk ?? '-');
                $setVal('jabatan_pptk', $kwitansi->pptk->jabatan_pptk ?? '-');
                $setVal('nip_pptk', $kwitansi->pptk->nip_pptk ?? '-');
            }

            // ✅ 9. PESANAN
            if ($pesanan) {
                $setVal('nama_pt', $pesanan->nama_pt ?? '-');
                $setVal('no_surat', $pesanan->no_surat ?? '-');
                $setVal('alamat_pt', $pesanan->alamat_pt ?? '-');
                $setVal('nomor_tlp_pt', $pesanan->nomor_tlp_pt ?? '-');
                $setVal('tanggal_diterima', $pesanan->tanggal_diterima ?? '-');
                $setVal('surat_dibuat', $pesanan->surat_dibuat ?? '-');
            }

            // ✅ 10. PEMERIKSAAN
            if ($pemeriksaan) {
                $setVal('hari_diterima', $pemeriksaan->hari_diterima ?? '-');
                $setVal('tanggals_diterima', $pemeriksaan->tanggals_diterima ?? '-');
                $setVal('bulan_diterima', $pemeriksaan->bulan_diterima ?? '-');
                $setVal('tahun_diterima', $pemeriksaan->tahun_diterima ?? '-');
                $setVal('nama_pihak_kedua', $pemeriksaan->nama_pihak_kedua ?? '-');
                $setVal('jabatan_pihak_kedua', $pemeriksaan->jabatan_pihak_kedua ?? '-');
                $setVal('alamat_pihak_kedua', $pemeriksaan->alamat_pihak_kedua ?? '-');
                $setVal('pekerjaan', $pemeriksaan->pekerjaan ?? '-');

                if ($pemeriksaan->plt) {
                    $setVal('nama_pertama', $pemeriksaan->plt->nama_pihak_pertama ?? '-');
                    $setVal('nip_pertama', $pemeriksaan->plt->nip_pihak_pertama ?? '-');
                    $setVal('gol_pertama', $pemeriksaan->plt->gol_pihak_pertama ?? '-');
                    $setVal('jab_pertama', $pemeriksaan->plt->jabatan_pihak_pertama ?? '-');
                }
            }

            // ✅ 11. PENERIMAAN
            if ($penerimaan) {
                $setVal('subtotal', number_format($penerimaan->subtotal ?? 0));
                $setVal('ppn', number_format($penerimaan->ppn ?? 0));
                $setVal('grandtotal', number_format($penerimaan->grandtotal ?? 0));
                $setVal('dibulatkan', number_format($penerimaan->dibulatkan ?? 0));
                $setVal('terbilang', $penerimaan->terbilang ?? '-');
            }

            // ✅ 12. DETAIL BARANG
            $details = $penerimaan && $penerimaan->details ? $penerimaan->details->load('pesananItem') : collect();

            if ($details->count() > 0) {
                $template->cloneRow('nama_barang1', $details->count());
                foreach ($details as $i => $detail) {
                    $n = $i + 1;
                    $item = $detail->pesananItem;

                    $setVal("no1#{$n}", $n);
                    $setVal("nama_barang1#{$n}", $item->nama_barang ?? '-');
                    $setVal("jumlah1#{$n}", $item->jumlah ?? '-');
                    $setVal("satuan1#{$n}", $detail->satuan ?? '-');
                    $setVal("harga_satuan1#{$n}", number_format($detail->harga_satuan ?? 0, 0, ',', '.'));
                    $setVal("total1#{$n}", number_format($detail->total ?? 0, 0, ',', '.'));
                }

                // Serah Terima
                $template->cloneRow('nama_barang2', $details->count());
                foreach ($details as $i => $detail) {
                    $n = $i + 1;
                    $item = $detail->pesananItem;

                    $setVal("no2#{$n}", $n);
                    $setVal("nama_barang2#{$n}", $item->nama_barang ?? '-');
                    $setVal("jumlah2#{$n}", $item->jumlah ?? '-');
                    $setVal("satuan2#{$n}", $detail->satuan ?? '-');
                    $setVal("harga_satuan2#{$n}", number_format($detail->harga_satuan ?? 0, 0, ',', '.'));
                    $setVal("total2#{$n}", number_format($detail->total ?? 0, 0, ',', '.'));
                }

                // Ringkasan
                $template->cloneRow('nama_barang3', $details->count());
                foreach ($details as $i => $detail) {
                    $n = $i + 1;
                    $item = $detail->pesananItem;

                    $setVal("no3#{$n}", $n);
                    $setVal("nama_barang3#{$n}", $item->nama_barang ?? '-');
                    $setVal("jumlah3#{$n}", $item->jumlah ?? '-');
                    $setVal("satuan3#{$n}", $detail->satuan ?? '-');
                }
            } else {
                $setVal('nama_barang1', '-');
                $setVal('jumlah1', '-');
                $setVal('satuan1', '-');
                $setVal('harga_satuan1', '-');
                $setVal('subtotal1', '-');
            }

            // ✅ 13. Simpan DOCX hasil isi
            $template->saveAs($outputDocx);
            Log::info("Template disimpan ke $outputDocx");

            // ✅ 14. Terapkan highlight valid XML (gunakan fungsi khusus)
            if (method_exists($this, 'applyHighlightToDocx')) {
                $this->applyHighlightToDocx($outputDocx);
                Log::info('Highlight diterapkan dengan sukses.');
            } else {
                Log::warning('Fungsi applyHighlightToDocx tidak ditemukan di controller.');
            }

            // ✅ 15. Konversi ke PDF (pakai LibreOffice)
            $soffice = '"C:\Program Files\LibreOffice\program\soffice.exe"';
            $cmd = $soffice . ' --headless --convert-to pdf --outdir ' .
                escapeshellarg($outputDir) . ' ' . escapeshellarg($outputDocx) . ' 2>&1';

            Log::info('[RUNNING PDF CONVERT] ' . $cmd);
            exec($cmd, $output, $code);
            Log::info('[PDF CONVERT] Code=' . $code . ' Output=' . implode("\n", $output));

            Log::info('=== [SPJ PROCESS END SUCCESS] ===');

            return response()->json([
                'success' => true,
                'message' => 'SPJ berhasil disimpan dengan highlight warna merah dan latar kuning.',
                'revised_docx' => asset('storage/spj_marked/spj_revisi_' . $spj->id . '.docx'),
                'revised_pdf' => file_exists($outputPdf) ? asset('storage/spj_marked/spj_revisi_' . $spj->id . '.pdf') : null,
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal proses SPJ: ' . $e->getMessage() . ' di ' . $e->getFile() . ':' . $e->getLine());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    private function applyHighlightToDocx($path)
    {
        $zip = new \ZipArchive;
        if ($zip->open($path) !== true) {
            Log::error("Gagal membuka DOCX: $path");
            return;
        }

        $xml = $zip->getFromName('word/document.xml');
        if (!$xml) {
            Log::error("Tidak bisa baca document.xml dari $path");
            $zip->close();
            return;
        }

        // Gabungkan potongan teks agar token utuh
        $xml = preg_replace('/<\/w:t>\s*<w:t[^>]*>/', '', $xml);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        @$dom->loadXML($xml);

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        $nodes = $xpath->query('//w:t[contains(., "__HIGHLIGHT_START__")]');
        $count = 0;

        foreach ($nodes as $node) {
            $text = $node->nodeValue;

            // Pecah teks di antara token highlight
            if (preg_match('/(.*?)__HIGHLIGHT_START__(.*?)__HIGHLIGHT_END__(.*)/s', $text, $m)) {
                [$all, $before, $highlight, $after] = $m;

                /** @var \DOMElement $r */
                $r = $node->parentNode; // <w:r>

                // Ambil gaya font dari run asal
                $oldRPr = $r->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'rPr')->item(0);
                $copiedRPr = $oldRPr ? $oldRPr->cloneNode(true) : null;

                // Buat run baru untuk highlight
                $newRun = $dom->createElementNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'w:r');

                // Gunakan gaya font lama jika ada
                if ($copiedRPr) {
                    $rPr = $copiedRPr;
                } else {
                    $rPr = $dom->createElementNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'w:rPr');
                }

                // Tambahkan hanya warna & shading (tidak ubah font/size)
                $color = $dom->createElementNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'w:color');
                $color->setAttribute('w:val', 'FF0000');
                $highlightEl = $dom->createElementNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'w:highlight');
                $highlightEl->setAttribute('w:val', 'yellow');
                $shd = $dom->createElementNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'w:shd');
                $shd->setAttribute('w:val', 'clear');
                $shd->setAttribute('w:fill', 'FFFF00');

                $rPr->appendChild($color);
                $rPr->appendChild($highlightEl);
                $rPr->appendChild($shd);
                $newRun->appendChild($rPr);

                // Tambahkan teks yang di-highlight
                $newText = $dom->createElementNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'w:t', htmlspecialchars($highlight));
                $newRun->appendChild($newText);

                // Update teks sebelum highlight
                $node->nodeValue = $before;

                // Sisipkan run baru setelah teks sebelum highlight
                if ($r->nextSibling) {
                    $r->parentNode->insertBefore($newRun, $r->nextSibling);
                } else {
                    $r->parentNode->appendChild($newRun);
                }

                // Jika ada teks sesudah highlight
                if (trim($after) !== '') {
                    /** @var \DOMElement $afterRun */
                    $afterRun = $r->cloneNode(true);
                    /** @var \DOMNodeList $nodeList */
                    $afterRunText = $afterRun->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 't')->item(0);

                    if ($afterRunText) {
                        $afterRunText->nodeValue = $after;
                    }
                    $r->parentNode->insertBefore($afterRun, $newRun->nextSibling);
                }

                $count++;
            }
        }

        // Simpan ulang
        $newXml = $dom->saveXML();
        $zip->deleteName('word/document.xml');
        $zip->addFromString('word/document.xml', $newXml);
        $zip->close();

        Log::info("Highlight diterapkan pada {$count} field tanpa mengubah font atau ukuran teks.");
    }


}
