<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    @yield('styles')
    <!-- Styles -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Favicon -->
    <link href="{{ asset('img/Logoudi.png') }}" rel="icon" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center bg-warning" href="{{ route('home') }}">
            <div class="sidebar-brand-icon">
                <img src="{{ asset('img/Logoudi.png') }}" alt="Logo Udi">
            </div>
            <div class="sidebar-brand-text mx-3">Mejora<span class="text-primary">Soft</span></div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ Nav::isRoute('home') }}">
            <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-home fa-2x text-gray-300"></i>
            <span>{{ __('Inicio') }}</span></a>
        </li>

        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            {{ __('Menú') }}
        </div>

        <!-- Nav Item - Miembros -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('homeCorporacion') }}">
                <i class="fas fa-university fa-2x text-gray-300"></i>
                <span>{{ __('Corporación') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Nav::isRoute('universidad') }}">
            <a class="nav-link" href="" data-toggle="collapse" data-target="#collapseUsuarios" aria-expanded="true" aria-controls="collapseUsuarios">
                <i class="fas fa-users fa-2x text-gray-300"></i>
                <span>{{ __('Miembros') }}</span>
            </a>
            <div id="collapseUsuarios" class="collapse" aria-labelledby="headingUsuarios" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('usuarios.index') }}">Ver</a>
                    @if(auth()->user()->rol->nombre=="Decano")
                    <a class="collapse-item" href="{{ route('usuarios.create') }}">Crear</a>
                    @endif
                </div>
            </div>
        </li>

        @if(Auth::user()->rol->nombre=="Decano")
            <!-- Nav Item - Planes -->
            <li class="nav-item {{ Nav::isRoute('planes') }}">
                <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapsePlanes" aria-expanded="true" aria-controls="collapsePlanes">
                <i class="fas fa-2x text-gray-300 fa-database"></i>
                    <span>{{ __('Planes') }}</span>
                </a>
                <div id="collapsePlanes" class="collapse" aria-labelledby="headingPlanes" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('planes.index') }}">Ver</a>
                        <a class="collapse-item" href="{{ route('planes.create') }}">Crear</a>
                    </div>
                </div>
            </li>
        @endif

        {{--

        <!-- Nav Item - Factores -->
        <li class="nav-item {{ Nav::isRoute('factores') }}">
            <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseFactores" aria-expanded="true" aria-controls="collapseFactores">
                <i class="fas fa-2x text-gray-300 fa-list-ol"></i>
                <span>{{ __('Factores') }}</span>
            </a>
            <div id="collapseFactores" class="collapse" aria-labelledby="headingFactores" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="#">Ver</a>
                    <a class="collapse-item" href="#">Crear</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Caracteristicas -->
        <li class="nav-item {{ Nav::isRoute('caracteristicas') }}">
            <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseCaracteristicas" aria-expanded="true" aria-controls="collapseCaracteristicas">
                <i class="fas fa-2x text-gray-300 fa-th-list"></i>
                <span>{{ __('Características') }}</span>
            </a>
            <div id="collapseCaracteristicas" class="collapse" aria-labelledby="headingCaracteristicas" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="#">Ver</a>
                    <a class="collapse-item" href="#">Crear</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Aspectos -->
        <li class="nav-item {{ Nav::isRoute('aspectos') }}">
            <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseAspectos" aria-expanded="true" aria-controls="collapseAspectos">
                <i class="fas fa-2x text-gray-300 fa-copy"></i>
                <span>{{ __('Aspectos') }}</span>
            </a>
            <div id="collapseAspectos" class="collapse" aria-labelledby="headingAspectos" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="#">Ver</a>
                    <a class="collapse-item" href="#">Crear</a>
                </div>
            </div>
        </li>
        @endif --}}

        <!-- Nav Item - Indicadores -->
        {{-- <li class="nav-item {{ Nav::isRoute('indicadores') }}">
            <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseIndicadores" aria-expanded="true" aria-controls="collapseIndicadores">
                <i class="fas fa-2x text-gray-300 fa-tag"></i>
                <span>{{ __('Indicadores') }}</span>
            </a>
            <div id="collapseIndicadores" class="collapse" aria-labelledby="headingIndicadores" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="#">Ver</a>
                    <a class="collapse-item" href="#">Crear</a>
                </div>
            </div>
        </li> --}}

        <!-- Nav Item - Actividades -->
        {{-- <li class="nav-item {{ Nav::isRoute('actividades') }}">
            <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseActividades" aria-expanded="true" aria-controls="collapseActividades">
            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                <span>{{ __('Actividades') }}</span>
            </a>
            <div id="collapseActividades" class="collapse" aria-labelledby="headingActividades" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="#">Ver</a>
                    <a class="collapse-item" href="#">Crear</a>
                </div>
            </div>
        </li> --}}

        @if(Auth::user()->rol->nombre=="Decano")
                 <!-- Nav Item - Roles -->
        <li class="nav-item {{ Nav::isRoute('roles') }}">
            <a class="nav-link collapsed" href="" data-toggle="collapse" data-target="#collapseRoles" aria-expanded="true" aria-controls="collapseRoles">
                <i class="fas fa-2x text-gray-300 fa-user"></i>
                <span>{{ __('Roles') }}</span>
            </a>
            <div id="collapseRoles" class="collapse" aria-labelledby="headingRoles" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('roles.index') }}">Ver</a>
                </div>
            </div>
        </li>
        @endif

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">
        <!--  Nav Item - Manuelaes
        <li class="nav-item {{ Nav::isRoute('manuales') }}">
            <a class="nav-link" href="{{ route('manuales') }}" >
                <i class="fas fa-2x text-gray-300 fa-book"></i>
                <span>{{ __('Manuales') }}</span>
            </a>
        </li> -->

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Search
                <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..." aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form> -->

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="img-profile rounded-circle mr-2" src="/storage/{{ Auth::user()->imagen }}">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->nombre }}</span>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>{{ __('Perfil') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{ __('Cerrar Sesion') }}
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <!-- End of Topbar -->
            <!-- Begin Page Content -->
            <div class="container-fluid">
                @yield('main-content')
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Universidad de Investigacion y Desarrollo 2020</span>
                </div>
                <div class="text-center my-2">Iconos diseñados por <a href="https://smashicons.com/" title="Smashicons">Smashicons</a> from <a href="https://www.flaticon.es/" title="Flaticon">www.flaticon.es</a></div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('¿Listo para salir?') }}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Seleccione "Cerrar Sesion" para cerrar su sesion actual.</div>
            <div class="modal-footer">
                <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancelar') }}</button>
                <a class="btn btn-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Cerrar Sesion') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

@yield('scripts')

<!-- Scripts -->

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('js/demo/datatables-demo.js')}}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

</body>
</html>
