@extends('layouts.main3')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Update Data Nomor Surat</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('nosurat.update', $nosurats->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Awalan</label>
                            <input type="text" name="no_awal" class="form-control" value="{{ old('no_awal',$nosurats->no_awal) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Dinas</label>
                            <input type="text" name="nama_dinas" class="form-control" value="{{ old('nama_dinas',$nosurats->nama_dinas) }}">
                        </div>
                    </div>

                    <!-- Kanan -->

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun</label>
                            <input type="text" name="tahun" class="form-control" value="{{ old('tahun',$nosurats->tahun) }}">
                        </div>
                    </div>

                    
                </div>
                <!-- Tombol -->
                <div class="d-flex justify-content-end">
                    <a href="{{ route('shownosurat') }}" class="btn btn-secondary px-4 py-2 me-3">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success px-4 py-2">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

