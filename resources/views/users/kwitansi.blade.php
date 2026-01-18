@extends('layouts.main')

@section('pageheads')
<div class="container-fluid px-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12">
            <h1 class="h3 mb-2 text-gray-800">Kelola Data Kwitansi</h1>
        </div>
    </div>

    
</div>
@endsection

@section('content')


<div class="action-cards-wrapper">
                <div class="action-cards-grid">
                    <!-- Baris Atas: 2 Card -->
                    <div class="action-cards-row-top">
                        <form action="{{ route('Kwitansigu') }}" method="GET" class="action-card">
                            @if($notifGU == 1)
                                <span class="badge-notif-dashboard">1</span>
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

                        <form action="{{ route('kwitansils') }}" method="GET" class="action-card">
                            @if($notifLS == 1)
                                <span class="badge-notif-dashboard">1</span>
                            @endif
                            <button type="submit" class="action-card-button">
                                <div class="action-icon-wrapper ls">
                                    <i class="fas fa-file-invoice-dollar"></i>
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
                        <form action="{{ route('kwitansipo') }}" method="GET" class="action-card">
                            @if($notifPO == 1)
                                <span class="badge-notif-dashboard">1</span>
                            @endif
                            <button type="submit" class="action-card-button">
                                <div class="action-icon-wrapper po">
                                    <i class="fas fa-shopping-cart"></i>
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

@section('scripts')
    <script src="{{ asset('js/chats.js') }}"></script>
@endsection





