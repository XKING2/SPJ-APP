@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data Kwitansi</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('kwitansis.store') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="spj_id" value="{{ $spj->id }}">

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Rekening</label>
                            <input type="text" name="no_rekening" class="form-control" value="{{ old('no_rekening') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">No Rekening Tujuan</label>
                            <input type="text" name="no_rekening_tujuan" class="form-control" value="{{ old('no_rekening_tujuan') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Bank</label>
                            <input type="text" name="nama_bank" class="form-control" value="{{ old('nama_bank') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Yang Menerima Kwitansi</label>
                            <input type="text" name="penerima_kwitansi" class="form-control"  value="{{ old('penerima_kwitansi') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Sub Kegiatan</label>
                            <select name="sub_kegiatan" id="sub_kegiatan" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Sub Kegiatan --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Telah Diterima Dari</label>
                            <input type="text" name="telah_diterima_dari" class="form-control" value="{{ old('telah_diterima_dari') }}"required>
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

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Penerima Kwitansi</label>
                            <input type="text" name="jabatan_penerima" class="form-control" value="{{ old('jabatan_penerima') }}"required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">NPWP</label>
                            <input type="text" name="npwp" class="form-control" value="{{ old('npwp') }}"required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih PPTK</label>
                            <select name="id_pptk" class="form-control" required>
                                <option value="" disabled selected>-- Pilih PPTK --</option>
                                @foreach($pptks as $pptk)
                                    <option value="{{ $pptk->id }}">{{ $pptk->nama_pptk }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Untuk Pembayaran -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Untuk Pembayaran</label>
                    <textarea name="pembayaran" class="form-control" rows="3" required>{{ old('pembayaran') }}</textarea>
                </div>

                <!-- Tombol Simpan -->
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success px-4 py-2">
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
document.addEventListener("DOMContentLoaded", function () {
    const pptkSelect = document.querySelector("select[name='id_pptk']");
    const subKegiatanSelect = document.getElementById("sub_kegiatan");

    pptkSelect.addEventListener("change", function () {
        const pptkId = this.value;
        subKegiatanSelect.innerHTML = `<option value="" disabled selected>Loading...</option>`;

        fetch(`/get-subkegiatan/${pptkId}`)
            .then(response => response.json())
            .then(data => {
                subKegiatanSelect.innerHTML = `<option value="" disabled selected>-- Pilih Sub Kegiatan --</option>`;
                data.forEach(item => {
                    subKegiatanSelect.innerHTML += `<option value="${item.subkegiatan}">${item.subkegiatan}</option>`;
                });
            })
            .catch(error => {
                console.error("Error fetching sub kegiatan:", error);
                subKegiatanSelect.innerHTML = `<option value="" disabled selected>Gagal memuat data</option>`;
            });
    });
});
</script>

<!-- Script konversi nominal ke terbilang -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const nominalInput = document.getElementById("jumlah_nominal");
    const terbilangInput = document.getElementById("uang_terbilang");

    nominalInput.addEventListener("input", function () {
        const angka = parseInt(this.value) || 0;
        terbilangInput.value = terbilangRupiah(angka);
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
                str += puluhan[Math.floor(num / 10)] + " ";
                str += satuan[num % 10] + " ";
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

        return result.trim() + " Rupiah";
    }
});
</script>
@endsection
