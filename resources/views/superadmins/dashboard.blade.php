@extends('layouts.main3')

@section('pageheads')
<div class="container-fluid px-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12">
            <h1 class="h3 mb-2 text-gray-800">Dashboard Kasubag</h1>
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
                <div class="value">{{ $totalSPJ }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon bg-primary">
                <i class="fas fa-money-bill-wave fa-2x"></i>
            </div>
            <div class="info">
                <div class="label">Data SPJ Tervalidasi</div>
                <div class="value">{{ $spjTervalidasi }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon bg-primary">
                <i class="fas fa-chart-bar fa-2x"></i>
            </div>
            <div class="info">
                <div class="label">Laporan</div>
                <div class="value">{{ $ditolax }}</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="icon bg-primary">
                <i class="fas fa-clock fa-2x"></i>
            </div>
            <div class="info">
                <div class="label">Data SPJ Belum Divalidasi</div>
                <div class="value">{{ $spjBelumValid }}</div>
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
    @if(session('spj_status_list_kasubag'))
    <script>
    Swal.fire({
        title: "SPJ Baru Diajukan",
        html: `
            @php
                $list = session('spj_status_list_kasubag');
            @endphp

            <ul style="text-align:left">
                <li>Terdapat <b>{{ $list->count() }}</b> SPJ baru yang perlu divalidasi.</li>
            </ul>
        `,
        icon: "warning",
        confirmButtonText: "Cek Sekarang"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('Validasi') }}"; 
            }
        });
    </script>
    @endif
@endsection


