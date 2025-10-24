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
    <link href="{{ asset('css/aksi.css') }}" rel="stylesheet">


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
        <li class="nav-item {{ Request::routeIs('userdashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('userdashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Kwitansi -->
        <li class="nav-item {{ Request::routeIs('kwitansi') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kwitansi') }}">
                <i class="fas fa-fw fa-receipt"></i>
                <span>Kwitansi</span>
            </a>
        </li>

        <!-- Pesanan -->
        <li class="nav-item {{ Request::routeIs('pesanan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pesanan') }}">
                <i class="fas fa-fw fa-wrench"></i>
                <span>Pesanan</span>
            </a>
        </li>

        <!-- Berita Acara -->
        <li class="nav-item {{ Request::routeIs('pemeriksaan') || Request::routeIs('penerimaan') ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBeritaAcara"
                aria-expanded="{{ Request::routeIs('pemeriksaan') || Request::routeIs('penerimaan') ? 'true' : 'false' }}"
                aria-controls="collapseBeritaAcara">
                <i class="fas fa-fw fa-file-alt"></i>
                <span>Berita Acara</span>
            </a>
            <div id="collapseBeritaAcara"
                class="collapse {{ Request::routeIs('pemeriksaan') || Request::routeIs('penerimaan') ? 'show' : '' }}"
                aria-labelledby="headingBeritaAcara" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ Request::routeIs('pemeriksaan') ? 'active' : '' }}" href="{{ route('pemeriksaan') }}">Pemeriksaan</a>
                    <a class="collapse-item {{ Request::routeIs('penerimaan') ? 'active' : '' }}" href="{{ route('penerimaan') }}">Penerimaan</a>
                </div>
            </div>
        </li>

        <!-- Review -->
        <li class="nav-item {{ Request::routeIs('reviewSPJ') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('reviewSPJ') }}">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Review Data SPJ</span>
            </a>
        </li>

        <!-- Cetak -->
        <li class="nav-item {{ Request::routeIs('cetakSPJ') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('cetakSPJ') }}">
                <i class="fas fa-fw fa-print"></i>
                <span>Cetak Data SPJ</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>


        <!-- End of Sidebar -->

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

                </div>

                    <!-- Page Heading -->
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



        <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script>
            /* ==========================================================
            🚫 PATCH: CEGAH LOTTIE MUNCUL SAAT SWEETALERT AKTIF
            ========================================================== */

            // Fungsi helper untuk deteksi SweetAlert sedang aktif
            function isSweetAlertOpen() {
                return document.querySelector('.swal2-container') !== null;
            }

            // Bungkus fungsi showLoader dan hideLoader supaya aman
            const originalShowLoader = window.showLoader;
            const originalHideLoader = window.hideLoader;

            window.showLoader = function() {
                // Jika SweetAlert aktif → jangan tampilkan loader
                if (isSweetAlertOpen()) {
                    console.log('⚠️ Loader diblokir karena SweetAlert aktif');
                    return;
                }
                originalShowLoader && originalShowLoader();
            };

            window.hideLoader = function() {
                // Jika SweetAlert aktif → langsung pastikan loader disembunyikan
                if (isSweetAlertOpen()) {
                    document.getElementById('loading-screen').style.display = 'none';
                    return;
                }
                originalHideLoader && originalHideLoader();
            };

            // Tangkap event global — kalau SweetAlert muncul, pastikan loader hilang
            const observer = new MutationObserver(() => {
                if (isSweetAlertOpen()) {
                    document.getElementById('loading-screen').style.display = 'none';
                    document.getElementById('loading-screen').style.opacity = '0';
                }
            });
            observer.observe(document.body, { childList: true, subtree: true });
            </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
    <script>
        const loadingScreen = document.getElementById('loading-screen');

        // Inisialisasi animasi Lottie
        const animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '/lottie/blue_loading.json'
        });

        // ✅ Fungsi kontrol loader
        function showLoader() {
            if (window._loaderDisabled) return; // 🔒 cegah saat SweetAlert aktif
            loadingScreen.style.display = 'flex';
            requestAnimationFrame(() => {
                loadingScreen.style.opacity = '1';
            });
        }

        function hideLoader() {
            loadingScreen.style.opacity = '0';
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 400);
        }

        // ✅ Saat halaman selesai dimuat, sembunyikan loader
        window.addEventListener('load', hideLoader);

        // ✅ Tangani form submit
        document.addEventListener('DOMContentLoaded', () => {
            hideLoader();

            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', (e) => {
                    // ❌ Jangan tampilkan loader saat SweetAlert aktif
                    if (window._loaderDisabled || document.querySelector('.swal2-container')) return;
                    showLoader();
                });
            });
        });

        // ✅ Tangani navigasi antar halaman
        window.addEventListener('beforeunload', (e) => {
            // Cegah loader saat SweetAlert muncul
            if (window._loaderDisabled || document.querySelector('.swal2-container')) return;
            showLoader();
        });
    </script>


    <!-- SweetAlert -->

    <!-- Custom scripts -->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>

    <!-- Chart.js (opsional, paling akhir) -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>

</body>

</html>