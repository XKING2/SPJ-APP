@extends('layouts.main3')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Edit Data PPTK & Kegiatan</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">

            {{-- ✅ Alert sukses --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- ❌ Alert error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 📝 Form Edit --}}
            <form method="POST" action="{{ route('pptk.update', $pptk->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama PPTK</label>
                            <input type="text" name="nama_pptk" 
                                   class="form-control @error('nama_pptk') is-invalid @enderror"
                                   value="{{ old('nama_pptk', $pptk->nama_pptk) }}">
                            @error('nama_pptk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Golongan PPTK</label>
                            <input type="text" name="gol_pptk" 
                                   class="form-control @error('gol_pptk') is-invalid @enderror"
                                   value="{{ old('gol_pptk', $pptk->gol_pptk) }}">
                            @error('gol_pptk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIP PPTK</label>
                            <input type="text" name="nip_pptk" 
                                   class="form-control @error('nip_pptk') is-invalid @enderror"
                                   value="{{ old('nip_pptk', $pptk->nip_pptk) }}">
                            @error('nip_pptk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- 🔹 Kegiatan --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Program</label>
                    <input type="text" name="program" 
                           class="form-control @error('program') is-invalid @enderror"
                           value="{{ old('program', optional($pptk->kegiatan->first())->program) }}">
                    @error('program') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kegiatan</label>
                    <input type="text" name="kegiatan" 
                           class="form-control @error('kegiatan') is-invalid @enderror"
                           value="{{ old('kegiatan', optional($pptk->kegiatan->first())->kegiatan) }}">
                    @error('kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
    

                {{-- 🔹 Subkegiatan Dinamis --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Sub Kegiatan</label>
                    <div id="subkegiatan-wrapper">
                        @php
                            $kegiatan = $pptk->kegiatan->first(); 
                            $subkegiatans = $kegiatan ? [$kegiatan->subkegiatan] : [];
                        @endphp

                        @foreach(old('subkegiatan', $subkegiatans) as $sub)
                            <div class="input-group mb-2">
                                <textarea name="subkegiatan[]" class="form-control" rows="2">{{ trim($sub) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 🔹 Tombol --}}
                <div class="d-flex justify-content-end gap-3 mt-4">
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

{{-- 🔹 Script Dinamis --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('subkegiatan-wrapper');
    const addBtn = document.getElementById('add-subkegiatan');

    addBtn.addEventListener('click', () => {
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-2');
        div.innerHTML = `
            <textarea name="subkegiatan[]" class="form-control" rows="2" placeholder="Masukkan sub kegiatan baru"></textarea>
            <button type="button" class="btn btn-danger remove-subkegiatan">Hapus</button>
        `;
        wrapper.appendChild(div);
    });

    wrapper.addEventListener('click', e => {
        if (e.target.classList.contains('remove-subkegiatan')) {
            e.target.closest('.input-group').remove();
        }
    });
});
</script>
@endsection
