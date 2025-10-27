<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SPJ Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/page.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('userdashboard') }}">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('images/logo2.png') }}" alt="Logo E-SPJ" 
                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                </div>
                <div class="sidebar-brand-text mx-3 fw-bold">E-SPJ</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Dashboard -->
            <li class="nav-item {{ Request::routeIs('superdashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('superdashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Data Anggota -->
            <li class="nav-item {{ Request::routeIs('showanggota') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('showanggota') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Anggota</span>
                </a>
            </li>

            <!-- Validasi SPJ -->
            <li class="nav-item {{ Request::routeIs('Validasi') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('Validasi') }}">
                    <i class="fas fa-fw fa-receipt"></i>
                    <span>Validasi SPJ</span>
                </a>
            </li>

            <!-- Setting SPJ -->
            @php
                $isSettingActive = Request::routeIs('settings.index') || Request::routeIs('showpptk');
            @endphp
            <li class="nav-item {{ $isSettingActive ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" 
                data-target="#collapseBeritaAcara"
                aria-expanded="{{ $isSettingActive ? 'true' : 'false' }}"
                aria-controls="collapseBeritaAcara">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Setting SPJ</span>
                </a>
                <div id="collapseBeritaAcara" 
                    class="collapse {{ $isSettingActive ? 'show' : '' }}"
                    aria-labelledby="headingBeritaAcara" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{ Request::routeIs('settings.index') ? 'active' : '' }}" 
                        href="{{ route('settings.index') }}">
                            Setting PPN
                        </a>
                        <a class="collapse-item {{ Request::routeIs('showpptk') ? 'active' : '' }}" 
                        href="{{ route('showpptk') }}">
                            Setting PPTK
                        </a>
                        <a class="collapse-item {{ Request::routeIs('showplt') ? 'active' : '' }}" 
                        href="{{ route('showplt') }}">
                            Setting Pihak Pertama
                        </a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
                <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column min-vh-100">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                               
                            
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                      

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    {{ Auth::user()->nama ?? 'User' }}
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="{{asset('img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                     <div id="loading-screen" 
                        style="display: none; 
                                position: fixed; 
                                top: 0; left: 0; right: 0; bottom: 0; 
                                background-color: rgba(255, 255, 255, 0.7); 
                                z-index: 9999; 
                                align-items: center; 
                                justify-content: center; 
                                opacity: 0; 
                                transition: opacity 0.4s ease;">
                        
                        <div id="loader-box" 
                            style="position: relative;
                                    background: white; 
                                    border-radius: 20px; 
                                    box-shadow: 0 4px 10px rgba(0,0,0,0.2); 
                                    padding: 10px; 
                                    width: 80px; 
                                    height: 80px; 
                                    display: flex; 
                                    align-items: center; 
                                    justify-content: center;
                                    overflow: hidden;">
                            
                            <div id="lottie-container" 
                                style="position: absolute;
                                        width: 70; 
                                        height: 70px; 
                                        transform: scale(1.5); 
                                        top: 50%; 
                                        left: 50%; 
                                        transform: translate(-50%, -50%) scale(1.5);">
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid mt-4">
                        
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        @yield('pageheads')
                    </div>

                    
                    @yield('content')
                </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('login') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

       <!-- Lottie -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>

    <script>
        // Load animasi dari file JSON
        const animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '/lottie/blue_loading.json' // ganti path sesuai lokasi file kamu
        });

        const loadingScreen = document.getElementById('loading-screen');

        function showLoader() {
            loadingScreen.style.display = 'flex';
            setTimeout(() => {
                loadingScreen.style.opacity = '1';
            }, 10);
        }

        function hideLoader() {
            loadingScreen.style.opacity = '0';
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 400); // durasi fade-out sama dengan transition CSS (0.4s)
        }

        document.addEventListener('DOMContentLoaded', function () {
        hideLoader();

            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    // Kalau SweetAlert sedang aktif, jangan tampilkan loader
                    if (document.querySelector('.swal2-container')) return;
                    showLoader();
                });
            });
        });

        window.addEventListener('beforeunload', () => {
            if (!document.querySelector('.swal2-container')) {
                showLoader();
            }
        });

            // Loader global saat berpindah halaman
            window.addEventListener('beforeunload', showLoader);
            window.addEventListener('load', hideLoader);
    </script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('js/demo/chart-pie-demo.js') }}"></script>
    

</body>

</html>