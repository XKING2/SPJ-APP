@extends('layouts.main')

@section('pageheads')
<div class="container-fluid px-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12">
            <h1 class="h3 mb-2 text-gray-800">Dashboard</h1>
        </div>
        <div class="col-md-12 col-12 text-md-end text-start mt-2 mt-md-0">
            <a href="{{ route('spj.create') }}" class="btn btn-success btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 me-2"></i> Tambah SPJ
            </a>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="dashboard-grid">

        <!-- Data SPJ -->
        <div class="dashboard-card">
            <div class="icon bg-primary">
                <i class="fas fa-landmark fa-2x"></i>
            </div>
            <div class="info">
                <div class="label">Data SPJ</div>
                <div class="value">{{$user_SPJ}}</div>
            </div>
        </div>

        <!-- Data SPJ Tervalidasi -->
        <div class="dashboard-card">
            <div class="icon bg-primary">
                <i class="fas fa-money-bill-wave fa-2x"></i>
            </div>
            <div class="info">
                <div class="label">Data SPJ Tervalidasi Kasubag</div>
                <div class="value">{{$spjTervalidasikasubag}}</div>
            </div>
        </div>

        <!-- Laporan -->
        <div class="dashboard-card">
            <div class="icon bg-primary">
                <i class="fas fa-chart-bar fa-2x"></i>
            </div>
            <div class="info">
                <div class="label">Laporan</div>
                <div class="value">{{$laporan}}</div>
            </div>
        </div>

        <!-- Data SPJ Belum Divalidasi -->
        <div class="dashboard-card">
            <div class="icon bg-primary">
                <i class="fas fa-clock fa-2x"></i>
            </div>
            <div class="info">
                <div class="label">Data SPJ Divalidasi Bendahara</div>
                <div class="value">{{$spjTervalidasibendahara}}</div>
            </div>
        </div>

    </div>
</div>

{{-- ==== CHAT WIDGET (React Entry Point) ==== --}}
<div id="chat-root"
     style="position: fixed; bottom: 20px; right: 20px; z-index: 99999;">
</div>


@endsection

@vite(['resources/js/app.js'])

{{-- SweetAlert (auto tampil dari session) --}}
@if(session('success'))
    <div data-swal-success="{{ session('success') }}"></div>
@endif

@if($errors->any())
    <div data-swal-errors="{{ implode('|', $errors->all()) }}"></div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const successData = document.querySelector('[data-swal-success]');
    const errorData = document.querySelector('[data-swal-errors]');

    if (successData) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successData.dataset.swalSuccess,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }

    if (errorData) {
        const errorMessages = errorData.dataset.swalErrors.split('|');
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            html: errorMessages.join('<br>'),
            confirmButtonColor: '#d33',
            confirmButtonText: 'Coba Lagi'
        });
    }
});
</script>
