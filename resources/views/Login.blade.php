<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem E-SPJ - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/costomcss.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body data-auth-error="{{ session('auth_error') }}">
    <div class="login-box text-center">
        <img src="{{ asset('images/Logo1.png') }}" class="logo" alt="Logo">
        <h4 class="fw-bold">E-SPJ</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3 text-start">
                <label class="form-label">NIP</label>
                <input type="text" name="NIP" class="form-control" placeholder="Masukkan NIP" required>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                {{ $errors->first() }}
            </div>
        @endif
    </div>
</body>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('auth_error'))
<script>
    const msg = document.body.dataset.authError;
    if (msg) {
        Swal.fire({
            icon: 'warning',
            title: 'Akses Ditolak',
            text: msg
        });
    }
</script>
@endif

<script>
document.addEventListener("DOMContentLoaded", function () {
    const successData = document.querySelector('[data-swal-success]');
    const errorData = document.querySelector('[data-swal-errors]');

    if (successData) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successData.dataset.swalSuccess,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }

    if (errorData) {
        const errorMessages = errorData.dataset.swalErrors.split('|');
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            html: errorMessages.join('<br>'),
            confirmButtonColor: '#d33',
            confirmButtonText: 'Coba Lagi'
        });
    }
});
</script>

</html>
