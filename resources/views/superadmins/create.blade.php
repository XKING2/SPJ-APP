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
                        <div class="form-group mb-3 position-relative">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" value="{{ old('password') }}"
                                    placeholder="Masukkan password" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
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
                        {{-- Jabatan Atasan Langsung --}}
                        <div class="form-group mb-3">
                            <label for="jabatan_atasan">Jabatan Atasan Langsung <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('jabatan_atasan') is-invalid @enderror" 
                                id="jabatan_atasan" name="jabatan_atasan" value="{{ old('jabatan_atasan') }}" 
                                placeholder="Masukkan jabatan atasan" required>
                            @error('jabatan_atasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- IDINJAB --}}
                        <div class="form-group mb-3">
                            <label for="idinjab">IDINJAB <span class="text-danger">*</span></label>
                            <input class="form-control @error('idinjab') is-invalid @enderror" 
                                id="idinjab" name="idinjab"
                                placeholder="Masukkan IDINJAB lengkap" required>{{ old('idinjab') }}</input>
                            @error('idinjab')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div class="form-group mb-3">
                            <label for="role">Role <span class="text-danger">*</span></label>
                            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="kasubag" {{ old('role') == 'kasubag' ? 'selected' : '' }}>Kasubag</option>
                                <option value="users" {{ old('role') == 'users' ? 'selected' : '' }}>User</option>
                                <option value="bendahara" {{ old('role') == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="form-group mb-3">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="PNS" {{ old('status') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                <option value="PPPK" {{ old('status') == 'PPPK' ? 'selected' : '' }}>PPPK</option>
                            </select>
                            @error('status')
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

{{-- 🔹 Script Dinamis --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const groupWrapper = document.getElementById('kasubagGroupWrapper');

    function toggleGroup() {
        if (roleSelect.value.toLowerCase() === 'kasubag') {
            groupWrapper.style.display = 'block';
        } else {
            groupWrapper.style.display = 'none';
            document.getElementById('kasubag_group').value = ''; // reset kalau bukan kasubag
        }
    }

    roleSelect.addEventListener('change', toggleGroup);

    // Pastikan tetap tampil kalau user submit gagal (old value masih kasubag)
    toggleGroup();
});
</script>

<style>
    .form-group label { font-weight: 600; }
    .form-control, textarea, select { border-radius: 8px; }
    @media (max-width: 768px) { .col-md-6 { margin-bottom: 1.5rem; } }
</style>
@endsection


<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');
    const icon = toggleButton.querySelector('i');

    toggleButton.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    });

    // Script yang sudah ada tetap dijalankan
    const roleSelect = document.getElementById('role');
    const groupWrapper = document.getElementById('kasubagGroupWrapper');

    function toggleGroup() {
        if (roleSelect.value.toLowerCase() === 'kasubag') {
            groupWrapper.style.display = 'block';
        } else {
            groupWrapper.style.display = 'none';
            document.getElementById('kasubag_group').value = '';
        }
    }

    roleSelect.addEventListener('change', toggleGroup);
    toggleGroup();
});
</script>

