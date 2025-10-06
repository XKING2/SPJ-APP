@extends('layouts.main')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Preview SPJ - {{ $spj->nomor_spj ?? 'Tanpa Nomor' }}</h1>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Preview Surat Pertanggungjawaban</h6>
            <a href="{{ $publicUrl }}" class="btn btn-success btn-sm" download>
                <i class="fas fa-file-word"></i> Download File
            </a>
        </div>

        <div class="card-body">
            <iframe 
                src="{{ $googleViewerUrl }}" 
                style="width:100%; height:80vh; border:none;" 
                frameborder="0">
            </iframe>
        </div>
    </div>

</div>
@endsection
