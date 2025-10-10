@extends('layouts.main')
<link href="../css/sb-admin-2.min.css" rel="stylesheet">
<link href="../css/page.css" rel="stylesheet">

@section('pageheads')
<h1 class="h3 mb-4 text-gray-800">Kelola Data Kwitansi</h1>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Kwitansi</h6>
        </div>
        <div class="card-body">

            <!-- Search & Print -->
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('kwitansi') }}" method="GET" class="form-inline">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="form-control form-control-sm mr-2" placeholder="Cari...">
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
                            <th>Untuk Pembayaran</th>
                            <th>Uang Sebanyak</th>
                            <th>Telah Diterima Dari</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kwitansis as $index => $kwitansi)
                            <tr>
                                <td>{{ $loop->iteration + ($kwitansis->currentPage() - 1) * $kwitansis->perPage() }}</td>
                                <td>{{ $kwitansi->pembayaran ?? '-' }}</td>
                                <td>Rp {{ number_format($kwitansi->jumlah_nominal ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $kwitansi->nama_pt ?? '-' }}</td>
                                <td>
                                <!-- Edit: gunakan id kwitansi, bukan $spj -->
                                    <a href="{{ route('kwitansi.edit', $kwitansi->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <!-- Delete: contoh route destroy (pastikan route & controller ada) -->
                                    <form action="#" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin hapus?')">
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
                                <td colspan="5" class="text-center">Data tidak tersedia</td>
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
@endsection
