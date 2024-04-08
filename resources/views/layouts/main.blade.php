<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SISMORE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('css')
    <meta content="Responsive bootstrap 4 admin template" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('/') }}public/assets/images/favicon.ico">

    <link href="{{ asset('/') }}public/assets/jquery-ui/jquery-ui.css" rel="stylesheet" />

    <!-- Plugins css-->
    <link href="{{ asset('/') }}public/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet"
        type="text/css" />

    <!-- App css -->
    <link href="{{ asset('/') }}public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"
        id="bootstrap-stylesheet" />
    <link href="{{ asset('/') }}public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <link href="{{ asset('/') }}public/assets/css/app.min.css" rel="stylesheet" type="text/css"
        id="app-stylesheet" />

    {{-- {{assets('/')}} --}}
    <!-- estilos personalizados XD-->
    <link rel="stylesheet" href="{{ asset('/') }}public/assets/css/otros/personalizado.css" type='text/css'>

    {{-- pretty-checkbok css --}}
    <link rel="stylesheet" href="{{ asset('/') }}public/assets/css/otros/pretty-checkbox.min.css" type='text/css'>


    {{-- toastr css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <script src="{{ asset('/') }}public/assets/libs/toastr/toastr.min.css"></script> --}}

    <style>
        .bg-green-0 {
            background-color: #43beac;
        }

        .text-green-0 {
            color: #43beac !important;
        }

        .bg-orange-0 {
            background-color: #f04c27;
        }

        .text-orange-0 {
            color: #f04c27;
        }

        .btn-orange-0 {
            background-color: #f04c27;
        }

        .btn-success-0 {
            color: #fff;
            background-color: #43beac;
            border-color: #43beac;
        }

        .border-orange-0 {
            border-color: #f04c27 !important;
        }

        .text-white-0 {
            color: #fff;
        }

        .border-success-0 {
            border-color: #43beac !important;
        }

        .bg-success-0 {
            background-color: #43beac !important;
        }

        .table-success-0 {
            background-color: #43beac !important;
        }

        .text-success-0 {
            color: #43beac !important;
        }

        .border-plomo-0 {
            border-color: #98a6ad !important;
        }

        .bg-warning-0 {
            background-color: #f5bd22 !important;
        }

        .font-8 {
            font-size: 8px !important;
        }

        .font-9 {
            font-size: 9px !important;
        }

        .font-10 {
            font-size: 10px !important;
        }

        .font-11 {
            font-size: 11px !important;
        }

        .font-31 {
            font-size: 31px !important;
        }

        .font-32 {
            font-size: 32px !important;
        }

        .font-33 {
            font-size: 33px !important;
        }

        .font-34 {
            font-size: 34px !important;
        }

        .font-35 {
            font-size: 35px !important;
        }

        .font-36 {
            font-size: 36px !important;
        }

        .font-37 {
            font-size: 37px !important;
        }

        .font-38 {
            font-size: 38px !important;
        }

        .font-39 {
            font-size: 39px !important;
        }

        .font-40 {
            font-size: 40px !important;
        }

        .font-41 {
            font-size: 41px !important;
        }

        .font-42 {
            font-size: 42px !important;
        }

        .font-43 {
            font-size: 43px !important;
        }

        .font-44 {
            font-size: 44px !important;
        }

        .requerid {
            color: red;
        }
    </style>
</head>


<body> {{--  class="enlarged" data-keep-enlarged="true" --}}

    <!-- Begin page -->
    <div id="wrapper">
        @auth()
            <!-- Topbar Start -->
            <div class="navbar-custom bg-green-0">
                <ul class="list-unstyled topnav-menu float-right mb-0">

                    {{-- <li class="dropdown d-none d-lg-block">
                        <a class="nav-link dropdown-toggle mr-0 waves-effect waves-light" data-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="assets/images/flags/us.jpg" alt="user-image" class="mr-2" height="12"> <span
                                class="align-middle">English <i class="mdi mdi-chevron-down"></i> </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <img src="assets/images/flags/germany.jpg" alt="user-image" class="mr-2" height="12">
                                <span class="align-middle">German</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <img src="assets/images/flags/italy.jpg" alt="user-image" class="mr-2" height="12">
                                <span class="align-middle">Italian</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <img src="assets/images/flags/spain.jpg" alt="user-image" class="mr-2" height="12">
                                <span class="align-middle">Spanish</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <img src="assets/images/flags/russia.jpg" alt="user-image" class="mr-2" height="12">
                                <span class="align-middle">Russian</span>
                            </a>
                        </div>
                    </li> --}}

                    {{-- F11 --}}
                    <li class="dropdown notification-list d-none d-md-inline-block">
                        <a href="#" id="btn-fullscreen" class="nav-link waves-effect waves-light">
                            <i class="mdi mdi-crop-free noti-icon"></i>
                        </a>
                    </li>

                    {{-- notificaciones --}}
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="mdi mdi-bell noti-icon"></i>
                            @if (session('ncon') != 0)
                                <span class="badge badge-danger rounded-circle noti-icon-badge">{{ session('ncon') }}</span>
                            @endif

                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="font-16 m-0">
                                    <span class="float-right">
                                        {{-- <a href="" class="text-dark">
                                            <small>Clear All</small>
                                        </a> --}}
                                    </span>Notificaciones
                                </h5>
                            </div>

                            <div class="slimscroll noti-scroll" style="font-size: 12px">
                                {{-- @if (isset(session('nimp'))) --}}
                                @if (session()->has('nimp'))
                                    @foreach (session('nimp') as $item)
                                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                                            <div class="notify-icon text-danger">
                                                <i class="far fa-bell text-danger"></i>
                                            </div>
                                            <p class="notify-details">{{ $item->nombre }}
                                                [{{ date('d/m/Y', strtotime($item->created_at)) }}]
                                                <small class="noti-time">{{ $item->formato }}</small>
                                            </p>
                                        </a>
                                    @endforeach
                                @endif

                            </div>

                            <!-- All-->
                            {{-- <a href="javascript:void(0);" class="dropdown-item text-center notify-item notify-all">
                                See all notifications
                            </a> --}}

                        </div>
                    </li>

                    {{-- acceso --}}
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="{{ asset('/') }}public/assets/images/users/avatar-1.jpg" alt="user-image"
                                class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">

                            <!-- item-->
                            <a href="#" class="dropdown-item notify-item"
                                onclick="editPerfilUsuario('{{ Auth::user()->id }}')">
                                <i class="mdi mdi-face-profile"></i>
                                <span>Perfil</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            {{--  @if (session()->get('total_sistema') > 1)
                                <a href="{{ route('home') }}" class="dropdown-item notify-item">
                                    <i class="mdi mdi-settings-outline noti-icon"></i>
                                    <span>Cambiar Sistemas</span>
                                </a>
                            @endif --}}

                            {{--  --}}
                            {{-- <a href="{{ route('usuario.layouts.horizontal', auth()->user()->id) }}"
                                class="dropdown-item notify-item">
                                <i class="mdi mdi-page-layout-header noti-icon"></i>
                                <span>Horizontal</span>
                            </a> --}}

                            <!-- item-->
                            <a href="{{ route('logout') }}" class="dropdown-item notify-item"
                                onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-power-settings"></i>
                                <span>Cerrar Sesión</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>

                        </div>
                    </li>

                    {{-- engranaje --}}
                    {{-- <li class="dropdown notification-list">
                        <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                            <i class="mdi mdi-settings-outline noti-icon"></i>
                        </a>
                    </li> --}}


                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="#" class="logo text-center logo-dark">
                        <span class="logo-lg">
                            <img src="{{ asset('/') }}public/assets/images/logo-dark.png" alt="" height="16">
                            <!-- <span class="logo-lg-text-dark">Moltran</span> -->
                        </span>
                        <span class="logo-sm">
                            <!-- <span class="logo-lg-text-dark">M</span> -->
                            <img src="{{ asset('/') }}public/assets/images/logo-sm.png" alt=""
                                height="25">
                        </span>
                    </a>

                    <a href="#" class="logo text-center logo-light">
                        <span class="logo-lg">
                            {{-- <img src="{{ asset('/') }}public/assets/images/logo-light.png" alt=""
                                height="16"> --}}
                            <!-- <span class="logo-lg-text-dark">Moltran</span> -->
                            <img src="{{ asset('/') }}public/assets/images/logo-sm-blanco.png" alt=""
                                height="50">&nbsp;&nbsp;&nbsp;
                            <span class="logo-lg-text-light"></span>
                        </span>
                        <span class="logo-sm">
                            <!-- <span class="logo-lg-text-dark">M</span> -->
                            {{-- <img src="{{ asset('/') }}public/assets/images/logo-sm.png" alt=""
                                height="25"> --}}
                            <img src="{{ asset('/') }}public/assets/images/logo-sm-blanco.png" alt=""
                                height="30">
                        </span>
                    </a>
                </div>
                <!-- LOGO -->

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    {{-- sanguchito --}}
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>

                    <li class="d-none d-sm-block">
                        <form class="app-search" style="max-width: 600px;">
                            <div class="app-search-box">
                                {{-- <span style="color:white; font-size: 25px;"><strong>S I S M O R
                                        E</strong></span><br> --}}
                                <span style="color:white; font-size: 20px;"><strong>SISTEMA DE MONITOREO
                                        REGIONAL</strong></span>
                            </div>
                        </form>
                    </li>

                    {{-- <li class="d-none d-sm-block">
                        <form class="app-search" style="max-width: 600px;">
                            <div class="app-search-box">
                                <span style="color:white; font-size: 20px;"><strong>SISTEMA DE MONITOREO
                                        REGIONAL</strong></span>
                            </div>
                        </form>
                    </li> --}}

                    {{-- buscador --}}
                    {{-- <li class="d-none d-sm-block">
                        <form class="app-search">
                            <div class="app-search-box">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search...">
                                    <div class="input-group-append">
                                        <button class="btn" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </li> --}}
                </ul>
            </div>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu">

                <div class="slimscroll-menu">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <div class="user-box">

                            <div class="float-left">
                                <img src="{{ asset('/') }}public/assets/images/users/avatar-1.jpg" alt=""
                                    class="avatar-md rounded-circle">
                            </div>
                            <div class="user-info">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle font-14" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        {{ explode(' ', Auth::user()->nombre)[0] }} {{ Auth::user()->apellido1 }}
                                        {{-- <i class="mdi mdi-chevron-down"></i> --}}
                                    </a>
                                    {{-- <ul class="dropdown-menu" x-placement="bottom-start"
                                        style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <li><a href="javascript:void(0)" class="dropdown-item"><i
                                                    class="mdi mdi-face-profile mr-2"></i> Profile<div
                                                    class="ripple-wrapper"></div></a></li>
                                        <li><a href="javascript:void(0)" class="dropdown-item"><i
                                                    class="mdi mdi-settings mr-2"></i> Settings</a></li>
                                        <li><a href="javascript:void(0)" class="dropdown-item"><i
                                                    class="mdi mdi-lock mr-2"></i> Lock screen</a></li>
                                        <li><a href="javascript:void(0)" class="dropdown-item"><i
                                                    class="mdi mdi-power-settings mr-2"></i> Logout</a></li>
                                    </ul> --}}
                                </div>
                                <p class="font-10 text-muted m-0">{{ session('sistema_nombre') }}</p>
                            </div>
                        </div>

                        <ul class="metismenu" id="side-menu">
                            @if (session()->has('menuNivel01'))
                                @foreach (session('menuNivel01') as $key => $menu)
                                    <li>
                                        @if ($menu->url == '')
                                            <a href="javascript: void(0);" class="waves-effect">
                                                <i class="{{ $menu->icono }}"></i>
                                                <span> {{ $menu->nombre }} </span>
                                                <span class="menu-arrow"></span>
                                            </a>

                                            <ul class="nav-second-level" aria-expanded="false">

                                                @foreach (session('menuNivel02') as $key => $subMenu)
                                                    @if ($menu->id == $subMenu->dependencia)
                                                        @if ($subMenu->link == '')
                                                            <li><a
                                                                    href="{{ route($subMenu->url) }}">{{ $subMenu->nombre }}</a>
                                                            </li>
                                                        @else
                                                            <li><a
                                                                    href="{{ route($subMenu->url, $subMenu->id) }}">{{ $subMenu->nombre }}</a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endforeach

                                            </ul>
                                        @else
                                            {{-- <a href="{{ route($menu->url, $menu->link == '' ? $menu->parametro : $menu->id) }}" --}}
                                            <a href="{{ route($menu->url, $menu->link == '' ? mb_strtolower(session('sistema_nombre'), 'UTF-8') : $menu->id) }}"
                                                class="waves-effect">
                                                <i class="{{ $menu->icono }}"></i>
                                                <span> {{ $menu->nombre }}</span>
                                            </a>
                                        @endif

                                    </li>
                                @endforeach
                            @endif
                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        @if (session('sistema_id') == 1) <br>
                            {{-- <div class="row">
                                <div class="col-12">
                                    @if ($titlePage != '')
                                        <div class="page-title-box">
                                            <!--h4 class="page-title">
                                                        Ejecución Presupuestal del Gobierno Regional de Ucayali
                                                    </h4-->
                                            <h4 class="page-title">{{ $titlePage }}</h4>

                                            <div class="page-title-right">
                                                <ol class="breadcrumb p-0 m-0">
                                                    {{ $actualizado }}
                                                </ol>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @else
                                        <br>
                                    @endif
                                </div>
                            </div> --}}
                        @elseif (session('sistema_id') == 5)
                            <div class="row">
                                <div class="col-12">
                                    @if (!isset($tipo_acceso))<br>
                                        @if ($titlePage != '')
                                            {{-- <div class="page-title-box">
                                                <h4 class="page-title">{{ $titlePage }}</h4>
                                                <div class="page-title-right">
                                                    <ol class="breadcrumb p-0 m-0">
                                                        @isset($actualizado)
                                                            {{ $actualizado }}
                                                        @endisset
                                                    </ol>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div> --}}
                                        @else
                                            {{-- <br> --}}
                                        @endif
                                    @else
                                        <br>
                                    @endif

                                </div>
                            </div>
                        @else
                            {{-- <br> --}}
                            <div class="row">
                                <div class="col-12">
                                    @if ($titlePage != '')
                                        <div class="page-title-box">
                                            <h4 class="page-title">{{ $titlePage }}</h4>
                                            <div class="page-title-right">
                                                <ol class="breadcrumb p-0 m-0">
                                                    @isset($breadcrumb)
                                                        @foreach ($breadcrumb as $key => $item)
                                                            @if ($key == count($breadcrumb) - 1)
                                                                <li class="breadcrumb-item">{{ $item['titulo'] }}</li>
                                                            @else
                                                                <li class="breadcrumb-item"><a
                                                                        href="{{ $item['url'] }}">{{ $item['titulo'] }}</a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @endisset
                                                </ol>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @else
                                        <br>
                                    @endif

                                </div>
                            </div>

                        @endif


                        @yield('content')

                    </div>
                    <!-- end container-fluid -->

                </div>
                <!-- end content -->



                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            Derechos Reservados - Gobierno Regional de Ucayali
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->



            </div>

        @endauth
        <!-- ============================================================== -->
        <!-- endauth content -->
        <!-- ============================================================== -->


        @guest()
            {{-- @yield('content') --}}

            <!-- Topbar Start -->
            <div class="navbar-custom bg-green-0">
                <ul class="list-unstyled topnav-menu float-right mb-0">

                    {{-- F11 - pantalla completa --}}
                    <li class="dropdown notification-list d-none d-md-inline-block">
                        <a href="#" id="btn-fullscreen" class="nav-link waves-effect waves-light">
                            <i class="mdi mdi-crop-free noti-icon"></i>
                        </a>
                    </li>


                    {{-- acceso --}}
                    <li class="dropdown notification-list">{{-- data-toggle="dropdown" --}}
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light"
                            href="{{ route('login') }}" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="{{ asset('/') }}public/assets/images/users/avatar-1.jpg" alt="user-image"
                                class="rounded-circle"> <span>Iniciar Sesión</span>
                        </a>
                        {{-- <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <a href="{{ route('login') }}" class="dropdown-item notify-item">
                                <i class="mdi mdi-face-profile"></i>
                                <span>Iniciar Sesión</span>
                            </a>
                        </div> --}}
                    </li>

                    {{-- engranaje --}}
                    {{-- <li class="dropdown notification-list">
                        <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                            <i class="mdi mdi-settings-outline noti-icon"></i>
                        </a>
                    </li> --}}


                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="index.html" class="logo text-center logo-dark">
                        <span class="logo-lg">
                            <img src="{{ asset('/') }}public/assets/images/logo-dark.png" alt=""
                                height="16">
                            <!-- <span class="logo-lg-text-dark">Moltran</span> -->
                        </span>
                        <span class="logo-sm">
                            <!-- <span class="logo-lg-text-dark">M</span> -->
                            <img src="{{ asset('/') }}public/assets/images/logo-sm.png" alt=""
                                height="25">
                        </span>
                    </a>

                    <a href="index.html" class="logo text-center logo-light">
                        <span class="logo-lg">
                            {{-- <img src="{{ asset('/') }}public/assets/images/logo-light.png" alt=""
                                height="16"> --}}
                            <!-- <span class="logo-lg-text-dark">Moltran</span> -->
                            <img src="{{ asset('/') }}public/assets/images/logo-sm-blanco.png" alt=""
                                height="50">&nbsp;&nbsp;&nbsp;
                            <span class="logo-lg-text-light"></span>
                        </span>
                        <span class="logo-sm">
                            <!-- <span class="logo-lg-text-dark">M</span> -->
                            {{-- <img src="{{ asset('/') }}public/assets/images/logo-sm.png" alt=""
                                height="25"> --}}
                            <img src="{{ asset('/') }}public/assets/images/logo-sm-blanco.png" alt=""
                                height="30">
                        </span>
                    </a>
                </div>
                <!-- LOGO -->

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    {{-- sanguchito --}}
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>

                    <li class="d-none d-sm-block">
                        <form class="app-search" style="max-width: 600px;">
                            <div class="app-search-box">
                                {{-- <span style="color:white; font-size: 25px;"><strong>S I S M O R
                                        E</strong></span><br> --}}
                                <span style="color:white; font-size: 20px;"><strong>SISTEMA DE MONITOREO
                                        REGIONAL</strong></span>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu">

                <div class="slimscroll-menu">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <div class="user-box">

                            <div class="float-left">
                                <img src="{{ asset('/') }}public/assets/images/users/avatar-1.jpg" alt=""
                                    class="avatar-md rounded-circle">
                            </div>
                            <div class="user-info">
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        Publico {{-- <i class="mdi mdi-chevron-down"></i> --}}
                                    </a>
                                    {{-- <ul class="dropdown-menu" x-placement="bottom-start"
                                        style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <li><a href="javascript:void(0)" class="dropdown-item"><i
                                                    class="mdi mdi-face-profile mr-2"></i> Profile<div
                                                    class="ripple-wrapper"></div></a></li>
                                        <li><a href="javascript:void(0)" class="dropdown-item"><i
                                                    class="mdi mdi-settings mr-2"></i> Settings</a></li>
                                        <li><a href="javascript:void(0)" class="dropdown-item"><i
                                                    class="mdi mdi-lock mr-2"></i> Lock screen</a></li>
                                        <li><a href="javascript:void(0)" class="dropdown-item"><i
                                                    class="mdi mdi-power-settings mr-2"></i> Logout</a></li>
                                    </ul> --}}
                                </div>
                                <p class="font-13 text-muted m-0">{{ session('sistema_nombre') }}</p>
                            </div>
                        </div>

                        <ul class="metismenu" id="side-menu">
                            <li>
                                <a href="{{ route('acceso.publico.modulo', session('sistema_publico_id')) }}"
                                    class="waves-effect">
                                    <i class="mdi mdi-home"></i>
                                    <span> Dashboard </span>
                                </a>
                            </li>

                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">



                    <!-- Start Content-->
                    <div class="container-fluid">

                        @if (session('sistema_publico_id') == 5)
                            {{-- <div class="row">
                                <div class="col-12">
                                    @if ($titlePage != '')
                                        <div class="page-title-box">
                                            <h4 class="page-title">{{ $titlePage }}</h4>
                                            <div class="page-title-right">
                                                <ol class="breadcrumb p-0 m-0">
                                                    {{ $actualizado }}
                                                </ol>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @else
                                        <br>
                                    @endif
                                </div>
                            </div> --}}
                            <br>
                        @elseif(session('sistema_publico_id') == 1)
                            <div class="row">
                                <div class="col-12">
                                    @if ($titlePage != '')
                                        <div class="page-title-box">
                                            <h4 class="page-title">{{ $titlePage }}</h4>
                                            <div class="page-title-right">
                                                <ol class="breadcrumb p-0 m-0">
                                                    {{ $actualizado }}
                                                </ol>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @else
                                        <br>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-12">
                                    @if ($titlePage != '')
                                        <div class="page-title-box">
                                            <h4 class="page-title">{{ $titlePage }}</h4>
                                            <div class="page-title-right">
                                                <ol class="breadcrumb p-0 m-0">
                                                    @isset($breadcrumb)
                                                        @foreach ($breadcrumb as $key => $item)
                                                            @if ($key == count($breadcrumb) - 1)
                                                                <li class="breadcrumb-item">{{ $item['titulo'] }}</li>
                                                            @else
                                                                <li class="breadcrumb-item"><a
                                                                        href="{{ $item['url'] }}">{{ $item['titulo'] }}</a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @endisset
                                                </ol>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @else
                                        <br>
                                    @endif

                                </div>
                            </div>
                        @endif


                        @yield('content')

                    </div>
                    <!-- end container-fluid -->

                </div>
                <!-- end content -->



                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            Derechos Reservados - Gobierno Regional de Ucayali
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>

        @endguest

        <!-- ============================================================== -->
        <!-- endguest content -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    @auth
        <!-- Bootstrap modal -->
        <div id="modal_perfil_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            style="overflow:auto">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="form_title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="form_perfil_usuario" class="form-horizontal" autocomplete="off">
                            @csrf
                            <input type="hidden" id="idp" name="idp">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card pb-0">
                                        {{-- <div class="card-header">
                                    <h3 class="card-title"></h3>
                                </div> --}}
                                        <div class="card-body">
                                            <div class="form">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>DNI<span class="required">*</span></label>
                                                            <input id="dnip" name="dnip" class="form-control"
                                                                type="text" maxlength="8"
                                                                onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                                            <span class="help-block"></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Nombres<span class="required">*</span></label>
                                                            <input id="nombrep" name="nombrep" class="form-control"
                                                                type="text"
                                                                onkeyup="this.value=this.value.toUpperCase()">
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <label>Apellido Paterno<span class="required">*</span></label>
                                                            <input id="apellido1p" name="apellido1p" class="form-control"
                                                                type="text"
                                                                onkeyup="this.value=this.value.toUpperCase()">
                                                            <span class="help-block"></span>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Apellido Materno<span class="required">*</span></label>
                                                            <input id="apellido2p" name="apellido2p" class="form-control"
                                                                type="text"
                                                                onkeyup="this.value=this.value.toUpperCase()">
                                                            <span class="help-block"></span>
                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <label>Sexo<span class="required">*</span></label>
                                                            <select id="sexop" name="sexop" class="form-control">
                                                                <option value="M">MASCULINO</option>
                                                                <option value="F">FEMENINO</option>
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Correo Electronico<span
                                                                    class="required">*</span></label>
                                                            <input id="emailp" name="emailp" class="form-control"
                                                                type="email" required>
                                                            <span class="help-block"></span>
                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Celular
                                                                <!--span class="required">*</span-->
                                                            </label>
                                                            <input id="celularp" name="celularp" class="form-control"
                                                                type="text">
                                                            <span class="help-block"></span>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Oficinas<span class="required">*</span></label>
                                                            <select name="entidadp" id="entidadp" class="form-control"
                                                                disabled>
                                                                <option value="">SELECCIONAR</option>
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>


                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Oficina<span class="required">*</span></label>
                                                            <select name="entidadgerenciap" id="entidadgerenciap"
                                                                class="form-control" disabled>
                                                                <option value="">SELECCIONAR</option>
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>
                                                        {{-- <div class="col-md-6">
                                                            <label>Oficinas<span class="required">*</span></label>
                                                            <select name="entidadoficinap" id="entidadoficinap"
                                                                class="form-control" disabled>
                                                                <option value="">SELECCIONAR</option>
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div> --}}
                                                        <div class="col-md-6">
                                                            <label>Cargo<span class="required">*</span></label>
                                                            <input type="text" name="cargop" id="cargop"
                                                                class="form-control" readonly>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Usuario<span class="required">*</span></label>
                                                            <input id="usuariop" name="usuariop" class="form-control"
                                                                type="text">
                                                            <span class="help-block"></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Contraseña<span class="required"
                                                                    id="password-required">*</span></label>
                                                            <input id="passwordp" name="passwordp" class="form-control"
                                                                type="password">
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- .form -->
                                        </div>
                                        <!-- card-body -->
                                    </div>
                                    <!-- card -->
                                </div>
                                <!-- col -->
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="button" id="btnSavePerfilUsuario" onclick="savePerfilUsuario()"
                            class="btn btn-primary">Guardar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- End Bootstrap modal -->
    @endauth

    {{--  --}}

    @auth()
        @if (session('total_sistema') > 1)
            <!-- Right Sidebar -->
            <div class="right-bar">
                <div class="rightbar-title">
                    <a href="javascript:void(0);" class="right-bar-toggle float-right">
                        <i class="mdi mdi-close"></i>
                    </a>
                    <h4 class="font-17 m-0 text-white">Modulos</h4>
                </div>
                <div class="slimscroll-menu">
                    <div class="p-2">
                        <div class="row">
                            @foreach (session('sistemas') as $sistema)
                                <div class="col-md-12">
                                    <div
                                        class="card-box {{ $sistema->sistema_id == session('sistema_id') ? 'bg-secondary' : '' }} border m-1">
                                        @php
                                            //   $sis=ucwords($sistema->nombre);
                                            // $str = ucfirst($sistema->nombre);
                                        @endphp
                                        <a
                                            href="{{ route('sistema_acceder', mb_strtolower($sistema->nombre, 'UTF-8')) }}">
                                            <div class="media">
                                                <div class="avatar-md bg-info rounded-circle mr-2"
                                                    style="height: 2.5rem;width: 2.5rem;">
                                                    <i class="{{ $sistema->icono }} avatar-title font-26 text-white"></i>
                                                </div>
                                                <div class="media-body align-self-center">
                                                    <div
                                                        class="text-left {{ $sistema->sistema_id == session('sistema_id') ? 'text-white' : '' }}">
                                                        <p class="mb-0 mt-1 text-truncate">{{ $sistema->nombre }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Right-bar -->

            <!-- Right bar overlay-->
            <div class="rightbar-overlay"></div>
            <a href="javascript:void(0);" class="right-bar-toggle demos-show-btn">
                <i class="mdi mdi-settings-outline mdi-spin"></i> &nbsp;MODULOS
            </a>
        @endif
    @endauth

    {{--  --}}

    @guest()

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div class="rightbar-title">
                <a href="javascript:void(0);" class="right-bar-toggle float-right">
                    <i class="mdi mdi-close"></i>
                </a>
                <h4 class="font-17 m-0 text-white">Modulos</h4>
            </div>
            <div class="slimscroll-menu">
                <div class="p-4">
                    @foreach (session('sistemas_publico') as $sistema)
                        <div class="col-md-12">
                            <div class="card-box">
                                <a href="{{ route('acceso.publico.modulo', $sistema->nombre) }}">
                                    <div class="media">
                                        <div class="avatar-md bg-info rounded-circle mr-2"
                                            style="height: 2.5rem;width: 2.5rem;">
                                            <i class="{{ $sistema->icono }} avatar-title font-26 text-white"></i>
                                        </div>
                                        <div class="media-body align-self-center">
                                            <div class="text-left">{{-- text-right --}}
                                                {{-- <h4 class="font-20 my-0 font-weight-bold"><span
                                                data-plugin="counterup">15852</span></h4> --}}
                                                <p class="mb-0 mt-1 text-truncate">{{ $sistema->nombre }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="mt-4">
                                <h6 class="text-uppercase">Target <span class="float-right">60%</span></h6>
                                <div class="progress progress-sm m-0">
                                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60"
                                        aria-valuemin="0" aria-valuemax="100" style="width:  100%">
                                        <span class="sr-only">60% Complete</span>
                                    </div>
                                </div>
                            </div> --}}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        <a href="javascript:void(0);" class="right-bar-toggle demos-show-btn">
            <i class="mdi mdi-settings-outline mdi-spin"></i> &nbsp;MODULOS
        </a>

    @endguest



    <!-- Vendor js -->
    <script src="{{ asset('/') }}public/assets/js/vendor.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/moment/moment.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-scrollto/jquery.scrollTo.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    {{-- autocompletar --}}
    <script src="{{ asset('/') }}public/assets/jquery-ui/jquery-ui.js"></script>

    <!-- Chat app -->
    <script src="{{ asset('/') }}public/assets/js/pages/jquery.chat.js"></script>

    <!-- Todo app -->
    <script src="{{ asset('/') }}public/assets/js/pages/jquery.todo.js"></script>

    <!-- flot chart -->
    {{-- <script src="{{ asset('/') }}public/assets/libs/flot-charts/jquery.flot.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/flot-charts/jquery.flot.time.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/flot-charts/jquery.flot.tooltip.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/flot-charts/jquery.flot.resize.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/flot-charts/jquery.flot.pie.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/flot-charts/jquery.flot.selection.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/flot-charts/jquery.flot.stack.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/flot-charts/jquery.flot.crosshair.js"></script> --}}

    <!-- Dashboard init JS -->
    {{-- <script src="{{ asset('/') }}public/assets/js/pages/dashboard.init.js"></script> --}}

    <!-- App js -->
    <script src="{{ asset('/') }}public/assets/js/app.min.js"></script>

    <script src="{{ asset('/') }}public/assets/js/bootbox.js"></script>

    {{-- toastr js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- <script src="{{ asset('/') }}public/assets/libs/toastr/toastr.min.js"></script> --}}

    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        var paleta_colores = ['#317eeb', '#ef5350', '#33b86c', '#33b86c', '#33b86c', '#6c757d', '#ec407a', '#7e57c2',
            '#ffd740'
        ];
        var table_language = { // class="custom-select custom-select-sm form-control form-control-sm"
            "lengthMenu": "Mostrar " +
                `<select>
                        <option value = '10'> 10</option>
                        <option value = '25'> 25</option>
                        <option value = '50'> 50</option>
                        <option value = '100'>100</option>
                        <option value = '-1'>Todos</option>
                        </select>` + " registros por página",
            "info": "Mostrando la página _PAGE_ de _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(Filtrado de _MAX_ registros totales)",
            "emptyTable": "No hay datos disponibles en la tabla.",
            "info": "Del _START_ al _END_ de _TOTAL_ registros ",
            "infoEmpty": "Mostrando 0 registros de un total de 0. registros",
            "infoFiltered": "(filtrados de un total de _MAX_ )",
            "infoPostFix": "",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "searchPlaceholder": "Dato para buscar",
            "zeroRecords": "No se han encontrado coincidencias.",
            "paginate": {
                "next": "siguiente",
                "previous": "anterior"
            }
        };
        $(document).ready(function() {
            @auth
            //cargar_entidad(0);
        @endauth

        });
        /* toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "2000",
            "extendedTimeOut": "1000"
        } */



        function savePerfilUsuario() {
            $('#btnSavePerfilUsuario').text('guardando...');
            $('#btnSavePerfilUsuario').attr('disabled', true);
            var url;
            url = "{{ url('/') }}/Usuario/ajax_updateaux";
            msgsuccess = "El registro fue actualizado exitosamente.";
            msgerror = "El registro no se pudo actualizar. Verifique la operación";
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_perfil_usuario').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        $('#modal_perfil_usuario').modal('hide');
                        toastr.success(msgsuccess, 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSavePerfilUsuario').text('Guardar');
                    $('#btnSavePerfilUsuario').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#btnSavePerfilUsuario').text('Guardar');
                    $('#btnSavePerfilUsuario').attr('disabled', false);
                    toastr.error(msgerror, 'Mensaje');
                }
            });
        }

        function editPerfilUsuario(id) {
            $('#form_perfil_usuario')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.col-md-6').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ url('/') }}/Usuario/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="idp"]').val(data.usuario.id);
                    $('[name="dnip"]').val(data.usuario.dni);
                    $('[name="nombrep"]').val(data.usuario.nombre);
                    $('[name="apellido1p"]').val(data.usuario.apellido1);
                    $('[name="apellido2p"]').val(data.usuario.apellido2);
                    $('[name="sexop"]').val(data.usuario.sexo);
                    $('[name="emailp"]').val(data.usuario.email);
                    $('[name="celularp"]').val(data.usuario.celular);
                    $('[name="usuariop"]').val(data.usuario.usuario);
                    $('[name="tipop"]').val(data.usuario.tipo);
                    $('[name="cargop"]').val(data.usuario.cargo);
                    if (data.entidad) {
                        var de1 = data.entidad.entidad;
                        //var de2 = data.entidad.gerencia;
                        var de2 = data.entidad.oficina;
                        /* begin entidad */
                        $.ajax({
                            url: "{{ route('entidad.ajax.cargar') }}",
                            data: {
                                "dependencia": 0
                            },
                            type: 'get',
                            success: function(data1) {
                                $("#entidadp option").remove();
                                var options = '<option value="">SELECCIONAR</option>';
                                $.each(data1.entidades, function(index, value) {
                                    ss = (de1 == value
                                        .id ? "selected" :
                                        "");
                                    options += "<option value='" + value.id + "' " + ss +
                                        ">" + value.nombre +
                                        "</option>"
                                });
                                $("#entidadp").append(options);
                                /* begin gerencia */
                                $.ajax({
                                    url: "{{ route('entidad.ajax.cargar') }}",
                                    data: {
                                        "dependencia": de1
                                    },
                                    type: 'get',
                                    success: function(data2) {
                                        $("#entidadgerenciap option").remove();
                                        var options =
                                            '<option value="">SELECCIONAR</option>';
                                        $.each(data2.entidades, function(index, value) {
                                            ss = (de2 == value
                                                .id ? "selected" : "");
                                            options += "<option value='" + value
                                                .id + "' " + ss + ">" + value
                                                .nombre +
                                                "</option>"
                                        });
                                        $("#entidadgerenciap").append(options);
                                        /* begin oficina */
                                        /* $.ajax({
                                            url: "{{ route('entidad.ajax.cargar') }}",
                                            data: {
                                                "dependencia": de2
                                            },
                                            type: 'get',
                                            success: function(data3) {
                                                $("#entidadoficinap option")
                                                    .remove();
                                                var options =
                                                    '<option value="">SELECCIONAR</option>';
                                                $.each(data3.entidades,
                                                    function(index,
                                                        value) {
                                                        ss = (de3 ==
                                                            value
                                                            .id ?
                                                            "selected" :
                                                            "");
                                                        options +=
                                                            "<option value='" +
                                                            value.id +
                                                            "' " + ss +
                                                            ">" + value
                                                            .nombre +
                                                            "</option>"
                                                    });
                                                $("#entidadoficinap")
                                                    .append(
                                                        options);
                                            },
                                            error: function(jqXHR, textStatus,
                                                errorThrown) {
                                                console.log(jqXHR);
                                            },
                                        }); */
                                        /* end oficina */
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        console.log(jqXHR);
                                    },
                                });
                                /* end gerencia */
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(jqXHR);
                            },
                        });
                        /* end entidad */
                    }
                    $('#modal_perfil_usuario').modal('show');
                    $('#modal_perfil_usuario .modal-title').text('Modificar Datos Personales');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('Error get data from ajax', 'Mensaje');
                }
            });
        }

        /* function cargar_entidad(id) {
            $.ajax({
                url: "{{ url('/') }}/Usuario/CargarEntidad/3",
                type: 'get',
                success: function(data) {
                    $("#unidadejecutorap option").remove();
                    var options = '<option value="">SELECCIONAR</option>';
                    $.each(data.unidadejecutadora, function(index, value) {
                        ss = (id == "" ? "" : (id == value.id ? "selected" : ""));
                        options += "<option value='" + value.id + "' " + ss + ">" + value
                            .unidad_ejecutora +
                            "</option>"
                    });
                    $("#unidadejecutorap").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        } */

        /* function cargar_gerencia2(id) {
            $("#entidadoficina option").remove();
            $.ajax({
                url: "{{ url('/') }}/Usuario/CargarGerencia/" + $('#unidadejecutorap').val(),
                type: 'get',
                success: function(data) {
                    $("#entidadgerenciap option").remove();
                    var options = '<option value="">SELECCIONAR</option>';
                    $.each(data.gerencias, function(index, value) {
                        ss = (id == "" ? "" : (id == value.id ? "selected" : ""));
                        options += "<option value='" + value.id + "' " + ss + ">" + value.entidad +
                            "</option>"
                    });
                    $("#entidadgerenciap").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        } */

        /* function cargar_oficina2(id) {
            $.ajax({
                url: "{{ url('/') }}/Usuario/CargarOficina/" + $('#entidadgerenciap').val(),
                type: 'get',
                success: function(data) {
                    $("#entidadoficinap option").remove();
                    var options = '<option value="">SELECCIONAR</option>';
                    $.each(data.oficinas, function(index, value) {
                        ss = (id == "" ? "" : (id == value.id ? "selected" : ""));
                        options += "<option value='" + value.id + "' " + ss + ">" + value.entidad +
                            "</option>"
                    });
                    $("#entidadoficinap").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        } */
    </script>
    @yield('js')
</body>

</html>
