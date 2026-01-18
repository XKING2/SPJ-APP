'use strict';

window.FormValidation = {
    init() {
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', e => this.handle(e, form));
        });
    },

    handle(e, form) {
        e.preventDefault();
        if (form.dataset.submitting) return;

        const empty = [...form.querySelectorAll('[required]')]
            .filter(i => !i.value.trim());

        if (empty.length) {
            Swal.fire('Error', 'Lengkapi semua data wajib', 'error');
            return;
        }

        Swal.fire({
            title: 'Simpan data?',
            icon: 'warning',
            showCancelButton: true
        }).then(r => {
            if (r.isConfirmed) {
                form.dataset.submitting = 'true';
                form.submit();
            }
        });
    }
};
