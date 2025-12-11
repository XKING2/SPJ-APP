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


<div class="dashboard-grid mt-3">

        <form action="{{ route('pesanangu') }}" method="GET" class="dashboard-card">
            <button type="submit" class="btn w-100 d-flex p-0" style="background:none;border:none; position:relative;">

                @if($notifGU == 1)
                    <span class="badge-notif-dashboard">1</span>
                @endif

                <div class="icon bg-primary">
                    <i class="fas fa-file-invoice-dollar fa-2x"></i>
                </div>
                <div class="info">
                    <div class="label">Lihat Data Pesanan GU</div>
                    <div class="value">General Umum</div>
                </div>
            </button>
        </form>

        <form action="{{ route('pesananls') }}" method="GET" class="dashboard-card">
            <button type="submit" class="btn w-100 d-flex p-0" style="background:none;border:none; position:relative;">

                @if($notifLS == 1)
                    <span class="badge-notif-dashboard">1</span>
                @endif

                <div class="icon bg-success">
                    <i class="fas fa-file-contract fa-2x"></i>
                </div>
                <div class="info">
                    <div class="label">Lihat Data Pesanan LS</div>
                    <div class="value">Langsung</div>
                </div>
            </button>
        </form>



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
