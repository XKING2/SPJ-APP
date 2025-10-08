@extends('layouts.main3')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Preview SPJ</h1>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Preview SPJ</h6>
            <a href="{{ route('Validasi') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-times"></i> Tutup Preview
            </a>
        </div>

        <div class="card-body">
            <!-- Info Singkat -->
            <div class="mb-3">
                <h6 class="text-primary mb-1">
                    Nomor SPJ: <strong>{{ $spj->pesanan->no_surat ?? 'Tanpa Nomor' }}</strong>
                </h6>
                <p class="mb-0">
                    Tanggal Dibuat: {{ \Carbon\Carbon::parse($spj->pesanan->surat_dibuat ?? now())->translatedFormat('d F Y') }}
                </p>
            </div>

            <!-- PDF Viewer -->
            <div id="pdf-viewer" class="mt-3">
                <iframe 
                    id="pdf-frame" 
                    src="{{ $fileUrl }}#toolbar=0&navpanes=0&scrollbar=0" 
                    width="100%" 
                    height="650px" 
                    style="border:1px solid #ccc; border-radius: 8px;">
                </iframe>
            </div>

            <!-- Tombol Tutup -->
            <div class="mt-3 d-flex justify-content-end">
                <a href="{{ route('reviewSPJ') }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-times"></i> Tutup Preview
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
