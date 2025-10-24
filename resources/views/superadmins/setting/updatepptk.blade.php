@extends('layouts.main3')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Edit Data PPTK</h4>
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
            <form method="POST" action="{{ route('pptk.update', $pptk->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama PPTK</label>
                            <input type="text" 
                                   name="nama_pptk" 
                                   class="form-control @error('nama_pptk') is-invalid @enderror"
                                   value="{{ old('nama_pptk', $pptk->nama_pptk) }}">
                            @error('nama_pptk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan PPTK</label>
                            <input type="text" 
                                   name="jabatan_pptk" 
                                   class="form-control @error('jabatan_pptk') is-invalid @enderror"
                                   value="{{ old('jabatan_pptk', $pptk->jabatan_pptk) }}">
                            @error('jabatan_pptk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIP PPTK</label>
                            <input type="text" 
                                   name="nip_pptk" 
                                   class="form-control @error('nip_pptk') is-invalid @enderror"
                                   value="{{ old('nip_pptk', $pptk->nip_pptk) }}">
                            @error('nip_pptk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sub Kegiatan -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Sub Kegiatan</label>
                    <textarea name="subkegiatan" 
                              class="form-control @error('subkegiatan') is-invalid @enderror" 
                              rows="3">{{ old('subkegiatan', $pptk->subkegiatan) }}</textarea>
                    @error('subkegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('showpptk') }}" class="btn btn-secondary">
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
