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
                    <!-- 🟩 Kolom Kiri -->
                    <div class="col-md-6">

                        {{-- Pilih Pihak Pertama --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Pihak Pertama</label>
                            <select name="id_plt" class="form-control" required>
                                <option value="" disabled>-- Pilih Pihak Pertama --</option>
                                @foreach($plts as $plt)
                                    <option value="{{ $plt->id }}" 
                                        {{ old('id_plt', $pemeriksaan->id_plt) == $plt->id ? 'selected' : '' }}>
                                        {{ $plt->nama_pihak_pertama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pekerjaan --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pekerjaan yang Dilakukan</label>
                            <input type="text" 
                                   name="pekerjaan" 
                                   class="form-control" 
                                   value="{{ old('pekerjaan', $pemeriksaan->pekerjaan) }}">
                        </div>

                        {{-- Hari, Tanggal, Bulan, Tahun Diterima --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hari Diterima</label>
                            <input type="text" 
                                   name="hari_diterima" 
                                   class="form-control" 
                                   value="{{ old('hari_diterima', $pemeriksaan->hari_diterima) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Diterima</label>
                            <input type="text" 
                                   name="tanggals_diterima" 
                                   class="form-control" 
                                   value="{{ old('tanggals_diterima', $pemeriksaan->tanggals_diterima) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bulan Diterima</label>
                            <input type="text" 
                                   name="bulan_diterima" 
                                   class="form-control" 
                                   value="{{ old('bulan_diterima', $pemeriksaan->bulan_diterima) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun Diterima</label>
                            <input type="text" 
                                   name="tahun_diterima" 
                                   class="form-control" 
                                   value="{{ old('tahun_diterima', $pemeriksaan->tahun_diterima) }}">
                        </div>
                    </div>

                    <!-- 🟦 Kolom Kanan -->
                    <div class="col-md-6">

                        {{-- Nama Pihak Kedua --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pihak Kedua</label>
                            <input type="text" 
                                   name="nama_pihak_kedua" 
                                   class="form-control" 
                                   value="{{ old('nama_pihak_kedua', $pemeriksaan->nama_pihak_kedua) }}">
                        </div>

                        {{-- Jabatan Pihak Kedua --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Pihak Kedua</label>
                            <input type="text" 
                                   name="jabatan_pihak_kedua" 
                                   class="form-control" 
                                   value="{{ old('jabatan_pihak_kedua', $pemeriksaan->jabatan_pihak_kedua) }}">
                        </div>

                        {{-- Alamat Pihak Kedua --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Pihak Kedua</label>
                            <textarea name="alamat_pihak_kedua" 
                                      class="form-control" 
                                      rows="3">{{ old('alamat_pihak_kedua', $pemeriksaan->alamat_pihak_kedua) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 🟧 Tombol Aksi -->
                <div class="d-flex justify-content-end gap-3 mt-4">
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

{{-- Style agar rapi --}}
<style>
    .form-label {
        font-weight: 600;
    }
    .form-control, textarea, select {
        border-radius: 8px;
    }
    @media (max-width: 768px) {
        .col-md-6 {
            margin-bottom: 1.5rem;
        }
    }
</style>
@endsection
