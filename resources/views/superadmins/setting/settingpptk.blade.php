@extends('layouts.main3')

@section('pageheads')
<h1 class="h3 mb-4 text-gray-800">Kelola Data PPTK</h1>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data PPTK</h6>
            <a href="{{ route('createpptk') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah PPTK
            </a>
        </div>

        <div class="card-body">

            <!-- Search -->
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('showpptk') }}" method="GET" class="form-inline">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-control form-control-sm me-2" placeholder="Cari...">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama PPTK</th>
                            <th>Golongan PPTK</th>
                            <th>Sub Kegiatan</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse ($pptks as $pptk)
                            @foreach ($pptk->kegiatan as $kegiatan)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $pptk->nama_pptk ?? '-' }}</td>
                                    <td>{{ $pptk->gol_pptk ?? '-' }}</td>
                                    <td>{{ $kegiatan->subkegiatan ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('pptk.edit', $pptk->id) }}" 
                                           class="btn btn-sm btn-success btn-edit"
                                           data-edit-url="{{ route('pptk.edit', $pptk->id) }}">
                                            <i class="fas fa-edit"></i> Edit 
                                        </a>

                                        <form action="{{ route('pptk.destroy', $kegiatan->id) }}" 
                                              method="POST" class="d-inline form-delete" 
                                              data-item-name="{{ $kegiatan->sub_kegiatan }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $pptks->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
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

    // ---------- Edit Confirmation ----------
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const editUrl = this.dataset.editUrl;

            Swal.fire({
                title: "Yakin ingin mengedit data ini?",
                text: "Perubahan akan mempengaruhi data terkait.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, edit",
                cancelButtonText: "Batal"
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = editUrl;
                }
            });
        });
    });

    // ---------- Delete Confirmation ----------
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const itemName = this.dataset.itemName;

            Swal.fire({
                title: "Hapus data?",
                text: `Data PPTK "${itemName}" akan dihapus permanen.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal"
            }).then(result => {
                if (result.isConfirmed) {
                    const btn = this.querySelector('button[type="submit"]');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
                    this.submit();
                }
            });
        });
    });

    // ---------- SweetAlert Feedback ----------
    const swalSuccess = document.querySelector('[data-swal-success]');
    if (swalSuccess) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: swalSuccess.dataset.swalSuccess,
            timer: 2000,
            showConfirmButton: false
        });
    }

    const swalErrors = document.querySelector('[data-swal-errors]');
    if (swalErrors) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            html: swalErrors.dataset.swalErrors.split('|').join('<br>')
        });
    }
});
</script>
