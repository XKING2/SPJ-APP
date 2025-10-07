@extends('layouts.main')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Kelola Data SPJ</h1>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data SPJ</h6>
        </div>

        <div class="card-body">
            <!-- 🔍 Search bar -->
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('reviewSPJ') }}" method="GET" class="form-inline">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control form-control-sm mr-2"
                        placeholder="Cari status atau nomor surat..."
                        value="{{ request('search') }}"
                    >
                    <button type="submit" class="btn btn-sm btn-secondary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </form>

                <!-- Tombol cetak -->
                <a href="#" target="_blank" class="btn btn-info btn-sm">
                    <i class="fas fa-print"></i> Cetak
                </a>
            </div>

            <!-- 📋 Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nomor SPJ</th>
                            <th>Tanggal Surat Dibuat</th>
                            <th>Status Validasi</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($spjs as $spj)
                            <tr>
                                <td>{{ $loop->iteration + ($spjs->currentPage() - 1) * $spjs->perPage() }}</td>
                                <td>{{ $spj->pesanan->no_surat ?? '-' }}</td>
                                <td>
                                    @if (!empty($spj->pesanan?->surat_dibuat))
                                        {{ \Carbon\Carbon::parse($spj->pesanan->surat_dibuat)->translatedFormat('d F Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($spj->status)
                                        @case('valid')
                                            <span class="badge bg-success text-white">Valid</span>
                                            @break
                                        @case('draft')
                                            <span class="badge bg-warning text-dark">Draft</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary text-white">Belum Valid</span>
                                    @endswitch
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <!-- Tombol Preview -->
                                    <a href="{{ route('spj.preview', ['id' => $spj->id]) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Preview
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    Tidak ada data SPJ untuk akun ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 📑 Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $spjs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
