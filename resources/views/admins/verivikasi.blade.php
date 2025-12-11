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


    <div class="modal fade" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="feedbackForm">
                <div class="modal-header">
                    <h5 class="modal-title">Masukkan Alasan Penolakan</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="feedback_spj_id" name="spj_id">

                    <div id="feedback-list">

                        <div class="feedback-item border rounded p-3 mb-2">

                            <!-- auto-filled by JS -->
                            <input type="hidden" name="section[]" class="section-field">
                            <input type="hidden" name="record_id[]" class="record-id-field">

                            <label><strong>Bagian yang Salah</strong></label>

                            <select name="field[]" class="form-control field-selector" required>
                                <option value="">-- Pilih Bagian yang Salah --</option>

                                <!-- KWITANSI -->
                                <optgroup label="Bagian Kwitansi">
                                    <option data-section="kwitansi" value="uang_terbilang">Uang Terbilang</option>
                                    <option data-section="kwitansi" value="jumlah_nominal">Jumlah Nominal</option>
                                    <option data-section="kwitansi" value="pembayaran">Pembayaran</option>
                                    <option data-section="kwitansi" value="no_rekening">Nomor Rekening</option>
                                    <option data-section="kwitansi" value="no_rekening_tujuan">Nomor Rekening Tujuan</option>
                                    <option data-section="kwitansi" value="nama_bank">Nama Bank</option>
                                    <option data-section="kwitansi" value="npwp">NPWP</option>
                                    <option data-section="kwitansi" value="telah_diterima_dari">Telah Diterima Dari</option>
                                    <option data-section="kwitansi" value="penerima_kwitansi">Penerima Kwitansi</option>
                                    <option data-section="kwitansi" value="jabatan_penerima">Jabatan Penerima</option>
                                </optgroup>

                                <!-- PESANAN -->
                                <optgroup label="Bagian Pesanan">
                                    <option data-section="pesanan" value="no_surat">Nomor Surat</option>
                                    <option data-section="pesanan" value="nama_pt">Nama PT</option>
                                    <option data-section="pesanan" value="alamat_pt">Alamat PT</option>
                                    <option data-section="pesanan" value="nomor_tlp_pt">Nomor Telepon PT</option>
                                    <option data-section="pesanan" value="tanggal_diterima">Tanggal Diterima</option>
                                    <option data-section="pesanan" value="surat_dibuat">Tanggal Surat Dibuat</option>
                                </optgroup>

                                <!-- PEMERIKSAAN -->
                                <optgroup label="Bagian Pemeriksaan">
                                    <option data-section="pemeriksaan" value="nama_pihak_kedua">Nama Pihak Kedua</option>
                                    <option data-section="pemeriksaan" value="jabatan_pihak_kedua">Jabatan Pihak Kedua</option>
                                    <option data-section="pemeriksaan" value="alamat_pihak_kedua">Alamat Pihak Kedua</option>
                                    <option data-section="pemeriksaan" value="pekerjaan">Pekerjaan</option>
                                </optgroup>

                                <!-- PENERIMAAN -->
                                <optgroup label="Bagian Penerimaan">
                                    <option data-section="penerimaan" value="subtotal">Subtotal</option>
                                    <option data-section="penerimaan" value="ppn">PPN</option>
                                    <option data-section="penerimaan" value="grandtotal">Grand Total</option>
                                    <option data-section="penerimaan" value="dibulatkan">Dibulatkan</option>
                                    <option data-section="penerimaan" value="terbilang">Terbilang</option>
                                    <option data-section="penerimaan" value="pph">PPH</option>
                                </optgroup>

                                <!-- DETAIL BARANG -->
                                <optgroup label="Bagian Daftar Barang">
                                    <option data-section="detail_barang" value="nama_barang">Nama Barang</option>
                                    <option data-section="detail_barang" value="jumlah">Jumlah</option>
                                    <option data-section="detail_barang" value="satuan">Satuan</option>
                                    <option data-section="detail_barang" value="harga_satuan">Harga Satuan</option>
                                    <option data-section="detail_barang" value="total">Total</option>
                                </optgroup>

                            </select>

                            <div class="form-group mb-2 mt-3">
                                <label><strong>Catatan:</strong></label>
                                <textarea name="message[]" class="form-control" rows="2" required></textarea>
                            </div>

                            <button type="button" class="btn btn-sm btn-outline-danger remove-item">Hapus</button>
                        </div>

                    </div>

                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-feedback">
                        + Tambah Alasan
                    </button>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </div>

            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // ============================
    // 🔓 BUKA MODAL FEEDBACK
    // ============================
    document.querySelectorAll('.status-option').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();

            let id = btn.dataset.id;
            document.getElementById('feedback_spj_id').value = id;

            $('#feedbackModal').modal('show');
        });
    });

    // ============================
    // ➕ TAMBAH ITEM FEEDBACK
    // ============================
    document.getElementById('add-feedback').addEventListener('click', function () {
        const container = document.getElementById('feedback-list');
        const clone = container.firstElementChild.cloneNode(true);

        // reset nilai
        clone.querySelectorAll('textarea').forEach(e => e.value = '');
        clone.querySelectorAll('select').forEach(e => e.value = '');

        clone.querySelector('.section-field').value = '';
        clone.querySelector('.record-id-field').value = '';

        container.appendChild(clone);
    });

    // ============================
    // ❌ HAPUS ITEM FEEDBACK
    // ============================
    document.addEventListener('click', e => {
        if (e.target.classList.contains('remove-item')) {
            const container = document.getElementById('feedback-list');
            if (container.children.length > 1) {
                e.target.closest('.feedback-item').remove();
            }
        }
    });

    // ============================
    // 🔥 AUTO SET SECTION + RECORD_ID
    // ============================
    document.addEventListener('change', e => {
        if (e.target.classList.contains('field-selector')) {

            const fieldEl = e.target;

            // Ambil section dari <option>
            const section = fieldEl.options[fieldEl.selectedIndex].dataset.section;

            // Cari elemen wrapper
            const wrapper = fieldEl.closest('.feedback-item');

            // Set value section
            wrapper.querySelector('.section-field').value = section;

            // Ambil SPJ ID
            let spjId = document.getElementById('feedback_spj_id').value;

            // Query ke server: /spj/{id}/record/{section}
            fetch(`/spj/${spjId}/record/${section}`)
                .then(res => res.json())
                .then(data => {

                    if (Array.isArray(data.record_id)) {
                        // Jika detail_barang (multiple record)
                        wrapper.querySelector('.record-id-field').value = data.record_id.join(',');
                    } else {
                        wrapper.querySelector('.record-id-field').value = data.record_id ?? null;
                    }
                })
                .catch(err => console.error('Fetch record_id error:', err));
        }
    });

    // ============================
    // 📤 SUBMIT FEEDBACK FORM
    // ============================
    document.getElementById('feedbackForm').addEventListener('submit', async e => {
        e.preventDefault();

        let spj_id = document.getElementById('feedback_spj_id').value;
        let formData = new FormData(e.target);

        try {
            const res = await fetch(`/spj/${spj_id}/revisi`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            });

            const data = await res.json();
            $('#feedbackModal').modal('hide');

            Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.message,
                timer: 1800
            });

            if (data.success) location.reload();

        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Gagal mengirim feedback.', 'error');
        }
    });

});
</script>



@endsection








