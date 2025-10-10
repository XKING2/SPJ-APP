@extends('layouts.main')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Kelola Data Berita Acara Pemeriksaan</h1>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Pemeriksaan</h6>
        </div>
        <div class="card-body">
            <!-- Search & Print -->
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('pemeriksaan') }}" method="GET" class="form-inline">
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
                            <th style="width: 560px;">Pekerjaan</th>
                            <th>Nomor SP</th>
                            <th>Tanggal SP</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemeriksaans as $index => $pemeriksaan)
                            <tr>
                                <td>{{ $loop->iteration + ($pemeriksaans->currentPage() - 1) * $pemeriksaans->perPage() }}</td>
                                <td>{{ $pemeriksaan->pekerjaan ?? '-' }}</td>
                                <td>{{ $pemeriksaan->pesanan->no_surat ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($pemeriksaan->surat_dibuat ?? now())->translatedFormat('d F Y') }}</td>
                                <td>
                                    <a href="{{ route('pemeriksaan.edit', $pemeriksaan->id) }}" class="btn btn-sm btn-success">
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
                {{ $pemeriksaans->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>
@endsection
