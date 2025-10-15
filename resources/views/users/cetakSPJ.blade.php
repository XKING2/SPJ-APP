@extends('layouts.main')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Cetak Data SPJ</h1>
@endsection

@section('content')
<div class="container-fluid">

    <!-- 🗂️ Card utama -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data SPJ</h6>
        </div>

        <div class="card-body">
            <!-- 🔍 Search bar -->
            <form action="{{ route('cetakSPJ') }}" method="GET" class="form-inline mb-3 d-flex justify-content-end">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control form-control-sm mr-2"
                    placeholder="Cari status atau nomor surat..."
                    value="{{ request('search') }}"
                >
                <button type="submit" class="btn btn-sm btn-secondary">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>

            <!-- 📋 Tabel data SPJ -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nomor SPJ</th>
                            <th>Tanggal Surat Dibuat</th>
                            <th>Status Validasi Bendahara</th>
                            <th>Status Validasi Kasubag</th>
                            <th style="width: 260px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($spjs as $spj)
                        <tr>
                            <td>{{ $loop->iteration + ($spjs->currentPage() - 1) * $spjs->perPage() }}</td>
                            <td>{{ $spj->pesanan->no_surat ?? '-' }}</td>
                            <td>
                                @if (!empty($spj->pesanan?->surat_dibuat))
                                    {{ \Carbon\Carbon::parse($spj->pesanan->surat_dibuat)->translatedFormat('d F Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @switch($spj->status)
                                    @case('valid')
                                        <span class="badge badge-success">Disetujui</span>
                                        @break
                                    @case('belum_valid')
                                        <span class="badge badge-danger">Tidak Disetujui</span>
                                        <!-- 🔔 Tombol lonceng -->
                                        <button 
                                            type="button" 
                                            class="btn btn-link p-0 ml-1 alasan-btn position-relative"
                                            data-nomor="{{ $spj->pesanan->no_surat ?? '-' }}"
                                            data-tanggal="{{ $spj->pesanan?->surat_dibuat ? \Carbon\Carbon::parse($spj->pesanan->surat_dibuat)->translatedFormat('d F Y') : '-' }}"
                                            data-alasan="{{ $spj->komentar_bendahara ?? 'Tidak ada komentar dari Bendahara.' }}"
                                            title="Lihat alasan penolakan"
                                        >
                                            <i class="fas fa-bell text-warning"></i>
                                            <span class="notif-badge position-absolute badge badge-danger d-none">1</span>
                                        </button>
                                        @break
                                    @default
                                        <span class="badge badge-warning text-dark">Menunggu Validasi</span>
                                @endswitch
                            </td>
                            <td>
                                @switch($spj->status2)
                                    @case('valid')
                                        <span class="badge badge-success">Disetujui</span>
                                        @break
                                    @case('belum_valid')
                                        <span class="badge badge-danger">Tidak Disetujui</span>
                                        <!-- 🔔 Tombol lonceng -->
                                        <button 
                                            type="button" 
                                            class="btn btn-link p-0 ml-1 alasan-btn position-relative"
                                            data-nomor="{{ $spj->pesanan->no_surat ?? '-' }}"
                                            data-tanggal="{{ $spj->pesanan?->surat_dibuat ? \Carbon\Carbon::parse($spj->pesanan->surat_dibuat)->translatedFormat('d F Y') : '-' }}"
                                            data-alasan="{{ $spj->komentar_kasubag ?? 'Tidak ada komentar dari Kasubag.' }}"
                                            title="Lihat alasan penolakan"
                                        >
                                            <i class="fas fa-bell text-warning"></i>
                                            <span class="notif-badge position-absolute badge badge-danger d-none">1</span>
                                        </button>
                                        @break
                                    @default
                                        <span class="badge badge-warning text-dark">Menunggu Validasi</span>
                                @endswitch
                            </td>
                            <td>  <!-- 🖨️ Cetak hanya jika kedua status valid -->
                                @if($spj->status === 'valid' && $spj->status2 === 'valid')
                                    <a href="{{ route('spj.cetak', $spj->id) }}" target="_blank" class="btn btn-sm btn-primary mb-1">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Tidak ada data SPJ untuk akun ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 📑 Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $spjs->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- 🔔 Modal Dinamis -->
    <div class="modal fade" id="modalAlasan" tabindex="-1" role="dialog" aria-labelledby="modalAlasanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content animate__animated animate__fadeIn">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalAlasanLabel">
                        <i class="fas fa-exclamation-circle mr-2"></i> Alasan Penolakan SPJ
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Nomor Surat:</strong> <span id="modalNomor"></span></p>
                    <p><strong>Tanggal Surat:</strong> <span id="modalTanggal"></span></p>
                    <hr>
                    <p><strong>Alasan Penolakan:</strong></p>
                    <p id="modalAlasanText" class="text-muted"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div data-swal-success="{{ session('success') }}"></div>
@endif

@if($errors->any())
    <div data-swal-errors="{{ implode('|', $errors->all()) }}"></div>
@endif
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .notif-badge {
        font-size: 0.65rem;
        padding: 4px 6px;
    }
</style>
@endpush

@push('scripts')
<!-- ✅ Pastikan jQuery & SweetAlert2 dimuat -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // 🔔 Modal alasan
    $('.alasan-btn').on('click', function() {
        const nomor = $(this).data('nomor');
        const tanggal = $(this).data('tanggal');
        const alasan = $(this).data('alasan');
        $('#modalNomor').text(nomor);
        $('#modalTanggal').text(tanggal);
        $('#modalAlasanText').text(alasan);
        $('#modalAlasan').modal('show');
    });

    // 📨 Konfirmasi pengajuan ke bendahara
    $('.submit-bendahara-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        if (typeof Swal === 'undefined') {
            alert('SweetAlert tidak ditemukan. Pastikan koneksi ke CDN aktif.');
            form.submit();
            return;
        }
        Swal.fire({
            title: 'Ajukan ke Bendahara?',
            text: 'SPJ ini akan dikirim untuk proses validasi bendahara.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, ajukan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    // ✅ Notifikasi sukses
    const successMsg = $('[data-swal-success]').data('swal-success');
    if (successMsg && typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'success', title: 'Berhasil', text: successMsg });
    }

    // ❗ Notifikasi error
    const errorMsgs = $('[data-swal-errors]').data('swal-errors');
    if (errorMsgs && typeof Swal !== 'undefined') {
        const list = errorMsgs.split('|').join('\n');
        Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: list });
    }
});
</script>
@endpush
