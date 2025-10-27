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
            <form action="{{ route('pesanan.store') }}" method="POST" novalidate>
                <input type="hidden" name="spj_id" value="{{ $spj->id }}">
                <input type="hidden" name="kwitansi_id" value="{{ $kwitansi->id }}">
                @csrf

                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Surat</label>
                            <input type="text" name="no_surat" class="form-control" value="{{ old('no_surat') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama PT</label>
                            <input type="text" name="nama_pt" class="form-control" value="{{ old('nama_pt') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat PT</label>
                            <input type="text" name="alamat_pt" class="form-control" value="{{ old('alamat_pt') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Surat Dibuat</label>
                            <input type="date" id="surat_dibuat" name="surat_dibuat" class="form-control" value="{{ old('surat_dibuat') }}" required>
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Barang Diterima</label>
                            <input type="date" id="tanggal_diterima" name="tanggal_diterima" class="form-control" value="{{ old('tanggal_diterima') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Telepon PT</label>
                            <input type="text" name="nomor_tlp_pt" class="form-control" value="{{ old('nomor_tlp_pt') }}" required>
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
                                <td><input type="text" name="items[0][nama_barang]" class="form-control" required></td>
                                <td><input type="number" name="items[0][jumlah]" class="form-control" value="1" required></td>
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

 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function (e) {
            // Cegah submit default dulu
            e.preventDefault();

            if (form.dataset.submitting === "true") return;
            if (document.querySelector('.swal2-container')) return;

            window._loaderDisabled = true;
            hideLoader();

            // 🔍 Cari input yang wajib diisi (required)
            const requiredFields = form.querySelectorAll('[required]');
            const emptyFields = [];

            requiredFields.forEach(input => {
                const label = input.closest('.mb-3')?.querySelector('label')?.innerText || input.name;
                if (!input.value.trim()) {
                    emptyFields.push(label.replace('*', '').trim());
                }
            });

            const phoneInput = form.querySelector('input[name="nomor_tlp_pt"]');
                if (phoneInput && !/^(\+62|62|0)[0-9]{9,14}$/.test(phoneInput.value.trim())) {
                    Swal.fire({
                        title: 'Nomor Telepon Tidak Valid!',
                        text: 'Nomor telepon harus 10–15 digit dan diawali dengan 0.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                    phoneInput.classList.add('is-invalid');
                    return;
                }

            // ⚠️ Jika ada yang kosong, tampilkan SweetAlert error
            if (emptyFields.length > 0) {
                Swal.fire({
                    title: 'Data Belum Lengkap!',
                    html: `
                        <p>Harap isi semua kolom berikut sebelum menyimpan:</p>
                        <ul style="text-align:left; margin-left: 20px;">
                            ${emptyFields.map(f => `<li>${f}</li>`).join('')}
                        </ul>
                    `,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                    allowOutsideClick: false
                });
                return;
            }

            // ✅ Jika semua terisi, tampilkan konfirmasi submit
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pastikan data yang Anda isi sudah benar sebelum disimpan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.submitting = "true";
                    window._loaderDisabled = false;
                    showLoader();
                    HTMLFormElement.prototype.submit.call(form);
                } else {
                    hideLoader();
                    window._loaderDisabled = false;
                }
            });
        });
    });
});
</script>

<script>
// Tambah baris barang
document.addEventListener("DOMContentLoaded", function() {
    let rowIndex = 1;

    document.getElementById("add-row").addEventListener("click", function() {
        const tableBody = document.getElementById("items-table");
        const newRow = `
            <tr>
                <td><input type="text" name="items[${rowIndex}][nama_barang]" class="form-control" required></td>
                <td><input type="number" name="items[${rowIndex}][jumlah]" class="form-control" value="1" required></td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);
        rowIndex++;
    });
});
</script>

<script>
// Debug format tanggal (optional)
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
                console.log(`Tanggal ${id}: ${formatted}`);
            }
        });
    });
});
</script>
@endsection
