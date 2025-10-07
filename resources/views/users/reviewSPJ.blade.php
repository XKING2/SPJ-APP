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
            <!-- Search -->
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('reviewSPJ') }}" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control form-control-sm mr-2"
                           placeholder="Cari SPJ atau Nomor Surat..." value="{{ request('search') }}">
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
                            <th>Nomor SPJ</th>
                            <th>Tanggal Surat Dibuat</th>
                            <th>Status Validasi</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($spjs as $index => $spj)
                            <tr>
                                <td>{{ $loop->iteration + ($spjs->currentPage() - 1) * $spjs->perPage() }}</td>
                                <td>{{ $spj->pesanan->no_surat ?? '-' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($spj->pesanan->surat_dibuat ?? now())->translatedFormat('d F Y') }}
                                </td>
                                <td>
                                    @if ($spj->status == 'valid')
                                        <span class="badge bg-success text-white">Valid</span>
                                    @elseif ($spj->status == 'draft')
                                        <span class="badge bg-warning text-dark">Draft</span>
                                    @else
                                        <span class="badge bg-secondary text-white">Belum Valid</span>
                                    @endif
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
                                <td colspan="5" class="text-center">Data tidak tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $spjs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
