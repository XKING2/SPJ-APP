@extends('layouts.main')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Kelola Data Pesanan</h1>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Pesanan</h6>
        </div>
        <div class="card-body">

            <!-- Search & Print -->
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('pesanan') }}" method="GET" class="form-inline">
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
                            <th>No Surat</th>
                            <th>Tanggal Surat</th>
                            <th style="width: 150px;">Banyak Barang</th>
                            <th>Dikirim Kepada</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanans as $index => $pesanan)
                            <tr>
                                <td>{{ $loop->iteration + ($pesanans->currentPage() - 1) * $pesanans->perPage() }}</td>
                                <td>{{ $pesanan->no_surat ?? '-' }}</td>
                                <td>{{ $pesanan->first()->surat_dibuat ?? '-' }}</td>
                                <td>
                                    {{ $pesanan->items->sum('jumlah') ?? 0 }}
                                    {{ $pesanan->items->first()->satuan ?? '' }}
                                </td>
                                <td>{{ $pesanan->nama_pt ?? '-' }}</td>
                                <td>
                                    <!-- Tombol Edit dengan popup konfirmasi -->
                                    <a href="{{ route('pesanan.edit', $pesanan->id) }}" 
                                       class="btn btn-sm btn-success btn-edit"
                                       data-edit-url="{{ route('pesanan.edit', $pesanan->id) }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
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
                {{ $pesanans->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
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

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tangkap semua tombol Edit
    const editButtons = document.querySelectorAll('.btn-edit');

    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Cegah redirect langsung
            const editUrl = this.getAttribute('data-edit-url');

            Swal.fire({
                title: "Apakah Anda yakin ingin mengedit pesanan ini?",
                text: "Perubahan data akan mempengaruhi laporan terkait.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, lanjutkan",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = editUrl;
                }
            });
        });
    });

    // Tampilkan notifikasi sukses
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

    // Tampilkan error jika ada
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
@endpush
