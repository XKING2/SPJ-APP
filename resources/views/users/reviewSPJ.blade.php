@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Review Surat Pertanggungjawaban (SPJ)</h3>

    <div class="card shadow-sm p-3 mb-4">
        <h5 class="mb-3">Data SPJ</h5>
        <table class="table table-bordered">
            <tr>
                <th>No Surat</th>
                <td>{{ $spj->no_surat }}</td>
            </tr>
            <tr>
                <th>Tanggal Surat</th>
                <td>{{ $spj->tanggal_surat }}</td>
            </tr>
            <tr>
                <th>Nama PT</th>
                <td>{{ $spj->nama_pt }}</td>
            </tr>
            <tr>
                <th>Alamat PT</th>
                <td>{{ $spj->alamat_pt }}</td>
            </tr>
            <tr>
                <th>Telpon PT</th>
                <td>{{ $spj->nomor_tlp_pt }}</td>
            </tr>
            <tr>
                <th>Pekerjaan</th>
                <td>{{ $spj->pekerjaan }}</td>
            </tr>
            <tr>
                <th>Pihak Kedua</th>
                <td>{{ $spj->nama_pihak_kedua }} ({{ $spj->jabatan_pihak_kedua }})</td>
            </tr>
            <tr>
                <th>Subtotal</th>
                <td>Rp {{ number_format($spj->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>PPN</th>
                <td>Rp {{ number_format($spj->ppn, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Grand Total</th>
                <td>Rp {{ number_format($spj->grandtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Dibulatkan</th>
                <td>Rp {{ number_format($spj->dibulatkan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Terbilang</th>
                <td>{{ $spj->terbilang }}</td>
            </tr>
        </table>
    </div>

    <div class="d-flex gap-2">
        {{-- Tombol Preview PDF --}}
        <a href="{{ route('spj.preview', $spj->id) }}" target="_blank" class="btn btn-primary">
            <i class="fas fa-file-pdf"></i> Preview PDF
        </a>

        {{-- Tombol Download Word --}}
        <a href="{{ route('spj.download', $spj->id) }}" class="btn btn-success">
            <i class="fas fa-file-word"></i> Download Word
        </a>
    </div>
</div>
@endsection
