@extends('layouts.main')

@section('pageheads')
<div class="container">
    <h4 class="mb-1">Edit Data Pesanan</h4>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('pesanan.update', $pesanan->id) }}" method="POST" id="pesananForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="spj_id" value="{{ $spj->id }}">

                <div class="row">
                    <!-- Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No Surat</label>
                            <input type="text" name="no_surat" class="form-control" 
                                value="{{ old('no_surat', $pesanan->no_surat) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama PT</label>
                            <input type="text" name="nama_pt" class="form-control" 
                                value="{{ old('nama_pt', $pesanan->nama_pt) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat PT</label>
                            <input type="text" name="alamat_pt" class="form-control" 
                                value="{{ old('alamat_pt', $pesanan->alamat_pt) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Surat Dibuat</label>
                            <input type="date" name="surat_dibuat" class="form-control" 
                                value="{{ old('surat_dibuat', $pesanan->surat_dibuat) }}">
                        </div>
                    </div>

                    <!-- Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Barang Diterima</label>
                            <input type="date" name="tanggal_diterima" class="form-control"
                                value="{{ old('tanggal_diterima', $pesanan->tanggal_diterima) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Telpon PT</label>
                            <input type="number" name="nomor_tlp_pt" class="form-control" 
                                value="{{ old('nomor_tlp_pt', $pesanan->nomor_tlp_pt) }}">
                        </div>
                    </div>
                </div>

                <!-- Tabel Barang -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Tabel Barang Pesanan</label>
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40%">Nama Barang</th>
                                <th style="width: 25%">Jumlah</th>
                                <th style="width: 10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="items-table">
                            @foreach($pesanan->items as $index => $item)
                                <tr>
                                    <td>
                                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                        <input type="text" class="form-control" name="items[{{ $index }}][nama_barang]" 
                                            value="{{ $item->nama_barang }}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="items[{{ $index }}][jumlah]" 
                                            value="{{ $item->jumlah }}">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-sm btn-primary" id="addRowBtn"
                        data-item-count="{{ $pesanan->items->count() }}">
                        <i class="bi bi-plus-circle"></i> Tambah Barang
                    </button>
                </div>

                <!-- Tombol Submit -->
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('pesanan') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ✅ FIXED SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ✅ Ambil jumlah item dari attribute HTML (aman untuk Blade)
    let addBtn = document.getElementById('addRowBtn');
    let itemIndex = parseInt(addBtn.dataset.itemCount, 10) || 0;

    window.removeRow = function (btn) {
        btn.closest('tr').remove();
    }

    addBtn.addEventListener('click', function () {
        const table = document.getElementById('items-table');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>
                <input type="hidden" name="items[${itemIndex}][id]" value="">
                <input type="text" class="form-control" name="items[${itemIndex}][nama_barang]" placeholder="Nama Barang">
            </td>
            <td>
                <input type="number" class="form-control" name="items[${itemIndex}][jumlah]" placeholder="Jumlah">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </td>
        `;
        table.appendChild(row);
        itemIndex++;
    });
});
</script>
@endsection
