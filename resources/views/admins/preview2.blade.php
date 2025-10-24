@extends('layouts.main2')

@section('pageheads')
<h1 class="h3 mb-4 text-gray-800">Preview SPJ</h1>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Preview SPJ</h6>
            <a href="{{ route('verivikasi') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-times"></i> Tutup
            </a>
        </div>

        <div class="card-body position-relative" style="min-height: 700px;">
            <div class="mb-3">
                <h6>Nomor SPJ: <strong>{{ $spj->pesanan->no_surat ?? '-' }}</strong></h6>
                <p>Tanggal: {{ \Carbon\Carbon::parse($spj->pesanan->surat_dibuat ?? now())->translatedFormat('d F Y') }}</p>
            </div>

            <!-- PDF viewer -->
            <div id="pdf-preview" data-spj-id="{{ $spj->id }}">
                <iframe id="pdf-frame" src="{{ $fileUrl }}#toolbar=0" width="100%" height="700px"></iframe>
                <canvas id="feedback-layer" 
                    style="position:absolute; top:0; left:0; width:100%; height:700px; cursor:crosshair;">
                </canvas>
            </div>

            <div class="mt-3 text-end">
                <button id="clearBtn" class="btn btn-secondary btn-sm">
                    <i class="fas fa-eraser"></i> Bersihkan Catatan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk input alasan -->
<div class="modal fade" id="noteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Catatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <textarea id="noteText" class="form-control" rows="3" placeholder="Tuliskan alasan ketidaksetujuan..."></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="addNoteBtn" class="btn btn-danger btn-sm">Simpan</button>
      </div>
    </div>
  </div>
</div>
@endsection


<script>
document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.getElementById('pdf-preview');
    const spjId = wrapper.dataset.spjId; // ✅ Ambil dari atribut data
    const canvas = document.getElementById('feedback-layer');
    const ctx = canvas.getContext('2d');
    const rect = canvas.getBoundingClientRect();
    let currentClick = null;

    function drawMarker(x, y, msg) {
        ctx.fillStyle = 'red';
        ctx.beginPath();
        ctx.arc(x, y, 5, 0, 2 * Math.PI);
        ctx.fill();
        ctx.font = '11px Arial';
        ctx.fillText(msg, x + 8, y + 4);
    }

    fetch(`/spj/${spjId}/feedback/points`)
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                json.data.forEach(fb => {
                    drawMarker(fb.x_pct * canvas.width, fb.y_pct * canvas.height, fb.message);
                });
            }
        });

    canvas.addEventListener('click', e => {
        const x_pct = (e.clientX - rect.left) / rect.width;
        const y_pct = (e.clientY - rect.top) / rect.height;
        currentClick = { x_pct, y_pct };
        new bootstrap.Modal(document.getElementById('noteModal')).show();
    });

    document.getElementById('addNoteBtn').addEventListener('click', () => {
        const msg = document.getElementById('noteText').value.trim();
        if (!msg) return alert('Catatan tidak boleh kosong.');

        drawMarker(currentClick.x_pct * canvas.width, currentClick.y_pct * canvas.height, msg);

        fetch(`/spj/${spjId}/feedback/point`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                ...currentClick,
                message: msg,
                page: 1
            })
        });

        document.getElementById('noteText').value = '';
        bootstrap.Modal.getInstance(document.getElementById('noteModal')).hide();
    });

    document.getElementById('clearBtn').addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    });
});
</script>
