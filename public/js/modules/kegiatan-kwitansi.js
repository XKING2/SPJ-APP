'use strict';

window.KegiatanKwitansiModal = {
    init() {
        const btn = document.getElementById('simpanKegiatanKwitansi');
        const input = document.getElementById('nama_kegiatan_baru');
        if (!btn || !input) return;

        btn.addEventListener('click', () => this.save(input));
    },

    async save(input) {
        const nama = input.value.trim();
        if (!nama) {
            Swal.fire('Oops', 'Nama kegiatan wajib diisi', 'warning');
            return;
        }

        try {
            const data = await Utils.fetchJSON('/kegiatan-kwitansi/store-ajax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': Utils.getCSRFToken()
                },
                body: JSON.stringify({ nama_kegiatan: nama })
            });

            this.addToSelect(data);
            this.closeModal();
            input.value = '';

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                timer: 1200,
                showConfirmButton: false
            });

        } catch {
            Swal.fire('Error', 'Gagal menyimpan kegiatan', 'error');
        }
    },

    addToSelect(data) {
        const select = document.getElementById('kwitansi_keg_id');
        if (!select) return;

        const opt = document.createElement('option');
        opt.value = data.id;
        opt.textContent = data.nama_kegiatan;
        opt.selected = true;
        select.appendChild(opt);
    },

    closeModal() {
        const modal = bootstrap.Modal.getInstance(
            document.getElementById('modalTambahKegiatanKwitansi')
        );
        modal?.hide();
    }
};
