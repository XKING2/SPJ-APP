@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data Penerimaan</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('penerimaan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pemeriksaan_id" value="{{ $pemeriksaan->id }}">
                <input type="hidden" name="spj_id" value="{{ $spj->id }}">
                <input type="hidden" id="ppn_rate" value="{{ $ppnRate }}">

                <div class="row">
                    <!-- Kolom kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control"
                                value="{{ old('pekerjaan', $pemeriksaan->pekerjaan ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor SP</label>
                            <input type="text" name="no_surat" class="form-control"
                                value="{{ old('no_surat', $pemeriksaan->pesanan->no_surat ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal SP</label>
                            <input type="date" name="surat_dibuat" class="form-control"
                                value="{{ old('surat_dibuat', $pemeriksaan->pesanan->surat_dibuat ?? '') }}">
                        </div>
                    </div>

                    <!-- Kolom kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pihak Kedua</label>
                            <input type="text" name="nama_pihak_kedua" class="form-control"
                                value="{{ old('nama_pihak_kedua', $pemeriksaan->nama_pihak_kedua ?? '') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Pihak Kedua</label>
                            <input type="text" name="jabatan_pihak_kedua" class="form-control"
                                value="{{ old('jabatan_pihak_kedua', $pemeriksaan->jabatan_pihak_kedua ?? '') }}">
                        </div>
                    </div>
                </div>

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
                            </tr>
                        </thead>
                        <tbody id="barang-table">
                            @foreach($pemeriksaan->pesanan->items as $i => $item)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td><input type="text" name="barang[{{ $i }}][nama_barang]" class="form-control"
                                        value="{{ $item->nama_barang }}" readonly></td>
                                <td><input type="number" name="barang[{{ $i }}][jumlah]" class="form-control jumlah"
                                        value="{{ $item->jumlah }}" readonly></td>
                                <td><input type="text" name="barang[{{ $i }}][satuan]" class="form-control" value="Pcs">
                                </td>
                                <td><input type="number" name="barang[{{ $i }}][harga_satuan]" class="form-control harga"
                                        value="0"></td>
                                <td><input type="number" name="barang[{{ $i }}][total]" class="form-control total"
                                        value="0" readonly></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="table table-bordered w-50 ms-auto">
                        <tr>
                            <th class="text-end">Subtotal</th>
                            <td><input type="number" id="subtotal" name="subtotal" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th class="text-end">PPN ({{ $ppnRate }}%)</th>
                            <td><input type="number" id="ppn" name="ppn" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th class="text-end">Total Harga</th>
                            <td><input type="number" id="grandtotal" name="grandtotal" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th class="text-end">Dibulatkan</th>
                            <td><input type="number" id="dibulatkan" name="dibulatkan" class="form-control" readonly></td>
                        </tr>
                    </table>

                    <div class="mt-2">
                        <label class="form-label fw-bold">Terbilang :</label>
                        <input type="text" id="terbilang" name="terbilang" class="form-control" readonly>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-5">
                    <button type="submit" id="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const hargaInputs = document.querySelectorAll('.harga');
    const totalInputs = document.querySelectorAll('.total');
    const subtotalInput = document.getElementById('subtotal');
    const ppnInput = document.getElementById('ppn');
    const grandtotalInput = document.getElementById('grandtotal');
    const dibulatkanInput = document.getElementById('dibulatkan');
    const terbilangInput = document.getElementById('terbilang');
    const ppnRate = parseFloat(document.getElementById('ppn_rate').value) || 10;

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
            if (num >= 10 && num <= 19) str += belasan[num - 10] + " ";
            else if (num >= 20) {
                str += puluhan[Math.floor(num / 10)] + " ";
                str += satuan[num % 10] + " ";
            } else str += satuan[num] + " ";
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
        return result.trim() + " Rupiah";
    }

    function hitungTotal() {
        let subtotal = 0;
        hargaInputs.forEach((input, i) => {
            let harga = parseFloat(input.value) || 0;
            let jumlah = parseFloat(totalInputs[i].closest('tr').querySelector('.jumlah').value) || 0;
            let total = harga * jumlah;
            totalInputs[i].value = total;
            subtotal += total;
        });

        subtotalInput.value = subtotal;
        const ppn = subtotal * (ppnRate / 100);
        ppnInput.value = ppn.toFixed(2);
        const grandtotal = subtotal + ppn;
        grandtotalInput.value = grandtotal.toFixed(2);
        const dibulatkan = Math.round(grandtotal);
        dibulatkanInput.value = dibulatkan;
        terbilangInput.value = terbilangRupiah(dibulatkan);
    }

    hargaInputs.forEach(input => input.addEventListener('input', hitungTotal));
    hitungTotal();
});
</script>
@endsection
