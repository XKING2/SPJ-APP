@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data Pemeriksaan</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('pemeriksaan.store') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="pesanan_id" value="{{ $pesanan->id }}">
                <input type="hidden" name="spj_id" value="{{ $spj->id }}">

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">

                        {{-- Pihak Pertama --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Pihak Pertama</label>
                            <select name="id_plt" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Pihak Pertama --</option>
                                @foreach($plts as $plt)
                                    <option value="{{ $plt->id }}">{{ $plt->nama_pihak_pertama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pekerjaan --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pekerjaan yang Dilakukan</label>
                            <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan') }}" required>
                        </div>

                        {{-- Hari, Tanggal, Bulan, Tahun Diterima --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hari Diterima</label>
                            <input type="text" name="hari_diterima" class="form-control" value="{{ $hari }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Diterima</label>
                            <input type="text" name="tanggals_diterima" class="form-control" value="{{ $tglTeks }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bulan Diterima</label>
                            <input type="text" name="bulan_diterima" class="form-control" value="{{ $bulan }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun Diterima</label>
                            <input type="text" name="tahun_diterima" class="form-control" value="{{ $tahunTeks }}" readonly>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">

                        {{-- Pihak Kedua --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pihak Kedua</label>
                            <input type="text" name="nama_pihak_kedua" class="form-control" value="{{ old('nama_pihak_kedua') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Pihak Kedua</label>
                            <input type="text" name="jabatan_pihak_kedua" class="form-control" value="{{ old('jabatan_pihak_kedua') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Pihak Kedua</label>
                            <textarea name="alamat_pihak_kedua" class="form-control" rows="3" required>{{ old('alamat_pihak_kedua') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4 py-2">
                        <i class="bi bi-save"></i> Simpan Pemeriksaan
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

@endsection
