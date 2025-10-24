@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Edit Data Penerimaan</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('penerimaan.update', $penerimaan->id) }}" method="POST" id="penerimaanForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="spj_id" value="{{ $spj->id }}">
                <input type="hidden" name="pemeriksaan_id" value="{{ $pemeriksaan->id }}">
                <input type="hidden" name="pesanan_id" value="{{ $pesanan->id }}">

                <div class="row">
                    <!-- Kolom kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pekerjaan Yang Dilakukan</label>
                            <input type="text" name="pekerjaan" class="form-control" 
                                value="{{ old('pekerjaan', $penerimaan->pekerjaan ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor SP</label>
                            <input type="text" name="no_surat" class="form-control" 
                                value="{{ old('no_surat', $penerimaan->no_surat ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal SP</label>
                            <input type="date" name="surat_dibuat" class="form-control" 
                                value="{{ old('surat_dibuat', $penerimaan->surat_dibuat ?? '') }}">
                        </div>
                    </div>

                    <!-- Kolom kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pihak Kedua</label>
                            <input type="text" name="nama_pihak_kedua" class="form-control" 
                                value="{{ old('nama_pihak_kedua', $penerimaan->nama_pihak_kedua ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Pihak Kedua</label>
                            <input type="text" name="jabatan_pihak_kedua" class="form-control" 
                                value="{{ old('jabatan_pihak_kedua', $penerimaan->jabatan_pihak_kedua ?? '') }}">
                        </div>
                    </div>
                </div>

                <!-- Detail Barang -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Detail Barang</label>
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Jenis Barang</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Harga Satuan (Rp)</th>
                                <th>Total Harga (Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barang-table">
                            @foreach($barangList as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <input type="hidden" name="barang[{{ $i }}][id]" value="{{ $item->id }}">
                                        <input type="text" name="barang[{{ $i }}][nama_barang]" class="form-control" 
                                            value="{{ $item->nama_barang ?? ($item->pesananItem->nama_barang ?? '') }}">
                                    </td>
                                    <td>
                                        <input type="number" name="barang[{{ $i }}][jumlah]" class="form-control jumlah" 
                                            value="{{ $item->jumlah ?? ($item->pesananItem->jumlah ?? 1) }}">
                                    </td>
                                    <td>
                                        <input type="text" name="barang[{{ $i }}][satuan]" class="form-control" 
                                            value="{{ $item->satuan ?? ($item->pesananItem->satuan ?? '') }}">
                                    </td>
                                    <td>
                                        <input type="number" name="barang[{{ $i }}][harga_satuan]" class="form-control harga" 
                                            value="{{ $item->harga_satuan ?? 0 }}">
                                    </td>
                                    <td>
                                        <input type="number" name="barang[{{ $i }}][total]" class="form-control total" 
                                            value="{{ $item->total ?? 0 }}" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="button" id="addRowBtn" 
                        class="btn btn-sm btn-primary" 
                        data-row-count="{{ count($barangList) }}">
                        <i class="bi bi-plus-circle"></i> Tambah Barang
                    </button>

                    <table class="table table-bordered w-50 ms-auto mt-3">
                        <tr>
                            <th class="text-end">Jumlah</th>
                            <td><input type="number" id="subtotal" name="subtotal" class="form-control" value="{{ $penerimaan->subtotal }}" readonly></td>
                        </tr>
                        <tr>
                            <th class="text-end">PPN 10%</th>
                            <td><input type="number" id="ppn" name="ppn" class="form-control" value="{{ $penerimaan->ppn }}" readonly></td>
                        </tr>
                        <tr>
                            <th class="text-end">Total Harga</th>
                            <td><input type="number" id="grandtotal" name="grandtotal" class="form-control" value="{{ $penerimaan->grandtotal }}" readonly></td>
                        </tr>
                        <tr>
                            <th class="text-end">Dibulatkan</th>
                            <td><input type="number" id="dibulatkan" name="dibulatkan" class="form-control" value="{{ $penerimaan->dibulatkan }}" readonly></td>
                        </tr>
                    </table>
                    <div class="mt-2">
                        <label class="form-label fw-bold">Terbilang :</label>
                        <input type="text" id="terbilang" name="terbilang" class="form-control" value="{{ $penerimaan->terbilang }}" readonly>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('penerimaan') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Update Penerimaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ✅ SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    let addBtn = document.getElementById('addRowBtn');
    let rowIndex = parseInt(addBtn.dataset.rowCount) || 0;
    const table = document.getElementById('barang-table');

    window.removeRow = function (btn) {
        btn.closest('tr').remove();
        hitungTotal();
    }

    addBtn.addEventListener('click', function () {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${++rowIndex}</td>
            <td><input type="text" name="barang[${rowIndex}][nama_barang]" class="form-control"></td>
            <td><input type="number" name="barang[${rowIndex}][jumlah]" class="form-control jumlah" value="1"></td>
            <td><input type="text" name="barang[${rowIndex}][satuan]" class="form-control" value="Pcs"></td>
            <td><input type="number" name="barang[${rowIndex}][harga_satuan]" class="form-control harga" value="0"></td>
            <td><input type="number" name="barang[${rowIndex}][total]" class="form-control total" value="0" readonly></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="bi bi-trash"></i> Hapus</button></td>
        `;
        table.appendChild(row);
    });

    function terbilangRupiah(angka) {
        const satuan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan"];
        const belasan = ["Sepuluh", "Sebelas", "Dua Belas", "Tiga Belas", "Empat Belas", "Lima Belas", "Enam Belas", "Tujuh Belas", "Delapan Belas", "Sembilan Belas"];
        const puluhan = ["", "", "Dua Puluh", "Tiga Puluh", "Empat Puluh", "Lima Puluh", "Enam Puluh", "Tujuh Puluh", "Delapan Puluh", "Sembilan Puluh"];
        const ribuan = ["", "Ribu", "Juta", "Miliar", "Triliun"];
        if (angka === 0) return "Nol Rupiah";

        function konversi(num) {
            let str = "";
            if (num >= 100) {
                if (Math.floor(num / 100) === 1) str += "Seratus ";
                else str += satuan[Math.floor(num / 100)] + " Ratus ";
                num %= 100;
            }
            if (num >= 10 && num <= 19) {
                str += belasan[num - 10] + " ";
            } else if (num >= 20) {
                str += puluhan[Math.floor(num / 10)] + " " + satuan[num % 10] + " ";
            } else {
                str += satuan[num] + " ";
            }
            return str.trim();
        }

        let result = "";
        let i = 0;
        while (angka > 0) {
            const chunk = angka % 1000;
            if (chunk > 0) {
                let chunkStr = konversi(chunk);
                if (i === 1 && chunk === 1) chunkStr = "Seribu";
                result = chunkStr + " " + ribuan[i] + " " + result;
            }
            angka = Math.floor(angka / 1000);
            i++;
        }
        return result.trim().replace(/\s+/g, ' ') + " Rupiah";
    }

    function hitungTotal() {
        let subtotal = 0;
        document.querySelectorAll('#barang-table tr').forEach(tr => {
            const harga = parseFloat(tr.querySelector('.harga')?.value || 0);
            const jumlah = parseFloat(tr.querySelector('.jumlah')?.value || 0);
            const total = harga * jumlah;
            tr.querySelector('.total').value = total.toFixed(0);
            subtotal += total;
        });

        document.getElementById('subtotal').value = subtotal.toFixed(0);
        const ppn = subtotal * 0.1;
        document.getElementById('ppn').value = ppn.toFixed(0);
        const grand = subtotal + ppn;
        document.getElementById('grandtotal').value = grand.toFixed(0);
        document.getElementById('dibulatkan').value = Math.round(grand);
        document.getElementById('terbilang').value = terbilangRupiah(Math.round(grand));
    }

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('harga') || e.target.classList.contains('jumlah')) {
            hitungTotal();
        }
    });

    hitungTotal();
});
</script>
@endsection
