@extends('layouts.main3')

@section('pageheads')
<h1 class="h3 mb-4 text-gray-800">Kelola Data Nomor Surat</h1>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Card -->
    <div class="card shadow mb-4">
         {{-- 🔹 BAGIAN 2: TABEL NOMOR SURAT --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
            <span>Daftar Nomor Surat</span>
            <a href="{{ route('createnosurat') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus"></i> Tambah Nomor Surat
            </a>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('showplt') }}" method="GET" class="form-inline">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="form-control form-control-sm mr-2" placeholder="Cari...">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                </form>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nomor Surat</th>
                            <th>Nama Dinas</th>
                            <th>Tahun</th>
                            <th style="width: 240px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($no_surats as $index => $surat)
                            <tr>
                                <td>{{ $loop->iteration + ($no_surats->currentPage() - 1) * $no_surats->perPage() }}</td>
                                <td>{{ $surat->no_awal ?? '-' }}</td>
                                <td>{{ $surat->nama_dinas ?? '-' }}</td>
                                <td>{{ $surat->tahun ?? '-' }}</td>
                                <td>
                                    <!-- Tombol Edit dengan popup konfirmasi -->
                                    <a href="{{ route('nosurat.edit', $surat->id) }}" 
                                       class="btn btn-sm btn-success btn-edit"
                                       data-edit-url="{{ route('nosurat.edit', $surat->id) }}">
                                        <i class="fas fa-edit"></i> Edit 
                                    </a>

                                    <!-- Form Hapus (akan diproses oleh JS) -->
                                    <form action="{{ route('nosurat.destroy',$surat->id) }}" method="POST" class="d-inline form-delete" data-item-name="{{ $surat->no_awal }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---------- Tombol Edit ----------
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const editUrl = this.getAttribute('data-edit-url');

            Swal.fire({
                title: "Apakah Anda yakin ingin mengedit data ini?",
                text: "Perubahan akan mempengaruhi data terkait.",
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

    // ---------- Tombol Hapus ----------
    const deleteForms = document.querySelectorAll('.form-delete');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: "Apakah Anda yakin ingin menghapus data ini?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
                    }
                    form.submit();
                }
            });
        });
    });

    // ---------- Notifikasi sukses / error ----------
    const swalSuccess = document.querySelector('[data-swal-success]');
    if (swalSuccess) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: swalSuccess.getAttribute('data-swal-success'),
            timer: 2000,
            showConfirmButton: false
        });
    }

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
