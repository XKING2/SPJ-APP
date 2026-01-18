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
            <form id="form-penerimaan" action="{{ route('penerimaan.store') }}" method="POST" novalidate>

                @csrf
                <input type="hidden" name="id_serahbarang" value="{{ $serahbarang->id }}">
                <input type="hidden" name="spj_id" value="{{ $spj->id }}">
                <input type="hidden" name="pesanan_id" value="{{ $pemeriksaan->pesanan->id }}">
                <input type="hidden" id="ppn_rate" value="{{ $ppn_rate }}">
                

                <div class="row">
                    <!-- Kolom kiri -->
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
                            <label class="form-label fw-bold">Tanggal SP</label>
                            <input type="date" name="surat_dibuat" class="form-control"
                                value="{{ old('surat_dibuat', $pemeriksaan->pesanan->surat_dibuat ?? '') }}"required>
                        </div>
                    </div>

                    <!-- Kolom kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pihak Kedua</label>
                            <input type="text" name="nama_pihak_kedua" class="form-control"
                                value="{{ old('nama_pihak_kedua', $pemeriksaan->nama_pihak_kedua ?? '') }}"required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Pihak Kedua</label>
                            <input type="text" name="jabatan_pihak_kedua" class="form-control"
                                value="{{ old('jabatan_pihak_kedua', $pemeriksaan->jabatan_pihak_kedua ?? '') }}" required>
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
                                <td><input type="text" name="barang[{{ $i }}][satuan]" class="form-control" required value="Pcs">
                                </td>
                                <td><input type="number" name="barang[{{ $i }}][harga_satuan]" class="form-control harga"
                                        value="0" required></td>
                                <td><input type="number" name="barang[{{ $i }}][total]" class="form-control total"
                                        value="0" readonly></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="table table-bordered w-50 ms-auto">
                        <tr>
                            <th class="text-end">Subtotal</th>
                            <td>
                                <input type="number" id="subtotal" name="subtotal" class="form-control d-none" readonly>
                                <input type="text" id="subtotal_view" class="form-control" readonly>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">PPN</th>
                            <td>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="ppn_active" checked>
                                    <label class="form-check-label" for="ppn_active">
                                        Aktifkan PPN
                                    </label>
                                </div>

                                <div class="input-group mb-2">
                                    <input type="number" id="ppn_rate" name="ppn_rate"
                                        class="form-control" value="{{ $ppn_rate }}" readonly>
                                    <span class="input-group-text">%</span>
                                </div>

                                <input type="number" id="ppn" name="ppn" class="form-control d-none" readonly>
                                <input type="text" id="ppn_view" class="form-control mt-1" readonly>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">PPH</th>
                            <td>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih PPh</label>
                                    <select id="pph_select" class="form-control">
                                        <option value="0" data-rate="0">Tidak Ada PPh</option>
                                        @foreach($pph_list as $pph)
                                            <option value="{{ $pph->key }}" data-rate="{{ $pph->value }}">
                                                {{ strtoupper(str_replace('_', ' ', $pph->key)) }} - {{ $pph->value }}%
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="number" id="pph" name="pph" class="form-control d-none" readonly>
                                <input type="text" id="pph_view" class="form-control" readonly>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">Total Harga</th>
                            <td>
                                <input type="number" id="grandtotal" name="grandtotal" class="form-control d-none" readonly>
                                <input type="text" id="grandtotal_view" class="form-control" readonly>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-end">Dibulatkan</th>
                            <td>
                                <input type="number" id="dibulatkan" name="dibulatkan" class="form-control d-none" readonly>
                                <input type="text" id="dibulatkan_view" class="form-control" readonly>
                            </td>
                        </tr>
                    </table>

                    <div class="mt-2">
                        <label class="form-label fw-bold">Terbilang :</label>
                        <input type="text" id="terbilang" name="terbilang" class="form-control" readonly>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('form-penerimaan');


    if (!form) {
        console.error('Form penerimaan tidak ditemukan');
        return;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (form.dataset.submitting === "true") return;
        if (document.querySelector('.swal2-container')) return;

        // Reset invalid style
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        let emptyFields = [];

        // 1️⃣ Validasi umum untuk input required (di luar tabel)
        const requiredInputs = form.querySelectorAll('[required]');
        requiredInputs.forEach(input => {
            const label = input.closest('.mb-3')?.querySelector('label')?.innerText || input.name;
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                emptyFields.push(label.replace('*', '').trim());
            }
        });

        // 2️⃣ Validasi khusus untuk tabel barang
        const rows = document.querySelectorAll('#barang-table tr');
        rows.forEach((row, index) => {
            const nama = row.querySelector('input[name^="barang"][name$="[nama_barang]"]');
            const satuan = row.querySelector('input[name^="barang"][name$="[satuan]"]');
            const harga = row.querySelector('input[name^="barang"][name$="[harga_satuan]"]');
            const total = row.querySelector('input[name^="barang"][name$="[total]"]');

            if (!nama?.value.trim()) {
                nama?.classList.add('is-invalid');
                emptyFields.push(`Nama Barang (baris ${index + 1})`);
            }

            if (!satuan?.value.trim()) {
                satuan?.classList.add('is-invalid');
                emptyFields.push(`Satuan (baris ${index + 1})`);
            }

            if (!harga?.value || parseFloat(harga.value) <= 0) {
                harga?.classList.add('is-invalid');
                emptyFields.push(`Harga Satuan (baris ${index + 1})`);
            }
        });

        // 3️⃣ Kalau ada field kosong, tampilkan SweetAlert
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

        // 4️⃣ Kalau semua lengkap → konfirmasi simpan
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
                // 🔥 Tampilkan loader Lottie sebelum submit
                if (typeof showLoader === 'function') {
                    showLoader();
                }

                // Tunggu sebentar agar animasi sempat muncul (opsional)
                setTimeout(() => {
                    form.dataset.submitting = "true";
                    HTMLFormElement.prototype.submit.call(form);
                }, 400);
            }
        });

    });
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('lottie-container');
    console.log('🧩 Lottie container ditemukan:', container);

    const animation = lottie.loadAnimation({
        container: container,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: "{{ asset('lottie/blue_loading.json') }}"
    });

    animation.addEventListener('data_failed', () => {
        console.error('🚨 Gagal memuat animasi Lottie. Cek path JSON-nya!');
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const subtotalInput = document.getElementById("subtotal");
    const subtotalView = document.getElementById("subtotal_view");

    const ppnRateInput = document.getElementById("ppn_rate");
    const ppnActiveCheckbox = document.getElementById("ppn_active"); // checkbox baru
    const ppnInput = document.getElementById("ppn");
    const ppnView = document.getElementById("ppn_view");

    const pphSelect = document.getElementById("pph_select");
    const pphInput = document.getElementById("pph");
    const pphView = document.getElementById("pph_view");

    const grandtotalInput = document.getElementById("grandtotal");
    const grandtotalView = document.getElementById("grandtotal_view");

    const dibulatkanInput = document.getElementById("dibulatkan");
    const dibulatkanView = document.getElementById("dibulatkan_view");

    const terbilangInput = document.getElementById("terbilang");

    // ================= FORMAT RUPIAH =================
    function formatRupiah(angka) {
        return angka.toLocaleString('id-ID');
    }

    // ================= HITUNG SUBTOTAL =================
    function hitungSubtotal() {
        let subtotal = 0;
        document.querySelectorAll("#barang-table tr").forEach(row => {
            const qty = parseFloat(row.querySelector(".jumlah")?.value || 0);
            const harga = parseFloat(row.querySelector(".harga")?.value || 0);
            const totalInput = row.querySelector(".total");
            const rowTotal = qty * harga;
            totalInput.value = rowTotal;
            subtotal += rowTotal;
        });

        subtotalInput.value = subtotal;
        subtotalView.value = formatRupiah(subtotal);
        return subtotal;
    }

    // ================= TERBILANG =================
    function terbilangID(n) {
        const s = ["","Satu","Dua","Tiga","Empat","Lima","Enam","Tujuh","Delapan","Sembilan"];
        n = Math.floor(n);
        if (n < 10) return s[n];
        if (n < 20) return s[n - 10] + " Belas";
        if (n < 100) return s[Math.floor(n / 10)] + " Puluh " + s[n % 10];
        if (n < 200) return "Seratus " + terbilangID(n - 100);
        if (n < 1000) return s[Math.floor(n / 100)] + " Ratus " + terbilangID(n % 100);
        if (n < 2000) return "Seribu " + terbilangID(n - 1000);
        if (n < 1000000) return terbilangID(Math.floor(n / 1000)) + " Ribu " + terbilangID(n % 1000);
        if (n < 1000000000) return terbilangID(Math.floor(n / 1000000)) + " Juta " + terbilangID(n % 1000000);
        return "";
    }

    // ================= HITUNG PAJAK & GRANDTOTAL =================
    function hitungPajak() {
        const subtotal = hitungSubtotal();

        // --- PPN ---
        const ppnRate = parseFloat(ppnRateInput.value) || 0;
        const ppnActive = ppnActiveCheckbox?.checked ?? true;
        const ppn = ppnActive ? Math.round(subtotal * (ppnRate / 100)) : 0;
        ppnInput.value = ppn;
        ppnView.value = formatRupiah(ppn);

        // --- PPh ---
        const pphRate = parseFloat(pphSelect.selectedOptions[0]?.dataset.rate || 0);
        const pph = Math.round(subtotal * (pphRate / 100));
        pphInput.value = pph;
        pphView.value = formatRupiah(pph);

        // --- GRAND TOTAL (subtotal + PPN) ---
        const grand = subtotal + ppn;
        grandtotalInput.value = grand;
        grandtotalView.value = formatRupiah(grand);

        // --- DIBULATKAN ---
        const bulat = Math.round(grand / 1000) * 1000;
        dibulatkanInput.value = bulat;
        dibulatkanView.value = formatRupiah(bulat);

        // --- TERBILANG ---
        terbilangInput.value = (terbilangID(bulat).trim() + " Rupiah").replace(/\s+/g, " ");
    }

    // ================= EVENT LISTENER =================
    // Saat input jumlah/harga berubah
    document.querySelectorAll(".harga, .jumlah").forEach(input => {
        input.addEventListener("input", hitungPajak);
    });

    // Saat PPh berubah
    pphSelect.addEventListener("change", hitungPajak);

    // Saat PPN diaktifkan/nonaktifkan
    ppnActiveCheckbox?.addEventListener("change", hitungPajak);

    // Hitung pertama kali saat halaman load
    hitungPajak();
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
@endsection
