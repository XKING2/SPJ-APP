@extends('layouts.main2')

@section('pageheads')
<div class="container-fluid px-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12">
            <h1 class="h3 mb-2 text-gray-800">Dashboard Bendahara</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="icon bg-primary">
                <i class="fas fa-landmark fa-2x"></i>
            </div>
            <div class="info">
                <div class="label">Data SPJ</div>
                <div class="value">{{ $totalSPJs }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon bg-primary">
                <i class="fas fa-money-bill-wave fa-2x"></i>
            </div>
            <div class="info">
                <div class="label">Data SPJ Tervalidasi</div>
                <div class="value">{{ $spjTervalidasis }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon bg-primary">
                <i class="fas fa-chart-bar fa-2x"></i>
            </div>
            <div class="info">
                <div class="label">Data SPJ Ditolak</div>
                <div class="value">{{ $ditolak }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon bg-primary">
                <i class="fas fa-clock fa-2x"></i>
            </div>
            <div class="info">
                <div class="label">Data SPJ Perlu Divalidasi</div>
                <div class="value">{{ $spjperludivalidasi }}</div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentUserId = JSON.parse('@json(auth()->id())');
    window.currentUserId = currentUserId;
</script>

@include('chats.chat_widget')

@endsection

@section('scripts')
    <script src="{{ asset('js/chats.js') }}"></script>
@endsection

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
