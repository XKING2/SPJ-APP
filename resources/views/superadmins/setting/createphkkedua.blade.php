@extends('layouts.main3')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data Pihak Pertama</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('kedua.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pihak Kedua</label>
                            <input type="text" name="nama_pihak_kedua" class="form-control" value="{{ old('nama_pihak_kedua') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Pihak Kedua</label>
                            <input type="text" name="jabatan_pihak_kedua" class="form-control" value="{{ old('jabatan_pihak_kedua') }}">
                        </div>
                    </div>

                    <!-- Kanan -->

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIP Pihak Kedua</label>
                            <input type="text" name="nip_pihak_kedua" class="form-control" value="{{ old('nip_pihak_kedua') }}">
                        </div>

            
                        <div class="mb-3">
                            <label class="form-label fw-bold">Golongan/Pangkat Pihak Kedua</label>
                            <input type="text" name="gol_pihak_kedua" class="form-control" value="{{ old('golongan_pihak_kedua') }}">
                        </div>
                
                    </div>

                    
                </div>
                <!-- Tombol -->
                <div class="d-flex justify-content-end gap-5">
                    <a href="{{ route('showplt') }}" class="btn btn-secondary px-4 py-2">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

