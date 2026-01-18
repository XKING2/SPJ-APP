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
            <form action="{{ route('kwitansisls.store') }}" method="POST" novalidate>
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

                        <!-- Wrapper kotak (sesuaikan max-width seperti yang kamu mau) -->
                        <div class="select-wrapper" style="max-width:480px;">
                        <div class="mb-3">
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
                            <div id="kegiatan-dropdown" class="card shadow-sm mt-1" style="display:none; position:absolute; z-index:1050; width:100%; max-height:320px; overflow:auto;">
                            <div class="card-body p-2">
                                <!-- search box (small) -->
                                <div class="mb-2">
                                <input type="search" id="kegiatan-search" class="form-control form-control-sm" placeholder="Cari sub kegiatan..." />
                                </div>

                                <!-- list container -->
                                <div id="kegiatan-list" style="max-height:220px; overflow:auto;">
                                <!-- items injected here -->
                                </div>

                                <!-- footer load hint -->
                                <div id="kegiatan-footer" class="text-muted small mt-2" style="display:none;">Scroll untuk melihat lebih banyak</div>
                            </div>
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
                                    <input type="text" name="no_rek_manual" 
                                        class="form-control" placeholder="Tambahan user">
                                </div>
                            </div>
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

                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kegiatan</label>

                    <div class="input-group">
                        <select name="kwitansi_keg_id" id="kegiatan_kwitansi_id"
                            class="form-control">
                            <option value="">-- Pilih Kegiatan --</option>
                            @foreach($kegiatanKwitansis as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kegiatan }}</option>
                            @endforeach
                        </select>

                        <button type="button"
                            class="btn btn-outline-primary"
                            data-toggle="modal"
                            data-target="#modalTambahKegiatanKwitansi">
                            +
                        </button>
                    </div>
                </div>

                <button type="button"
                  class="btn btn-outline-primary"
                  data-toggle="modal"
                  data-target="#modalUploadBuktiSPJ">
                  +
                </button>

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


<div class="modal fade" id="modalTambahKegiatanKwitansi">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Tambah Kegiatan</h5>
      </div>
      <div class="modal-body">
        <textarea id="nama_kegiatan_baru"
            class="form-control"
            rows="3"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button"
            id="simpanKegiatanKwitansi"
            class="btn btn-success">
            Simpan
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalUploadBuktiSPJ" data-spj-id="{{ $spj->id }}">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5>Upload Bukti SPJ</h5>
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
              <button class="btn btn-danger remove-bukti d-none">🗑</button>
            </div>
          </div>

        </div>

        <button class="btn btn-outline-primary" id="addBukti">+ Tambah</button>
      </div>

      <div class="modal-footer">
        <button class="btn btn-success" id="uploadBuktiBtn">Upload</button>
      </div>

    </div>
  </div>
</div>




{{-- SweetAlert Validasi --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@section('scripts')
<script>
$(function () {

    $('#simpanKegiatanKwitansi').on('click', async function () {

        const $btn = $(this);
        const $namaInput = $('#nama_kegiatan_baru');
        const namaKegiatan = $namaInput.val().trim();

        if (!namaKegiatan) {
            Swal.fire('Oops', 'Nama kegiatan tidak boleh kosong', 'warning');
            return;
        }

        $btn.prop('disabled', true);

        try {
            const res = await fetch('/kegiatan-kwitansi/store-ajax', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ nama_kegiatan: namaKegiatan })
            });

            if (!res.ok) throw new Error('Gagal request');
            const data = await res.json();

            if (!data.success || !data.id) {
                throw new Error('Response tidak valid');
            }

            const $select = $('#kegiatan_kwitansi_id');

            // 🔥 Tambahkan option & langsung pilih
            const option = new Option(
                data.nama_kegiatan,
                data.id,
                true,   // selected
                true    // defaultSelected
            );

            $select.append(option);

            // 🔥 WAJIB trigger change (Select2 / watcher)
            $select.trigger('change');

            // Tutup modal
            $('#modalTambahKegiatanKwitansi').modal('hide');

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Kegiatan berhasil ditambahkan',
                timer: 1500,
                showConfirmButton: false
            });

            $namaInput.val('');

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Gagal menyimpan kegiatan', 'error');
        } finally {
            $btn.prop('disabled', false);
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const wrapper = document.getElementById('bukti-wrapper');
    const addBtn = document.getElementById('addBukti');

    addBtn.addEventListener('click', (e) => {
        e.preventDefault();

        // Ambil template bukti pertama
        const firstItem = wrapper.querySelector('.bukti-item');
        const clone = firstItem.cloneNode(true);

        // Reset value input
        clone.querySelectorAll('input').forEach(input => {
            input.value = '';
        });

        // Tampilkan tombol hapus
        const removeBtn = clone.querySelector('.remove-bukti');
        removeBtn.classList.remove('d-none');

        wrapper.appendChild(clone);
    });

    // Event delegation untuk tombol hapus
    wrapper.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-bukti')) {
            e.preventDefault();
            e.target.closest('.bukti-item').remove();
        }
    });
});
</script>
@endsection

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

<script src="{{ asset('js/kwitansi.js') }}"></script>

@endsection
    