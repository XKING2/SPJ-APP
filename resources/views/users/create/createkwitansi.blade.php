@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data Kwitansi</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('kwitansis.store') }}" method="POST">
                <input type="hidden" name="spj_id" value="{{ $spj->id }}">
                @csrf
                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Rekening</label>
                            <input type="text" name="no_rekening" class="form-control" value="{{ old('no_rekening') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Rekening Tujuan</label>
                            <input type="text" name="no_rekening_tujuan" class="form-control" value="{{ old('no_rekening_tujuan') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Bank</label>
                            <input type="text" name="nama_bank" class="form-control" value="{{ old('nama_bank') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Yang Menerima Kwitansi</label>
                            <input type="text" name="penerima_kwitansi" class="form-control" value="{{ old('penerima_kwitansi') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sub Kegiatan</label>
                            <textarea name="sub_kegiatan" class="form-control" rows="5">{{ old('sub_kegiatan') }}</textarea>
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Telah Diterima Dari</label>
                            <input type="text" name="telah_diterima_dari" class="form-control" value="{{ old('telah_diterima_dari') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Nominal</label>
                            <input type="number" name="jumlah_nominal" class="form-control" value="{{ old('jumlah_nominal') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Uang Terbilang</label>
                            <input type="text" name="uang_terbilang" class="form-control" value="{{ old('uang_terbilang') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Penerima Kwitansi</label>
                            <input type="text" name="jabatan_penerima" class="form-control" value="{{ old('jabatan_penerima') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">NPWP</label>
                            <input type="text" name="npwp" class="form-control" value="{{ old('npwp') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama PT</label>
                            <input type="text" name="nama_pt" class="form-control" value="{{ old('nama_pt') }}">
                        </div>
                    </div>
                </div>

                <!-- Untuk Pembayaran -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Untuk Pembayaran</label>
                    <textarea name="pembayaran" class="form-control" rows="3">{{ old('pembayaran') }}</textarea>
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

