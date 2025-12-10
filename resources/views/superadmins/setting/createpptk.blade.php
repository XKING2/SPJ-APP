@extends('layouts.main3')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Tambah Data PPTK & Kegiatan</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('pptk.store') }}" method="POST">
                @csrf

                {{-- 🔹 PILIH PPTK YANG SUDAH ADA --}}
                <div id="existingPptkSection" class="row mb-4 align-items-end">
                    <div class="col-md-6">
                        <label for="id_pptk" class="form-label fw-bold mb-2">
                            Pilih PPTK (jika sudah ada)
                        </label>
                        <select name="id_pptk" id="id_pptk" class="form-control shadow-sm rounded">
                            <option value="">-- Pilih PPTK yang sudah ada --</option>
                            @foreach($pptks as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_pptk }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-6 text-end">
                        <button type="button" id="addNewPptkBtn" class="btn btn-outline-primary mt-3">
                            <i class="bi bi-person-plus"></i> Tambah PPTK Baru
                        </button>
                    </div>
                </div>

                {{-- 🔹 FORM PPTK BARU --}}
                <div id="newPptkForm" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            {{-- Pilih user dari tabel users --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Nama dari User</label>
                                <select id="selectUser" name="user_id" class="form-control" required>
                                    <option value="" disabled selected>-- Pilih Nama User --</option>
                                    @foreach ($users as $user)
                                        <option 
                                            value="{{ $user->id }}"
                                            data-nama="{{ $user->nama }}"
                                            data-nip="{{ $user->NIP }}"
                                            data-gol="{{ $user->idinjab }}">
                                            {{ $user->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Nama PPTK otomatis terisi --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama PPTK</label>
                                <input type="text" id="nama_pptk" name="nama_pptk" class="form-control" readonly>
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Idinjab PPTK</label>
                                <input type="text" id="idinjab_pptk" name="idinjab_pptk" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- NIP otomatis --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">NIP PPTK</label>
                                <input type="text" id="nip_pptk" name="nip_pptk" class="form-control" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Golongan PPTK</label>
                                <input type="text" id="gol_pptk" name="gol_pptk" class="form-control" required>
                            </div>

                            <div class="text-end mb-3 mt-4">
                                <button type="button" id="cancelNewPptkBtn" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Batal Tambah PPTK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- 🔹 FORM KEGIATAN --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Program</label>
                            <input type="text" name="program" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kegiatan</label>
                            <input type="text" name="kegiatan" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sub Kegiatan</label>
                            <textarea name="subkegiatan" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Rek Sub Kegiatan</label>
                            <input name="no_rek_sub" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('showpptk') }}" class="btn btn-secondary px-4 py-2">
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

{{-- 🔹 SCRIPT DINAMIS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Elemen utama
    const selectExisting = document.getElementById('id_pptk');// kamu salah ID tadi
    const newForm = document.getElementById('newPptkForm');
    const existingSection = document.getElementById('existingPptkSection');

    // Tombol
    const addBtn = document.getElementById('addNewPptkBtn');
    const cancelBtn = document.getElementById('cancelNewPptkBtn');

    // Field PPTK baru
    const selectUser = document.getElementById('selectUser');
    const namaPptk = document.getElementById('nama_pptk');
    const nipPptk = document.getElementById('nip_pptk');
    const idinjab = document.getElementById('idinjab_pptk');
    const golPptk = document.getElementById('gol_pptk');

    // === FUNCTION UTAMA UNTUK VALIDASI ===
    function togglePptkFields(isNew) {
        const fields = document.querySelectorAll('#newPptkForm input, #newPptkForm select');

        fields.forEach(f => {
            if (isNew) {
                f.disabled = false;
                f.required = true;  // aktif validasi
            } else {
                f.disabled = true;
                f.required = false; // matikan validasi
            }
        });
    }

    // Default: matikan form PPTK baru
    togglePptkFields(false);

    // === KLIK TAMBAH PPTK BARU ===
    addBtn.addEventListener('click', () => {
        existingSection.style.display = 'none';
        newForm.style.display = 'block';
        togglePptkFields(true);
    });

    // === KLIK BATAL TAMBAH PPTK BARU ===
    cancelBtn.addEventListener('click', () => {
        newForm.style.display = 'none';
        existingSection.style.display = 'flex';

        // Reset field
        selectUser.value = '';
        namaPptk.value = '';
        nipPptk.value = '';
        idinjab.value = '';
        golPptk.value = '';

        togglePptkFields(false);
    });

    // === JIKA PILIH PPTK LAMA ===
    selectExisting.addEventListener('change', () => {
        newForm.style.display = 'none';
        existingSection.style.display = 'flex';
        togglePptkFields(false);  // pastikan field baru tidak required
    });

    // === SAAT USER DIPILIH UNTUK PPTK BARU ===
    selectUser.addEventListener('change', function () {
        let opt = this.options[this.selectedIndex];
        namaPptk.value = opt.dataset.nama || '';
        nipPptk.value = opt.dataset.nip || '';
        idinjab.value = opt.dataset.gol || '';
    });

});
</script>

@endsection
