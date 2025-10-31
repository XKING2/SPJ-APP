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
                <div id="selectPptkSection" class="row mb-4 align-items-end">
                    <div class="col-md-6">
                        <label for="pptk_id" class="form-label fw-bold mb-2">Pilih PPTK (Pilih ini Jika Nama PPTK Sama)</label>
                        <select name="pptk_id" id="pptk_id" class="form-control shadow-sm rounded">
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
                <div id="pptkForm" style="display: none;">
                    <div class="row">
                        {{-- Kolom kiri --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama PPTK</label>
                                <input type="text" name="nama_pptk" class="form-control" placeholder="Masukkan nama PPTK">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Golongan PPTK</label>
                                <input type="text" name="gol_pptk" class="form-control" placeholder="Masukkan golongan PPTK">
                            </div>
                        </div>

                        {{-- Kolom kanan --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">NIP PPTK</label>
                                <input type="text" name="nip_pptk" class="form-control" placeholder="Masukkan NIP PPTK">
                            </div>
                        </div>
                    </div>

                    <div class="text-end mb-3">
                        <button type="button" id="cancelNewPptkBtn" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Batal Tambah PPTK
                        </button>
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
                            <label class="form-label fw-bold">Kasubag</label>
                            <input type="text" name="kasubag" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sub Kegiatan</label>
                            <textarea name="subkegiatan" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 🔹 Script Dinamis --}}
<script>
document.getElementById('pptk_id').addEventListener('change', function() {
    const pptkForm = document.getElementById('pptkForm');
    pptkForm.style.display = this.value ? 'none' : 'block';
});

document.getElementById('addNewPptkBtn').addEventListener('click', function() {
    document.getElementById('selectPptkSection').style.display = 'none';
    document.getElementById('pptkForm').style.display = 'block';
});

document.getElementById('cancelNewPptkBtn').addEventListener('click', function() {
    document.getElementById('selectPptkSection').style.display = 'flex';
    document.getElementById('pptkForm').style.display = 'none';
    document.getElementById('pptk_id').value = '';
});
</script>
@endsection
