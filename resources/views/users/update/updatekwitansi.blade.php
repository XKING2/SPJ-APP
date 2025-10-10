@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Edit Data Kwitansi</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('kwitansi.update', $kwitansi->id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Rekening</label>
                            <input type="text" name="no_rekening" class="form-control"
                                value="{{ old('no_rekening', $kwitansi->no_rekening) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Rekening Tujuan</label>
                            <input type="text" name="no_rekening_tujuan" class="form-control"
                                value="{{ old('no_rekening_tujuan', $kwitansi->no_rekening_tujuan) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Bank</label>
                            <input type="text" name="nama_bank" class="form-control"
                                value="{{ old('nama_bank', $kwitansi->nama_bank) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Yang Menerima Kwitansi</label>
                            <input type="text" name="penerima_kwitansi" class="form-control"
                                value="{{ old('penerima_kwitansi', $kwitansi->penerima_kwitansi) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sub Kegiatan</label>
                            <textarea name="sub_kegiatan" class="form-control" rows="5"
                                required>{{ old('sub_kegiatan', $kwitansi->sub_kegiatan) }}</textarea>
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Telah Diterima Dari</label>
                            <input type="text" name="telah_diterima_dari" class="form-control"
                                value="{{ old('telah_diterima_dari', $kwitansi->telah_diterima_dari) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Nominal</label>
                            <input type="number" name="jumlah_nominal" id="jumlah_nominal"
                                class="form-control" value="{{ old('jumlah_nominal', $kwitansi->jumlah_nominal) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Uang Terbilang</label>
                            <input type="text" name="uang_terbilang" id="uang_terbilang"
                                class="form-control" value="{{ old('uang_terbilang', $kwitansi->uang_terbilang) }}" readonly required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Penerima Kwitansi</label>
                            <input type="text" name="jabatan_penerima" class="form-control"
                                value="{{ old('jabatan_penerima', $kwitansi->jabatan_penerima) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">NPWP</label>
                            <input type="text" name="npwp" class="form-control"
                                value="{{ old('npwp', $kwitansi->npwp) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama PT</label>
                            <input type="text" name="nama_pt" class="form-control"
                                value="{{ old('nama_pt', $kwitansi->nama_pt) }}" required>
                        </div>
                    </div>
                </div>

                <!-- Untuk Pembayaran -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Untuk Pembayaran</label>
                    <textarea name="pembayaran" class="form-control" rows="3"
                        required>{{ old('pembayaran', $kwitansi->pembayaran) }}</textarea>
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('kwitansi') }}" class="btn btn-secondary">
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const nominalInput = document.getElementById("jumlah_nominal");
    const terbilangInput = document.getElementById("uang_terbilang");

    nominalInput.addEventListener("input", function () {
        const angka = parseInt(this.value) || 0;
        terbilangInput.value = terbilangRupiah(angka);
    });

    function terbilangRupiah(angka) {
        const satuan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan"];
        const belasan = ["Sepuluh", "Sebelas", "Dua Belas", "Tiga Belas", "Empat Belas", "Lima Belas", "Enam Belas", "Tujuh Belas", "Delapan Belas", "Sembilan Belas"];
        const puluhan = ["", "", "Dua Puluh", "Tiga Puluh", "Empat Puluh", "Lima Puluh", "Enam Puluh", "Tujuh Puluh", "Delapan Puluh", "Sembilan Puluh"];
        const ribuan = ["", "Ribu", "Juta", "Miliar", "Triliun"];

        if (angka === 0) return "Nol Rupiah";

        function konversi(num) {
            let str = "";
            if (num >= 100) {
                str += (Math.floor(num / 100) === 1 ? "Seratus " : satuan[Math.floor(num / 100)] + " Ratus ");
                num %= 100;
            }
            if (num >= 10 && num <= 19) {
                str += belasan[num - 10] + " ";
            } else if (num >= 20) {
                str += puluhan[Math.floor(num / 10)] + " " + satuan[num % 10] + " ";
            } else {
                str += satuan[num] + " ";
            }
            return str.trim();
        }

        let result = "";
        let i = 0;
        while (angka > 0) {
            const chunk = angka % 1000;
            if (chunk > 0) {
                let chunkStr = konversi(chunk);
                if (i === 1 && chunk === 1) chunkStr = "Seribu";
                result = chunkStr + " " + ribuan[i] + " " + result;
            }
            angka = Math.floor(angka / 1000);
            i++;
        }
        return result.trim() + " Rupiah";
    }
});
</script>
@endsection
