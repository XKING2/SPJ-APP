@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Edit Data Kwitansi LS</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('kwitansils.update',$kwitansi->id) }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="no_rekening" id="no_rekening">
                <input type="hidden" name="spj_id" value="{{ $kwitansi->spj_id }}">
                @method('PUT')

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih PPTK</label>
                            <select name="id_pptk" id="id_pptk" class="form-control" required>
                                <option value="" disabled>-- Pilih PPTK --</option>
                                @foreach($pptks as $pptk)
                                    <option value="{{ $pptk->id }}"
                                        {{ old('id_pptk', $kwitansi->id_pptk) == $pptk->id ? 'selected' : '' }}>
                                        {{ $pptk->nama_pptk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <select name="id_kegiatan" id="id_kegiatan" class="form-control" required>
                            <option value="" disabled>-- Pilih Sub Kegiatan --</option>
                            @foreach($kegiatans as $kegiatan)
                                <option value="{{ $kegiatan->id }}"
                                    {{ old('id_kegiatan', $kwitansi->id_kegiatan) == $kegiatan->id ? 'selected' : '' }}>
                                    {{ $kegiatan->nama_kegiatan }}
                                </option>
                            @endforeach
                        </select>

                        <div class="mb-3 mt-3">
                            <label class="form-label fw-bold">No Rekening</label>

                            <div class="row g-2">
                                <!-- Otomatis -->
                                <div class="col-6">
                                    <input type="text" name="no_rek_sub" id="no_rek_sub" 
                                        class="form-control" readonly placeholder="Otomatis terisi">
                                </div>

                                <!-- Manual -->
                                <div class="col-6">
                                    <input type="text" name="no_rek_manual" 
                                        class="form-control" placeholder="Tambahan user">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Telah Diterima Dari</label>
                            <select name="telah_diterima_dari" class="form-control" required>
                                <option value="">-- Pilih --</option>

                                <option value="Bendahara Umum Daerah Kabupaten Gianyar"
                                    {{ old('telah_diterima_dari', $kwitansi->telah_diterima_dari) == 'Bendahara Umum Daerah Kabupaten Gianyar' ? 'selected' : '' }}>
                                    Bendahara Umum Daerah Kabupaten Gianyar
                                </option>

                                <option value="Bendahara Pengeluaran DPMD Gianyar"
                                    {{ old('telah_diterima_dari', $kwitansi->telah_diterima_dari) == 'Bendahara Pengeluaran DPMD Gianyar' ? 'selected' : '' }}>
                                    Bendahara Pengeluaran DPMD Gianyar
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Rekanan</label>
                            <input type="text" 
                                name="penerima_kwitansi" 
                                class="form-control" 
                                value="{{ old('penerima_kwitansi',$kwitansi->penerima_kwitansi) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Bank Rekanan</label>
                            <input type="text" 
                                name="nama_bank" 
                                class="form-control" 
                                value="{{ old('nama_bank',$kwitansi->nama_bank) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Rekening Bank Rekanan</label>
                            <input type="text" 
                                name="no_rekening_tujuan" 
                                class="form-control" 
                                value="{{ old('no_rekening_tujuan',$kwitansi->no_rekening_tujuan) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Rekanan</label>
                            <input type="text" 
                                name="jabatan_penerima" 
                                class="form-control" 
                                value="{{ old('jabatan_penerima',$kwitansi->jabatan_penerima) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">NPWP Rekanan</label>
                            <input type="text" 
                                name="npwp" 
                                class="form-control" 
                                value="{{ old('npwp',$kwitansi->npwp) }}" required>
                        </div>
                    </div>
                </div>

                <!-- Pembayaran -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Untuk Pembayaran</label>
                    <textarea name="pembayaran" class="form-control" rows="3" required>{{ old('pembayaran',$kwitansi->pembayaran) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-success px-4 py-2">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ========================= --}}
{{-- VALIDASI SWEETALERT --}}
{{-- ========================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (form.dataset.submitting === "true") return;
            if (document.querySelector('.swal2-container')) return;

            const requiredFields = form.querySelectorAll('[required]');
            const emptyFields = [];

            requiredFields.forEach(input => {
                const label = input.closest('.mb-3')?.querySelector('label')?.innerText || input.name;
                if (!input.value.trim()) emptyFields.push(label.replace('*', '').trim());
            });

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
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pastikan data yang Anda isi sudah benar sebelum disimpan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.submitting = "true";
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });
    });
});
</script>


{{-- ========================= --}}
{{-- LOAD SUBKEGIATAN + NOREK --}}
{{-- ========================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    const pptkSelect = document.getElementById("id_pptk");
    const kegiatanSelect = document.getElementById("id_kegiatan");
    const noRekSubInput = document.getElementById("no_rek_sub");
    const manualInput = document.querySelector("input[name='no_rek_manual']");
    const finalInput = document.getElementById("no_rekening");

    const selectedPptk = "{{ $kwitansi->id_pptk }}";
    const selectedKegiatan = "{{ $kwitansi->id_kegiatan }}";

    /* -----------------------------
       Fungsi: Update nomor rekening final
    ------------------------------*/
    function updateFinal() {
        let s = noRekSubInput.value.trim();
        let m = manualInput.value.trim();
        finalInput.value = m ? `${s}.${m}` : s;
    }

    /* -----------------------------
       Load saat halaman edit dibuka
    ------------------------------*/
    function loadSubKegiatanOnEdit() {
        if (!selectedPptk) return;

        kegiatanSelect.innerHTML = `<option disabled selected>Loading...</option>`;

        fetch(`/get-subkegiatan/${selectedPptk}`)
            .then(res => res.json())
            .then(data => {

                kegiatanSelect.innerHTML = `<option disabled>-- Pilih Sub Kegiatan --</option>`;

                data.forEach(item => {
                    kegiatanSelect.innerHTML += `
                        <option value="${item.id}" ${item.id == selectedKegiatan ? "selected" : ""}>
                            ${item.subkegiatan}
                        </option>`;
                });

                if (selectedKegiatan) {
                    fetch(`/get-norek-sub/${selectedKegiatan}`)
                        .then(r => r.json())
                        .then(d => {
                            noRekSubInput.value = d.no_rek_sub ?? "";
                            updateFinal();   // 🔥 WAJIB: supaya hidden input terisi
                        });
                }
            });
    }

    loadSubKegiatanOnEdit();


    /* -----------------------------
       Ganti PPTK = Load subkegiatan baru
    ------------------------------*/
    pptkSelect.addEventListener("change", function () {
        const pptkId = this.value;
        kegiatanSelect.innerHTML = `<option disabled selected>Loading...</option>`;
        noRekSubInput.value = "";
        updateFinal();

        fetch(`/get-subkegiatan/${pptkId}`)
            .then(r => r.json())
            .then(data => {
                kegiatanSelect.innerHTML = `<option disabled selected>-- Pilih Sub Kegiatan --</option>`;
                data.forEach(item => {
                    kegiatanSelect.innerHTML += `<option value="${item.id}">${item.subkegiatan}</option>`;
                });
            });
    });

    /* -----------------------------
       Ganti Subkegiatan = Load norek
    ------------------------------*/
    kegiatanSelect.addEventListener("change", function () {
        const kegiatanId = this.value;
        noRekSubInput.value = "Loading...";

        fetch(`/get-norek-sub/${kegiatanId}`)
            .then(r => r.json())
            .then(data => {
                noRekSubInput.value = data.no_rek_sub ?? "";
                updateFinal(); // 🔥 Penting juga
            });
    });

    /* -----------------------------
       Perubahan manual user
    ------------------------------*/
    manualInput.addEventListener("input", updateFinal);

});
</script>

@endsection
