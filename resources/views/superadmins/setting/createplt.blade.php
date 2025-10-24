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
            <form action="{{ route('plt.store') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pihak Pertama</label>
                            <input type="text" name="nama_pihak_pertama" class="form-control" value="{{ old('nama_pihak_pertama') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Pihak Pertama</label>
                            <input type="text" name="jabatan_pihak_pertama" class="form-control" value="{{ old('jabatan_pihak_pertama') }}">
                        </div>
                    </div>

                    <!-- Kanan -->

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIP Pihak Pertama</label>
                            <input type="text" name="nip_pihak_pertama" class="form-control" value="{{ old('nip_pihak_Pertama') }}">
                        </div>

            
                        <div class="mb-3">
                            <label class="form-label fw-bold">Golongan/Pangkat</label>
                            <input type="text" name="gol_pihak_pertama" class="form-control" value="{{ old('golongan_pihak_Pertama') }}">
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

