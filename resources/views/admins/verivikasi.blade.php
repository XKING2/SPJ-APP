@extends('layouts.main2')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Validasi SPJ</h1>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar SPJ</h6>
        </div>
        <div class="card-body">
            <!-- Search & Print -->
            <div class="d-flex justify-content-between mb-3">
                <form action="#" method="GET" class="form-inline">
                    <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Cari...">
                    <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nomor SPJ</th>
                            <th>Tanggal Surat Dibuat</th>
                            <th>Status Validasi Bendahara</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($spjs as $spj)
                        <tr>
                            <td>{{ $loop->iteration + ($spjs->currentPage() - 1) * $spjs->perPage() }}</td>
                            <td>{{ $spj->pesanan->no_surat ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($spj->pesanan->surat_dibuat ?? now())->translatedFormat('d F Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    <form action="{{ route('updateStatusbendahara', $spj->id) }}" method="POST" class="d-inline" id="form-{{ $spj->id }}">
                                        @csrf
                                        <div class="dropdown">
                                            @php
                                                $badgeClass = 'bg-warning text-dark';
                                                $badgeText = 'Draft';
                                                if ($spj->status == 'valid') {
                                                    $badgeClass = 'bg-success text-white';
                                                    $badgeText = 'Valid';
                                                } elseif ($spj->status == 'belum_valid') {
                                                    $badgeClass = 'bg-danger text-white';
                                                    $badgeText = 'Tidak Disetujui';
                                                } elseif ($spj->status == 'draft') {
                                                    $badgeClass = 'bg-warning text-dark';
                                                    $badgeText = 'Draft';
                                                }
                                            @endphp

                                            <button class="badge {{ $badgeClass }} dropdown-toggle border-0" type="button" id="dropdownMenuButton{{ $spj->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor:pointer;">
                                                {{ $badgeText }}
                                            </button>

                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $spj->id }}">
                                                <a class="dropdown-item status-option" href="#" data-id="{{ $spj->id }}" data-status="draft">Draft</a>
                                                <a class="dropdown-item status-option" href="#" data-id="{{ $spj->id }}" data-status="valid">Valid</a>
                                                <a class="dropdown-item status-option text-danger" href="#" data-id="{{ $spj->id }}" data-status="belum_valid">Tidak Disetujui</a>
                                            </div>
                                        </div>
                                        <input type="hidden" name="status" id="status_{{ $spj->id }}" value="{{ $spj->status }}">
                                        <input type="hidden" name="komentar_bendahara" id="komentar_{{ $spj->id }}">
                                    </form>
                                </div>
                            </td>
                            
                            <td>{{ $spj->user->nama ?? '-' }}</td>
                            <td>
                                <a href="{{ route('previewadmin', ['id' => $spj->id]) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Preview
                                </a>
                                @if ($spj->status === 'valid' && $spj->status2 !== 'valid' && $spj->status2 !== 'diajukan')
                                    <form action="{{ route('ajukanKasubag', $spj->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-paper-plane"></i> Ajukan ke Kasubag
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Data tidak tersedia</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Modal Komentar -->
<div class="modal fade" id="komentarModal" tabindex="-1" role="dialog" aria-labelledby="komentarModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="komentarForm">
        <div class="modal-header">
          <h5 class="modal-title" id="komentarModalLabel">Alasan Penolakan SPJ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <textarea id="komentarText" class="form-control" rows="4" placeholder="Tuliskan alasan kenapa SPJ ini tidak disetujui..." required></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Kirim</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedId = null;

    // 🔄 Dropdown status & komentar
    document.querySelectorAll('.status-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const status = this.dataset.status;
            document.getElementById(`status_${id}`).value = status;

            if (status === 'belum_valid') {
                selectedId = id;
                $('#komentarModal').modal('show');
            } else {
                document.getElementById(`form-${id}`).submit();
            }
        });
    });

    // 💬 Form komentar
    document.getElementById('komentarForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const komentar = document.getElementById('komentarText').value.trim();
        if (komentar === '') {
            alert('Komentar tidak boleh kosong.');
            return;
        }
        document.getElementById(`komentar_${selectedId}`).value = komentar;
        $('#komentarModal').modal('hide');
        document.getElementById(`form-${selectedId}`).submit();
    });

    // 📨 Konfirmasi pengajuan ke Kasubag
    document.querySelectorAll('form[action*="ajukanKasubag"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Ajukan ke Kasubag?',
                text: 'SPJ ini akan dikirim untuk proses validasi Kasubag.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, ajukan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // ✅ SweetAlert Notifikasi sukses
    const successMessage = document.querySelector('[data-swal-success]');
    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMessage.getAttribute('data-swal-success'),
            timer: 2500,
            showConfirmButton: false
        });
    }

    // ❗ SweetAlert Notifikasi error
    const errorMessage = document.querySelector('[data-swal-errors]');
    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            text: errorMessage.getAttribute('data-swal-errors').replaceAll('|', '\n'),
        });
    }
});
</script>






