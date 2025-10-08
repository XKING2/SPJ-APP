@extends('layouts.main3')

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
                <a href="#" target="_blank" class="btn btn-info btn-sm">
                    <i class="fas fa-print"></i> Cetak
                </a>
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
                            <th>Status Validasi Kasubag</th>
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
                                @if ($spj->status == 'valid')
                                    <span class="badge bg-success text-white">Valid</span>
                                @elseif ($spj->status == 'draft')
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @else
                                    <span class="badge bg-secondary text-white">Belum Valid</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    @if ($spj->status2 == 'valid')
                                        <span class="badge bg-success text-white me-2">Valid</span>
                                    @elseif ($spj->status2 == 'belum_valid')
                                        <span class="badge bg-danger text-white me-2">Tidak Disetujui</span>
                                    @else
                                        <span class="badge bg-warning text-dark me-2">Draft</span>
                                    @endif

                                    <form action="{{ route('updateStatusKasubag', $spj->id) }}" method="POST" class="d-inline" id="form-{{ $spj->id }}">
                                        @csrf
                                        <select name="status2" class="form-select form-select-sm status-dropdown" data-id="{{ $spj->id }}">
                                            <option value="draft" {{ $spj->status2 == 'draft' || $spj->status2 == null ? 'selected' : '' }}>Draft</option>
                                            <option value="valid" {{ $spj->status2 == 'valid' ? 'selected' : '' }}>Valid</option>
                                            <option value="belum_valid" {{ $spj->status2 == 'belum_valid' ? 'selected' : '' }}>Tidak Disetujui</option>
                                        </select>
                                        <input type="hidden" name="komentar_kasubag" id="komentar_{{ $spj->id }}">
                                    </form>
                                </div>
                            </td>
                            <td>{{ $spj->user->nama ?? '-' }}</td>
                            <td>
                                <a href="{{ route('previewsuper', ['id' => $spj->id]) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Preview
                                    </a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedId = null;

    document.querySelectorAll('.status-dropdown').forEach(dropdown => {
        dropdown.addEventListener('change', function() {
            const id = this.dataset.id;
            const status = this.value;
                if (status === 'belum_valid') {
                    selectedId = id;
                    $('#komentarModal').modal('show');
                } else {
                    document.getElementById(`form-${id}`).submit();
                }
        });
    });

    // Submit komentar form
    document.getElementById('komentarForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const komentar = document.getElementById('komentarText').value.trim();
        if (komentar === '') {
            alert('Komentar tidak boleh kosong.');
            return;
        }

        // masukkan komentar ke hidden input form
        document.getElementById(`komentar_${selectedId}`).value = komentar;

        // tutup modal dan submit form
        $('#komentarModal').modal('hide');
        document.getElementById(`form-${selectedId}`).submit();
    });
});
</script>

