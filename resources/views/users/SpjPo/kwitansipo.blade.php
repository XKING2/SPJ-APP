@extends('layouts.main')


@section('pageheads')
<h1 class="h3 mb-4 text-gray-800">Kelola Data Kwitansi PO</h1>

@endsection

@section('content')

<style>
    .spj-badge-alert {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #dc3545; 
        color: #fff;
        width: 18px;
        height: 18px;
        font-size: 12px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        box-shadow: 0 0 4px rgba(0,0,0,0.4);
        animation: badge-pulse 1.2s infinite ease-in-out;
        z-index: 10;
    }

    @keyframes badge-pulse {
        0%   { transform: scale(1); }
        50%  { transform: scale(1.15); }
        100% { transform: scale(1); }
    }
</style>
<div class="container-fluid">

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Kwitansi</h6>
        </div>
        <div class="card-body">

            <!-- Search & Print -->
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('kwitansipo') }}" method="GET" class="form-inline">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="form-control form-control-sm mr-2" placeholder="Cari...">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Untuk Pembayaran</th>
                            <th>No Rekening</th>
                            <th>Penerima Kwitansi</th>
                            <th>Type</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kwitansis as $index => $kwitansi)
                            <tr>
                                <td>{{ $loop->iteration + ($kwitansis->currentPage() - 1) * $kwitansis->perPage() }}</td>
                                <td>{{ $kwitansi->spj->pekerjaans->pekerjaan ?? '-' }}</td>
                                <td>{{ $kwitansi->no_rekening ?? '-' }}</td>
                                <td>{{ $kwitansi->penerima_kwitansi ?? '-' }}</td>
                                <td>{{ $kwitansi->spj->types ?? '-' }}</td>
                                <td>
                                    <div class="position-relative d-inline-block">

                                        <a href="{{ route('kwitansipo.edit', $kwitansi->id) }}" 
                                        class="btn btn-sm btn-success btn-edit"
                                        data-edit-url="{{ route('kwitansipo.edit', $kwitansi->id) }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        @php
                                            $spjId = $kwitansi->spj->id ?? null;
                                        @endphp

                                        @if ($spjId && isset($rejectedSpj[$spjId]))
                                            <span class="spj-badge-alert">!</span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $kwitansis->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tangkap semua tombol Edit
    const editButtons = document.querySelectorAll('.btn-edit');

    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Cegah langsung pindah halaman
            const editUrl = this.getAttribute('data-edit-url');

            Swal.fire({
                title: "Apakah Anda yakin ingin mengedit data ini?",
                text: "Perubahan akan mempengaruhi data kwitansi terkait.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, lanjutkan",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke halaman edit
                    window.location.href = editUrl;
                }
            });
        });
    });

    // Jika ada notifikasi sukses
    const swalSuccess = document.querySelector('[data-swal-success]');
    if (swalSuccess) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: swalSuccess.getAttribute('data-swal-success'),
            timer: 2500,
            showConfirmButton: false
        });
    }

    // Jika ada error
    const swalErrors = document.querySelector('[data-swal-errors]');
    if (swalErrors) {
        const messages = swalErrors.getAttribute('data-swal-errors').split('|');
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            html: messages.join('<br>'),
        });
    }
});
</script>
