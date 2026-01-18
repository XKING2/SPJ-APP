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
<div class="modern-dashboard-wrapper">
    <div class="dashboard-container">
        <div class="container-fluid px-4">
            <!-- Header -->
            <div class="dashboard-header">
                <h1 class="dashboard-title">Dashboard SPJ</h1>
                <p class="dashboard-subtitle">Kelola Surat Pertanggungjawaban dengan Mudah</p>
            </div>

            <!-- Stats Section - Sekarang di Atas -->
            <div class="stats-wrapper">
                <div class="stats-header">
                    <h2 class="stats-title">Statistik Data SPJ</h2>
                    <p class="stats-subtitle">Ringkasan data dan status SPJ Anda</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon-container">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-label">Total Data SPJ</div>
                        <div class="stat-value">{{ $totalSPJ }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon-container">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-label">Total Perlu Di Validasi</div>
                        <div class="stat-value">{{ $spjBelumValid }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon-container">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-label">SPJ Tervalidasi Bendahara</div>
                        <div class="stat-value">{{ $spjTervalidasi }}</div>
                    </div>

                    

                    <div class="stat-card">
                        <div class="stat-icon-container">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="stat-label">SPJ Ditolak Bendahara</div>
                        <div class="stat-value">{{ $ditolax }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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


