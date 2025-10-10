@extends('layouts.main2')
@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Kelola Data Anggota</h1>
@endsection
@section('content')
<div class="container-fluid">
    <!-- Alert Success -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Berhasil!</strong> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Anggota</h6>
        </div>
        <div class="card-body">
            <!-- Search & Print -->
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('showanggota') }}" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Cari..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                    @if(isset($search))
                    <a href="{{ route('showanggota') }}" class="btn btn-sm btn-light ml-2">Reset</a>
                    @endif
                </form>
                <a href="{{ route('anggota.create') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-plus"></i> Tambah Anggota
                </a>
            </div>
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Alamat</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggotas as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->NIP }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->jabatan }}</td>
                            <td>{{ $item->Alamat }}</td>
                            <td>
                                <a href="{{ route('anggota.edit', $item->id) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('anggota.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
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
        </div>
    </div>
</div>
@endsection