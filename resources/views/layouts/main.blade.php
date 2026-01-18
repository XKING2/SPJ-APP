<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    
    <title>SPJ Dashboard</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    
    <!-- Stylesheets -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/aksi.css') }}" rel="stylesheet">
    <link href="{{ asset('css/chats.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dropdown.css') }}" rel="stylesheet">
    <link href="{{ asset('css/userui.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mainuserui.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    

</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-flex flex-column" id="accordionSidebar">
            <!-- Brand -->
            <a class="sidebar-brand d-flex align-items-center px-3" href="{{ route('userdashboard') }}" style="gap: 12px;">
                <div class="sidebar-brand-icon d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px; border-radius: 12px; overflow: hidden;">
                    <img src="{{ asset('images/logo1.png') }}" alt="Logo E-SPJ" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <div class="sidebar-brand-text text-white fw-bold" style="font-size: 1.1rem;">
                    E-SPJ
                </div>
            </a>

            <hr class="sidebar-divider my-0">

            <!-- Dashboard -->
            <li class="nav-item {{ Request::routeIs('userdashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('userdashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Kwitansi -->
            <li class="nav-item {{ Request::routeIs('kwitansi') || Request::routeIs('Kwitansigu') || Request::routeIs('kwitansils') || Request::routeIs('kwitansipo') ? 'active' : '' }}">
                <a class="nav-link d-flex justify-content-between align-items-center" href="{{ route('kwitansi') }}">
                    <div>
                        <i class="fas fa-fw fa-receipt"></i>
                        <span>Kwitansi</span>
                    </div>
                    @if(!empty($feedbackCount['kwitansi']))
                        <span class="badge-notif">{{ $feedbackCount['kwitansi'] }}</span>
                    @endif
                </a>
            </li>

            <!-- Pesanan -->
            <li class="nav-item {{ Request::routeIs('pesanan') || Request::routeIs('pesanangu') || Request::routeIs('pesananls') ? 'active' : '' }}">
                <a class="nav-link d-flex justify-content-between align-items-center" href="{{ route('pesanan') }}">
                    <div>
                        <i class="fas fa-fw fa-shopping-cart"></i>
                        <span>Pesanan</span>
                    </div>
                    @if(!empty($feedbackCount['pesanan']))
                        <span class="badge-notif">{{ $feedbackCount['pesanan'] }}</span>
                    @endif
                </a>
            </li>

            <!-- Berita Acara -->
            <li class="nav-item {{ Request::routeIs('pemeriksaan') || Request::routeIs('serahbarang') || Request::routeIs('penerimaan') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBeritaAcara">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Berita Acara</span>
                </a>
                <div id="collapseBeritaAcara" class="collapse {{ Request::routeIs('pemeriksaan') || Request::routeIs('serahbarang') || Request::routeIs('penerimaan') ? 'show' : '' }}">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item d-flex justify-content-between {{ Request::routeIs('pemeriksaan') ? 'active' : '' }}" href="{{ route('pemeriksaan') }}">
                            <span>Pemeriksaan</span>
                            @if(!empty($feedbackCount['pemeriksaan']))
                                <span class="badge-notif">{{ $feedbackCount['pemeriksaan'] }}</span>
                            @endif
                        </a>
                        <a class="collapse-item d-flex justify-content-between {{ Request::routeIs('serahbarang') ? 'active' : '' }}" href="{{ route('serahbarang') }}">
                            <span>Serah Barang</span>
                            @if(!empty($feedbackCount['serah_barang']))
                                <span class="badge-notif">{{ $feedbackCount['serah_barang'] }}</span>
                            @endif
                        </a>
                        <a class="collapse-item d-flex justify-content-between {{ Request::routeIs('penerimaan') ? 'active' : '' }}" href="{{ route('penerimaan') }}">
                            <span>Penerimaan</span>
                            @if(!empty($feedbackCount['penerimaan']))
                                <span class="badge-notif">{{ $feedbackCount['penerimaan'] }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </li>

            <!-- Review Data SPJ -->
            <li class="nav-item {{ Request::routeIs('reviewSPJ') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('reviewSPJ') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Review Data SPJ</span>
                </a>
            </li>

            <!-- Cetak Data SPJ -->
            <li class="nav-item {{ Request::routeIs('cetakSPJ') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('cetakSPJ') }}">
                    <i class="fas fa-fw fa-print"></i>
                    <span>Cetak Data SPJ</span>
                </a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Berahlak Logo - Bersih Tanpa Background dan Border -->
            <div class="berahlak-logo-container">
                <img src="{{ asset('images/berahlak.png') }}" alt="Berahlak Logo">
            </div>
        </ul>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column min-vh-100">
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Searchbar -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Cari..." aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <ul class="navbar-nav ml-auto">
                        <!-- Mobile Search -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Cari...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Notifications -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown">
                                <i class="fas fa-bell fa-fw"></i>
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <h6 class="dropdown-header">Alerts Center</h6>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- User Info -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    {{ Auth::user()->nama ?? 'User' }}
                                </span>
                                <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
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

                <!-- Loading Screen -->
                <div id="loading-screen" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.4s ease;">
                    <div id="loader-box" style="position: relative; background: white; border-radius: 20px; padding: 10px; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <div id="lottie-container" style="position: absolute; width: 70px; height: 70px; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.5);"></div>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="container-fluid mt-4">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        @yield('pageheads')
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal">
                        <span>×</span>
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

    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    
    @yield('scripts')
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function isSweetAlertOpen() {
            return document.querySelector('.swal2-container') !== null;
        }

        const originalShowLoader = window.showLoader;
        const originalHideLoader = window.hideLoader;

        window.showLoader = function() {
            if (isSweetAlertOpen()) {
                console.log('⚠️ Loader diblokir karena SweetAlert aktif');
                return;
            }
            originalShowLoader && originalShowLoader();
        };

        window.hideLoader = function() {
            if (isSweetAlertOpen()) {
                document.getElementById('loading-screen').style.display = 'none';
                return;
            }
            originalHideLoader && originalHideLoader();
        };

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
        const animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: "{{ asset('lottie/blue_loading.json') }}"
        });

        function showLoader() {
            if (window._loaderDisabled) return; 
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

        window.addEventListener('load', hideLoader);

        document.addEventListener('DOMContentLoaded', () => {
            hideLoader();

            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', (e) => {
                    if (window._loaderDisabled || document.querySelector('.swal2-container')) return;
                    showLoader();
                });
            });
        });

        window.addEventListener('beforeunload', (e) => {
            if (window._loaderDisabled || document.querySelector('.swal2-container')) return;
            showLoader();
        });
    </script>
    
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"></script>

    <script>
        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env("PUSHER_APP_KEY") }}',
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            forceTLS: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }
        });

        const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');

        Echo.private(`chat.${userId}`)
            .listen('.MessageSent', (e) => {
                console.log('Pesan diterima:', e);
            });
    </script>
</body>

</html>