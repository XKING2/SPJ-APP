@extends('layouts.main3')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Validasi SPJ</h1>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar SPJ</h6>
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
                            <th>Nama Surat</th>
                            <th>Nama Pembuat</th>
                            <th>Tanggal Pembuatan</th>
                            <th>Surat</th>
                            <th style="width: 180px;">Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Contoh data statis --}}
                        <tr>
                            <td>1</td>
                            <td>Surat A</td>
                            <td>I Made Ary Nindya Pratama</td>
                            <td>01-01-2026</td>
                            <td>
    <a href="{{ asset('storage/surat/surat-a.pdf') }}" target="_blank">
        Buka Surat
         <td>
                                <a href="#" class="btn btn-sm btn-success">
                                    <i class="fas fa-edit"></i> Validasi
                                </a>
                            </td>


          <tr>
                            <td>2</td>
                            <td>Surat B</td>
                            <td>Budiono Sureger</td>
                            <td>25-01-2026</td>
                            <td>
    <a href="{{ asset('storage/surat/surat-c.pdf') }}" target="_blank">
        Buka Surat
    </a>
    </a>
</td>

                            <td>
                                <a href="#" class="btn btn-sm btn-success">
                                    <i class="fas fa-edit"></i> Validasi
                                </a>
                            </td>

                        </tr>
                        {{-- Jika tidak ada data --}}
                        <tr>
                            <td colspan="6" class="text-center">Data tidak tersedia</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
