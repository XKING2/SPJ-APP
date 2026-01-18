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
            <form action="{{ route('kwitansiGu.store') }}" method="POST" novalidate id="mainForm">
                @csrf
                <input type="hidden" name="spj_id" value="{{ $spj->id }}">
                <input type="hidden" name="no_rekening" id="no_rekening">

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih PPTK</label>
                            <select name="id_pptk" id="id_pptk" class="form-control" required>
                                <option value="" disabled selected>-- Pilih PPTK --</option>
                                @foreach($pptks as $pptk)
                                    <option value="{{ $pptk->id }}">{{ $pptk->nama_pptk }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Wrapper kotak -->
                        <div class="select-wrapper mb-3" style="max-width:480px; position: relative;">
                            <label class="form-label fw-bold">Sub Kegiatan</label>

                            <!-- visible combobox -->
                            <div class="input-group">
                                <input type="text" id="kegiatan-combobox" class="form-control" placeholder="-- Pilih Sub Kegiatan --" autocomplete="off" />
                                <button class="btn btn-outline-secondary" id="kegiatan-toggle" type="button">
                                    <i class="bi bi-caret-down-fill"></i>
                                </button>
                            </div>

                            <!-- hidden input actual to submit form -->
                            <input type="hidden" name="id_kegiatan" id="id_kegiatan_hidden" value="{{ old('id_kegiatan', $kwitansi->id_kegiatan ?? $pesanan->id_kegiatan ?? '') }}">

                            <!-- dropdown list -->
                            <div id="kegiatan-dropdown" class="card shadow-sm mt-1" style="display:none; position:absolute; z-index:1050; width:100%; max-height:320px; overflow:hidden;">
                                <div class="card-body p-2">
                                    <!-- search box (small) -->
                                    <div class="mb-2">
                                        <input type="search" id="kegiatan-search" class="form-control form-control-sm" placeholder="Cari sub kegiatan..." />
                                    </div>

                                    <!-- list container -->
                                    <div id="kegiatan-list" style="max-height:220px; overflow-y:auto;">
                                        <!-- items injected here -->
                                    </div>

                                    <!-- footer load hint -->
                                    <div id="kegiatan-footer" class="text-muted small mt-2" style="display:none;">Scroll untuk melihat lebih banyak</div>
                                </div>
                            </div>
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
                                    <input type="text" name="no_rek_manual" id="no_rek_manual"
                                        class="form-control" placeholder="Tambahan user">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Pihak PPK</label>
                            <select name="id_plt" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Pihak Pertama --</option>
                                @foreach($plts as $plt)
                                    <option value="{{ $plt->id }}">{{ $plt->nama_pihak_pertama }}</option>
                                @endforeach
                            </select>
                        </div> 
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Rekanan</label>
                            <input type="text" name="penerima_kwitansi" class="form-control" value="{{ old('penerima_kwitansi') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Rekanan</label>
                            <input type="text" name="jabatan_penerima" class="form-control" value="{{ old('jabatan_penerima') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Telah Diterima Dari</label>
                            <select name="telah_diterima_dari" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="Bendahara Umum Daerah Kabupaten Gianyar">Bendahara Umum Daerah Kabupaten Gianyar</option>
                                <option value="Bendahara Pengeluaran DPMD Gianyar">Bendahara Pengeluaran DPMD Gianyar</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kegiatan</label>
                    <div class="input-group">
                        <select name="kwitansi_keg_id" id="kwitansi_keg_id" class="form-control">
                            <option value="">-- Pilih Kegiatan --</option>
                            @foreach($kegiatanKwitansis as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kegiatan }}</option>
                            @endforeach
                        </select>

                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalTambahKegiatanKwitansi">
                            +
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pekerjaan yang Dilakukan</label>
                    <textarea name="pekerjaan" class="form-control" rows="3" required>{{ old('pekerjaan', $kwitansi->pembayaran ?? '') }}</textarea>
                </div>

                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalUploadBuktiSPJ">
                    + Upload Bukti SPJ
                </button>

                <!-- Tombol Simpan -->
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="submit" class="btn btn-success px-4 py-2">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Kegiatan -->
<div class="modal fade" id="modalTambahKegiatanKwitansi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kegiatan</h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea id="nama_kegiatan_baru" class="form-control" rows="3" placeholder="Masukkan nama kegiatan"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" id="simpanKegiatanKwitansi" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Bukti SPJ -->
<div class="modal fade" id="modalUploadBuktiSPJ" data-spj-id="{{ $spj->id }}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Bukti SPJ</h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="bukti-wrapper">
                    <div class="row bukti-item border rounded p-3 mb-3">
                        <div class="col-md-3">
                            <input type="text" name="jenis_bukti[]" class="form-control" placeholder="Jenis Bukti">
                        </div>
                        <div class="col-md-4">
                            <input type="file" name="bukti_spj[]" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="keterangan[]" class="form-control" placeholder="Keterangan">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-bukti d-none">🗑</button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary" id="addBukti">+ Tambah</button>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="uploadBuktiBtn">Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.kegiatan-item {
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.kegiatan-item:hover {
    background-color: #f0f0f0;
}

.kegiatan-item.selected {
    background-color: #e3f2fd;
    font-weight: 500;
}
</style>

{{-- SweetAlert Validasi --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('scripts')
<script>
$(function () {

    $('#simpanKegiatanKwitansi').on('click', async function () {

        const namaInput = $('#nama_kegiatan_baru');
        const namaKegiatan = namaInput.val().trim();

        if (!namaKegiatan) {
            Swal.fire('Oops', 'Nama kegiatan tidak boleh kosong', 'warning');
            return;
        }

        try {
            const r = await fetch('/kegiatan-kwitansi/store-ajax', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ nama_kegiatan: namaKegiatan })
            });

            if (!r.ok) throw new Error(await r.text());
            const d = await r.json();

            if (!d.success || !d.id) {
                throw new Error('Response tidak valid');
            }

            $('#kwitansi_keg_id')
                .append(new Option(d.nama_kegiatan, d.id, true, true));

            $('#modalTambahKegiatanKwitansi').modal('hide');

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Kegiatan berhasil ditambahkan',
                timer: 1500,
                showConfirmButton: false
            });

            namaInput.val('');

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Gagal menyimpan kegiatan', 'error');
        }
    });

});
</script>

<script>
$('#uploadBuktiBtn').on('click', async function () {

    const btn = $(this);
    const modal = $('#modalUploadBuktiSPJ');

    
    const spjId = modal.data('spj-id');

    if (!spjId) {
        Swal.fire('Error', 'SPJ tidak valid', 'error');
        return;
    }

    const items = modal.find('.bukti-item');
    if (items.length === 0) {
        Swal.fire('Oops', 'Minimal satu bukti harus ditambahkan', 'warning');
        return;
    }

    const formData = new FormData();
    let valid = true;

    items.each(function () {
        const jenis = $(this).find('input[name="jenis_bukti[]"]').val().trim();
        const file  = $(this).find('input[name="bukti_spj[]"]')[0].files[0];
        const ket   = $(this).find('input[name="keterangan[]"]').val().trim();

        if (!jenis || !file) {
            valid = false;
            return false;
        }

        formData.append('jenis_bukti[]', jenis);
        formData.append('bukti_spj[]', file);
        formData.append('keterangan[]', ket);
    });

    if (!valid) {
        Swal.fire('Data belum lengkap', 'Jenis bukti dan file wajib diisi', 'error');
        return;
    }

    btn.prop('disabled', true).text('Uploading...');

    try {
        const r = await fetch(`/spj/upload-bukti/${spjId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            body: formData
        });

        if (!r.ok) throw new Error(await r.text());
        const d = await r.json();

        if (!d.success) throw new Error(d.message);

        modal.modal('hide');

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Bukti SPJ berhasil diupload',
            timer: 1500,
            showConfirmButton: false
        });

        // reset form
        const first = $('#bukti-wrapper .bukti-item').first().clone();
        $('#bukti-wrapper').html(first);
        first.find('input').val('');
        first.find('.remove-bukti').addClass('d-none');

    } catch (err) {
        console.error(err);
        Swal.fire('Error', 'Gagal upload bukti SPJ', 'error');
    } finally {
        btn.prop('disabled', false).text('Upload');
    }
});

</script>
@endsection


<script src="{{ asset('js/kwitansi.js') }}"></script>

@endsection