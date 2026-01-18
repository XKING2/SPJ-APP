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

                                    <button
                                        class="btn btn-sm btn-secondary btn-preview-bukti"
                                        data-spj-id="{{ $spj->id }}">
                                        <i class="fas fa-file-alt"></i> Bukti
                                    </button>

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


<div class="modal fade" id="modalPreviewBuktiSPJ" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Preview Bukti SPJ</h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="preview-bukti-content" class="row g-3">
                    <div class="text-center text-muted w-100">
                        Memuat data...
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // ==========================================================
    // 🔓 KLIK STATUS → CEK STATUS, TENTUKAN BUKA MODAL ATAU LANGSUNG UPDATE
    // ==========================================================
    document.querySelectorAll('.status-option').forEach(btn => {
        btn.addEventListener('click', async e => {
            e.preventDefault();

            let id = btn.dataset.id;
            let status = btn.dataset.status;

            // Set hidden input status
            document.getElementById(`status_${id}`).value = status;

            // Jika status = "belum_valid", buka modal feedback
            if (status === 'belum_valid') {

                // Set SPJ ID untuk modal
                document.getElementById('feedback_spj_id').value = id;
                $('#feedbackModal').modal('show');
                return;
            }

            // =======================
            // Jika Valid atau Draft → UPDATE langsung tanpa modal
            // =======================
            let formUpdateStatus = document.getElementById(`form-${id}`);
            let formData = new FormData(formUpdateStatus);

            try {
                const res = await fetch(formUpdateStatus.action, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });

                Swal.fire({
                    icon: 'success',
                    title: 'Status berhasil diperbarui!',
                    timer: 1500
                });

                setTimeout(() => location.reload(), 700);

            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Gagal memperbarui status.', 'error');
            }
        });
    });

    document.getElementById('add-feedback').addEventListener('click', function () {
        const container = document.getElementById('feedback-list');
        const clone = container.firstElementChild.cloneNode(true);

        clone.querySelectorAll('textarea').forEach(e => e.value = '');
        clone.querySelectorAll('select').forEach(e => e.value = '');
        clone.querySelector('.section-field').value = '';
        clone.querySelector('.record-id-field').value = '';

        container.appendChild(clone);
    });

    // ==========================================================
    // ❌ HAPUS ITEM FEEDBACK
    // ==========================================================
    document.addEventListener('click', e => {
        if (e.target.classList.contains('remove-item')) {
            const container = document.getElementById('feedback-list');
            if (container.children.length > 1) {
                e.target.closest('.feedback-item').remove();
            }
        }
    });

    // ==========================================================
    // 🔥 AUTO SET RECORD_ID & SECTION
    // ==========================================================
    document.addEventListener('change', e => {
        if (e.target.classList.contains('field-selector')) {

            const fieldEl = e.target;
            const section = fieldEl.options[fieldEl.selectedIndex].dataset.section;
            const wrapper = fieldEl.closest('.feedback-item');

            wrapper.querySelector('.section-field').value = section;

            let spjId = document.getElementById('feedback_spj_id').value;

            fetch(`/spj/${spjId}/record/${section}`)
                .then(res => res.json())
                .then(data => {
                    if (Array.isArray(data.record_id)) {
                        wrapper.querySelector('.record-id-field').value = data.record_id.join(',');
                    } else {
                        wrapper.querySelector('.record-id-field').value = data.record_id ?? null;
                    }
                })
                .catch(err => console.error('Fetch error:', err));
        }
    });

    // ==========================================================
    // 📤 SUBMIT FEEDBACK MODAL (KHUSUS Tidak Disetujui)
    // ==========================================================
    document.getElementById('feedbackForm').addEventListener('submit', async e => {
        e.preventDefault();

        let spj_id = document.getElementById('feedback_spj_id').value;
        let formUpdateStatus = document.getElementById(`form-${spj_id}`);
        let formDataFeedback = new FormData(e.target);

        try {
            // Kirim feedback
            const res = await fetch(`/spj/${spj_id}/revisi`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formDataFeedback
            });

            const data = await res.json();
            if (!data.success) {
                Swal.fire('Error', data.message, 'error');
                return;
            }

            // Update status setelah feedback
            let statusData = new FormData(formUpdateStatus);

            await fetch(formUpdateStatus.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: statusData
            });

            $('#feedbackModal').modal('hide');

            Swal.fire({
                icon: 'success',
                title: 'Status berhasil diperbarui!',
                timer: 1500
            });

            setTimeout(() => location.reload(), 800);

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Gagal memproses data.', 'error');
        }
    });

});
</script>

@section('scripts')
<script>
$(document).on('click', '.btn-preview-bukti', async function () {

    const spjId = $(this).data('spj-id');
    const $modal = $('#modalPreviewBuktiSPJ');
    const $content = $('#preview-bukti-content');

    // Loading state
    $content.html('<div class="text-center text-muted w-100 py-5">Memuat data...</div>');
    $modal.modal('show');

    try {
        const res = await fetch(`/spj/${spjId}/bukti`, {
            headers: { 'Accept': 'application/json' }
        });

        if (!res.ok) throw new Error('Gagal memuat data');

        const data = await res.json();

        if (!data || data.length === 0) {
            $content.html('<div class="text-center text-muted w-100 py-5">Belum ada bukti</div>');
            return;
        }

        // Fungsi bantu render file
        function renderFile(b, isSingle=false) {
            const ext = b.file_url.split('.').pop().toLowerCase();

            if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                if (isSingle) {
                    // Single image → center full modal
                    return `
                        <div style="display:flex; justify-content:center; align-items:center; width:100%; min-height:60vh; ">
                            <img src="${b.file_url}" class="img-fluid rounded shadow" style="max-height:80vh; object-fit:contain;">
                        </div>
                        ${b.keterangan ? `<p class="mt-2 mb-0 text-center text-muted">${b.keterangan}</p>` : ''}
                    `;
                } else {
                    // Grid thumbnail
                    return `
                        <a href="${b.file_url}" class="glightbox" data-glightbox="title:${b.jenis}; description:${b.keterangan ?? '-'}">
                            <img src="${b.file_url}" class="img-fluid rounded shadow" style="height:200px; object-fit:cover;">
                            ${b.keterangan ? `<p class="mt-2 mb-0 text-center text-muted">${b.keterangan}</p>` : ''}
                        </a>
                    `;
                }
            } else if (ext === 'pdf') {
                if (isSingle) {
                    return `
                        <iframe src="${b.file_url}" style="width:100%; height:80vh;" frameborder="0"></iframe>
                        ${b.keterangan ? `<p class="mt-3 text-center mb-0"><a href="${b.file_url}" target="_blank" class="btn btn-primary btn-sm">Buka PDF</a></p>` : ''}
                    `;
                } else {
                    return `
                        <a href="${b.file_url}" class="glightbox" data-glightbox="type:iframe; title:${b.jenis}; description:${b.keterangan ?? '-'}">
                            <div class="border rounded p-3 text-center" style="height:200px; display:flex; align-items:center; justify-content:center; flex-direction:column;">
                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                <p class="mt-2 mb-0">${b.jenis}</p>
                            </div>
                        </a>
                    `;
                }
            } else {
                return `
                    <div class="border rounded p-3 text-center" style="height:200px; display:flex; align-items:center; justify-content:center;">
                        File tidak bisa ditampilkan. <a href="${b.file_url}" target="_blank">Download</a>
                    </div>
                `;
            }
        }

        // Bersihkan konten
        $content.empty();

        if (data.length === 1) {
            const b = data[0];
            $content.html(renderFile(b, true));
        } else {
            // Banyak file → grid
            let html = '';
            data.forEach(b => {
                html += `<div class="col-md-4 mb-3">${renderFile(b)}</div>`;
            });
            $content.html(`<div class="row g-3">${html}</div>`);

            // Inisialisasi GLightbox untuk grid
            GLightbox({ selector: '.glightbox' });
        }

    } catch (err) {
        console.error(err);
        $content.html('<div class="text-danger text-center w-100 py-5">Gagal memuat bukti</div>');
    }
});
</script>
@endsection








@endsection








