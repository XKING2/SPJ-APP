@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Edit Data Pemeriksaan</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('pemeriksaan.update', $pemeriksaan->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')

                <input type="hidden" name="spj_id" value="{{ $spj->id }}">

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                        <label class="form-label fw-bold">No Surat</label>
                        <div class="input-group">
                            <!-- Prefix dari tabel (readonly) -->
                            <input type="text" id="prefix_surat" 
                                class="form-control text-end" 
                                value="{{ $nosurat->no_awal ?? '' }}" readonly>

                            <!-- Bagian tengah diisi user -->
                            <span class="input-group-text">/</span>
                            <input type="text" id="no_surat_user" name="no_surat_user" 
                                class="form-control text-center" 
                                placeholder="Nomor Surat" 
                                value="{{ $nosurat->no_suratssss ?? '' }}" 
                                required>

                            <!-- Suffix 1: nama dinas -->
                            <span class="input-group-text">/</span>
                            <input type="text" id="suffix_dinas" 
                                class="form-control" 
                                value="{{ $nosurat->nama_dinas ?? '' }}" readonly>

                            <!-- Suffix 2: tahun -->
                            <span class="input-group-text">/</span>
                            <input type="text" id="suffix_tahun" 
                                class="form-control" 
                                value="{{ $nosurat->tahun ?? '' }}" readonly>
                        </div>

                        <!-- Hidden input gabungan final -->
                        <input type="hidden" name="no_suratssss" id="no_suratssss">
                    </div>

                        {{-- Pihak Kedua --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pihak Kedua</label>
                            <input type="text" name="nama_pihak_kedua" class="form-control" value="{{ old('nama_pihak_kedua',$pemeriksaan->nama_pihak_kedua) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Pihak Kedua</label>
                            <input type="text" name="jabatan_pihak_kedua" class="form-control" value="{{ old('jabatan_pihak_kedua',$pemeriksaan->jabatan_pihak_kedua) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pekerjaan yang Dilakukan</label>
                            <textarea name="pekerjaan" class="form-control" rows="3" required>{{ old('pekerjaan', $pemeriksaan->pekerjaan) }}</textarea>
                        </div>

                        {{-- Alamat Pihak Kedua --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Pihak Kedua</label>
                            <input name="alamat_pihak_kedua" class="form-control" required>{{ old('alamat_pihak_kedua',$pemeriksaan->alamat_pihak_kedua) }}</input>
                        </div>

                        
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
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
                </div>

                <!-- 🟧 Tombol Aksi -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('pemeriksaan') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Update Pemeriksaan
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
    const prefixInput = document.getElementById('prefix_surat');
    const userInput = document.getElementById('no_surat_user');
    const dinasInput = document.getElementById('suffix_dinas');
    const tahunInput = document.getElementById('suffix_tahun');
    const hiddenInput = document.getElementById('no_suratssss');

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

    userInput.addEventListener('input', updateNoSurat);
    updateNoSurat();
</script>

{{-- Style agar rapi --}}
<style>
    .form-label {
        font-weight: 600;
    }
    .form-control, textarea, select {
        border-radius: 8px;
    }
    @media (max-width: 768px) {
        .col-md-6 {
            margin-bottom: 1.5rem;
        }
    }
</style>
@endsection
