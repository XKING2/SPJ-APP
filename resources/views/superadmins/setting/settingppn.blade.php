@extends('layouts.main3')

@section('pageheads')
<div class="container-fluid px-4">
    <h1 class="h3 mb-3">Pengaturan</h1>
</div>
@endsection

@section('content')
<div class="container-fluid">

    {{-- 🔹 BAGIAN 1: FORM UPDATE PPN --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white fw-bold">
            Pengaturan Nilai PPN
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="ppn_rate" class="form-label fw-bold">Nilai PPN (%)</label>
                    <input type="number" name="ppn_rate" id="ppn_rate" class="form-control"
                           value="{{ old('ppn_rate', $ppn->value ?? '') }}" min="0" max="100">
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>


    {{-- 🔹 BAGIAN 2: FORM UPDATE PPH 22 DAN PPH 23 --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            Pengaturan Nilai PPh
        </div>
        <div class="card-body">

            <form action="{{ route('settings.update') }}" method="POST">
                @csrf

                {{-- PPh 22 --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">PPh 22 (%)</label>
                    <input type="number" step="0.01" name="pph[pph_22]" class="form-control w-25"
                        value="{{ old('pph.pph_22', $pph_list['pph_22'] ?? '') }}">
                </div>

                {{-- PPh 23 --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">PPh 23 (%)</label>
                    <input type="number" step="0.01" name="pph[pph_23]" class="form-control w-25"
                        value="{{ old('pph.pph_23', $pph_list['pph_23'] ?? '') }}">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan PPh
                </button>

            </form>

        </div>
    </div>


</div>
@endsection
