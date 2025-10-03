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
                <form action="#" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Cari...">
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
                            <th>Pihak Pertama</th>
                            <th>Pihak Kedua </th>
                            <th>Hal Yang Dikerjakan</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Contoh data statis --}}
                        <tr>
                            <td>1</td>
                            <td>Drs. I Wayan Arsana, MAP</td>
                            <td>Putu Agus Junaedi</td>
                            <td>Belanja Pemeliharaan Alat Angkutan-Alat Angkutan Darat</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-success">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        {{-- Jika tidak ada data --}}
                        <tr>
                            <td colspan="5" class="text-center">Data tidak tersedia</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
