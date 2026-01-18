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
            <form action="{{ route('serahbarang.update', $serahbarangs->id) }}" method="POST" novalidate>

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
                                    class="form-control text-center" placeholder="Nomor Surat" required>

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
                            <input type="hidden" name="no_suratsss" id="no_suratsss">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Pihak Pengelola</label>
                            <select name="id_pihak_kedua" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Pihak Pengelola --</option>
                                @foreach($keduas as $kedua)
                                    <option value="{{ $kedua->id }}">{{ $kedua->nama_pihak_kedua }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Pihak Pertama</label>
                            <select name="id_plt" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Pihak Pertama --</option>
                                @foreach($plts as $plt)
                                    <option value="{{ $plt->id }}">{{ $plt->nama_pihak_pertama }}</option>
                                @endforeach
                            </select>
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

<script>
    // Gabungkan seluruh bagian jadi satu string dengan tanda "/"
    const prefixInput = document.getElementById('prefix_surat');
    const userInput = document.getElementById('no_surat_user');
    const dinasInput = document.getElementById('suffix_dinas');
    const tahunInput = document.getElementById('suffix_tahun');
    const hiddenInput = document.getElementById('no_suratsss');

    function updateNoSurat() {
        const prefix = prefixInput.value.trim();
        const user = userInput.value.trim();
        const dinas = dinasInput.value.trim();
        const tahun = tahunInput.value.trim();

        // Gabungkan dengan tanda /
        const fullNo = [prefix, user, dinas, tahun]
            .filter(part => part !== '') // hilangkan kosong
            .join('/');

        hiddenInput.value = fullNo;
    }

    // Update setiap kali user mengetik
    userInput.addEventListener('input', updateNoSurat);

    // Jalankan saat halaman dimuat pertama kali
    updateNoSurat();
</script>

@endsection
