@extends('layouts.main')

@section('pageheads')


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
                        <div class="stat-value">{{ $user_SPJ }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon-container">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-label">SPJ Tervalidasi Kasubag</div>
                        <div class="stat-value">{{ $spjTervalidasikasubag }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon-container">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-label">Total Laporan</div>
                        <div class="stat-value">{{ $laporan }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon-container">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="stat-label">SPJ Divalidasi Bendahara</div>
                        <div class="stat-value">{{ $spjTervalidasibendahara }}</div>
                    </div>
                </div>
            </div>

            <!-- Action Cards - Layout Tengah -->
            <div class="action-cards-wrapper">
                <div class="action-cards-grid">
                    <!-- Baris Atas: 2 Card -->
                    <div class="action-cards-row-top">
                        <form action="{{ route('spj.store') }}" method="POST" class="action-card">
                            @csrf
                            <input type="hidden" name="types" value="GU">
                            <button type="submit" class="action-card-button">
                                <div class="action-icon-wrapper gu">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Tambah SPJ GU</div>
                                    <div class="action-description">
                                        <i class="fas fa-circle"></i>
                                        <span>Ganti Uang</span>
                                    </div>
                                </div>
                            </button>
                        </form>

                        <form action="{{ route('spj.store') }}" method="POST" class="action-card">
                            @csrf
                            <input type="hidden" name="types" value="LS">
                            <button type="submit" class="action-card-button">
                                <div class="action-icon-wrapper ls">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Tambah SPJ LS</div>
                                    <div class="action-description">
                                        <i class="fas fa-circle"></i>
                                        <span>Langsung</span>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>

                    <!-- Baris Bawah: 1 Card di Tengah -->
                    <div class="action-cards-row-bottom">
                        <form action="{{ route('spj.store') }}" method="POST" class="action-card">
                            @csrf
                            <input type="hidden" name="types" value="PO">
                            <button type="submit" class="action-card-button">
                                <div class="action-icon-wrapper po">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Tambah SPJ PO</div>
                                    <div class="action-description">
                                        <i class="fas fa-circle"></i>
                                        <span>Preorder</span>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
    <script src="{{ asset('js/chats.js') }}"></script>

    @if(session('spj_status_list_user'))
    <script>
    Swal.fire({
        title: "Status SPJ Anda",
        html: `
            @php
                $list = session('spj_status_list_user');
                $valid_bendahara = $list->where('status', 'valid')->count();
                $valid_kasubag   = $list->where('status2', 'valid')->count();
                $tolak_bendahara = $list->where('status', 'belum_valid')->count();
                $tolak_kasubag   = $list->where('status2', 'belum_valid')->count();
                $diajukan = $list->filter(function ($item) {
                    return $item->status === 'diajukan' || $item->status2 === 'diajukan';
                })->count();
            @endphp

            <ul style="text-align:left; list-style:none; padding-left:0;">
                @if($valid_bendahara > 0)
                    <li style="padding:0.5rem 0; border-bottom:1px solid #e2e8f0;">
                        <i class="fas fa-check-circle" style="color:#48bb78; margin-right:0.5rem;"></i>
                        <b>{{ $valid_bendahara }}</b> SPJ divalidasi Bendahara
                    </li>
                @endif

                @if($valid_kasubag > 0)
                    <li style="padding:0.5rem 0; border-bottom:1px solid #e2e8f0;">
                        <i class="fas fa-check-circle" style="color:#48bb78; margin-right:0.5rem;"></i>
                        <b>{{ $valid_kasubag }}</b> SPJ divalidasi Kasubag
                    </li>
                @endif

                @if($tolak_bendahara > 0)
                    <li style="padding:0.5rem 0; border-bottom:1px solid #e2e8f0;">
                        <i class="fas fa-times-circle" style="color:#f56565; margin-right:0.5rem;"></i>
                        <b>{{ $tolak_bendahara }}</b> SPJ ditolak Bendahara
                    </li>
                @endif

                @if($tolak_kasubag > 0)
                    <li style="padding:0.5rem 0; border-bottom:1px solid #e2e8f0;">
                        <i class="fas fa-times-circle" style="color:#f56565; margin-right:0.5rem;"></i>
                        <b>{{ $tolak_kasubag }}</b> SPJ ditolak Kasubag
                    </li>
                @endif

                @if($diajukan > 0)
                    <li style="padding:0.5rem 0;">
                        <i class="fas fa-clock" style="color:#ed8936; margin-right:0.5rem;"></i>
                        <b>{{ $diajukan }}</b> SPJ sedang diajukan
                    </li>
                @endif
            </ul>
        `,
        icon: "info",
        confirmButtonText: "Cek Sekarang",
        confirmButtonColor: "#667eea",
        customClass: {
            popup: 'swal-custom-popup',
            confirmButton: 'swal-custom-button'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('reviewSPJ') }}";
        }
    });
    </script>
    @endif
@endsection