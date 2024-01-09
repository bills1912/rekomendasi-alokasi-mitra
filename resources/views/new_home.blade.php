<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Manejemen Pengalokasian Wilayah Kerja Mitra
    </title>
    <link rel="icon" href="{{ url('/') }}/img/map.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.7/r-2.5.0/datatables.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/styles.css" rel="stylesheet" />
    <link href="{{ url('/') }}/css/map_style.css" rel="stylesheet" />
    <link href="{{ url('/') }}/css/loading_animation.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <link rel="stylesheet" href="{{ url('/') }}/js/leaflet.markercluster/dist/MarkerCluster.css">
    <link rel="stylesheet" href="{{ url('/') }}/js/leaflet.markercluster/dist/MarkerCluster.Default.css">
    <script src="{{ url('/') }}/js/leaflet.markercluster/dist/leaflet.markercluster-src.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.7/r-2.5.0/datatables.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ url('/dist') }}/leaflet.awesome-markers.css">
</head>

<body class="sb-nav-fixed">
    @include('sweetalert::alert')
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="{{ url('/') }}">Dashboard Alokasi</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar-->
        <ul class="navbar-nav d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false"><i
                        class="fas fa-user fa-fw pr-2"></i>{{ Auth::user()->name }}</a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <a class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();"
                                href="{{ route('logout') }}">Logout</a>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Jenis Kegiatan</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseLayouts1" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-square-poll-horizontal"></i></div>
                            Kegiatan Survei
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts1" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                @if (!session()->has('sample'))
                                    <a class="nav-link" href="{{ url('/upload_sample_bs') }}">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-upload mr-2"></i></div>
                                        Upload Sample BS dan Mitra Terpilih
                                    </a>
                                @endif
                                @if (session()->has('sample'))
                                    <a class="nav-link" href="{{ url('/alokasi_mitra_survei') }}">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-street-view mr-2"></i>
                                        </div>
                                        Rekomendasi Alokasi Mitra Survei
                                    </a>
                                    <a class="nav-link" href="{{ url('/list_mitra_survei_teralokasi') }}">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-list mr-2"></i>
                                        </div>
                                        Daftar Mitra Teralokasi
                                    </a>
                                @endif
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseLayouts2" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-people-group"></i></div>
                            Kegiatan Sensus
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts2" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ url('/upload_mitra_terpilih') }}">
                                    <div class="sb-nav-link-icon"><i class="fa-solid fa-upload mr-2"></i></div>
                                    Upload Mitra Terpilih
                                </a>
                                <a class="nav-link" href="{{ url('/alokasi_mitra_sensus') }}">
                                    <div class="sb-nav-link-icon"><i class="fa-solid fa-street-view mr-2"></i></i>
                                    </div>
                                    Rekomendasi Alokasi Mitra Sensus
                                </a>
                            </nav>
                        </div>
                        {{-- <div class="sb-sidenav-menu-heading">Interface</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Pages
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseAuth" aria-expanded="false"
                                    aria-controls="pagesCollapseAuth">
                                    Authentication
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="login.html">Login</a>
                                        <a class="nav-link" href="register.html">Register</a>
                                        <a class="nav-link" href="password.html">Forgot Password</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#pagesCollapseError" aria-expanded="false"
                                    aria-controls="pagesCollapseError">
                                    Error
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="401.html">401 Page</a>
                                        <a class="nav-link" href="404.html">404 Page</a>
                                        <a class="nav-link" href="500.html">500 Page</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Charts
                        </a>
                        <a class="nav-link" href="tables.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Tables
                        </a> --}}
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Subject Matter
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main role="main" class="main-content">
                @yield('container')
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted"><img class="logo-bps" src="{{ url('/') }}/img/logo_bps.png"
                                alt=""> BPS Kota Gunungstioli
                            {{ date('Y') }} &copy;</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ url('/') }}/js/app.js"></script>
    <script src="{{ url('/') }}/js/scripts.js"></script>
    <script src="{{ url('/dist') }}/leaflet.awesome-markers.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    {{-- <script src="{{ url('/') }}/js/datatables-simple-demo.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('#datatable-mitra-total').DataTable();
        });
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
        let mapMitra = L.map('map-mitra').setView([1.286099234224218, 97.61461569775827], 14);
        let mitra = document.getElementById("data-mitra");
        let coords = [];
        let mitraMarkerGroup = L.markerClusterGroup();
        setTimeout(function() {
            mapMitra.invalidateSize(true);
        }, 1);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(mapMitra);

        let data_mitra = {!! json_encode($data_mitra) !!}
        for (let i = 0; i <= data_mitra.length; i++) {
            coords.push([data_mitra[i]['latitude'], data_mitra[i]['longitude']])
            mitraMarkerGroup.addLayer(L.marker(coords[i], {
                icon: L.icon({
                    // if (data_mitra[i]['jenis_kelamin'] == 1) {
                    //     iconUrl: window.location.protocol + "//" + window.location.host + '/img/man.png',
                    // } else {
                    //     iconUrl: window.location.protocol + "//" + window.location.host + '/img/woman.png',
                    // }
                    iconUrl: window.location.protocol + "//" + window.location.host + '/img/man.png',
                    iconSize: [30, 30]
                })
            }).bindPopup('lorem ipsum'));
            mapMitra.addLayer(mitraMarkerGroup)
            $(".cari-lokasi").click(function() {
                var mitraIdx = $(this).closest('tr').index()
                mapMitra.flyTo(coords[mitraIdx], 18)
            });
        };
    </script>
    <script src="{{ url('/') }}/js/gunungsitoli.js"></script>
    <script src="{{ url('/') }}/js/1278_sls.js"></script>
    <script src="{{ url('/') }}/js/gusit_bs.js"></script>
    <script src="{{ url('/') }}/js/gusit_kec.js"></script>
    <script src="{{ url('/') }}/js/1278_finalbs_2023_sem2.js"></script>
    <script src="{{ url('/') }}/js/pilihWilayahMitra.js"></script>
    <script src="{{ url('/') }}/js/pilihWilayahMitraSensus.js"></script>
    <script src="{{ url('/') }}/js/pilihKegiatanSurvei.js"></script>
    <script src="{{ url('/') }}/js/mapMitra.js"></script>
    <script src="{{ url('/') }}/js/mapAlokasiMitra.js"></script>
    <script src="{{ url('/') }}/js/tabelMitra.js"></script>
    <script src="{{ url('/') }}/js/tabelMitraSensus.js"></script>
</body>

</html>
