@extends('layouts.main2')

@section('pageheads')
    <h1 class="h3 mb-4 text-gray-800">Verivikasi SPJ</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar SPJ</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <form action="#" method="GET" class="form-inline">
                        <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Cari...">
                        <button type="submit" class="btn btn-sm btn-secondary">Cari</button>
                    </form>
                </div>

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
        if (!csrfToken) console.warn('CSRF token meta tag not found! add <meta name="csrf-token" content="{{ csrf_token() }}"> in your layout.');

        let selectedId = null;

        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const status = this.dataset.status;
                document.getElementById(`status_${id}`).value = status;

                if (status === 'belum_valid') {
                    selectedId = id;
                    document.getElementById('feedback_spj_id').value = id;
                    $('#feedbackModal').modal('show');
                } else {
                    document.getElementById(`form-${id}`).submit();
                }
            });
        });

        document.getElementById('add-feedback').addEventListener('click', function() {
        const container = document.getElementById('feedback-list');
        const clone = container.firstElementChild.cloneNode(true);
        clone.querySelectorAll('select, textarea').forEach(el => el.value = '');
        container.appendChild(clone);
    });

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

                const badge = document.querySelector(`#dropdownMenuButton${spj_id}`);
                if (badge) {
                    badge.className = "badge bg-danger text-white dropdown-toggle border-0";
                    badge.textContent = "Tidak Disetujui";
                }

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







