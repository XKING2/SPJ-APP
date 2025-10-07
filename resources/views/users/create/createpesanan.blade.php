@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data Pesanan</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('pesanan.store') }}" method="POST">
                <input type="hidden" name="spj_id" value="{{ $spj->id }}">
                <input type="hidden" name="kwitansi_id" value="{{ $kwitansi->id }}">
                @csrf
                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Surat</label>
                            <input type="text" name="no_surat" class="form-control" value="{{ old('no_surat') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama PT</label>
                            <input type="text" name="nama_pt" class="form-control" value="{{ old('nama_pt') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat PT</label>
                            <input type="text" name="alamat_pt" class="form-control" value="{{ old('alamat_pt') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Surat Dibuat</label>
                            <input type="date" id="surat_dibuat" name="surat_dibuat" class="form-control" value="{{ old('surat_dibuat') }}">
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Barang Diterima</label>
                                <input type="date" id="tanggal_diterima" name="tanggal_diterima" class="form-control" value="{{ old('tanggal_diterima') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Telpon PT</label>
                            <input type="number" name="nomor_tlp_pt" class="form-control" value="{{ old('nomor_tlp_pt') }}">
                        </div>
                    </div>
                </div>

                <!-- Tabel Barang -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Tabel Barang Pesanan</label>
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th style="width: 40%">Nama Barang</th>
                                <th style="width: 25%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody id="items-table">
                            <tr>
                                <td><input type="text" name="items[0][nama_barang]" class="form-control"></td>
                                <td><input type="number" name="items[0][jumlah]" class="form-control" value="1"></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" id="add-row" class="btn btn-sm btn-primary">+ Tambah Baris</button>
                </div>

                <!-- Tombol Submit -->
                <div class="d-flex justify-content-end gap-3">
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
        let tableBody = document.getElementById("items-table");
        let newRow = `
            <tr>
                <td><input type="text" name="items[${rowIndex}][nama_barang]" class="form-control"></td>
                <td><input type="number" name="items[${rowIndex}][jumlah]" class="form-control" value="1"></td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);
        rowIndex++;
    });

    // Hapus baris
    document.addEventListener("click", function(e) {
        if(e.target.classList.contains("remove-row")) {
            e.target.closest("tr").remove();
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs = ['surat_dibuat', 'tanggal_diterima'];

    inputs.forEach(id => {
        const input = document.getElementById(id);
        input.addEventListener('change', function () {
            const date = new Date(this.value);
            if (!isNaN(date)) {
                const formatted = String(date.getDate()).padStart(2, '0') + '-' +
                                  String(date.getMonth() + 1).padStart(2, '0') + '-' +
                                  date.getFullYear();
                console.log(`Tanggal ${id}: ${formatted}`); // debug
            }
        });
    });
});
</script>
