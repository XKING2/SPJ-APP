@extends('layouts.main')

@section('pageheads')
<div class="container-fluid px-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12">
            <h1 class="h3 mb-2 text-gray-800">Kelola Data Pesanan</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
        <div class="action-cards-grid">
                    <!-- Baris Atas: 2 Card -->
                    <div class="action-cards-row-top">
                        <form action="{{ route('pesanangu') }}" method="GET" class="action-card">
                            @if($notifGU == 1)
                                <span class="badge-notif-dashboard">1</span>
                            @endif
                            <button type="submit" class="action-card-button">
                                <div class="action-icon-wrapper gu">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Pesanan SPJ GU</div>
                                    <div class="action-description">
                                        <i class="fas fa-circle"></i>
                                        <span>Ganti Uang</span>
                                    </div>
                                </div>
                            </button>
                        </form>

                        <form action="{{ route('pesananls') }}" method="GET" class="action-card">
                            @if($notifLS == 1)
                                <span class="badge-notif-dashboard">1</span>
                            @endif
                            <button type="submit" class="action-card-button">
                                <div class="action-icon-wrapper ls">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div class="action-content">
                                    <div class="action-title">Pesanan SPJ LS</div>
                                    <div class="action-description">
                                        <i class="fas fa-circle"></i>
                                        <span>Langsung</span>
                                    </div>
                                </div>
                            </button>
                        </form>
                    </div>
            </div>




@endsection

