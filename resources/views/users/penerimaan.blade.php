@extends('layouts.main')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Kelola Data Berita Acara Penerimaan</h1>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Penerimaan</h6>
        </div>
        <div class="card-body">
            <!-- Search & Print -->
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('penerimaan') }}" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control form-control-sm mr-2" 
                           placeholder="Cari..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                </form>
                <a href="#" target="_blank" class="btn btn-info btn-sm">
                    <i class="fas fa-print"></i> Cetak
                </a>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 180px;">No Surat</th>
                            <th style="width: 200px;">Pihak Kedua</th>
                            <th>Hal Yang Dikerjakan</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penerimaans as $index => $penerimaan)
                            <tr>
                                <td>{{ $loop->iteration + ($penerimaans->currentPage() - 1) * $penerimaans->perPage() }}</td>
                                <td>{{ $penerimaan->pesanan->no_surat ?? '-' }}</td>
                                <td>{{ $penerimaan->nama_pihak_kedua ?? '-' }}</td>
                                <td>{{ $penerimaan->pekerjaan ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('penerimaan.edit', $penerimaan->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="#" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $penerimaans->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
