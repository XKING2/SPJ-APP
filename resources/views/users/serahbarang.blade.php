@extends('layouts.main')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Kelola Data Berita Acara Serah barang</h1>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Serahbarang</h6>
        </div>
        <div class="card-body">
            <!-- Search & Print -->
            <div class="d-flex justify-content-between mb-3">
                <form action="#" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Cari...">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Pihak Pertama </th>
                            <th>Pihak Kedua </th>
                            <th>Nomor Surat</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($serahbarangs as $index => $item)
                        <tr>
                            <td>{{ $loop->iteration + ($serahbarangs->currentPage() - 1) * $serahbarangs->perPage() }}</td>
                            <td>{{ $item->plt->nama_pihak_pertama ?? '-' }}</td>
                            <td>{{ $item->pihak_kedua->nama_pihak_kedua ?? '-' }}</td>
                            <td>{{ $item->no_suratsss ?? '-' }}</td>
                            <td>
                                    <!-- Tombol Edit dengan popup konfirmasi -->
                                    <a href="{{ route('serahbarang.edit', $item->id) }}" 
                                        class="btn btn-sm btn-success btn-edit"
                                        data-edit-url="{{ route('serahbarang.edit', $item->id) }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
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

        </div>
    </div>
</div>
@endsection
