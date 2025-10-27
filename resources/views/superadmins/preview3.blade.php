@extends('layouts.main3')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Preview SPJ</h1>
@endsection

@section('content')
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Preview SPJ</h6>
            <div class="d-flex justify-content-center align-items-center">
                <form action="{{ route('updateStatusKasubag', $spj->id) }}" method="POST" class="d-inline" id="form-{{ $spj->id }}">
                    @csrf
                    <div class="dropdown">
                            @php
                                $badgeClass = 'bg-warning text-dark';
                                $badgeText = 'Draft';

                                if ($spj->status2 == 'valid') {
                                    $badgeClass = 'bg-success text-white';
                                    $badgeText = 'Valid';
                                } elseif ($spj->status2 == 'belum_valid') {
                                    $badgeClass = 'bg-danger text-white';
                                    $badgeText = 'Tidak Disetujui';
                                }
                            @endphp

                            <button class="badge {{ $badgeClass }} dropdown-toggle border-0" 
                                type="button" id="dropdownMenuButton{{ $spj->id }}" data-toggle="dropdown" 
                                aria-haspopup="true"  aria-expanded="false"  style="cursor:pointer;">{{ $badgeText }}
                            </button>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $spj->id }}">
                            <a class="dropdown-item status2-option" href="#" data-id="{{ $spj->id }}" data-status="draft">Draft</a>
                            <a class="dropdown-item status2-option" href="#" data-id="{{ $spj->id }}" data-status="valid">Valid</a>
                            <a class="dropdown-item status2-option text-danger" href="#" data-id="{{ $spj->id }}" data-status="belum_valid">Tidak Disetujui</a>
                        </div>
                    </div>
                        <input type="hidden" name="status2" id="status2_{{ $spj->id }}" value="{{ $spj->status2 }}">
                        <input type="hidden" name="komentar_kasubag" id="komentar_{{ $spj->id }}">
                </form>
            </div>
            <a href="{{ route('Validasi') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-times"></i> Tutup Preview
            </a>
        </div>

        <div class="card-body">
            <!-- Info Singkat -->
            <div class="mb-3">
                <h6 class="text-primary mb-1">
                    Nomor SPJ: <strong>{{ $spj->pesanan->no_surat ?? 'Tanpa Nomor' }}</strong>
                </h6>
                <p class="mb-0">
                    Tanggal Dibuat: {{ \Carbon\Carbon::parse($spj->pesanan->surat_dibuat ?? now())->translatedFormat('d F Y') }}
                </p>
            </div>

            <!-- PDF Viewer -->
            <div id="pdf-viewer" class="mt-3">
                <iframe 
                    id="pdf-frame" 
                    src="{{ $fileUrl }}#toolbar=0&navpanes=0&scrollbar=0" 
                    width="100%" 
                    height="650px" 
                    style="border:1px solid #ccc; border-radius: 8px;">
                </iframe>
            </div>

            <!-- Tombol Tutup -->
            <div class="mt-3 d-flex justify-content-end">
                <a href="{{ route('reviewSPJ') }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-times"></i> Tutup Preview
                </a>
            </div>
        </div>
    </div>
</div>


<!-- Modal Alasan Penolakan (Sama Seperti Bendahara) -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="feedbackForm">
        <div class="modal-header">
          <h5 class="modal-title" id="feedbackModalLabel">Masukkan Alasan Penolakan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="feedback_spj_id" name="spj_id">

          <div id="feedback-list">
            <div class="feedback-item border rounded p-3 mb-2">
              <div class="form-group mb-2">
                <label><strong>Bagian yang Salah</strong></label>
                <select name="field_name[]" class="form-control" required>
                  <option value="">-- Pilih Bagian yang Salah --</option>

                  <optgroup label="Bagian Kwitansi">
                    <option value="uang_terbilang">Uang Terbilang</option>
                    <option value="jumlah_nominal">Jumlah Nominal</option>
                    <option value="pembayaran">Pembayaran</option>
                    <option value="no_rekening">Nomor Rekening</option>
                    <option value="no_rekening_tujuan">Nomor Rekening Tujuan</option>
                    <option value="nama_bank">Nama Bank</option>
                    <option value="npwp">NPWP</option>
                    <option value="telah_diterima_dari">Telah Diterima Dari</option>
                    <option value="penerima_kwitansi">Penerima Kwitansi</option>
                    <option value="jabatan_penerima">Jabatan Penerima</option>
                  </optgroup>

                  <optgroup label="Bagian Pesanan">
                    <option value="no_surat">Nomor Surat</option>
                    <option value="nama_pt">Nama PT</option>
                    <option value="alamat_pt">Alamat PT</option>
                    <option value="nomor_tlp_pt">Nomor Telepon PT</option>
                    <option value="tanggal_diterima">Tanggal Diterima</option>
                    <option value="surat_dibuat">Tanggal Surat Dibuat</option>
                  </optgroup>

                  <optgroup label="Bagian Pemeriksaan">
                    <option value="nama_pihak_kedua">Nama Pihak Kedua</option>
                    <option value="jabatan_pihak_kedua">Jabatan Pihak Kedua</option>
                    <option value="alamat_pihak_kedua">Alamat Pihak Kedua</option>
                    <option value="nama_pihak_pertama">Nama Pihak Pertama (PLT)</option>
                    <option value="nip_pihak_pertama">NIP Pihak Pertama (PLT)</option>
                    <option value="gol_pertama">Golongan Pihak Pertama</option>
                    <option value="jab_pertama">Jabatan Pihak Pertama</option>
                  </optgroup>

                  <optgroup label="Bagian Penerimaan">
                    <option value="subtotal">Subtotal</option>
                    <option value="ppn">PPN</option>
                    <option value="grandtotal">Grand Total</option>
                    <option value="dibulatkan">Dibulatkan</option>
                    <option value="terbilang">Terbilang</option>
                  </optgroup>

                  <optgroup label="Bagian Daftar Barang">
                    <option value="nama_barang">Nama Barang</option>
                    <option value="jumlah">Jumlah</option>
                    <option value="satuan">Satuan</option>
                    <option value="harga_satuan">Harga Satuan</option>
                    <option value="total">Total</option>
                  </optgroup>
                </select>
              </div>

              <div class="form-group mb-2">
                <label><strong>Catatan / Alasan:</strong></label>
                <textarea name="message[]" class="form-control" rows="2" placeholder="Tuliskan alasan..." required></textarea>
              </div>

              <button type="button" class="btn btn-sm btn-outline-danger remove-item">Hapus</button>
            </div>
          </div>

          <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-feedback">
            + Tambah Alasan
          </button>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!csrfToken) console.warn('⚠️ CSRF token meta tag tidak ditemukan. Tambahkan di layout main3.');

    // 🧠 Event delegation agar dropdown tetap bisa digunakan berulang kali
    document.addEventListener('click', function(e) {
        const option = e.target.closest('.status2-option');
        if (!option) return;
        e.preventDefault();

        const id = option.dataset.id;
        const status2 = option.dataset.status; // ✅ variabel status2 disini
        const statusInput = document.getElementById(`status2_${id}`);

        if (!statusInput) return;
        statusInput.value = status2;

        if (status2 === 'belum_valid') { // ✅ variabel yang benar
            document.getElementById('feedback_spj_id').value = id;
            $('#feedbackModal').modal('show');
        } else {
            document.getElementById(`form-${id}`).submit();
        }
    });

    // ➕ Tambah alasan baru
    document.addEventListener('click', function(e) {
        if (e.target.id === 'add-feedback') {
            const container = document.getElementById('feedback-list');
            const clone = container.firstElementChild.cloneNode(true);
            clone.querySelectorAll('select, textarea').forEach(el => el.value = '');
            container.appendChild(clone);
        }
    });

    // ❌ Hapus alasan
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const container = document.getElementById('feedback-list');
            if (container.children.length > 1) {
                e.target.closest('.feedback-item').remove();
            } else {
                Swal.fire('Minimal satu alasan harus ada', '', 'warning');
            }
        }
    });

    // ♻️ Reset modal setelah ditutup
    $('#feedbackModal').on('hidden.bs.modal', function() {
        const container = document.getElementById('feedback-list');
        const first = container.firstElementChild.cloneNode(true);
        first.querySelectorAll('select, textarea').forEach(el => el.value = '');
        container.innerHTML = '';
        container.appendChild(first);
    });

    // 🚀 Submit form revisi (feedback)
    document.getElementById('feedbackForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const spj_id = document.getElementById('feedback_spj_id').value;
        const formData = new FormData(this);

        const fieldNames = formData.getAll('field_name[]').filter(v => v);
        const messages = formData.getAll('message[]').filter(v => v);

        if (fieldNames.length === 0 || messages.length === 0) {
            Swal.fire('Lengkapi Form', 'Minimal satu alasan harus diisi lengkap.', 'warning');
            return;
        }

        try {
            const res = await fetch(`/spj/${spj_id}/revisi`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            });

            const data = await res.json();
            $('#feedbackModal').modal('hide');

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Feedback Dikirim',
                    text: data.message,
                    timer: 1800,
                    showConfirmButton: false
                });

                // ✅ Update badge tampilan status
                const badge = document.querySelector(`#dropdownMenuButton${spj_id}`);
                if (badge) {
                    badge.className = 'badge bg-danger text-white dropdown-toggle border-0';
                    badge.textContent = 'Tidak Disetujui';
                }

                // ✅ Update hidden field komentar lalu submit form utama
                document.getElementById(`komentar_${spj_id}`).value = messages.join('; ');
                document.getElementById(`form-${spj_id}`).submit();
            } else {
                Swal.fire('Gagal', data.message || 'Terjadi kesalahan server', 'error');
            }
        } catch (error) {
            console.error('Fetch error:', error);
            $('#feedbackModal').modal('hide');
            Swal.fire('Terjadi Kesalahan', 'Tidak dapat mengirim feedback ke server.', 'error');
        }
    });
});
</script>

