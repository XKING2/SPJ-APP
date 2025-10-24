@extends('layouts.main3')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data Kwitansi</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('pptk.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama PPTK</label>
                            <input type="text" name="nama_pptk" class="form-control" value="{{ old('nama_pptk') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan PPTK</label>
                            <input type="text" name="jabatan_pptk" class="form-control" value="{{ old('jabatan_pptk') }}">
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIP PPTK</label>
                            <input type="text" name="nip_pptk" class="form-control" value="{{ old('nip_pptk') }}">
                        </div>
                    </div>
                </div>

                <!-- Untuk Pembayaran -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Sub Kegiatan</label>
                    <textarea name="subkegiatan" class="form-control" rows="3">{{ old('subkegiatan') }}</textarea>
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

