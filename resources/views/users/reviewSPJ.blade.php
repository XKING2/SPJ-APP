@extends('layouts.main')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Review SPJ</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data SPJ</h6>
        </div>

        <div class="card-body">
            <!-- 🔍 Search -->
            <form action="{{ route('reviewSPJ') }}" method="GET" class="form-inline mb-3 d-flex justify-content-end">
                <input type="text" name="search" class="form-control form-control-sm mr-2"
                    placeholder="Cari status atau nomor surat..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-secondary">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>

            <!-- 📋 Tabel -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nomor SPJ</th>
                            <th>Tanggal Dibuat</th>
                            <th>Status Bendahara</th>
                            <th>Status Kasubag</th>
                            <th style="width: 240px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spjs as $spj)
                        @php
                            // Pastikan relation feedbacks sudah eager loaded di controller:
                            // $spjs = Spj::with('feedbacks','pesanan','user')->paginate(...);

                            // Build feedback array yang benar (gunakan nama kolom DB)
                            $feedbackArray = $spj->feedbacks->map(function($f) {
                                return [
                                    'section'    => $f->section,
                                    'field'      => $f->field,
                                    'message'    => $f->message,
                                    'role'       => $f->role,
                                    // format created_at ke string agar JS dapat tampil langsung
                                    'created_at' => optional($f->created_at)->format('d-m-Y H:i'),
                                ];
                            })->toArray();

                            $status1 = $spj->status;
                            $status2 = $spj->status2;
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration + ($spjs->currentPage() - 1) * $spjs->perPage() }}</td>
                            <td>{{ $spj->pesanan->no_surat ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($spj->pesanan->surat_dibuat ?? now())->translatedFormat('d F Y') }}</td>

                            <!-- Status Bendahara -->
                            <td>
                                @if($status1 === 'valid')
                                    <span class="badge badge-success">Disetujui</span>
                                @elseif($status1 === 'belum_valid')
                                    <span class="badge badge-danger">Tidak Disetujui</span>

                                    @if(count($feedbackArray) > 0)
                                        <button type="button"
                                            class="btn btn-link p-0 ml-1 alasan-btn position-relative"
                                            data-feedback='@json($feedbackArray)'
                                            data-role="bendahara"
                                            title="Lihat alasan penolakan (Bendahara)">
                                            <i class="fas fa-bell text-warning"></i>
                                            <span class="notif-badge position-absolute badge badge-danger"
                                                  style="top:-8px; right:-8px; font-size:10px; padding:3px 6px;">
                                                {{ count($feedbackArray) }}
                                            </span>
                                        </button>
                                    @endif
                                @elseif($status1 === 'diajukan')
                                    <span class="badge badge-info">Diajukan</span>
                                @else
                                    <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </td>

                            <!-- Status Kasubag -->
                            <td>
                                @if($status2 === 'valid')
                                    <span class="badge badge-success">Disetujui</span>
                                @elseif($status2 === 'belum_valid')
                                    <span class="badge badge-danger">Tidak Disetujui</span>

                                    @if(count($feedbackArray) > 0)
                                        <button type="button"
                                            class="btn btn-link p-0 ml-1 alasan-btn position-relative"
                                            data-feedback='@json($feedbackArray)'
                                            data-role="kasubag"
                                            title="Lihat alasan penolakan (Kasubag)">
                                            <i class="fas fa-bell text-warning"></i>
                                            <span class="notif-badge position-absolute badge badge-danger"
                                                  style="top:-8px; right:-8px; font-size:10px; padding:3px 6px;">
                                                {{ count($feedbackArray) }}
                                            </span>
                                        </button>
                                    @endif
                                @elseif($status2 === 'diajukan')
                                    <span class="badge badge-info">Diajukan</span>
                                @else
                                    <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td>
                                <div class="d-flex flex-column align-items-center justify-content-center gap-5">

                                    <!-- 🔹 Baris atas -->
                                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-5">
                                        <a href="{{ route('spj.preview', $spj->id) }}" class="btn btn-sm btn-info action-btn">
                                            <i class="fas fa-eye"></i> Preview
                                        </a>

                                        @if(
                                            ($status1 === 'draft' && $status2 === 'draft') ||
                                            ($status1 === 'belum_valid' || $status2 === 'belum_valid')
                                        )
                                            <form action="{{ route('spj.destroy', $spj->id) }}" method="POST" class="form-delete d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger action-btn">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif

                                        @if($status1 === 'valid' && $status2 === 'valid')
                                            <a href="{{ route('spj.cetak', $spj->id) }}" target="_blank" class="btn btn-sm btn-secondary action-btn">
                                                <i class="fas fa-print"></i> Cetak
                                            </a>
                                        @endif
                                    </div>

                                    <!-- 🔹 Baris bawah -->
                                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-5">
                                        @if($status1 === 'draft' || $status1 === 'belum_valid')
                                            <form action="{{ route('spj.submitToBendahara', $spj->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success action-btn">
                                                    <i class="fas fa-paper-plane"></i> Ajukan Ke Bendahara
                                                </button>
                                            </form>
                                        @endif

                                        @if($status1 === 'valid' && ($status2 === null || $status2 === 'belum_valid'))
                                            <form action="{{ route('ajukanKasubag', $spj->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary action-btn">
                                                    <i class="fas fa-paper-plane"></i> Ajukan Ke Kasubag
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Tidak ada data SPJ</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $spjs->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- 🔔 Modal Alasan -->
<div class="modal fade" id="modalAlasan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content animate__animated animate__fadeIn">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-circle mr-2"></i> Alasan Penolakan SPJ
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table class="table table-sm table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>Section</th>
                            <th>Bagian</th>
                            <th>Catatan</th>
                            <th>Pengoreksi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="feedbackTableBody"></tbody>
                </table>
                <div id="feedback-debug" class="small text-muted mt-2" style="display:none;">
                    <strong>Debug payload (lihat console)</strong>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // delegated handler supaya aman kalau tombol dibuat dinamis
    $(document).on('click', '.alasan-btn', function () {
        const feedbacks = $(this).data('feedback');
        console.log("=== RAW FEEDBACK RECEIVED ===");
        console.log(feedbacks);

        const tbody = $('#feedbackTableBody');
        tbody.empty();

        if (!Array.isArray(feedbacks) || feedbacks.length === 0) {
            tbody.append(`<tr><td colspan="5" class="text-center text-muted">Tidak ada alasan penolakan.</td></tr>`);
            $('#modalAlasan').modal('show');
            return;
        }

        feedbacks.forEach(f => {
            // safe read
            const section = f.section ?? '-';
            const field = f.field ?? '-';
            const message = f.message ?? '-';
            const role = f.role ?? '-';
            const waktu = f.created_at ?? '-';

            tbody.append(`
                <tr>
                    <td>${section}</td>
                    <td>${field}</td>
                    <td>${message}</td>
                    <td>${role}</td>
                    <td>${waktu}</td>
                </tr>
            `);
        });

        $('#modalAlasan').modal('show');
    });
</script>

@endsection
