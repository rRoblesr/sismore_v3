<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SISMORE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style rel="stylesheet" type="text/css" media="print">
        /* @media  print {
                body {
                    transform: scale(1.5);
                    transform-origin: top;
                }
            } */

        /* @media  print {
                body {
                    margin-top: 5px;
                    margin-left: 10px;
                    transform: scale(0.43);
                    transform-origin: 0 0;
                }
            } */

        @media print {
            body {
                margin-top: 5px;
                margin-left: 10px;
                transform: scale(var(--scale-factor));
                transform-origin: 0 0;
            }
        }
    </style>
    <style>
        .tablex thead th {
            padding: 6px;
            text-align: center;
        }

        .tablex thead td {
            padding: 6px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tablex tbody td,
        .tablex tbody th,
        .tablex tfoot td,
        .tablex tfoot th {
            padding: 6px;
        }

        .fuentex {
            font-size: 10px;
            font-weight: bold;
        }
    </style>
    <meta content="Responsive bootstrap 4 admin template" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="http://localhost/_laravel/git/sismore_v3/public/assets/images/favicon.ico">

    <link href="http://localhost/_laravel/git/sismore_v3/public/assets/jquery-ui/jquery-ui.css" rel="stylesheet" />

    <!-- Plugins css-->
    <link href="http://localhost/_laravel/git/sismore_v3/public/assets/libs/sweetalert2/sweetalert2.min.css"
        rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="http://localhost/_laravel/git/sismore_v3/public/assets/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" id="bootstrap-stylesheet" />
    <link href="http://localhost/_laravel/git/sismore_v3/public/assets/css/icons.min.css" rel="stylesheet"
        type="text/css" />

    <link href="http://localhost/_laravel/git/sismore_v3/public/assets/css/app.min.css" rel="stylesheet" type="text/css"
        id="app-stylesheet" />


    <!-- estilos personalizados XD-->
    <link rel="stylesheet" href="http://localhost/_laravel/git/sismore_v3/public/assets/css/otros/personalizado.css"
        type='text/css'>


    <link rel="stylesheet"
        href="http://localhost/_laravel/git/sismore_v3/public/assets/css/otros/pretty-checkbox.min.css" type='text/css'>



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
        .bg-green-0 {
            background-color: #43beac;
        }

        .text-green-0 {
            background-color: #43beac;
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
    </style>
</head>


<body class="enlarged" data-keep-enlarged="true">

    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Start -->
        <div class="navbar-custom bg-green-0">
            <ul class="list-unstyled topnav-menu float-right mb-0">




                <li class="dropdown notification-list d-none d-md-inline-block">
                    <a href="#" id="btn-fullscreen" class="nav-link waves-effect waves-light">
                        <i class="mdi mdi-crop-free noti-icon"></i>
                    </a>
                </li>


                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="mdi mdi-bell noti-icon"></i>

                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="font-16 m-0">
                                <span class="float-right">

                                </span>Notificaciones
                            </h5>
                        </div>

                        <div class="slimscroll noti-scroll" style="font-size: 12px">


                        </div>

                        <!-- All-->


                    </div>
                </li>


                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="http://localhost/_laravel/git/sismore_v3/public/assets/images/users/avatar-1.jpg"
                            alt="user-image" class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">

                        <!-- item-->
                        <a href="#" class="dropdown-item notify-item" onclick="editPerfilUsuario('49')">
                            <i class="mdi mdi-face-profile"></i>
                            <span>Perfil</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- item-->





                        <!-- item-->
                        <a href="http://localhost/_laravel/git/sismore_v3/logout" class="dropdown-item notify-item"
                            onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-power-settings"></i>
                            <span>Cerrar Sesión</span>
                        </a>

                        <form id="logout-form" action="http://localhost/_laravel/git/sismore_v3/logout" method="POST"
                            class="d-none">
                            <input type="hidden" name="_token" value="E1CSJnlRAntdzSPIxaD7D6KjYG1LhD8qpoxVcNRv">
                        </form>

                    </div>
                </li>





            </ul>

            <!-- LOGO -->
            <div class="logo-box">
                <a href="#" class="logo text-center logo-dark">
                    <span class="logo-lg">
                        <img src="http://localhost/_laravel/git/sismore_v3/public/assets/images/logo-dark.png"
                            alt="" height="16">
                        <!-- <span class="logo-lg-text-dark">Moltran</span> -->
                    </span>
                    <span class="logo-sm">
                        <!-- <span class="logo-lg-text-dark">M</span> -->
                        <img src="http://localhost/_laravel/git/sismore_v3/public/assets/images/logo-sm.png"
                            alt="" height="25">
                    </span>
                </a>

                <a href="#" class="logo text-center logo-light">
                    <span class="logo-lg">

                        <!-- <span class="logo-lg-text-dark">Moltran</span> -->
                        <img src="http://localhost/_laravel/git/sismore_v3/public/assets/images/logo-sm-blanco.png"
                            alt="" height="50">&nbsp;&nbsp;&nbsp;
                        <span class="logo-lg-text-light">SISMORE</span>
                    </span>
                    <span class="logo-sm">
                        <!-- <span class="logo-lg-text-dark">M</span> -->

                        <img src="http://localhost/_laravel/git/sismore_v3/public/assets/images/logo-sm-blanco.png"
                            alt="" height="30">
                    </span>
                </a>
            </div>
            <!-- LOGO -->

            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">

                <li>
                    <button class="button-menu-mobile waves-effect waves-light">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </li>

                <li class="d-none d-sm-block">
                    <form class="app-search" style="max-width: 600px;">
                        <div class="app-search-box">

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
                            <img src="http://localhost/_laravel/git/sismore_v3/public/assets/images/users/avatar-1.jpg"
                                alt="" class="avatar-md rounded-circle">
                        </div>
                        <div class="user-info">
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    RONALD AMERICO
                                </a>

                            </div>
                            <p class="font-13 text-muted m-0">EDUCACION</p>
                        </div>
                    </div>

                    <ul class="metismenu" id="side-menu">

                        <li>
                            <a href="http://localhost/_laravel/git/sismore_v3/home/1" class="waves-effect">
                                <i class="mdi mdi-home"></i>
                                <span> Inicio</span>
                            </a>

                        </li>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="mdi mdi-folder-upload-outline""></i>
                                <span> Importación </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="http://localhost/_laravel/git/sismore_v3/ImporPadronWeb/Importar">Padron
                                        Web</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/CuadroAsigPersonal/Importar">Nexus
                                        Minedu</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/ImporMatriculaGeneral/Importar">Matricula
                                        SIAGIE</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/ImporIS/Importar">Instituto
                                        Superior</a>
                                </li>
                                <li><a
                                        href="http://localhost/_laravel/git/sismore_v3/ImporTableta/Importar">Tableta</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/ImporTableta/Importar">Textos
                                        Escolares</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/ImporCensoDocente/Importar">Censo
                                        Docente</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/ImporCensoMatricula/Importar">Censo
                                        Matricula</a>
                                </li>

                            </ul>

                        </li>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="ion ion-md-clipboard""></i>
                                <span> Registros </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="http://localhost/_laravel/git/sismore_v3/Mantenimiento/RER/Principal">Registro
                                        RER</a>
                                </li>
                                <li><a
                                        href="http://localhost/_laravel/git/sismore_v3/Mantenimiento/PadronRER/Principal">Asignación
                                        RER</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/PadronEIB/Principal">Padron
                                        EIB</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/Mantenimiento/Lengua/Principal">Registro
                                        Lenguas</a>
                                </li>
                                <li><a
                                        href="http://localhost/_laravel/git/sismore_v3/Man/INDICADORGENERAL/Principal/E">Indicador</a>
                                </li>

                            </ul>

                        </li>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="mdi mdi-school-outline""></i>
                                <span> Educación Básica </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/Home/01">Avance de
                                        Matriculas</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/MatriculaGeneral/EBR">Básica
                                        Regular</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/MatriculaDetalle/EBE">Básica
                                        Especial</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Básica
                                        Alternativa</a>
                                </li>

                            </ul>

                        </li>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="mdi mdi-poll-box""></i>
                                <span> Educación Técnica </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="http://localhost/_laravel/git/sismore_v3/TecnicoProductiva/Principal">Técnico
                                        Productiva</a>
                                </li>

                            </ul>

                        </li>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="ion ion-ios-people""></i>
                                <span> Educación Superior </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="http://localhost/_laravel/git/sismore_v3/SuperiorPedagogico/Principal">Superior
                                        Pedagógico</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/SuperiorTecnologico/Principal">Superior
                                        Tecnológico</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/SuperiorArtistico/Principal">Superior
                                        Artístico</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Superior
                                        Universitaria</a>
                                </li>

                            </ul>

                        </li>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="ion ion-md-person-add""></i>
                                <span> Personal de Educación </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="http://localhost/_laravel/git/sismore_v3/Plaza/Docentes/Principal">Personal
                                        Docente</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Auxiliares de
                                        Educación</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Personal
                                        Administrativo</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/Plaza/Docentes/CoberturaDePlaza">Cobertura
                                        de Plazas</a>
                                </li>

                            </ul>

                        </li>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="ion ion-md-apps""></i>
                                <span> Instituciones Educativas </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Locales
                                        Escolares</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Servicios
                                        Educativos</a>
                                </li>
                                <li><a
                                        href="http://localhost/_laravel/git/sismore_v3/presupuesto/Principal/vista2">Infraestructura</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/PadronRER/Avance">Redes
                                        Educativas</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Directorio de
                                        IIEE</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/MatriculaDetalle/EIB">Interculural
                                        Bilingüe</a>
                                </li>

                            </ul>

                        </li>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="mdi mdi-home-group""></i>
                                <span> Recursos Educativos </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="http://localhost/_laravel/git/sismore_v3/Tableta/Principal">Dotación de
                                        Tabletas</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Dotación de
                                        Materiales</a>
                                </li>

                            </ul>

                        </li>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="mdi mdi-equalizer-outline""></i>
                                <span> Ejecución Presupuestal </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="http://localhost/_laravel/git/sismore_v3/SiafGastos/NivelGobiernos">Gasto
                                        Presupuestal</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Ingreso
                                        Presupuestal</a>
                                </li>
                                <li><a
                                        href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">intervenciones</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Proyecto de
                                        Inversión</a>
                                </li>

                            </ul>

                        </li>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="mdi mdi-equalizer-outline""></i>
                                <span> Indicadores Educativos </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Indicadores
                                        PDRC</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Indicadores
                                        PEI</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Indicadores
                                        PPR</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Indicadores
                                        PER</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Indicadores
                                        FED</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/SINRUTA">Indicadores
                                        CdD</a>
                                </li>

                            </ul>

                        </li>
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="ion ion-md-cloud-download""></i>
                                <span> Base de Datos </span>
                                <span class="menu-arrow"></span>
                            </a>

                            <ul class="nav-second-level" aria-expanded="false">

                                <li><a href="http://localhost/_laravel/git/sismore_v3/ImporPadronWeb/Exportar">Padron
                                        WEB</a>
                                </li>
                                <li><a href="http://localhost/_laravel/git/sismore_v3/ImporMatricula/Exportar">Matricula
                                        SIAGIE</a>
                                </li>
                                <li><a
                                        href="http://localhost/_laravel/git/sismore_v3/CuadroAsigPersonal/Exportar">Nexus</a>
                                </li>

                            </ul>

                        </li>
                        <li>
                            <a href="http://localhost/_laravel/git/sismore_v3/Clasificador/01" class="waves-effect">
                                <i class="mdi mdi-equalizer-outline"></i>
                                <span> Indicadores</span>
                            </a>

                        </li>
                        <li>
                            <a href="http://localhost/_laravel/git/sismore_v3/Clasificador/04" class="waves-effect">
                                <i class="mdi mdi-chart-tree"></i>
                                <span> PDRC</span>
                            </a>

                        </li>
                        <li>
                            <a href="http://localhost/_laravel/git/sismore_v3/Clasificador/05" class="waves-effect">
                                <i class="mdi mdi-equalizer"></i>
                                <span> PEI</span>
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

                    <div class="row">
                        <div class="col-12">
                            <br>


                        </div>
                    </div>


                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-success-0">
                                            <div class="card-widgets">
                                                <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="verpdf(6)" title='FICHA TÉCNICA'><i
                                                        class="fas fa-file"></i> Ficha Técnica</button>
                                                <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="location.reload()" title='ACTUALIZAR'><i
                                                        class=" fas fa-history"></i></button>

                                                <a href="http://localhost/_laravel/git/sismore_v3/INDICADOR/Home/01/xxx"
                                                    class="btn btn-orange-0 btn-xs" title='IMPRIMIR'><i
                                                        class="fa fa-print"></i></a>
                                            </div>
                                            <h3 class="card-title text-white">Número de estudiantes matriculados en
                                                Educación Básica
                                            </h3>
                                        </div>
                                        <div class="card-body pb-0">
                                            <div class="form-group row align-items-center vh-5">
                                                <div class="col-lg-5 col-md-5 col-sm-5">
                                                    <h5 class="page-title font-12">Actualizado al 31 de diciembre del
                                                        2021</h5>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1  ">
                                                    <select id="anio" name="anio"
                                                        class="form-control btn-xs font-11" onchange="cargarCards();">
                                                        <option value="0">AÑO</option>
                                                        <option value="3">
                                                            2018</option>
                                                        <option value="4">
                                                            2019</option>
                                                        <option value="5">
                                                            2020</option>
                                                        <option value="6" selected>
                                                            2021</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="provincia" name="provincia"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarDistritos();cargarCards();">
                                                        <option value="0">PROVINCIA</option>
                                                        <option value="35">
                                                            CORONEL PORTILLO</option>
                                                        <option value="43">
                                                            ATALAYA</option>
                                                        <option value="48">
                                                            PADRE ABAD</option>
                                                        <option value="54">
                                                            PURUS</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="distrito" name="distrito"
                                                        class="form-control btn-xs font-11" onchange="cargarCards();">
                                                        <option value="0">DISTRITO</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="gestion" name="gestion"
                                                        class="form-control btn-xs font-11" onchange="cargarCards();">
                                                        <option value="0">TIPO DE GESTIÓN</option>
                                                        <option value="12">PUBLICA</option>
                                                        <option value="3">PRIVADA</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Widget-4 -->
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-box">
                                        <div class="media">
                                            <div class="text-center">
                                                <img src="http://localhost/_laravel/git/sismore_v3/public/img/icon/docentes.png"
                                                    alt="" class="" width="70%" height="70%">
                                            </div>
                                            <div class="media-body align-self-center">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup" id="basico"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Matriculados</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-0 font-9">
                                            <h6 class="">Avance <span class="float-right"
                                                    id="ibasico">0%</span></h6>
                                            <div class="progress progress-sm m-0">
                                                <div class="progress-bar bg-success-0" role="progressbar"
                                                    aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 100%" id="bbasico">
                                                    <span class="sr-only">0% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card-box-->
                                </div>

                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-box">
                                        <div class="media">
                                            <div class="text-center">
                                                <img src="http://localhost/_laravel/git/sismore_v3/public/img/icon/docentes.png"
                                                    alt="" class="" width="70%" height="70%">
                                            </div>
                                            <div class="media-body align-self-center">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup" id="ebr"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Matricula EBR </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-0 font-9">
                                            <h6 class="">Avance <span class="float-right"
                                                    id="iebr">0%</span></h6>
                                            <div class="progress progress-sm m-0">
                                                <div class="progress-bar bg-success-0" role="progressbar"
                                                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 100%" id="bebr">
                                                    <span class="sr-only">0% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card-box-->
                                </div>

                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-box">
                                        <div class="media">
                                            <div class="text-center">
                                                <img src="http://localhost/_laravel/git/sismore_v3/public/img/icon/docentes.png"
                                                    alt="" class="" width="70%" height="70%">
                                            </div>
                                            <div class="media-body align-self-center">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup" id="ebe"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Matricula EBE</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-0 font-9">
                                            <h6 class="">Avance <span class="float-right"
                                                    id="iebe">0%</span></h6>
                                            <div class="progress progress-sm m-0">
                                                <div class="progress-bar bg-success-0" role="progressbar"
                                                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 100%" id="bebe">
                                                    <span class="sr-only">0% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card-box-->
                                </div>

                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-box">
                                        <div class="media">
                                            <div class="text-center">
                                                <img src="http://localhost/_laravel/git/sismore_v3/public/img/icon/docentes.png"
                                                    alt="" class="" width="70%" height="70%">
                                            </div>
                                            <div class="media-body align-self-center">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup" id="eba"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Matricula EBA</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-0 font-9">
                                            <h6 class="">Avance <span class="float-right"
                                                    id="ieba">0%</span></h6>
                                            <div class="progress progress-sm m-0">
                                                <div class="progress-bar bg-success-0" role="progressbar"
                                                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                                    style="width: 100%" id="beba">
                                                    <span class="sr-only">0% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">


                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="#myModal"><i
                                                        class="mdi mdi-information text-orange-0"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11">
                                                Número de estudiantes matriculados en educación básica, periodo
                                                2018-2023 </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0 m-0">
                                                <div id="anal1" style="height: 16rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="anal1-fuente">Fuente:</span>
                                                <span class="float-right anal1-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">


                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="#myModal"><i
                                                        class="mdi mdi-information text-orange-0"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11">
                                                Matricula educativa acumulada mensual en educación básica </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0 m-0">
                                                <div id="anal2" style="height: 16rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="anal3-fuente">Fuente:</span>
                                                <span class="float-right anal3-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">


                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="#myModal"><i
                                                        class="mdi mdi-information text-orange-0"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11">
                                                Estudiantes matriculados según sexo</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0 m-0">
                                                <div id="anal3" style="height: 15rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="anal3-fuente">Fuente:</span>
                                                <span class="float-right anal3-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">


                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="#myModal"><i
                                                        class="mdi mdi-information text-orange-0"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11">
                                                Estudiantes matriculados según área geográfica</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0 m-0">
                                                <div id="anal4" style="height: 15rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="anal4-fuente">Fuente:</span>
                                                <span class="float-right anal4-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>



                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                                            <div class="card-widgets">
                                                <button type="button" class="btn btn-success btn-xs"
                                                    onclick="descargar1()"><i class="fa fa-file-excel"></i>
                                                    Descargar</button>
                                            </div>
                                            <h3 class="text-black font-14">Avance de la matricula de gestion educativa
                                                local</h3>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla1">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-vtabla1-fuente">Fuente: Censo
                                                    Educativo - MINEDU</span>
                                                <span class="float-right" id="span-vtabla1-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                                            <div class="card-widgets">
                                                <button type="button" class="btn btn-primary btn-xs"
                                                    onclick="cargarTablaNivel('tabla2', 0)"
                                                    title='Actualizar Tabla'><i class=" fas fa-history"></i></button>
                                                <button type="button" class="btn btn-success btn-xs"
                                                    onclick="descargar2()"><i class="fa fa-file-excel"></i>
                                                    Descargar</button>
                                            </div>
                                            <h3 class="text-black font-14">Avance de la matricula según nivel y
                                                modalidad</h3>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-vtabla2-fuente">Fuente: Censo
                                                    Educativo - MINEDU</span>
                                                <span class="float-right" id="span-vtabla2-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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



        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

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
                        <input type="hidden" name="_token" value="E1CSJnlRAntdzSPIxaD7D6KjYG1LhD8qpoxVcNRv"> <input
                            type="hidden" id="idp" name="idp">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card pb-0">

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
                    <div class="col-md-12">
                        <div class="card-box  border m-1">
                            <a href="http://localhost/_laravel/git/sismore_v3/home/4">
                                <div class="media">
                                    <div class="avatar-md bg-info rounded-circle mr-2"
                                        style="height: 2.5rem;width: 2.5rem;">
                                        <i class="ion ion-md-construct avatar-title font-26 text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <div class="text-left ">
                                            <p class="mb-0 mt-1 text-truncate">ADMINISTRADOR</p>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card-box  border m-1">
                            <a href="http://localhost/_laravel/git/sismore_v3/home/3">
                                <div class="media">
                                    <div class="avatar-md bg-info rounded-circle mr-2"
                                        style="height: 2.5rem;width: 2.5rem;">
                                        <i class="fa fa-plus avatar-title font-26 text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <div class="text-left ">
                                            <p class="mb-0 mt-1 text-truncate">SALUD</p>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card-box bg-secondary border m-1">
                            <a href="http://localhost/_laravel/git/sismore_v3/home/1">
                                <div class="media">
                                    <div class="avatar-md bg-info rounded-circle mr-2"
                                        style="height: 2.5rem;width: 2.5rem;">
                                        <i class="ion-md-school avatar-title font-26 text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <div class="text-left text-white">
                                            <p class="mb-0 mt-1 text-truncate">EDUCACION</p>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card-box  border m-1">
                            <a href="http://localhost/_laravel/git/sismore_v3/home/2">
                                <div class="media">
                                    <div class="avatar-md bg-info rounded-circle mr-2"
                                        style="height: 2.5rem;width: 2.5rem;">
                                        <i class="ion-md-home avatar-title font-26 text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <div class="text-left ">
                                            <p class="mb-0 mt-1 text-truncate">VIVIENDA</p>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card-box  border m-1">
                            <a href="http://localhost/_laravel/git/sismore_v3/home/6">
                                <div class="media">
                                    <div class="avatar-md bg-info rounded-circle mr-2"
                                        style="height: 2.5rem;width: 2.5rem;">
                                        <i class="mdi mdi-worker avatar-title font-26 text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <div class="text-left ">
                                            <p class="mb-0 mt-1 text-truncate">TRABAJO</p>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card-box  border m-1">
                            <a href="http://localhost/_laravel/git/sismore_v3/home/5">
                                <div class="media">
                                    <div class="avatar-md bg-info rounded-circle mr-2"
                                        style="height: 2.5rem;width: 2.5rem;">
                                        <i class="ion ion-md-calculator avatar-title font-26 text-white"></i>
                                    </div>
                                    <div class="media-body align-self-center">
                                        <div class="text-left ">
                                            <p class="mb-0 mt-1 text-truncate">PRESUPUESTO</p>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        </div>
                    </div>
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






    <!-- Vendor js -->
    <script src="http://localhost/_laravel/git/sismore_v3/public/assets/js/vendor.min.js"></script>

    <script src="http://localhost/_laravel/git/sismore_v3/public/assets/libs/moment/moment.min.js"></script>
    <script src="http://localhost/_laravel/git/sismore_v3/public/assets/libs/jquery-scrollto/jquery.scrollTo.min.js">
    </script>
    <script src="http://localhost/_laravel/git/sismore_v3/public/assets/libs/sweetalert2/sweetalert2.min.js"></script>


    <script src="http://localhost/_laravel/git/sismore_v3/public/assets/jquery-ui/jquery-ui.js"></script>

    <!-- Chat app -->
    <script src="http://localhost/_laravel/git/sismore_v3/public/assets/js/pages/jquery.chat.js"></script>

    <!-- Todo app -->
    <script src="http://localhost/_laravel/git/sismore_v3/public/assets/js/pages/jquery.todo.js"></script>

    <!-- flot chart -->


    <!-- Dashboard init JS -->


    <!-- App js -->
    <script src="http://localhost/_laravel/git/sismore_v3/public/assets/js/app.min.js"></script>

    <script src="http://localhost/_laravel/git/sismore_v3/public/assets/js/bootbox.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


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
            //cargar_entidad(0);

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
            url = "http://localhost/_laravel/git/sismore_v3/Usuario/ajax_updateaux";
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
                url: "http://localhost/_laravel/git/sismore_v3/Usuario/ajax_edit/" + id,
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
                            url: "http://localhost/_laravel/git/sismore_v3/Entidad/CargarEntidad",
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
                                    url: "http://localhost/_laravel/git/sismore_v3/Entidad/CargarEntidad",
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
                                            url: "http://localhost/_laravel/git/sismore_v3/Entidad/CargarEntidad",
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
                url: "http://localhost/_laravel/git/sismore_v3/Usuario/CargarEntidad/3",
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
                url: "http://localhost/_laravel/git/sismore_v3/Usuario/CargarGerencia/" + $('#unidadejecutorap').val(),
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
                url: "http://localhost/_laravel/git/sismore_v3/Usuario/CargarOficina/" + $('#entidadgerenciap').val(),
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
    <script type="text/javascript">
        var ugel_select = 0;
        $(document).ready(function() {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });
            cargarDistritos();
            cargarCards();
        });

        function cargarCards() {
            $.ajax({
                url: "http://localhost/_laravel/git/sismore_v3/INDICADOR/Home/01/Head",
                data: {
                    "anio": $('#anio').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "gestion": $('#gestion').val(),
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#basico').text(data.valor1);
                    $('#ebr').text(data.valor2);
                    $('#ebe').text(data.valor3);
                    $('#eba').text(data.valor4);
                    $('#ibasico').text(data.ind1 + '%');
                    $('#iebr').text(data.ind2 + '%');
                    $('#iebe').text(data.ind3 + '%');
                    $('#ieba').text(data.ind4 + '%');
                    //$('#bbasico').css('width','100px');
                    $('#bbasico').css('width', data.ind1 + '%')
                        .removeClass('bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind1 > 84 ? 'bg-success-0' : (data.ind1 > 49 ? 'bg-orange-0' :
                            'bg-warning-0'));
                    $('#bebr').css('width', data.ind2 + '%').removeClass(
                            'bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind2 > 84 ? 'bg-success-0' : (data.ind2 > 49 ? 'bg-orange-0' :
                            'bg-warning-0'));
                    $('#bebe').css('width', data.ind3 + '%').removeClass(
                            'bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind3 > 84 ? 'bg-success-0' : (data.ind3 > 49 ? 'bg-orange-0' :
                            'bg-warning-0'));
                    $('#beba').css('width', data.ind4 + '%').removeClass(
                            'bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind4 > 84 ? 'bg-success-0' : (data.ind4 > 49 ? 'bg-orange-0' :
                            'bg-warning-0'));
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });

            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('anal4');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "http://localhost/_laravel/git/sismore_v3/INDICADOR/Home/01/Tabla",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "gestion": $('#gestion').val(),
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    if (div == "tabla1") {
                        $('#v' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "tabla2") {
                        $('#v' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else {
                        $('#' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    }
                },
                success: function(data) {
                    if (div == "anal1") {
                        gAnidadaColumn(div,
                            data.info.categoria, data.info.series, '', '', data.info.maxbar
                        );
                        $('.anal1-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal1-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal2") {
                        gLineaBasica(div, data.info, '', '', '');
                        $('.anal2-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal2-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal3") {
                        gPie(div, data.info, '', '', '');
                        $('.anal3-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal3-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "anal4") {
                        gPie(div, data.info, '', '', '');
                        $('.anal4-fuente').html('Fuente: ' + data.reg.fuente);
                        $('.anal4-fecha').html('Actualizado: ' + data.reg.fecha);
                    } else if (div == "tabla1") {
                        $('#vtabla1').html(data.excel);
                    } else if (div == "tabla2") {
                        $('#vtabla2').html(data.excel);
                    }

                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargarTablaNivel(div, ugel) {
            $.ajax({
                url: "http://localhost/_laravel/git/sismore_v3/INDICADOR/Home/01/Tabla",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "gestion": $('#gestion').val(),
                    "ugel": ugel
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    ugel_select = ugel;
                    if (div == "tabla1") {
                        $('#v' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "tabla2") {
                        $('#v' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else {
                        $('#' + div).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    }
                },
                success: function(data) {
                    if (div == "tabla2") {
                        $('#vtabla2').html(data.excel);
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargarDistritos() {
            $.ajax({
                url: "http://localhost/_laravel/git/sismore_v3/Ubigeo/Distrito/" + $('#provincia').val(),
                type: 'GET',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#distrito").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function descargar1() {
            window.open("http://localhost/_laravel/git/sismore_v3/INDICADOR/Home/01/Excel/tabla1/" + $('#anio').val() +
                "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#gestion').val() + "/0");
        }

        function descargar2() {
            window.open("http://localhost/_laravel/git/sismore_v3/INDICADOR/Home/01/Excel/tabla2/" + $('#anio').val() +
                "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#gestion').val() + "/" + ugel_select);
        }

        function verpdf(id) {
            window.open("http://localhost/_laravel/git/sismore_v3/Man/INDICADORGENERAL/Exportar/" + id);
        };

        function printer() {
            window.open("http://localhost/_laravel/git/sismore_v3/INDICADOR/Home/01/xxx");
        }

        function gSimpleColumn(div, datax, titulo, subtitulo, tituloserie) {

            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    enabled: false,
                    text: titulo,
                },
                subtitle: {
                    enabled: false,
                    //text: subtitulo,
                },
                xAxis: {
                    type: 'category',
                },
                yAxis: {
                    /* max: 100, */
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    }
                },
                /* colors: [
                    '#8085e9',
                    '#2b908f',
                ], */
                series: [{
                    showInLegend: tituloserie != '',
                    name: tituloserie,
                    label: {
                        enabled: false
                    },
                    colorByPoint: false,
                    data: datax,
                }],
                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                        },
                    }
                },
                exporting: {
                    enabled: false
                },
                credits: false,
            });
        }

        function gPie(div, datos, titulo, subtitulo, tituloserie) {
            const colors = ["#5eb9aa", "#f5bd22", "#e65310"];
            Highcharts.chart(div, {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    enabled: false,
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    enabled: false,
                    //text: subtitulo,
                },
                tooltip: {
                    //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                    pointFormat: '<b>{point.percentage:.1f}% ({point.y:,0f})</b>',
                    style: {
                        fontSize: '10px'
                    }
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                }, //labels:{style:{fontSize:'10px'},}
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        colors,
                        dataLabels: {
                            enabled: true,
                            distance: -20,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            //format: '{point.percentage:.1f}% ({point.y})',
                            // format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            format: '{point.percentage:.1f}%',
                            connectorColor: 'silver',
                        },
                    }
                },
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        cursor: "pointer",
                        fontSize: "10px",
                        fontWeight: "normal",
                        textOverflow: "ellipsis"
                    },
                },
                series: [{
                    innerSize: '50%',
                    showInLegend: true,
                    //name: 'Share',
                    data: datos,
                }],
                exporting: {
                    enabled: false
                },
                credits: false,
            });
        }

        function gBasicColumn(div, categorias, datos, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                xAxis: {
                    categories: categorias,
                },
                yAxis: {

                    min: 0,
                    title: {
                        text: 'Rainfall (mm)',
                        enabled: false
                    }
                },

                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                        },
                    }
                },
                series: datos,
                credits: false,
            });
        }

        function gsemidona(div, valor) {
            Highcharts.chart(div, {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: 0,
                    plotShadow: false,
                    height: 200,
                },
                title: {
                    text: valor + '%', // 'Browser<br>shares<br>January<br>2022',
                    align: 'center',
                    verticalAlign: 'middle',
                    y: 15, //60,
                    style: {
                        //fontWeight: 'bold',
                        //color: 'orange',
                        fontSize: '30'
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            enabled: true,
                            distance: -50,
                            style: {
                                fontWeight: 'bold',
                                color: 'white'
                            },

                        },
                        startAngle: -90,
                        endAngle: 90,
                        center: ['50%', '50%'], //['50%', '75%'],
                        size: '120%',
                        borderColor: '#98a6ad',
                        color: '#fff'
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Avance',
                    innerSize: '65%',
                    data: [
                        ['', valor],
                        //['Edge', 11.97],
                        //['Firefox', 5.52],
                        //['Safari', 2.98],
                        //['Internet Explorer', 1.90],
                        {
                            name: '',
                            y: 100 - valor,
                            dataLabels: {
                                enabled: false
                            }
                        }
                    ]
                }],
                exporting: {
                    enabled: false
                },
                credits: false
            });
        }

        function gLineaBasica(div, data, titulo, subtitulo, titulovetical) {
            const colors = ["#5eb9aa", "#f5bd22", "#e65310"];
            Highcharts.chart(div, {
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                yAxis: {
                    title: {
                        text: titulovetical
                    },
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    },
                    min: 0,
                },
                xAxis: {
                    categories: data.cat,
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
                    /* accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
                    } */
                },
                /* legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                }, */
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '10px',
                                fontWeight: 'normal',
                            }
                        },
                        /* label: {
                            connectorAllowed: false
                        },
                        pointStart: 2010 */
                    }
                },
                series: [{
                    name: 'Matriculados',
                    showInLegend: false,
                    data: data.dat
                }],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                },
                exporting: {
                    enabled: false,
                },
                credits: false,

            });
        }

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo, maxBar) {
            var rango = categoria.length;
            var posPorcentaje = rango * 2 + 1;
            var cont = 0;
            var porMaxBar = maxBar * 0.5;
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                colors: ['#5eb9aa', '#ef5350', '#f5bd22', '#ef5350'],
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    }
                }],
                yAxis: [{ // Primary yAxis
                        max: maxBar > 0 ? maxBar + porMaxBar : null,
                        labels: {
                            enabled: true,
                            style: {
                                //color: Highcharts.getOptions().colors[2],
                                fontSize: '10px',
                            }
                        },
                        title: {
                            enabled: false,
                        },
                        /* labels: {
                            //format: '{value}°C',
                            //style: {
                            //    color: Highcharts.getOptions().colors[2]
                            //}
                        }, */
                        title: {
                            text: 'Matriculados',
                            style: {
                                //color: Highcharts.getOptions().colors[2],
                                fontSize: '11px',
                            }
                        },
                        //opposite: true,
                    }, { // Secondary yAxis
                        gridLineWidth: 0, //solo indica el tamaño de la linea
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* title: {
                            //text: 'Rainfall',
                            text: '%Indicador',
                            //style: {
                            //    color: Highcharts.getOptions().colors[0]
                            //}
                        }, */
                        /* labels: {
                            //format: '{value} mm',
                            format: '{value} %',
                            //style: {
                            //   color: Highcharts.getOptions().colors[0]
                            //}
                        }, */
                        //min: -200,
                        min: 0,
                        max: 120,
                        opposite: true,
                    },
                    /* { // Tertiary yAxis
                        gridLineWidth: 0,
                        title: {
                            text: 'Sea-Level Pressure',
                            style: {
                                color: Highcharts.getOptions().colors[1]
                            }
                        },
                        labels: {
                            format: '{value} mb',
                            style: {
                                color: Highcharts.getOptions().colors[1]
                            }
                        },
                        opposite: true
                    } */
                ],
                series: series,
                plotOptions: {
                    /* columns: {
                        stacking: 'normal'
                    }, */
                    series: {
                        showInLegend: true,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.colorIndex == 1)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
                            },
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                            }
                        },
                    },
                },
                tooltip: {
                    shared: true,
                },
                legend: {
                    itemStyle: {
                        //"color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal",
                        "textOverflow": "ellipsis"
                    },
                },
                exporting: {
                    enabled: false
                },
                credits: false,
            });
        }

        function gAnidadaColumnx(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true
                }],
                yAxis: [{ // Primary yAxis
                        //max: 2000000000,
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* labels: {
                            format: '{value}°C',
                            style: {
                                color: Highcharts.getOptions().colors[2]
                            }
                        },
                        title: {
                            text: 'Temperature',
                            style: {
                                color: Highcharts.getOptions().colors[2]
                            }
                        }, */
                        //opposite: true,
                    }, { // Secondary yAxis
                        gridLineWidth: 0,
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* title: {
                            text: 'Rainfall',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        },
                        labels: {
                            format: '{value} mm',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        }, */
                        min: -200,
                        max: 150,
                        opposite: true,
                    },
                    /* { // Tertiary yAxis
                                       gridLineWidth: 0,
                                       title: {
                                           text: 'Sea-Level Pressure',
                                           style: {
                                               color: Highcharts.getOptions().colors[1]
                                           }
                                       },
                                       labels: {
                                           format: '{value} mb',
                                           style: {
                                               color: Highcharts.getOptions().colors[1]
                                           }
                                       },
                                       opposite: true
                                   } */
                ],
                series: series,
                plotOptions: {
                    /* columns: {
                        stacking: 'normal'
                    }, */
                    series: {
                        showInLegend: false,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else if (this.y < 101) {
                                    return this.y + "%";
                                } else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                    },
                },
                tooltip: {
                    shared: true,
                },
                legend: {
                    itemStyle: {
                        //"color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal",
                        "textOverflow": "ellipsis"
                    },
                },
                exporting: {
                    enabled: false
                },
                credits: false,
            });
        }
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>


</body>

</html>
