document.addEventListener('DOMContentLoaded', function() {
    const success = document.querySelector('[data-swal-success]');
    const errors = document.querySelector('[data-swal-errors]');

    if (success) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: success.dataset.swalSuccess,
            showConfirmButton: false,
            timer: 2000
        });
    }

    if (errors) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan!',
            html: errors.dataset.swalErrors.replace(/\|/g, '<br>'),
            confirmButtonText: 'OK'
        });
    }
});

