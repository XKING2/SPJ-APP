@extends('layouts.main3')

@section('pageheads')
<div class="container-fluid px-4">
    <h1 class="h3 mb-3">Kelola Data Jabatan</h1>
</div>
@endsection

@section('content')
<div class="container-fluid">

    {{-- 🔹 TABEL PIHAK PERTAMA --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Pihak Pertama</h6>
            <a href="{{ route('createplt') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pihak Pertama
            </a>
        </div>
        <div class="card-body">
            {{-- 🔍 Search --}}
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('showplt') }}" method="GET" class="form-inline">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-control form-control-sm me-2" placeholder="Cari...">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                </form>
            </div>

            {{-- 📋 Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Pihak Pertama</th>
                            <th>Jabatan</th>
                            <th>NIP</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($plts as $index => $plt)
                            <tr>
                                <td>{{ $loop->iteration + ($plts->currentPage() - 1) * $plts->perPage() }}</td>
                                <td>{{ $plt->nama_pihak_pertama ?? '-' }}</td>
                                <td>{{ $plt->jabatan_pihak_pertama ?? '-' }}</td>
                                <td>{{ $plt->nip_pihak_pertama ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('plt.edit', $plt->id) }}" 
                                       class="btn btn-sm btn-success btn-edit"
                                       data-edit-url="{{ route('plt.edit', $plt->id) }}">
                                        <i class="fas fa-edit"></i> Edit 
                                    </a>

                                    <form action="{{ route('plt.destroy', $plt->id) }}" 
                                          method="POST" 
                                          class="d-inline form-delete" 
                                          data-item-name="{{ $plt->nama_pihak_pertama }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Data tidak tersedia</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 🔹 TABEL NOMOR SURAT (tabel kedua) --}}
    <div class="card shadow-sm">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Pihak Kedua</h6>
            <a href="{{ route('createkedua') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Pihak Kedua
            </a>
        </div>
        <div class="card-body">
            {{-- 🔍 Search --}}
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('showplt') }}" method="GET" class="form-inline">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-control form-control-sm me-2" placeholder="Cari No Surat...">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                </form>
            </div>

            {{-- 📋 Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Pihak Kedua</th>
                            <th>Jabatan</th>
                            <th>NIP</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keduas as $index => $kedua)
                            <tr>
                                <td>{{ $loop->iteration + ($keduas->currentPage() - 1) * $keduas->perPage() }}</td>
                                <td>{{ $kedua->nama_pihak_kedua ?? '-' }}</td>
                                <td>{{ $kedua->jabatan_pihak_kedua ?? '-' }}</td>
                                <td>{{ $kedua->nip_pihak_kedua ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('kedua.edit', $kedua->id) }}" 
                                       class="btn btn-sm btn-success btn-edit"
                                       data-edit-url="{{ route('kedua.edit', $kedua->id) }}">
                                        <i class="fas fa-edit"></i> Edit 
                                    </a>

                                    <form action="{{ route('kedua.destroy', $kedua->id) }}" 
                                          method="POST" 
                                          class="d-inline form-delete" 
                                          data-item-name="{{ $kedua->nama_pihak_pertama }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Data tidak tersedia</td></tr>
                        @endforelse
                    </tbody>
                </table>
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


{{-- 🔹 SweetAlert Script --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const editUrl = this.getAttribute('data-edit-url');
            Swal.fire({
                title: "Edit data ini?",
                text: "Perubahan akan mempengaruhi data terkait.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, lanjutkan",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) window.location.href = editUrl;
            });
        });
    });

    const deleteForms = document.querySelectorAll('.form-delete');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Hapus data ini?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = form.querySelector('button[type="submit"]');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menghapus...';
                    form.submit();
                }
            });
        });
    });
});
</script>
