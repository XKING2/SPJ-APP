@extends('layouts.main3')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Edit Data Pihak Pertama</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">

            {{-- ✅ Notifikasi sukses --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- ❌ Notifikasi error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 📝 Form Edit PPTK --}}
            <form method="POST" action="{{ route('plt.update', $plt->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pihak Kedua</label>
                            <input type="text" 
                                   name="nama_pihak_pertama" 
                                   class="form-control @error('nama_pihak_pertama') is-invalid @enderror"
                                   value="{{ old('nama_pihak_pertama', $plt->nama_pihak_pertama) }}">
                            @error('nama_pihak_pertama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Pihak Kedua</label>
                            <input type="text" 
                                   name="jabatan_pihak_pertama" 
                                   class="form-control @error('jabatan_pihak_pertama') is-invalid @enderror"
                                   value="{{ old('jabatan_pihak_pertama', $plt->jabatan_pihak_pertama) }}">
                            @error('jabatan_pihak_pertama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIP Pihak Pertama</label>
                            <input type="text" 
                                   name="nip_pihak_pertama" 
                                   class="form-control @error('nip_pihak_pertama') is-invalid @enderror"
                                   value="{{ old('nip_pihak_pertama', $plt->nip_pihak_pertama) }}">
                            @error('nip_pihak_pertama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIP Pihak Pertama</label>
                            <input type="text" 
                                   name="gol_pihak_pertama" 
                                   class="form-control @error('gol_pihak_pertama') is-invalid @enderror"
                                   value="{{ old('gol_pihak_pertama', $plt->gol_pihak_pertama) }}">
                            @error('gol_pihak_pertama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('showplt') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SweetAlert Success/Error --}}
@if(session('success'))
    <div data-swal-success="{{ session('success') }}"></div>
@endif

@if($errors->any())
    <div data-swal-errors="{{ implode('|', $errors->all()) }}"></div>
@endif
@endsection
