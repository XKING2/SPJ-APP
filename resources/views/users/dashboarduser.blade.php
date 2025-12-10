@extends('layouts.main')

@section('pageheads')
<div class="container-fluid px-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6 col-12">
            <h1 class="h3 mb-2 text-gray-800">Dashboard</h1>
        </div>
    </div>

    <!-- Bagian Card SPJ -->
    <div class="dashboard-grid mt-3">

        <form action="{{ route('spj.store') }}" method="POST" class="dashboard-card text-decoration-none">
            @csrf
            <input type="hidden" name="types" value="gu">
            <button type="submit" class="btn w-100 d-flex p-0" style="background:none;border:none;">
                <div class="icon bg-primary">
                    <i class="fas fa-file-invoice-dollar fa-2x"></i>
                </div>
                <div class="info">
                    <div class="label">Tambah SPJ LS</div>
                    <div class="value">Langsung</div>
                </div>
            </button>
        </form>


        <form action="{{ route('spj.store') }}" method="POST" class="dashboard-card text-decoration-none">
            @csrf
            <input type="hidden" name="types" value="ls">
            <button type="submit" class="btn w-100 d-flex p-0" style="background:none;border:none;">
                <div class="icon bg-success">
                    <i class="fas fa-file-contract fa-2x"></i>
                </div>
                <div class="info">
                    <div class="label">Tambah SPJ GU</div>
                    <div class="value">General Umum</div>
                </div>
            </button>
        </form>


    </div>
</div>
@endsection

@section('content')


<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="dashboard-grid">

        <div class="dashboard-card">
            <div class="icon bg-primary"><i class="fas fa-landmark fa-2x"></i></div>
            <div class="info"><div class="label">Data SPJ</div><div class="value">{{$user_SPJ}}</div></div>
        </div>

        <div class="dashboard-card">
            <div class="icon bg-primary"><i class="fas fa-money-bill-wave fa-2x"></i></div>
            <div class="info"><div class="label">Data SPJ Tervalidasi Kasubag</div><div class="value">{{$spjTervalidasikasubag}}</div></div>
        </div>

        <div class="dashboard-card">
            <div class="icon bg-primary"><i class="fas fa-chart-bar fa-2x"></i></div>
            <div class="info"><div class="label">Laporan</div><div class="value">{{$laporan}}</div></div>
        </div>

        <div class="dashboard-card">
            <div class="icon bg-primary"><i class="fas fa-clock fa-2x"></i></div>
            <div class="info"><div class="label">Data SPJ Divalidasi Bendahara</div><div class="value">{{$spjTervalidasibendahara}}</div></div>
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

            <ul style="text-align:left">
                @if($valid_bendahara > 0)
                    <li><b>{{ $valid_bendahara }}</b> SPJ divalidasi Bendahara.</li>
                @endif

                @if($valid_kasubag > 0)
                    <li><b>{{ $valid_kasubag }}</b> SPJ divalidasi Kasubag.</li>
                @endif

                @if($tolak_bendahara > 0)
                    <li><b>{{ $tolak_bendahara }}</b> SPJ ditolak Bendahara.</li>
                @endif

                @if($tolak_kasubag > 0)
                    <li><b>{{ $tolak_kasubag }}</b> SPJ ditolak Kasubag.</li>
                @endif

                @if($diajukan > 0)
                    <li><b>{{ $diajukan }}</b> SPJ sedang diajukan.</li>
                @endif
            </ul>

        `,
        icon: "info",
        confirmButtonText: "Cek Sekarang",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('reviewSPJ') }}";
        }
    });
    </script>
    @endif
@endsection





