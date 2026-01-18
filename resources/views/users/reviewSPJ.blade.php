@extends('layouts.main')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Review SPJ</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data SPJ</h6>
        </div>

        <div class="card-body">
            <!-- 🔍 Search -->
            <form action="{{ route('reviewSPJ') }}" method="GET" class="form-inline mb-3 d-flex justify-content-end">
                <input type="text" name="search" class="form-control form-control-sm mr-2"
                    placeholder="Cari status atau nomor surat..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-secondary">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>

            <!-- 📋 Tabel -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nomor SPJ</th>
                            <th>Tanggal Dibuat</th>
                            <th>Type SPJ</th>
                            <th>Status Bendahara</th>
                            <th>Status Kasubag</th>
                            <th style="width: 240px;">Aksi</th>
                            <th style="width: 240px;">Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spjs as $spj)
                        @php
                            // Pastikan relation feedbacks sudah eager loaded di controller:
                            // $spjs = Spj::with('feedbacks','pesanan','user')->paginate(...);

                            // Build feedback array yang benar (gunakan nama kolom DB)
                            $feedbackArray = $spj->feedbacks->map(function($f) {
                                return [
                                    'section'    => $f->section,
                                    'field'      => $f->field,
                                    'message'    => $f->message,
                                    'role'       => $f->role,
                                    // format created_at ke string agar JS dapat tampil langsung
                                    'created_at' => optional($f->created_at)->format('d-m-Y H:i'),
                                ];
                            })->toArray();

                            $status1 = $spj->status;
                            $status2 = $spj->status2;
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration + ($spjs->currentPage() - 1) * $spjs->perPage() }}</td>
                            <td>{{ $spj->pesanan->no_surat ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($spj->pesanan->surat_dibuat ?? now())->translatedFormat('d F Y') }}</td>
                            <td>{{ $spj->types ?? '-' }}</td>

                            <!-- Status Bendahara -->
                            <td>
                                @if($status1 === 'valid')
                                    <span class="badge badge-success">Disetujui</span>
                                @elseif($status1 === 'belum_valid')
                                    <span class="badge badge-danger">Tidak Disetujui</span>

                                    @if(count($feedbackArray) > 0)
                                        <button type="button"
                                            class="btn btn-link p-0 ml-1 alasan-btn position-relative"
                                            data-feedback='@json($feedbackArray)'
                                            data-role="bendahara"
                                            title="Lihat alasan penolakan (Bendahara)">
                                            <i class="fas fa-bell text-warning"></i>
                                            <span class="notif-badge position-absolute badge badge-danger"
                                                  style="top:-8px; right:-8px; font-size:10px; padding:3px 6px;">
                                                {{ count($feedbackArray) }}
                                            </span>
                                        </button>
                                    @endif
                                @elseif($status1 === 'diajukan')
                                    <span class="badge badge-info">Diajukan</span>
                                @else
                                    <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </td>

                            <!-- Status Kasubag -->
                            <td>
                                @if($status2 === 'valid')
                                    <span class="badge badge-success">Disetujui</span>
                                @elseif($status2 === 'belum_valid')
                                    <span class="badge badge-danger">Tidak Disetujui</span>

                                    @if(count($feedbackArray) > 0)
                                        <button type="button"
                                            class="btn btn-link p-0 ml-1 alasan-btn position-relative"
                                            data-feedback='@json($feedbackArray)'
                                            data-role="kasubag"
                                            title="Lihat alasan penolakan (Kasubag)">
                                            <i class="fas fa-bell text-warning"></i>
                                            <span class="notif-badge position-absolute badge badge-danger"
                                                  style="top:-8px; right:-8px; font-size:10px; padding:3px 6px;">
                                                {{ count($feedbackArray) }}
                                            </span>
                                        </button>
                                    @endif
                                @elseif($status2 === 'diajukan')
                                    <span class="badge badge-info">Diajukan</span>
                                @else
                                    <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td>
                                <div class="d-flex flex-column align-items-center justify-content-center gap-5">

                                    <!-- 🔹 Baris atas -->
                                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-5">
                                        <a href="{{ route('spj.preview', $spj->id) }}" class="btn btn-sm btn-info action-btn">
                                            <i class="fas fa-eye"></i> Preview
                                        </a>

                                        @if(
                                            ($status1 === 'draft' && $status2 === 'draft') ||
                                            ($status1 === 'belum_valid' || $status2 === 'belum_valid')
                                        )
                                            <form action="{{ route('spj.destroy', $spj->id) }}" method="POST" class="form-delete d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger action-btn">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif

                                        @if($status1 === 'valid' && $status2 === 'valid')
                                            <a href="{{ route('spj.cetak', $spj->id) }}" target="_blank" class="btn btn-sm btn-secondary action-btn">
                                                <i class="fas fa-print"></i> Cetak
                                            </a>
                                        @endif
                                    </div>

                                    <!-- 🔹 Baris bawah -->
                                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-5">
                                        @if($status1 === 'draft' || $status1 === 'belum_valid')
                                            <form action="{{ route('spj.submitToBendahara', $spj->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success action-btn">
                                                    <i class="fas fa-paper-plane"></i> Ajukan Ke Bendahara
                                                </button>
                                            </form>
                                        @endif

                                        @if($status1 === 'valid' && ($status2 === null || $status2 === 'belum_valid'))
                                            <form action="{{ route('ajukanKasubag', $spj->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary action-btn">
                                                    <i class="fas fa-paper-plane"></i> Ajukan Ke Kasubag
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button 
                                    class="btn btn-sm btn-outline-primary btn-preview-bukti"
                                    data-spj-id="{{ $spj->id }}">
                                    <i class="fas fa-eye"></i> Bukti
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Tidak ada data SPJ</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $spjs->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- 🔔 Modal Alasan -->
<div class="modal fade" id="modalAlasan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content animate__animated animate__fadeIn">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-circle mr-2"></i> Alasan Penolakan SPJ
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table class="table table-sm table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Section</th>
                            <th>Bagian</th>
                            <th>Catatan</th>
                            <th>Pengoreksi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="feedbackTableBody"></tbody>
                </table>
                <div id="feedback-debug" class="small text-muted mt-2" style="display:none;">
                    <strong>Debug payload (lihat console)</strong>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalPreviewBuktiSPJ" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Bukti SPJ</h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- FORM UPLOAD --}}
                <form id="formUploadBukti" class="border rounded p-3 mb-3">
                    <input type="hidden" id="upload_spj_id">

                    <div id="upload-wrapper">
                        <div class="row g-2 mb-2 upload-row">
                            <div class="col-md-4">
                                <input type="file" name="bukti_spj[]" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="jenis_bukti[]" class="form-control form-control-sm" placeholder="Jenis bukti" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="keterangan[]" class="form-control form-control-sm" placeholder="Keterangan">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="button" id="btnTambahInput" class="btn btn-sm btn-secondary">
                            + Tambah File
                        </button>
                        <button class="btn btn-sm btn-primary">
                            Upload
                        </button>
                    </div>
                </form>

                {{-- PREVIEW --}}
                <div id="preview-bukti-content" class="row g-3">
                    <div class="text-center text-muted w-100">Memuat data...</div>
                </div>

            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // delegated handler supaya aman kalau tombol dibuat dinamis
    $(document).on('click', '.alasan-btn', function () {
        const feedbacks = $(this).data('feedback');
        console.log("=== RAW FEEDBACK RECEIVED ===");
        console.log(feedbacks);

        const tbody = $('#feedbackTableBody');
        tbody.empty();

        if (!Array.isArray(feedbacks) || feedbacks.length === 0) {
            tbody.append(`<tr><td colspan="5" class="text-center text-muted">Tidak ada alasan penolakan.</td></tr>`);
            $('#modalAlasan').modal('show');
            return;
        }

        feedbacks.forEach(f => {
            // safe read
            const section = f.section ?? '-';
            const field = f.field ?? '-';
            const message = f.message ?? '-';
            const role = f.role ?? '-';
            const waktu = f.created_at ?? '-';

            tbody.append(`
                <tr>
                    <td>${section}</td>
                    <td>${field}</td>
                    <td>${message}</td>
                    <td>${role}</td>
                    <td>${waktu}</td>
                </tr>
            `);
        });

        $('#modalAlasan').modal('show');
    });
</script>

<script>
function notifySuccess(title = 'Berhasil', text = '') {
    Swal.fire({
        icon: 'success',
        title: title,
        text: text,
        timer: 1800,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

function notifyError(title = 'Gagal', text = '') {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
        timer: 2200,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}
</script>

@section('scripts')
<script>
let activeSpjId = null;

/* OPEN MODAL */
$(document).on('click', '.btn-preview-bukti', async function () {

    activeSpjId = $(this).data('spj-id');

    const $modal = $('#modalPreviewBuktiSPJ');
    const $content = $('#preview-bukti-content');

    $content.html('<div class="text-center text-muted py-5">Memuat data...</div>');
    $modal.modal('show');

    loadBukti();
});

/* LOAD BUKTI */
async function loadBukti() {
    const $content = $('#preview-bukti-content');

    const res = await fetch(`/spj/${activeSpjId}/bukti`);
    const data = await res.json();

    if (!data.length) {
        $content.html('<div class="text-center text-muted py-5">Belum ada bukti</div>');
        return;
    }

    let html = '';

    data.forEach(b => {
        const isImage = ['jpg','jpeg','png','webp'].includes(b.file_url.split('.').pop());

        html += `
        <div class="col-md-6">
            <div class="border rounded p-2 h-100">
                ${isImage
                    ? `<a href="${b.file_url}" class="glightbox">
                        <img src="${b.file_url}" class="img-fluid rounded mb-2" style="cursor:zoom-in">
                       </a>`
                    : `<iframe src="${b.file_url}" style="width:100%; height:220px;"></iframe>`
                }

                <textarea class="form-control form-control-sm mb-2 keterangan">${b.keterangan ?? ''}</textarea>

                <div class="d-flex justify-content-between">
                    <button class="btn btn-sm btn-success btn-update" data-id="${b.id}">
                        Simpan
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="${b.id}">
                        Hapus
                    </button>
                </div>
            </div>
        </div>`;
    });

    $content.html(html);
    GLightbox({ selector: '.glightbox' });
}

/* UPDATE */
$(document).on('click', '.btn-update', async function () {
    const id = Number($(this).data('id'));
    const ket = $(this).closest('.border').find('.keterangan').val();

    const res = await fetch(`/spj/bukti/${id}/update`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ keterangan: ket })
    });

    if (!res.ok) {
        notifyError('Gagal', 'Keterangan tidak tersimpan');
        return;
    }

    notifySuccess('Tersimpan', 'Keterangan diperbarui');
});

/* DELETE */
$(document).on('click', '.btn-delete', async function () {

    const id = Number($(this).data('id'));

    if (!Number.isInteger(id)) {
        notifyError('Error', 'ID bukti tidak valid');
        return;
    }

    const confirm = await Swal.fire({
        title: 'Hapus bukti?',
        text: 'Data tidak dapat dikembalikan',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    });

    if (!confirm.isConfirmed) return;

    const res = await fetch(`/spj/bukti/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    });

    if (!res.ok) {
        notifyError('Gagal', 'Bukti tidak terhapus');
        return;
    }

    notifySuccess('Terhapus', 'Bukti berhasil dihapus');
    loadBukti();
});

/* TAMBAH INPUT */
$('#btnTambahInput').on('click', function () {
    $('#upload-wrapper').append(`
        <div class="row g-2 mb-2 upload-row">
            <div class="col-md-4">
                <input type="file" name="bukti_spj[]" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="jenis_bukti[]" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="keterangan[]" class="form-control form-control-sm">
            </div>
        </div>
    `);
});

/* UPLOAD */
$('#formUploadBukti').on('submit', async function (e) {
    e.preventDefault();

    if (!activeSpjId) {
        notifyError('Error', 'SPJ tidak valid');
        return;
    }

    const formData = new FormData(this);

    const res = await fetch(`/spj/upload-bukti/${activeSpjId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    });

    if (!res.ok) {
        notifyError('Gagal', 'Upload bukti gagal');
        return;
    }

    this.reset();
    $('#upload-wrapper').html('');
    notifySuccess('Berhasil', 'Bukti berhasil diupload');
    loadBukti();
});
</script>
@endsection


@endsection
