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
            <form action="{{ route('pesananls.store') }}" method="POST" novalidate>
                <input type="hidden" name="spj_id" value="{{ $spj->id }}">
                @csrf

                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Surat</label>
                            <div class="input-group">
                                <!-- Pilihan nomor awal -->
                                <select id="prefix_surat" class="form-control text-end" required>
                                    <option value="" disabled selected>-- Pilih Nomor Awal --</option>
                                    @foreach ($nosurat as $item)
                                        <option value="{{ $item->no_awal }}"
                                            data-dinas="{{ $item->nama_dinas }}"
                                            data-tahun="{{ $item->tahun }}">
                                            {{ $item->no_awal }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Bagian tengah diisi user -->
                                <span class="input-group-text">/</span>
                                <input type="text" id="no_surat_user" name="no_surat_user" 
                                    class="form-control text-center" placeholder="Nomor Surat" required>

                                <!-- Nama dinas otomatis dari pilihan -->
                                <span class="input-group-text">/</span>
                                <input type="text" id="suffix_dinas" class="form-control" readonly>

                                <!-- Tahun otomatis dari pilihan -->
                                <span class="input-group-text">/</span>
                                <input type="text" id="suffix_tahun" class="form-control" readonly>
                            </div>

                            <!-- Hidden input gabungan final -->
                            <input type="hidden" name="no_surat" id="no_surat">
                        </div>


                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Perusahaan Rekanan</label>
                            <input type="text" name="nama_pt" class="form-control" value="{{ old('nama_pt') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Perusahaan Rekanan</label>
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
                            <label class="form-label fw-bold">Nomor Telepon Perusahaan Rekanan</label>
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
                                <th style="width: 10%">Aksi</th> <!-- kolom baru -->
                            </tr>
                        </thead>
                        <tbody id="items-table">
                            <tr>
                                <td><input type="text" name="items[0][nama_barang]" class="form-control" required></td>
                                <td><input type="number" name="items[0][jumlah]" class="form-control" value="1" required></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger delete-row">X</button>
                                </td>
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
document.addEventListener("DOMContentLoaded", function() {

    let rowIndex = 1;

    // Tambah baris barang
    document.getElementById("add-row").addEventListener("click", function() {
        const tableBody = document.getElementById("items-table");

        const newRow = `
            <tr>
                <td><input type="text" name="items[${rowIndex}][nama_barang]" class="form-control" required></td>
                <td><input type="number" name="items[${rowIndex}][jumlah]" class="form-control" value="1" required></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger delete-row">X</button>
                </td>
            </tr>
        `;

        tableBody.insertAdjacentHTML('beforeend', newRow);
        rowIndex++;
    });

    // Hapus baris barang (pakai event delegation)
    document.getElementById("items-table").addEventListener("click", function(e) {
        if (e.target.classList.contains("delete-row")) {
            const row = e.target.closest("tr");
            row.remove();
        }
    });

});
</script>

<script>
    const prefixInput = document.getElementById('prefix_surat');
    const userInput = document.getElementById('no_surat_user');
    const dinasInput = document.getElementById('suffix_dinas');
    const tahunInput = document.getElementById('suffix_tahun');
    const hiddenInput = document.getElementById('no_surat');

    function updateNoSurat() {
        const prefix = prefixInput.value.trim();
        const user = userInput.value.trim();
        const dinas = dinasInput.value.trim();
        const tahun = tahunInput.value.trim();

        const fullNo = [prefix, user, dinas, tahun]
            .filter(part => part !== '')
            .join('/');

        hiddenInput.value = fullNo;
    }

    // Saat user mengetik nomor tengah
    userInput.addEventListener('input', updateNoSurat);

    // Saat user memilih prefix (ambil juga dinas & tahun)
    prefixInput.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        dinasInput.value = selected.getAttribute('data-dinas') || '';
        tahunInput.value = selected.getAttribute('data-tahun') || '';
        updateNoSurat();
    });

    // Jalankan saat halaman dimuat
    updateNoSurat();
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
