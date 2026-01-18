@extends('layouts.main3')

@section('pageheads')
<div class="container-fluid px-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12">
            <h1 class="h3 mb-2 text-gray-800">Kelola Data SPJ</h1>
        </div>
    </div>
</div>
@endsection

@section('content')

<!-- Filter Tahun -->
<div class="filter-year-card">
    <form method="GET">
        <div class="filter-year-wrapper">
            <div class="filter-year-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="filter-year-content">
                <label class="filter-year-label">Tahun SPJ</label>
                <select name="tahun" class="filter-year-select" onchange="this.form.submit()">
                    @for ($th = $minTahunDb; $th <= $maxTahunDb; $th++)
                        <option value="{{ $th }}" {{ $tahunDipilih == $th ? 'selected' : '' }}>
                            {{ $th }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>
    </form>
</div>

<!-- Spacer untuk memberikan jarak -->
<div style="margin-bottom: 3rem;"></div>

<!-- Action Cards -->
<div class="action-cards-wrapper" style="padding-bottom: 4rem;">
    <div class="action-cards-grid">
        <!-- Baris Atas: 2 Card -->
        <div class="action-cards-row-top">
            <form action="{{ route('validasigu') }}" method="GET" class="action-card">
                <input type="hidden" name="tahun" value="{{ $tahunDipilih }}">

                @if($notifGU > 0)
                    <span class="badge-notif-dashboard">{{ $notifGU }}</span>
                @endif

                <button type="submit" class="action-card-button">
                    <div class="action-icon-wrapper gu">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Kwitansi SPJ GU</div>
                        <div class="action-description">
                            <i class="fas fa-circle"></i>
                            <span>Ganti Uang</span>
                        </div>
                    </div>
                </button>
            </form>

            <form action="{{ route('validasils') }}" method="GET" class="action-card">
                <input type="hidden" name="tahun" value="{{ $tahunDipilih }}">

                @if($notifLS > 0)
                    <span class="badge-notif-dashboard">{{ $notifLS }}</span>
                @endif

                <button type="submit" class="action-card-button">
                    <div class="action-icon-wrapper gu">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Kwitansi SPJ LS</div>
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
            <form action="{{ route('validasipo') }}" method="GET" class="action-card">
                <input type="hidden" name="tahun" value="{{ $tahunDipilih }}">

                @if($notifPO > 0)
                    <span class="badge-notif-dashboard">{{ $notifPO }}</span>
                @endif

                <button type="submit" class="action-card-button">
                    <div class="action-icon-wrapper gu">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="action-content">
                        <div class="action-title">Kwitansi SPJ PO</div>
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



@endsection