@extends('layouts.main')

@section('pageheads')
<h1 class="h3 mb-4 text-gray-800">Kelola Data SPJ</h1>
@endsection

@section('content')
<div class="container-fluid">

    <!-- 🗂️ Card utama -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data SPJ</h6>
            <a href="#" target="_blank" class="btn btn-sm btn-info">
                <i class="fas fa-print"></i> Cetak
            </a>
        </div>

        <div class="card-body">
            <!-- 🔍 Search bar -->
            <form action="{{ route('reviewSPJ') }}" method="GET" class="form-inline mb-3 d-flex justify-content-end">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control form-control-sm me-2"
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
                            <th>Status Validasi Kasubag</th>
                            <th style="width: 200px;">Aksi</th>
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
                                @switch($spj->status2)
                                    @case('valid')
                                        <span class="badge bg-success text-white">Disetujui</span>
                                        @break

                                    @case('belum_valid')
                                        <span class="badge bg-danger text-white">Tidak Disetujui</span>

                                        <!-- 🔔 Tombol lonceng -->
                                        <button 
                                            type="button" 
                                            class="btn btn-link p-0 ms-1 alasan-btn position-relative"
                                            style="font-size:0.9rem;"
                                            data-nomor="{{ $spj->pesanan->no_surat ?? '-' }}"
                                            data-tanggal="{{ $spj->pesanan?->surat_dibuat ? \Carbon\Carbon::parse($spj->pesanan->surat_dibuat)->translatedFormat('d F Y') : '-' }}"
                                            data-alasan="{{ $spj->komentar_kasubag ?? 'Tidak ada komentar dari Kasubag.' }}"
                                            title="Lihat alasan penolakan"
                                        >
                                            <i class="fas fa-bell text-warning"></i>
                                            <!-- 🔴 Badge notif -->
                                            <span class="notif-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
                                                1
                                            </span>
                                        </button>
                                        @break

                                    @default
                                        <span class="badge bg-warning text-dark">Menunggu Validasi</span>
                                @endswitch
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('spj.preview', ['id' => $spj->id]) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Preview
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                Tidak ada data SPJ untuk akun ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 📑 Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $spjs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- 🔔 Modal Dinamis -->
    <div class="modal fade" id="modalAlasan" tabindex="-1" aria-labelledby="modalAlasanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content animate__animated animate__faster">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalAlasanLabel">
                        <i class="fas fa-exclamation-circle me-2"></i> Alasan Penolakan SPJ
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nomor Surat:</strong> <span id="modalNomor"></span></p>
                    <p><strong>Tanggal Surat:</strong> <span id="modalTanggal"></span></p>
                    <hr>
                    <p><strong>Alasan Penolakan:</strong></p>
                    <p id="modalAlasanText" class="text-muted"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- 🎨 Animasi dari Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .notif-badge {
        font-size: 0.65rem;
        padding: 4px 6px;
    }
</style>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function () {
    const alasanButtons = document.querySelectorAll('.alasan-btn');
    const modalNomor = document.getElementById('modalNomor');
    const modalTanggal = document.getElementById('modalTanggal');
    const modalAlasanText = document.getElementById('modalAlasanText');
    const modalEl = document.getElementById('modalAlasan');

    alasanButtons.forEach(button => {
        const badge = button.querySelector('.notif-badge');

        button.addEventListener('click', function () {
            // 🔢 Tampilkan badge saat pertama kali diklik
            if (badge.classList.contains('d-none')) {
                badge.classList.remove('d-none');
                setTimeout(() => {
                    badge.classList.add('animate__animated', 'animate__bounceIn');
                }, 50);
            }

            const nomor = this.dataset.nomor;
            const tanggal = this.dataset.tanggal;
            const alasan = this.dataset.alasan;

            modalNomor.textContent = nomor;
            modalTanggal.textContent = tanggal;
            modalAlasanText.textContent = alasan;

            // ✨ Efek animasi modal
            const modal = new bootstrap.Modal(modalEl);
            modalEl.querySelector('.modal-content').classList.add('animate__fadeInDown');
            modal.show();

            // Hapus animasi setelah modal ditutup agar bisa muncul lagi nanti
            modalEl.addEventListener('hidden.bs.modal', () => {
                modalEl.querySelector('.modal-content').classList.remove('animate__fadeInDown');
            });
        });
    });
});
</script>
