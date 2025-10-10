@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data Serah Terima</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="#" method="POST">
                @csrf
                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hari</label>
                            <input type="text" name="hari_diterima" class="form-control" value="{{ old('no_rekening') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal</label>
                            <input type="text" name="tanggal_diterima" class="form-control" value="{{ old('no_rekening') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bulan</label>
                            <input type="text" name="bulan_diterima" class="form-control" value="{{ old('no_rekening') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun</label>
                            <input type="text" name="tahun_diterima" class="form-control" value="{{ old('no_rekening') }}">
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sub Kegiatan</label>
                            <textarea name="sub_kegiatan" class="form-control" rows="5">{{ old('sub_kegiatan') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pekerjaan Yang dilakukan</label>
                            <textarea name="pekerjaan" class="form-control" rows="5">{{ old('sub_kegiatan') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Detail Barang</label>

                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 35%">Jenis Barang</th>
                                <th style="width: 10%">Jumlah</th>
                                <th style="width: 10%">Satuan</th>
                                <th style="width: 20%">Harga Satuan (Rp)</th>
                                <th style="width: 20%">Total Harga (Rp)</th>
                            </tr>
                        </thead>
                        <tbody id="barang-table">
                            <tr>
                                <td>1</td>
                                <td><input type="text" name="nama_barang" class="form-control"></td>
                                <td><input type="number" name="jumlah" class="form-control jumlah" value="1"></td>
                                <td><input type="text" name="satuan" class="form-control" value="Pcs"></td>
                                <td><input type="number" name="harga_satuan" class="form-control harga" value="0"></td>
                                <td><input type="number" name="total" class="form-control total" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered w-50 ms-auto">
                        <tr>
                            <th class="text-end">Jumlah</th>
                            <td><input type="number" id="subtotal" name="subtotal" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <th class="text-end">PPN 10%</th>
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

                    <button type="button" id="add-row" class="btn btn-sm btn-primary mb-3">+ Tambah Baris</button>

                    <!-- Rangkuman Harga -->
                    

                    <!-- Terbilang -->
                    <div class="mt-2">
                        <label class="form-label fw-bold">Terbilang :</label>
                        <input type="text" id="terbilang" name="terbilang" class="form-control" readonly>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-end gap-5">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function() {
    let rowIndex = 1;

    // Tambah baris
    document.getElementById("add-row").addEventListener("click", function() {
        let tableBody = document.getElementById("barang-table");
        let newRow = `
            <tr>
                <td>${rowIndex + 1}</td>
                <td><input type="text" name="barang[${rowIndex}][jenis]" class="form-control"></td>
                <td><input type="number" name="barang[${rowIndex}][jumlah]" class="form-control jumlah" value="1"></td>
                <td><input type="text" name="barang[${rowIndex}][satuan]" class="form-control" value="Pcs"></td>
                <td><input type="number" name="barang[${rowIndex}][harga]" class="form-control harga" value="0"></td>
                <td><input type="number" name="barang[${rowIndex}][total]" class="form-control total" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
            </tr>`;
        tableBody.insertAdjacentHTML("beforeend", newRow);
        rowIndex++;
    });

    // Hitung total tiap kali input berubah
    document.addEventListener("input", function(e) {
        if(e.target.classList.contains("jumlah") || e.target.classList.contains("harga")) {
            let row = e.target.closest("tr");
            let jumlah = row.querySelector(".jumlah").value || 0;
            let harga = row.querySelector(".harga").value || 0;
            let total = row.querySelector(".total");
            total.value = jumlah * harga;
        }
        updateSummary();
    });

    // Hapus baris
    document.addEventListener("click", function(e) {
        if(e.target.classList.contains("remove-row")) {
            e.target.closest("tr").remove();
            updateSummary();
        }
    });

    // Update subtotal, ppn, grandtotal
    function updateSummary() {
        let totals = document.querySelectorAll(".total");
        let subtotal = 0;
        totals.forEach(t => subtotal += Number(t.value) || 0);

        let ppn = subtotal * 0.1;
        let grandtotal = subtotal + ppn;
        let dibulatkan = Math.round(grandtotal);

        document.getElementById("subtotal").value = subtotal;
        document.getElementById("ppn").value = ppn;
        document.getElementById("grandtotal").value = grandtotal;
        document.getElementById("dibulatkan").value = dibulatkan;

        // Konversi ke terbilang (sederhana)
        document.getElementById("terbilang").value = terbilang(dibulatkan) + " Rupiah";
    }

    // Fungsi angka ke terbilang (versi sederhana)
    function terbilang(n) {
        const satuan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", 
                        "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        n = Math.floor(n);
        if (n < 12) return satuan[n];
        if (n < 20) return terbilang(n - 10) + " Belas";
        if (n < 100) return terbilang(Math.floor(n / 10)) + " Puluh " + terbilang(n % 10);
        if (n < 200) return "Seratus " + terbilang(n - 100);
        if (n < 1000) return terbilang(Math.floor(n / 100)) + " Ratus " + terbilang(n % 100);
        if (n < 2000) return "Seribu " + terbilang(n - 1000);
        if (n < 1000000) return terbilang(Math.floor(n / 1000)) + " Ribu " + terbilang(n % 1000);
        if (n < 1000000000) return terbilang(Math.floor(n / 1000000)) + " Juta " + terbilang(n % 1000000);
        return n; // fallback
    }
});
</script>

