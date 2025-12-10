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

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Sub Kegiatan</label>
                            <select name="id_kegiatan" id="id_kegiatan" class="form-control" required>
                                <option value="" disabled>-- Pilih Sub Kegiatan --</option>
                                @foreach($kegiatans as $kegiatan)
                                    <option value="{{ $kegiatan->id }}"
                                        {{ old('id_kegiatan', $kwitansi->id_kegiatan) == $kegiatan->id ? 'selected' : '' }}>
                                        {{ $kegiatan->nama_kegiatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        


                        <div class="mb-3">
                            <label class="form-label fw-bold">No Rekening</label>

                            <div class="row g-2">
                                <!-- Kiri -->
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="text" name="no_rek_sub" id="no_rek_sub" 
                                            class="form-control" readonly placeholder="Otomatis terisi">
                                    </div>
                                </div>

                                <!-- Kanan -->
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

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Pihak PPK</label>
                            <select name="id_plt" class="form-control" required>
                                <option value="" disabled>-- Pilih Pihak Pertama --</option>

                                @foreach($plts as $plt)
                                    <option value="{{ $plt->id }}"
                                        {{ old('id_plt', $kwitansi->id_plt) == $plt->id ? 'selected' : '' }}>
                                        {{ $plt->nama_pihak_pertama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Rekanan</label>
                            <input type="text" name="penerima_kwitansi" class="form-control" value="{{ old('penerima_kwitansi',$kwitansi->penerima_kwitansi) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Bank Rekanan</label>
                            <input type="text" name="nama_bank" class="form-control" value="{{ old('nama_bank',$kwitansi->nama_bank) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Rekening Bank Rekanan</label>
                            <input type="text" name="no_rekening_tujuan" class="form-control" value="{{ old('no_rekening_tujuan',$kwitansi->no_rekening_tujuan) }}" required>
                        </div>

                        

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Rekanan</label>
                            <input type="text" name="jabatan_penerima" class="form-control" value="{{ old('jabatan_penerima',$kwitansi->jabatan_penerima) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">NPWP Rekanan</label>
                            <input type="text" name="npwp" class="form-control" value="{{ old('npwp',$kwitansi->npwp) }}" required>
                        </div>
                    </div>
                </div>

                <!-- Untuk Pembayaran -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Untuk Pembayaran</label>
                    <textarea name="pembayaran" class="form-control" rows="3" required>{{ old('pembayaran',$kwitansi->pembayaran) }}</textarea>
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

{{-- SweetAlert Validasi --}}
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
                if (!input.value.trim()) {
                    emptyFields.push(label.replace('*', '').trim());
                }
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
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                    allowOutsideClick: false
                });
                return;
            }

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
                    HTMLFormElement.prototype.submit.call(form);
                }
            });
        });
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const pptkSelect = document.getElementById("id_pptk");
    const kegiatanSelect = document.getElementById("id_kegiatan");
    const noRekSubInput = document.getElementById("no_rek_sub");

    const selectedPptk = "{{ $kwitansi->id_pptk }}";
    const selectedKegiatan = "{{ $kwitansi->id_kegiatan }}";

    /* -------------------------------------------------------
       1️⃣ LOAD SUB KEGIATAN SAAT HALAMAN EDIT DIBUKA
       ------------------------------------------------------- */
    function loadSubKegiatanOnEdit() {
        if (!selectedPptk) return;

        kegiatanSelect.innerHTML = `<option value="" disabled selected>Loading...</option>`;

        fetch(`/get-subkegiatan/${selectedPptk}`)
            .then(res => res.json())
            .then(data => {
                kegiatanSelect.innerHTML = `<option value="" disabled>-- Pilih Sub Kegiatan --</option>`;

                data.forEach(item => {
                    kegiatanSelect.innerHTML += `
                        <option value="${item.id}" ${item.id == selectedKegiatan ? "selected" : ""}>
                            ${item.subkegiatan}
                        </option>`;
                });

                // Setelah sub kegiatan terpilih → load nomor rekening
                if (selectedKegiatan) {
                    fetch(`/get-norek-sub/${selectedKegiatan}`)
                        .then(r => r.json())
                        .then(d => {
                            noRekSubInput.value = d.no_rek_sub ?? "";
                        });
                }
            });
    }

    loadSubKegiatanOnEdit(); // ⬅ AUTO LOAD SAAT EDIT page terbuka


    /* -------------------------------------------------------
       2️⃣ FETCH SUB KEGIATAN SAAT PPTK DIGANTI USER
       ------------------------------------------------------- */
    pptkSelect.addEventListener("change", function () {
        const pptkId = this.value;
        kegiatanSelect.innerHTML = `<option value="" disabled selected>Loading...</option>`;
        noRekSubInput.value = "";

        fetch(`/get-subkegiatan/${pptkId}`)
            .then(response => response.json())
            .then(data => {
                kegiatanSelect.innerHTML = `<option value="" disabled selected>-- Pilih Sub Kegiatan --</option>`;
                data.forEach(item => {
                    kegiatanSelect.innerHTML += `<option value="${item.id}">${item.subkegiatan}</option>`;
                });
            });
    });


    /* -------------------------------------------------------
       3️⃣ FETCH NOMOR REKENING SAAT SUB KEGIATAN DIGANTI
       ------------------------------------------------------- */
    kegiatanSelect.addEventListener("change", function () {
        const kegiatanId = this.value;
        noRekSubInput.value = "Loading...";

        fetch(`/get-norek-sub/${kegiatanId}`)
            .then(response => response.json())
            .then(data => {
                noRekSubInput.value = data.no_rek_sub ?? "";
            });
    });

});

</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const sub = document.getElementById("no_rek_sub");
    const manual = document.querySelector("input[name='no_rek_manual']");
    const finalInput = document.getElementById("no_rekening");

    function updateFinal() {
        let s = sub.value.trim();
        let m = manual.value.trim();
        finalInput.value = m ? `${s}.${m}` : s;
    }

    manual.addEventListener("input", updateFinal);
    sub.addEventListener("input", updateFinal);
});
</script>

@endsection
