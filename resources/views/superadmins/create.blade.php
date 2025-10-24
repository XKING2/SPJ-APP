@extends('layouts.main3')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Tambah Data Anggota</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Anggota</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('anggota.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- 🟦 Kolom Kiri -->
                    <div class="col-md-6">
                        {{-- NIP --}}
                        <div class="form-group mb-3">
                            <label for="nip">NIP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nip') is-invalid @enderror" 
                                id="nip" name="nip" value="{{ old('nip') }}" 
                                placeholder="Masukkan NIP" required>
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-group mb-3">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('password') is-invalid @enderror" 
                                id="password" name="password" value="{{ old('password') }}" 
                                placeholder="Masukkan password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama --}}
                        <div class="form-group mb-3">
                            <label for="nama">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                id="nama" name="nama" value="{{ old('nama') }}" 
                                placeholder="Masukkan nama lengkap" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jabatan --}}
                        <div class="form-group mb-3">
                            <label for="jabatan">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror" 
                                id="jabatan" name="jabatan" value="{{ old('jabatan') }}" 
                                placeholder="Masukkan jabatan" required>
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- 🟩 Kolom Kanan -->
                    <div class="col-md-6">
                        {{-- Nomor Telepon --}}
                        <div class="form-group mb-3">
                            <label for="nomor_tlp">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nomor_tlp') is-invalid @enderror" 
                                id="nomor_tlp" name="nomor_tlp" value="{{ old('nomor_tlp') }}" 
                                placeholder="Masukkan nomor telepon" required>
                            @error('nomor_tlp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="form-group mb-3">
                            <label for="alamat">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                id="alamat" name="alamat" rows="4" 
                                placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div class="form-group mb-3">
                            <label for="role">Role <span class="text-danger">*</span></label>
                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="Bendahara" {{ old('role') == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                                <option value="Kasubag" {{ old('role') == 'Kasubag' ? 'selected' : '' }}>Kasubag</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 🟧 Tombol Aksi -->
                <div class="d-flex justify-content-end mt-4 gap-2">
                    <a href="{{ route('showanggota') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Style tambahan agar tampil lebih seimbang -->
<style>
    .form-group label {
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
