<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;


class responspart extends Controller
{
    
    /**
     * PROSES SPJ berdasarkan jenis (LS atau GU)
     */
    public function processSPJByType($spj, $errorFields)
    {
        return $spj->types === 'ls'
            ? $this->generateLS($spj, $errorFields)
            : $this->generateGU($spj, $errorFields);
    }

    private function generateGU($spj, $errorFields)
{
    $templatePath = storage_path('app/public/Tamplate_SPJ.docx');
    if (!file_exists($templatePath)) {
        return ['success' => false, 'message' => 'Template GU tidak ditemukan'];
    }

    $outputDir = storage_path('app/public/spj_marked');
    if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);

    $outputDocx = "{$outputDir}/spj_revisi_{$spj->id}.docx";
    $outputPdf  = "{$outputDir}/spj_revisi_{$spj->id}.pdf";

    $template = new TemplateProcessor($templatePath);

    // setter
    $setVal = function($key, $value) use (&$template, $errorFields) {
        $val = $value ?? '';
        if (in_array($key, $errorFields)) {
            $val = "__HIGHLIGHT_START__{$val}__HIGHLIGHT_END__";
        }
        $template->setValue($key, $val);
    };

    // ✔ PANGGIL FUNGSI YANG BENAR (case-sensitive)
    $this->fillGuDataToTemplate($spj, $setVal, $template);

    $template->saveAs($outputDocx);
    $this->applyHighlightToDocx($outputDocx);

    $cmd = '"C:\Program Files\LibreOffice\program\soffice.exe" '
         . '--headless --convert-to pdf --outdir '
         . escapeshellarg($outputDir).' '.escapeshellarg($outputDocx).' 2>&1';

    exec($cmd);

    return [
        'success' => true,
        'docx' => $outputDocx,
        'pdf'  => $outputPdf
    ];
}


    private function generateLS($spj, $errorFields)
{
    $templatePath = storage_path('app/public/Tamplate_SPJls.docx');

    if (!file_exists($templatePath)) {
        return ['success' => false, 'message' => 'Template SPJ LS tidak ditemukan'];
    }

    $outputDir = storage_path('app/public/spj_marked');
    if (!is_dir($outputDir)) mkdir($outputDir, 0755, true);

    $outputDocx = "{$outputDir}/spj_revisi_{$spj->id}.docx";
    $outputPdf  = "{$outputDir}/spj_revisi_{$spj->id}.pdf";

    $template = new TemplateProcessor($templatePath);

    $setVal = function($key, $value) use (&$template, $errorFields) {
        $val = $value ?? '';
        if (in_array($key, $errorFields)) {
            $val = "__HIGHLIGHT_START__{$val}__HIGHLIGHT_END__";
        }
        $template->setValue($key, $val);
    };

    // ✔ PANGGIL FUNGSI YANG BENAR SESUAI LS
    $this->fillLsDataToTemplate($spj, $setVal, $template);

    $template->saveAs($outputDocx);
    $this->applyHighlightToDocx($outputDocx);

    $cmd = '"C:\Program Files\LibreOffice\program\soffice.exe" '
         . '--headless --convert-to pdf --outdir '
         . escapeshellarg($outputDir).' '.escapeshellarg($outputDocx).' 2>&1';

    exec($cmd);

    return [
        'success' => true,
        'docx' => $outputDocx,
        'pdf'  => $outputPdf
    ];
}


    private function fillGuDataToTemplate($spj, $setVal, &$template)
    {
        // --------------------------
        // Ambil relasi SPJ
        //---------------------------
        $pesanan     = $spj->pesanan;
        $penerimaan  = $spj->penerimaan;
        $kwitansi    = $spj->kwitansi;
        $pemeriksaan = $spj->pemeriksaan;
        $serahbarang = $spj->serah_barang;

        // --------------------------
        // KWITANSI (FULL MATCH)
        // --------------------------
        if ($kwitansi) {
            $setVal('no_rekening', $kwitansi->no_rekening);
            $setVal('no_rekening_tujuan', $kwitansi->no_rekening_tujuan);
            $setVal('nama_bank', $kwitansi->nama_bank);
            $setVal('npwp', $kwitansi->npwp);
            $setVal('telah_diterima_dari', $kwitansi->telah_diterima_dari);
            $setVal('uang_terbilang', $penerimaan->terbilang ?? '');
            $setVal('pembayaran', $kwitansi->pembayaran);
            $setVal('sub_kegiatan', $kwitansi->kegiatan->subkegiatan ?? '');
            $setVal('jumlah_nominal', number_format($penerimaan->grandtotal ?? 0));
            $setVal('penerima_kwitansi', $kwitansi->penerima_kwitansi);
            $setVal('jabatan_penerima', $kwitansi->jabatan_penerima);
            $setVal('nama_pptk', $kwitansi->pptk->nama_pptk ?? '');
            $setVal('jabatan_pptk', $kwitansi->pptk->gol_pptk ?? '');
            $setVal('nip_pptk', $kwitansi->pptk->nip_pptk ?? '');
        }

        // --------------------------
        // PESANAN (FULL MATCH)
        // --------------------------
        if ($pesanan) {
            $setVal('nama_pt', $pesanan->nama_pt);
            $setVal('no_surat', $pesanan->no_surat);
            $setVal('alamat_pt', $pesanan->alamat_pt);
            $setVal('nomor_tlp_pt', $pesanan->nomor_tlp_pt);
            $setVal('tanggal_diterima', Carbon::parse($pesanan->tanggal_diterima)->format('d-m-Y'));
            $setVal('surat_dibuat', Carbon::parse($pesanan->surat_dibuat)->format('d-m-Y'));
        }

        // --------------------------
        // PEMERIKSAAN (FULL MATCH)
        // --------------------------
        if ($pemeriksaan) {
            $setVal('hari_diterima', $pemeriksaan->hari_diterima);
            $setVal('tanggals_diterima', $pemeriksaan->tanggals_diterima);
            $setVal('bulan_diterima', $pemeriksaan->bulan_diterima);
            $setVal('tahun_diterima', $pemeriksaan->tahun_diterima);
            $setVal('nama_pihak_kedua', $pemeriksaan->nama_pihak_kedua);
            $setVal('jabatan_pihak_kedua', $pemeriksaan->jabatan_pihak_kedua);
            $setVal('alamat_pihak_kedua', $pemeriksaan->alamat_pihak_kedua);
            $setVal('pekerjaan', $pemeriksaan->pekerjaan);

            // INI YANG PALING KRUSIAL!!!
            $setVal('no_suratss', $pemeriksaan->no_suratssss);
        }

        // --------------------------
        // SERAH BARANG (FULL MATCH)
        // --------------------------
        if ($serahbarang) {

            if ($serahbarang->plt) {
                $setVal('nama_pertama', $serahbarang->plt->nama_pihak_pertama);
                $setVal('nip_pertama', $serahbarang->plt->nip_pihak_pertama);
                $setVal('gol_pertama', $serahbarang->plt->gol_pihak_pertama);
                $setVal('jab_pertama', $serahbarang->plt->jabatan_pihak_pertama);
            }

            if ($serahbarang->pihak_kedua) {
                $setVal('nama_pengelola', $serahbarang->pihak_kedua->nama_pihak_kedua);
                $setVal('nip_pengelola', $serahbarang->pihak_kedua->nip_pihak_kedua);
                $setVal('gol_pengelola', $serahbarang->pihak_kedua->gol_pihak_kedua);
                $setVal('jabatan_pengelola', $serahbarang->pihak_kedua->jabatan_pihak_kedua);
            }

            $setVal('no_suratsss', $serahbarang->no_suratsss);
        }

        // --------------------------
        // PENERIMAAN (FULL MATCH)
        // --------------------------
        if ($penerimaan) {
            $setVal('subtotal', number_format($penerimaan->subtotal ?? 0));
            $setVal('ppn', number_format($penerimaan->ppn ?? 0));
            $setVal('grandtotal', number_format($penerimaan->grandtotal ?? 0));
            $setVal('dibulatkan', number_format($penerimaan->dibulatkan ?? 0));
            $setVal('terbilang', $penerimaan->terbilang ?? '');
            $setVal('pph', $penerimaan->pph ?? '');
            $setVal('no_suratssss', $penerimaan->no_surat);
        }

        // --------------------------
        // DETAIL BARANG (FULL MATCH)
        // --------------------------
        $details = $penerimaan?->details ?? collect();

        if ($details->count()) {

            $template->cloneRow('nama_barang1', $details->count());

            foreach ($details as $i => $detail) {
                $n = $i + 1;
                $item = $detail->pesananItem;

                $setVal("no1#{$n}", $n);
                $setVal("nama_barang1#{$n}", $item->nama_barang ?? '-');
                $setVal("jumlah1#{$n}", $item->jumlah ?? '-');
                $setVal("satuan1#{$n}", $detail->satuan ?? '-');
                $setVal("harga_satuan1#{$n}", number_format($detail->harga_satuan ?? 0));
                $setVal("total1#{$n}", number_format($detail->total ?? 0));
            }

            // blok 2
            $template->cloneRow('nama_barang2', $details->count());
            foreach ($details as $i => $detail) {
                $n = $i + 1;
                $item = $detail->pesananItem;

                $setVal("no2#{$n}", $n);
                $setVal("nama_barang2#{$n}", $item->nama_barang ?? '-');
                $setVal("jumlah2#{$n}", $item->jumlah ?? '-');
                $setVal("satuan2#{$n}", $detail->satuan ?? '-');
                $setVal("harga_satuan2#{$n}", number_format($detail->harga_satuan ?? 0));
                $setVal("total2#{$n}", number_format($detail->total ?? 0));
            }

            // blok 3
            $template->cloneRow('nama_barang3', $details->count());
            foreach ($details as $i => $detail) {
                $n = $i + 1;
                $item = $detail->pesananItem;

                $setVal("no3#{$n}", $n);
                $setVal("nama_barang3#{$n}", $item->nama_barang ?? '-');
                $setVal("jumlah3#{$n}", $item->jumlah ?? '-');
                $setVal("satuan3#{$n}", $detail->satuan ?? '-');
            }
        }
    }

    private function fillLsDataToTemplate($spj, $setVal, &$template)
    {
        $pesanan  = $spj->pesanan;
        $kwitansi = $spj->kwitansi;

        $setVal('no_rekening', $kwitansi->no_rekening);
        $setVal('no_rekening_tujuan', $kwitansi->no_rekening_tujuan);
        $setVal('nama_bank', $kwitansi->nama_bank);
        $setVal('npwp', $kwitansi->npwp);
        $setVal('telah_diterima_dari', $kwitansi->telah_diterima_dari);
        $setVal('pembayaran', $kwitansi->pembayaran);
        $setVal('sub_kegiatan', $kwitansi->kegiatan->subkegiatan);
        $setVal('penerima_kwitansi', $kwitansi->penerima_kwitansi);
        $setVal('jabatan_penerima', $kwitansi->jabatan_penerima);
        $setVal('nama_pptk', $kwitansi->pptk->nama_pptk);
        $setVal('jabatan_pptk', $kwitansi->pptk->gol_pptk);
        $setVal('nip_pptk', $kwitansi->pptk->nip_pptk);
        $setVal('nama_pertama', $kwitansi->plt->nama_pihak_pertama);
        $setVal('nip_pertama', $kwitansi->plt->nip_pihak_pertama);
        $setVal('gol_pertama', $kwitansi->plt->gol_pihak_pertama);
        $setVal('jab_pertama', $kwitansi->plt->jabatan_pihak_pertama);

        if ($pesanan) {
            $setVal('nama_pt', $pesanan->nama_pt);
            $setVal('no_surat', $pesanan->no_surat);
            $setVal('alamat_pt', $pesanan->alamat_pt);
            $setVal('nomor_tlp_pt', $pesanan->nomor_tlp_pt);
            $setVal('tanggal_diterima', Carbon::parse($pesanan->tanggal_diterima)->format('d-m-Y'));
            $setVal('surat_dibuat', Carbon::parse($pesanan->surat_dibuat)->format('d-m-Y'));
            $setVal('uang_terbilang', $pesanan->uang_terbilang);
            $setVal('jumlah_nominal', $pesanan->jumlah_nominal);
        }

        // clone row GU
        $items = $pesanan ? $pesanan->items : collect();

        if ($items->count()) {
            $template->cloneRow('nama_barang3', $items->count());

            foreach ($items as $i => $item) {
                $n = $i + 1;
                $setVal("no3#{$n}", $n);
                $setVal("nama_barang3#{$n}", $item->nama_barang);
                $setVal("jumlah3#{$n}", $item->jumlah);
                $setVal("satuan3#{$n}", $item->satuan);
            }
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
