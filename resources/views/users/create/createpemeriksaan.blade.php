@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data Pemeriksaan</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('pemeriksaan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pesanan_id" value="{{ $pesanan->id }}">
                <input type="hidden" name="spj_id" value="{{ $spj->id }}">
                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hari Diterima</label>
                            <input type="text" name="hari_diterima" class="form-control" value="{{ old('no_rekening') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Diterima</label>
                            <input type="text" name="tanggals_diterima" class="form-control" value="{{ old('no_rekening_tujuan') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bulan Diterima</label>
                            <input type="text" name="bulan_diterima" class="form-control" value="{{ old('nama_bank') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun Diterima</label>
                            <input type="text" name="tahun_diterima" class="form-control" value="{{ old('penerima_kwitansi') }}">
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pihak Kedua</label>
                            <input type="text" name="nama_pihak_kedua" class="form-control" value="{{ old('telah_diterima_dari') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Pihak Kedua</label>
                            <input type="text" name="jabatan_pihak_kedua" class="form-control" value="{{ old('jumlah_nominal') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Pihak Kedua</label>
                            <input type="text" name="alamat_pihak_kedua" class="form-control" value="{{ old('uang_terbilang') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pekerjaan Yang Dilakukan</label>
                            <input type="text" name="pekerjaan" class="form-control" value="{{ old('jabatan_penerima') }}">
                        </div>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-end gap-5">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
