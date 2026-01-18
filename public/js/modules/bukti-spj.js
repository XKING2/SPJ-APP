'use strict';

window.BuktiSPJManager = {
    init() {
        const addBtn = document.getElementById('addBukti');
        const uploadBtn = document.getElementById('uploadBuktiBtn');
        const wrapper = document.getElementById('bukti-wrapper');

        if (!addBtn || !uploadBtn || !wrapper) return;

        addBtn.addEventListener('click', () => this.addRow());
        uploadBtn.addEventListener('click', () => this.upload());
        wrapper.addEventListener('click', e => {
            if (e.target.closest('.remove-bukti')) {
                this.removeRow(e.target);
            }
        });
    },

    addRow() {
        const wrapper = document.getElementById('bukti-wrapper');
        const first = wrapper.querySelector('.bukti-item');
        if (!first) return;

        const clone = first.cloneNode(true);
        clone.querySelectorAll('input').forEach(i => i.value = '');
        clone.querySelector('.remove-bukti')?.classList.remove('d-none');
        wrapper.appendChild(clone);
    },

    removeRow(btn) {
        const wrapper = document.getElementById('bukti-wrapper');
        if (wrapper.children.length > 1) {
            btn.closest('.bukti-item').remove();
        }
    },

    async upload() {
        const form = document.getElementById('formUploadBukti');
        const data = new FormData(form);

        try {
            await fetch(form.action, {
                method: 'POST',
                body: data
            });

            Swal.fire('Sukses', 'Bukti berhasil diupload', 'success');
            bootstrap.Modal.getInstance(
                document.getElementById('modalUploadBuktiSPJ')
            )?.hide();

            form.reset();
        } catch {
            Swal.fire('Error', 'Upload gagal', 'error');
        }
    }
};
