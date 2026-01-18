@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data Pesanan GU</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('pesanangu.store') }}" method="POST" novalidate>
                <input type="hidden" name="spj_id" value="{{ $spj->id }}">
                @csrf

                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                       <div class="mb-3">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold m-0">No Surat</label>

                            <!-- Tombol toggle -->
                            <button type="button" id="toggleNoSurat" class="btn btn-sm btn-secondary">
                                + Tambah Nomor Surat
                            </button>
                        </div>

                        <!-- WRAPPER agar bisa ditampilkan/sembunyikan -->
                        <div id="noSuratWrapper" style="display:none;">

                            <div class="input-group mb-2">

                                <select id="prefix_surat" name="prefix_surat" class="form-control text-end">
                                    <option value="" disabled selected>-- Pilih Nomor Awal --</option>
                                    @foreach ($nosurat as $item)
                                        <option value="{{ $item->no_awal }}"
                                            data-dinas="{{ $item->nama_dinas }}"
                                            data-tahun="{{ $item->tahun }}">
                                            {{ $item->no_awal }}
                                        </option>
                                    @endforeach
                                </select>

                                <span class="input-group-text">/</span>

                                <input type="text" id="no_surat_user" 
                                    name="no_surat_user" 
                                    class="form-control text-center" 
                                    placeholder="Nomor Surat">

                                <span class="input-group-text">/</span>
                                <input type="text" id="suffix_dinas" class="form-control" readonly>

                                <span class="input-group-text">/</span>
                                <input type="text" id="suffix_tahun" class="form-control" readonly>

                            </div>

                            <!-- hidden gabungan final -->
                            <input type="hidden" name="no_surat" id="no_surat">

                        </div>

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
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Barang Diterima</label>
                            <input type="date" id="tanggal_diterima" name="tanggal_diterima" class="form-control" value="{{ old('tanggal_diterima') }}" required>
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div class="col-md-6">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bulan Diterima</label>
                            <input type="text" name="bulan_diterima" id="bulan_diterima"
                                class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun Diterima</label>
                            <input type="text" name="tahun_diterima" id="tahun_diterima"
                                class="form-control" readonly>
                        </div>


                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Telepon Perusahaan Rekanan</label>
                            <input type="text" name="nomor_tlp_pt" class="form-control" value="{{ old('nomor_tlp_pt') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Nominal</label>
                            <input type="number" name="jumlah_nominal" id="jumlah_nominal" class="form-control"
                                   value="{{ old('jumlah_nominal') }}" placeholder="Masukkan jumlah nominal" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Uang Terbilang</label>
                            <input type="text" name="uang_terbilang" id="uang_terbilang" class="form-control"
                                   value="{{ old('uang_terbilang') }} " readonly>
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
// ==== TOGGLE INPUT NOMOR SURAT ====
document.addEventListener("DOMContentLoaded", () => {
    const wrapper = document.getElementById("noSuratWrapper");
    const btnToggle = document.getElementById("toggleNoSurat");

    const prefix = document.getElementById("prefix_surat");
    const user = document.getElementById("no_surat_user");
    const dinas = document.getElementById("suffix_dinas");
    const tahun = document.getElementById("suffix_tahun");
    const hidden = document.getElementById("no_surat");

    function updateNoSurat() {
        const a = prefix.value.trim();
        const b = user.value.trim();
        const c = dinas.value.trim();
        const d = tahun.value.trim();

        const full = [a, b, c, d].filter(x => x !== "").join("/");
        hidden.value = full;
    }

    // Event input
    user.addEventListener("input", updateNoSurat);
    prefix.addEventListener("change", function() {
        const opt = this.options[this.selectedIndex];
        dinas.value = opt.getAttribute("data-dinas") || "";
        tahun.value = opt.getAttribute("data-tahun") || "";
        updateNoSurat();
    });

    // Klik tombol toggle
    btnToggle.addEventListener("click", () => {
        if (wrapper.style.display === "none") {
            
            // TAMPILKAN
            wrapper.style.display = "block";
            btnToggle.innerText = "− Hapus Nomor Surat";

        } else {

            // SEMBUNYIKAN + RESET FIELD
            wrapper.style.display = "none";
            btnToggle.innerText = "+ Tambah Nomor Surat";

            prefix.value = "";
            user.value = "";
            dinas.value = "";
            tahun.value = "";
            hidden.value = "";
        }
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

<script>
// ============================================
// Konversi Angka ke Terbilang Bahasa Indonesia
// ============================================
function terbilang(n) {
    const satuan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

    n = parseInt(n);

    if (isNaN(n)) return "";

    if (n < 12) return satuan[n];
    if (n < 20) return terbilang(n - 10) + " Belas";
    if (n < 100) return terbilang(Math.floor(n / 10)) + " Puluh " + terbilang(n % 10);
    if (n < 200) return "Seratus " + terbilang(n - 100);
    if (n < 1000) return terbilang(Math.floor(n / 100)) + " Ratus " + terbilang(n % 100);
    if (n < 2000) return "Seribu " + terbilang(n - 1000);
    if (n < 1000000) return terbilang(Math.floor(n / 1000)) + " Ribu " + terbilang(n % 1000);
    if (n < 1000000000) return terbilang(Math.floor(n / 1000000)) + " Juta " + terbilang(n % 1000000);
    if (n < 1000000000000) return terbilang(Math.floor(n / 1000000000)) + " Miliar " + terbilang(n % 1000000000);
    if (n < 1000000000000000) return terbilang(Math.floor(n / 1000000000000)) + " Triliun " + terbilang(n % 1000000000000);

    return "";
}

// =======================================================
// Event Listener: Saat user mengetik jumlah nominal
// otomatis mengisi input "uang terbilang"
// =======================================================
document.addEventListener("DOMContentLoaded", () => {
    const inputNominal = document.getElementById("jumlah_nominal");
    const inputTerbilang = document.getElementById("uang_terbilang");

    inputNominal.addEventListener("input", function () {
        let angka = this.value.replace(/\D/g, ""); // hanya angka
        if (!angka) {
            inputTerbilang.value = "";
            return;
        }

        let hasil = terbilang(angka).trim();
        hasil = hasil.charAt(0).toUpperCase() + hasil.slice(1) + " Rupiah";

        inputTerbilang.value = hasil;
    });
});
</script>


<script>
// ===============================
// Bulan & Tahun dari Tanggal
// ===============================
document.addEventListener("DOMContentLoaded", () => {
    const tanggalInput = document.getElementById("tanggal_diterima");
    const bulanTerbilang = document.getElementById("bulan_diterima");
    const tahunTerbilang = document.getElementById("tahun_diterima");

    const namaBulan = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    tanggalInput.addEventListener("change", function () {
        if (!this.value) {
            bulanTerbilang.value = "";
            tahunTerbilang.value = "";
            return;
        }

        const date = new Date(this.value);
        if (isNaN(date)) return;

        const bulanIndex = date.getMonth(); // 0–11
        const tahun = date.getFullYear();

        // Bulan tulisan (nama bulan)
        bulanTerbilang.value = namaBulan[bulanIndex];

        // Tahun terbilang (pakai fungsi terbilang yang sudah ada)
        let tahunText = terbilang(tahun).trim();
        tahunText = tahunText.charAt(0).toUpperCase() + tahunText.slice(1);

        tahunTerbilang.value = tahunText;
    });
});
</script>
@endsection
