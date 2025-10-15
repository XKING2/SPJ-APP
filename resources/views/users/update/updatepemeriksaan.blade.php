@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Edit Data Pemeriksaan</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('pemeriksaan.update', $pemeriksaan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="spj_id" value="{{ $spj->id }}">

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hari Diterima</label>
                            <input type="text" name="hari_diterima" class="form-control" 
                                value="{{ old('hari_diterima', $pemeriksaan->hari_diterima) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Diterima</label>
                            <input type="text" name="tanggals_diterima" class="form-control" 
                                value="{{ old('tanggals_diterima', $pemeriksaan->tanggals_diterima) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bulan Diterima</label>
                            <input type="text" name="bulan_diterima" class="form-control" 
                                value="{{ old('bulan_diterima', $pemeriksaan->bulan_diterima) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun Diterima</label>
                            <input type="text" name="tahun_diterima" class="form-control" 
                                value="{{ old('tahun_diterima', $pemeriksaan->tahun_diterima) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pihak Kedua</label>
                            <input type="text" name="nama_pihak_kedua" class="form-control" 
                                value="{{ old('nama_pihak_kedua', $pemeriksaan->nama_pihak_kedua) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Pihak Kedua</label>
                            <input type="text" name="jabatan_pihak_kedua" class="form-control" 
                                value="{{ old('jabatan_pihak_kedua', $pemeriksaan->jabatan_pihak_kedua) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Pihak Kedua</label>
                             <input type="text" name="alamat_pihak_kedua" class="form-control" 
                                value="{{ old('alamat_pihak_kedua', $pemeriksaan->alamat_pihak_kedua) }}">
                        </div>
                         
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pekerjaan</label>
                            <textarea name="pekerjaan" class="form-control">{{ old('pekerjaan', $pemeriksaan->pekerjaan) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('pemeriksaan') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Update Pemeriksaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
