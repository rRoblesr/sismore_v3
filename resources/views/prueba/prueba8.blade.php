<script>
    topNameGC = 'Nacional';
    jsonPATH = '';
    mBrowser = 'Chrome';
    mapColor = '#d11314';
</script>
<!DOCTYPE html>
<html>

<head>
    <!-- Page Title -->
    <title>COVID-19 Tablero México - CONAHCYT - CentroGeo - GeoInt - DataLab</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="faviconF.png">

    <!-- Page Options -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Page Info -->
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- MS, fb & Whatsapp -->

    <!-- MS Tile - for Microsoft apps-->
    <meta name="msapplication-TileImage" content="https://datos.covid-19.conacyt.mx/img/logos/fLogo.jpg">

    <!-- fb & Whatsapp -->
    <!-- fb & Whatsapp -->
    <!-- Site Name, Title, and Description to be displayed (https://css-tricks.com/essential-meta-tags-social-media/)-->
    <meta property="og:site_name" content="COVID - 19 Tablero México">
    <meta property="og:title" content="COVID-19 Tablero México">
    <meta property="og:description" content="Sistema de seguimiento y monitoreo de COVID-19 en México">
    <meta property="og:image" content="https://datos.covid-19.conacyt.mx/img/logos/fLogo.jpg">
    <meta property="og:url" content="https://datos.covid-19.conacyt.mx/index.php">

    <!-- No need to change anything here -->
    <meta property="og:type" content="website" />
    <meta property="og:image:type" content="image/jpeg">

    <!-- Size of image. Any size up to 300. Anything above 300px will not work in WhatsApp -->
    <meta property="og:image:width" content="300">
    <meta property="og:image:height" content="300">

    <!-- Website to visit when clicked in fb or WhatsApp-->
    <meta property="og:url" content="https://datos.covid-19.conacyt.mx/">


    <meta property="og:locale" content="en_ES" />
    <meta property="og:locale:alternate" content="es_EU" />

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css?v2008" rel="stylesheet">
    <link href="css/style.css?v2008" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-162041023-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-162041023-1');
    </script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="hideinPDF navbar-nav sidebar sidebar-dark accordion toggled animated--grow-in" id="accordionSidebar"
            style="background-color:#0b231e; z-index:2000;">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center bg-white" target="_blank"
                href="https://www.gob.mx/" style="background-color:#0b231e!important">
                <div class="sidebar-brand-icon">
                    <img src="img/logos/MEX_I_SL.png" width="40px" />
                </div>
                <div class="sidebar-brand-text mx-2">
                    <img src="img/logos/MEX_T_SL.png" width="90px" />
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <li class="nav-item active my-3">
                <a class="nav-link nav-link-AC flink-item" href="#DOView"
                    onclick="UpdatePage('Overview/overView.php');">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Información General</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block my-0">

            <li class="nav-item active">
                <a class="nav-link nav-link-AC flink-item" href="#COMNac"
                    onclick="UpdatePage('Comparative/local.php');">
                    <i class="fas fa-fw fa-balance-scale"></i>
                    <span>Comparativo</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block my-0">

            <li class="nav-item active noHREF">
                <a class="nav-link nav-link-AC collapsed" data-toggle="collapse" data-target="#collapseHDMAPS"
                    aria-expanded="true" aria-controls="collapseHDMAPS">
                    <i class="fas fa-desktop"></i>
                    <span>Mapas Generales</span></a>
                </a>
                <div id="collapseHDMAPS" class="collapse" aria-labelledby="headingPages"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a style="display:none;" id="fsMapO" class="collapse-item flink-item" href="/fHDMap/"
                            target="_blank"><i class="fas fa-th-large mr-2"></i>por Estados</a>
                        <a class="collapse-item flink-item" href="/fHDMap/mun.php" target="_blank"><i
                                class="fas fa-th mr-2"></i>por Municipios</a>
                        <a class="collapse-item flink-item" href="/fHDMap/munNA.php" target="_blank"><i
                                class="fas fa-shapes mr-2"></i>por Vecindades</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block my-0">

            <li class="nav-item active noHREF">
                <a class="nav-link nav-link-AC collapsed" data-toggle="collapse" data-target="#collapseDownloads"
                    aria-expanded="true" aria-controls="collapseDownloads">
                    <i class="fas fa-download"></i>
                    <span>Descargas</span></a>
                </a>
                <div id="collapseDownloads" class="collapse" aria-labelledby="headingPages"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Vista actual</h6>
                        <a class="collapse-item flink-item" onclick="getPDF();"><i
                                class="fas fa-file-pdf mr-2"></i>generar PDF </a>
                        <h6 class="collapse-header">Datos</h6>
                        <a class="collapse-item flink-item" href="#DownZCSV"
                            onclick="UpdatePage('Downloads/files.php?csv');"><i
                                class="fas fa-file-csv mr-2"></i>catálogo CSV </a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block my-0">

            <li class="nav-item active mt-0">
                <a class="nav-link nav-link-AC flink-item noHREF" onclick="showMetod();">
                    <i class="fas fa-fw fa-info-circle"></i>
                    <span>Nota Metodológica</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column canvas_div_pdf" style="overflow: unset;">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"
                    style="background-color:#0b231e!important">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <img class="mr-5 showinPDF" style="display:none;"
                        src="https://framework-gb.cdn.gob.mx/landing/img/logoheader.png" />

                    <h1 class="h3 mb-0 text-white text-truncate"><b>C</b>ovid-19 <b>M</b>éxico</h1>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">

                            <div data-v-4a3754a3="" class="container d-none d-sm-block" style="z-index:2000;">

                                <div data-v-4a3754a3="" id="collapsibleNavbar"
                                    class="collapse navbar-collapse justify-content-end">
                                    <form data-v-4a3754a3="" class="hideinPDF form-inline">
                                        <ul data-v-4a3754a3="" class="navbar-nav">
                                            <li data-v-4a3754a3="" align="left" class="nav-item dropdown"><a
                                                    data-v-4a3754a3="" href="#" data-toggle="dropdown"
                                                    id="op2" aria-haspopup="true" aria-expanded="false"
                                                    class="nav-link menu m-0 p-0">Trámites</a>
                                                <div data-v-4a3754a3="" class="dropdown-menu dropdown-menu-right"><a
                                                        data-v-4a3754a3="" href="#"
                                                        class="dropdown-item op opmenu"
                                                        style="font-weight: bold;">Consulta por categoría de
                                                        trámite</a>
                                                    <div data-v-4a3754a3="" class="dropdown-divider"></div>
                                                    <a data-v-4a3754a3=""
                                                        href="https://www.gob.mx/tramites/comunicaciones"
                                                        target="_blank" class="dropdown-item op">Comunicaciones y
                                                        transportes</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/economia"
                                                        target="_blank" class="dropdown-item op">Economía</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/educacion"
                                                        target="_blank" class="dropdown-item op">Educación</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/energia"
                                                        target="_blank" class="dropdown-item op">Energía</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/identidad"
                                                        target="_blank" class="dropdown-item op">Identidad, pasaporte
                                                        y migración</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/impuestos"
                                                        target="_blank" class="dropdown-item op">Impuestos y
                                                        contribuciones</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/ambiente"
                                                        target="_blank" class="dropdown-item op">Medio Ambiente</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/programas"
                                                        target="_blank" class="dropdown-item op">Programas
                                                        sociales</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/salud"
                                                        target="_blank" class="dropdown-item op">Salud</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/seguridad"
                                                        target="_blank" class="dropdown-item op">Seguridad, legalidad
                                                        y justicia</a>
                                                    <a data-v-4a3754a3=""
                                                        href="https://www.gob.mx/tramites/financieros" target="_blank"
                                                        class="dropdown-item op">Servicios financieros</a>
                                                    <a data-v-4a3754a3=""
                                                        href="https://www.gob.mx/tramites/territorio" target="_blank"
                                                        class="dropdown-item op">Territorio y vivienda</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/trabajo"
                                                        target="_blank" class="dropdown-item op">Trabajo</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/turismo"
                                                        target="_blank" class="dropdown-item op">Turismo</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/tramites/otros"
                                                        target="_blank" class="dropdown-item op">Otros</a>
                                                </div>
                                            </li>
                                            <li data-v-4a3754a3="" class="nav-item dropdown">
                                                <a data-v-4a3754a3="" href="#" data-toggle="dropdown"
                                                    id="op3" aria-haspopup="true" aria-expanded="false"
                                                    class="nav-link menu ml-2 p-0">Gobierno</a>
                                                <div data-v-4a3754a3="" class="dropdown-menu dropdown-menu-right">
                                                    <a data-v-4a3754a3="" href="#"
                                                        class="dropdown-item op opmenu"
                                                        style="font-weight: bold;">Instituciones del Gobierno de
                                                        México</a>
                                                    <div data-v-4a3754a3="" class="dropdown-divider"></div>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/sader"
                                                        target="_blank" class="dropdown-item op">Agricultura</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/bienestar"
                                                        target="_blank" class="dropdown-item op">Bienestar</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/sct"
                                                        target="_blank" class="dropdown-item op">Comunicaciones y
                                                        Transportes</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/cultura"
                                                        target="_blank" class="dropdown-item op">Cultura</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/sedena"
                                                        target="_blank" class="dropdown-item op">Defensa Nacional</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/sedatu"
                                                        target="_blank" class="dropdown-item op">Desarrollo
                                                        Territorial</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/se"
                                                        target="_blank" class="dropdown-item op">Economía</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/sep"
                                                        target="_blank" class="dropdown-item op">Educación Pública</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/sener"
                                                        target="_blank" class="dropdown-item op">Energía</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/sfp"
                                                        target="_blank" class="dropdown-item op">Función Pública</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/segob"
                                                        target="_blank" class="dropdown-item op">Gobernación</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/shcp"
                                                        target="_blank" class="dropdown-item op">Hacienda</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/semar"
                                                        target="_blank" class="dropdown-item op">Marina</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/semarnat"
                                                        target="_blank" class="dropdown-item op">Medio Ambiente</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/presidencia"
                                                        target="_blank" class="dropdown-item op">Presidencia</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/sre"
                                                        target="_blank" class="dropdown-item op">Relaciones
                                                        Exteriores</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/salud"
                                                        target="_blank" class="dropdown-item op">Salud</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/stps"
                                                        target="_blank" class="dropdown-item op">Trabajo</a>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/sectur"
                                                        target="_blank" class="dropdown-item op">Turismo</a>
                                                    <div data-v-4a3754a3="" class="dropdown-divider"></div>
                                                    <a data-v-4a3754a3="" href="https://www.gob.mx/gobierno"
                                                        target="_blank" class="dropdown-item op">Ver más</a>
                                                </div>
                                            </li>
                                            <button data-v-4a3754a3="" id="botonbuscar" type="button"
                                                class="btn btn-default">
                                                <a data-v-4a3754a3="" href="https://www.gob.mx/busqueda?utf8=✓"
                                                    target="_blank" id="botbusca">
                                                    <img data-v-4a3754a3="" alt="Buscar"
                                                        src="https://framework-gb.cdn.gob.mx/landing/img/lupa.png">
                                                </a>
                                            </button>
                                        </ul>
                                    </form>
                                </div>
                            </div>



                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <div class="col-sm-12 mt-1" style = "display:none;">
                    <div class="text-center">

                        <a target="_blank" href="http://conacyt.mx/" class="mr-3 text-nowrap"><img
                                class="sidebar-logo" src="img/logos/CONACYT_I_SL.png?V2" /> <img class="sidebar-logo"
                                src="img/logos/CONACYT_T_SL.png?V2" /></a>

                        <a target="_blank" href="http://www.centrogeo.org.mx/" class="mr-3 text-nowrap"><img
                                class="sidebar-logo" src="img/logos/CentroGeo_I_SL.png" /> <img class="sidebar-logo"
                                src="img/logos/CentroGeo_T_SL.png" /></a>

                        <a target="_blank" href="http://geoint.mx/" class="mr-3 text-nowrap"><img
                                class="sidebar-logo" src="img/logos/GeoInt_I_SL.png" /> <img class="sidebar-logo"
                                src="img/logos/GeoInt_T_SL.png" /></a>

                        <a target="_blank" href="http://datalab.geoint.mx/" class="mr-3 text-nowrap"><img
                                class="sidebar-logo" src="img/logos/DataLab_I_SL.png" /><img class="sidebar-logo"
                                src="img/logos/DataLab_T_SL.png" /></a>
                    </div>
                </div>

                <!-- Begin Page Content -->
                <div class="container-fluid m-2" id="myMContainer" style="min-height:70vh;">
                    <!--div class="text-center">
        <i class="far fa-compass fa-spin fa-6x"></i>
        <p class="lead text-gray-800 mt-2 mb-3">Loading...</p>
        <p class="h5 text-gray-600 mb-0">Please wait while we retrieve the information</p>
       </div-->
                </div>
                <!-- /.container-fluid -->

                <!-- Institutional Logos -->
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">

                        <!-- Institutional Logos -->
                        <a target="_blank" href="http://conacyt.mx/" class="mr-3 text-nowrap"><img
                                class="sidebar-logo" src="img/logos/CONACYT_I_SL.png?V2" /> <img class="sidebar-logo"
                                src="img/logos/CONACYT_T_SL.png?V2" /></a>
                        <a target="_blank" href="http://www.centrogeo.org.mx/" class="mr-3 text-nowrap"><img
                                class="sidebar-logo" src="img/logos/CentroGeo_I_SL.png" /> <img class="sidebar-logo"
                                src="img/logos/CentroGeo_T_SL.png" /></a>
                        <a target="_blank" href="http://geoint.mx/" class="mr-3 text-nowrap"><img
                                class="sidebar-logo" src="img/logos/GeoInt_I_SL.png" /> <img class="sidebar-logo"
                                src="img/logos/GeoInt_T_SL.png" /></a>
                        <a target="_blank" href="http://datalab.geoint.mx/" class="mr-3 text-nowrap"><img
                                class="sidebar-logo" src="img/logos/DataLab_I_SL.png" /><img class="sidebar-logo"
                                src="img/logos/DataLab_T_SL.png" /></a>
                        <br><span class="m-2">Copyright 2020 &copy; CONAHCYT - CentroGeo - GeoInt - DataLab</span>
                    </div>
                </div>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer mt-2" style="background-color:#0b231e;">
                <div class="container my-auto hideinPDF">

                    <div data-v-41055fb8="" class="container text-white">
                        <div data-v-41055fb8="" class="row">
                            <div data-v-41055fb8="" class="col-sm-6 col-lg-3">
                                <img data-v-41055fb8=""
                                    src="https://framework-gb.cdn.gob.mx/landing/img/logofooter.png" href="/"
                                    alt="logo gobierno de méxico" class="logo_footer"
                                    style="max-width: 90%; margin-left: -6%; margin-top: 22%; margin-bottom: 50px;">
                            </div>
                            <div data-v-41055fb8="" class=" hideinPDF col-sm-6 col-lg-3">
                                <h6 data-v-41055fb8="" align="left" id="division"><b>Enlaces</b></h6>
                                <ul data-v-41055fb8="" class="list-unstyled">
                                    <li data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                            href="http://www.participa.gob.mx" target="_blank">Participa</a></li>
                                    <li data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                            href="https://www.gob.mx/publicaciones" target="_blank">Publicaciones
                                            Oficiales</a></li>
                                    <li data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                            href="http://www.ordenjuridico.gob.mx" target="_blank">Marco Jurídico</a>
                                    </li>
                                    <li data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                            href="https://consultapublicamx.inai.org.mx/vut-web/"
                                            target="_blank">Plataforma Nacional de Transparencia</a></li>
                                </ul>
                            </div>
                            <div data-v-41055fb8="" class="hideinPDF col-sm-6 col-lg-3">
                                <h6 data-v-41055fb8="" align="left" id="division"><b>¿Qué es gob.mx?</b></h6>
                                <p data-v-41055fb8="">Es el portal único de trámites, información y participación
                                    ciudadana. <a class="text-white" data-v-41055fb8="" target="_blank"
                                        href="https://www.gob.mx/que-es-gobmx"><u data-v-41055fb8="">Leer más</u></a>
                                </p>
                                <ul data-v-41055fb8="" class="list-unstyled">
                                    <li data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                            href="https://datos.gob.mx">Portal de datos abiertos</a></li>
                                    <li data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                            href="https://www.gob.mx/accesibilidad">Declaración de accesibilidad</a>
                                    </li>
                                    <li data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                            href="https://www.gob.mx/privacidadintegral">Aviso de privacidad
                                            integral</a></li>
                                    <li data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                            href="https://www.gob.mx/privacidadsimplificado">Aviso de privacidad
                                            simplificado</a></li>
                                    <li data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                            href="https://www.gob.mx/terminos">Términos y condiciones</a></li>
                                    <li data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                            href="https://www.gob.mx/terminos#medidas-seguridad-informacion">Política
                                            de seguridad</a></li>
                                    <li data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                            href="https://www.gob.mx/sitemap">Mapa del sitio</a></li>
                                </ul>
                            </div>
                            <div data-v-41055fb8="" class="hideinPDF col-sm-6 col-lg-3">
                                <h6 data-v-41055fb8="" align="left" id="division"></h6>
                                <h6 data-v-41055fb8=""><a class="text-white" data-v-41055fb8="" target="_blank"
                                        href="https://www.gob.mx/tramites/ficha/presentacion-de-quejas-y-denuncias-en-la-sfp/SFP54"><u
                                            data-v-41055fb8="">Denuncia contra servidores públicos</u></a></h6>
                                <br data-v-41055fb8="">
                                <h6 data-v-41055fb8="" id="redes"><b>Síguenos en:</b></h6>
                                <ul data-v-41055fb8="" id="social" class="list-inline">
                                    <li data-v-41055fb8="" class="list-inline-item"><a class="text-white"
                                            data-v-41055fb8="" target="_blank"
                                            href="https://www.facebook.com/gobmexico/" target="_blank" red="Facebook"
                                            title="Enlace a facebook abre en una nueva ventana"
                                            class="sendEstFooterRs share-info"><img data-v-41055fb8="" alt="Facebook"
                                                src="https://framework-gb.cdn.gob.mx/landing/img/facebook.png"></a>
                                    </li>
                                    <li data-v-41055fb8="" class="list-inline-item"><a class="text-white"
                                            data-v-41055fb8="" target="_blank" href="https://twitter.com/GobiernoMX"
                                            target="_blank" red="Twitter"
                                            title="Enlace a twitter abre en una nueva ventana"
                                            class="sendEstFooterRs share-info"><img data-v-41055fb8="" alt="Twitter"
                                                src="https://framework-gb.cdn.gob.mx/landing/img/twitter.png"></a></li>
                                </ul>
                                <br><br>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
        </footer>
        <!-- End of Footer -->

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
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Simple -->
    <div class="modal fade" id="sMModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sMModalTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left" id="sMModalContent">
                </div>
                <div id ="mSMButtonDIV" class="modal-footer">
                    <button id ="mSMButton" type="button" class="btn bg-gradient-info text-white"
                        data-dismiss="modal">Click me</button>
                </div>
            </div>
        </div>
    </div>

    <div id="DivPMap" style="display:none;"></div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="vendor/datatables/buttons/dataTables.buttons.min.js"></script>
    <script src="vendor/datatables/buttons/jszip.min.js"></script>
    <script src="vendor/datatables/buttons/buttons.html5.js"></script>

    <!-- AMCharts -->
    <script src="js/amCharts/core.js"></script>
    <script src="js/amCharts/charts.js"></script>
    <script src="js/amCharts/dataviz.js"></script>
    <script src="js/amCharts/material.js"></script>
    <script src="js/amCharts/animated.js"></script>


    <!-- Scripts de leaflet -->
    <script src="js/leaflet/leaflet.js"></script>
    <script src="js/leaflet/leaflet.curve.js"></script>
    <script src="js/leaflet/plugins/leaflet.ajax.min.js"></script>

    <!-- Estilos leaflet -->
    <link rel="stylesheet" href="js/leaflet/leaflet.css" />

    <!-- Save as PDF -->
    <script src="js/jspdf/jspdf.min.js"></script>
    <script src="js/html2canvas/html2canvas.js?v0"></script>

    <!-- Cool selects -->
    <link rel="stylesheet" href="js/select/bootstrap-select.min.css">
    <script src="js/select/bootstrap-select.min.js"></script>

    <!-- Overlay Loading -->
    <link rel="stylesheet" href="js/overlay/jquery.loadingModal.css">
    <script src="js/overlay/jquery.loadingModal.js"></script>

    <!-- Main Scripts -->
    <script>
        topNameGC = 'Nacional';
        jsonPATH = '';
        mBrowser = 'Chrome';
        mapColor = '#d11314';
    </script>
    <script>
        // Div to process scripts...
        var DivPMap = document.getElementById("DivPMap");
        // Obtenemos el control del DIV principal...
        var DivMain = document.getElementById("myMContainer");
        // Bandera local de AJAX...
        var mpAJAX = false;

        // Loading message init...
        $('body').loadingModal({
            animation: 'chasingDots',
            text: '<b>Cargando...</b><br>Espere un momento por favor'
        });

        // Loading message...
        function mLoading() {
            $('body').loadingModal("show");
            return;
        }

        // Request de actualización dinámica (main)...
        var mainClient = new XMLHttpRequest();

        // Última página web...
        var lPage = "";

        // Actualiza div contenedor (main)...
        function UpdatePage(wPage, force, dContainerID) {
            // SAFARI
            if (force === undefined) force = false;
            if (dContainerID === undefined) dContainerID = null;
            // If it is the same page, do nothing....
            if (lPage == wPage && !force) return false;
            // Bandera de AJAX...
            if (mpAJAX && !force) return;
            mpAJAX = true;
            // Save last page...
            lPage = wPage;
            // Loading message...
            mLoading();
            // Create a FormData instance...
            var upData = getFormData(dContainerID);
            // Set POST data...
            mainClient.open("post", wPage, true);
            // Send to server...
            mainClient.send(upData);
            // Terminamos...
            return true;
        }

        // Procesa div contenedor (process)...
        function ProcessPage(wService, dContainerID) {
            // SAFARI
            if (dContainerID === undefined) dContainerID = null;
            // Bandera de AJAX...
            if (mpAJAX) return;
            mpAJAX = true;
            // Loading message...
            mLoading();
            // Create a FormData instance...
            var upData = getFormData(dContainerID);
            // Set POST data...
            mainClient.open("post", wService, true);
            // Send to server...
            mainClient.send(upData);
            // Terminamos...
            return true;
        }

        // Respuesta del cliente main...
        mainClient.onreadystatechange = function() {
            // Status OK...
            if (mainClient.readyState == 4 && mainClient.status == 200) {
                // Do we need to update?
                if (mainClient.responseText.substring(0, 5) == "NOUPD") {
                    // Present data to user...
                    DivPMap.innerHTML = mainClient.responseText;
                    // Procesamos Scripts recibidos...
                    processScripts(DivPMap);
                } else {
                    // Present data to user...
                    DivMain.innerHTML = mainClient.responseText;
                    // Procesamos Scripts recibidos...
                    processScripts(DivMain);
                }
                // Clear response...
                mainClient.responseText = "";
                // Bandera de AJAX...
                mpAJAX = false;
            }
        }

        // Process scripts on container div...
        function processScripts(pDIV) {
            // Get the scripts...
            var sArr = pDIV.getElementsByTagName('script');
            // Process each...
            for (var n = 0; n < sArr.length; n++)
                eval(sArr[n].innerHTML);
        }

        // Get POST parameters from a container ID...
        function getFormData(dContainerID) {

            // Create a virtual form to post data...
            var formData = new FormData();

            //Return the empty form data...
            if (dContainerID == null) return formData;

            // Get the form container...
            dataContainter = document.getElementById(dContainerID);

            // Add all the information in the Container to a formdata...
            $(dataContainter).find('input, select, textarea').each(function() {
                formData.append(this.name, this.value);
            });

            // Return the formData...
            return formData;
        }

        // Simple modal...
        function showInfModal(title, html, bText, cFunction) {
            document.getElementById("sMModalTitle").innerHTML = title;
            document.getElementById("sMModalContent").innerHTML = html;
            document.getElementById("mSMButton").innerHTML = bText;
            if (cFunction !== undefined) {
                $('#sMModal').on('hidden.bs.modal', cFunction);
            }
            $('#sMModal').modal('show');
        }

        // =============== Start Config Global Vars and Functions ===============

        var mapSEF, SEFonEachEnt, SEFstyleEnt, SEFentPolySelect, SEFresetView, SEFcenterView, SEFinitializeMap, SEMinit,
            SEFSMap;
        var ShowNotes, mBrowser, mapColor, curCase, shadeColor, showOnly, testDownload, drawArc;
        var Total = new Array();
        var TotalMun = new Array();
        var dataSSAMAP, onEachEntCC, drawCCC;
        var conMexP, conMexN, conMexS, conMexD, createOptimal;
        var tDaysCount = 0;
        var topNameGC, jsonPATH;
        // OverViewMap Global Variables...
        var geojsonLayerEnt, geojsonLayerEntC, geojsonLayerMun, geojsonLayerEntCText, sDate, sTime, map, onEachEnt,
            styleEnt, onEachMun, styleMun, qDataEnt, qDataMun, getOpacityEnt, entSelected, munSelected, cCurEntP, cCurMupP,
            entPolySelect, munPolySelect, onEachEntC, drawCC, cCurEntC, entCentroidSelect, onEachMun, styleMun, resetView,
            cCurEntT, updatefromTable, togleMap, initializeMap, fullColorHex, rgbToHex, aMapClient, oViewAJAXf, infoLoading,
            cCurEntUp, UpdateiDCEnt, UpdateiDOEnt, UpdateiDMEnt, UpdateiDACEnt;
        var processtChartData, reUpdate, reLoad, getColorQuantiles, createAgeSeries, showCard, hideCard, bBTable, getChart,
            mEntName, centerView, UpdateiDAEnt;
        // Compare Global Variables...
        var accSeriesDC = new Array();
        var pendCompCalls = 0;
        var getRandomColor, invertHex, togleGraph;
        var dVChartName;
        var MyTable;
        var reSelect;
        var geojsonLayerMunC, geojsonLayerMunCText;
        var createModelSeries;
        var geojsonLayerETraj, mapC, geojsonLayerEntCC, cCurEntCC, entCentroidSelectC;

        // =============== End Config Global Vars and Functions ===============

        // Are we in mobile...
        var iMobile = false;
        var iTablet = false;

        // Autostart function set...
        window.onload = (function() {
            readyState();
        });

        // Autostart function...
        function readyState() {

            // Are we in mobile?
            if (screen.height > screen.width)
                iMobile = true;

            // Are we in tablet?
            if ($(window).width() < 1200)
                iTablet = true;

            // Show HD map on big screens...
            if (screen.width >= 1920 && screen.height >= 1080) {
                if (topNameGC == "Nacional") $('#fsMapO').show();
                if (topNameGC != "Nacional") $('#fsMapCDMX').show();
            }

            // DesCollapse sidebar on big devices..
            if ($(window).width() > 1400) {
                $("#accordionSidebar").removeClass("toggled");
            }

            // Auto close left menus when clicked
            jQuery("a.collapse-item").click(function() {
                // Hide all sub menus...
                $('.collapse').collapse('hide');
            });

            // Auto close sidebar when clicked (only on small devices)
            jQuery("a.flink-item").click(function() {
                // Close side bar...
                if ($(window).width() < 768) {
                    $("#accordionSidebar").addClass("toggled");
                }
                // Hide all sub menus...
                $('.collapse').collapse('hide');
            });

            // Tomamos dirección URL...
            var myURL = document.location.href;
            // Redirget to tag...
            if (myURL.toLowerCase().indexOf("#comnac") != -1)
                // Iniciamos en comparativa...
                UpdatePage('Comparative/local.php');
            else if (myURL.toLowerCase().indexOf("#downzcsv") != -1)
                // Iniicamos en zona de descargas...
                UpdatePage('Downloads/files.php?csv');

            // Versión CDMX
            else if (myURL.toLowerCase().indexOf("#mxan") != -1)
                // Iniicamos en zona de Análisis...
                UpdatePage('Analysis/analysis.php');
            else if (myURL.toLowerCase().indexOf("#sedesa") != -1)
                // Iniicamos en zona de sedesa...
                UpdatePage('SEDESA/sedesa.php');

            // Versión NACIONAL
            else if (myURL.toLowerCase().indexOf("#dcases") != -1)
                // Iniicamos en zona de casos acumulados...
                UpdatePage('DCases/dcases.php');
            else if (myURL.toLowerCase().indexOf("#mobi") != -1)
                // Iniicamos en zona de movilidad...
                UpdatePage('Mobility/mobility.php');
            else if (myURL.toLowerCase().indexOf("#agraphs") != -1)
                // Iniicamos en zona de gráficas adicionales...
                UpdatePage('AGraphs/aGraphs.php');

            else
                // Start in overvie dash...
                UpdatePage('Overview/overView.php');

        }

        // Capture view in PDF TODO: firefox / safari not working...
        function getPDF() {

            if (mBrowser != "Chrome") {
                showInfModal("<i class='fas fa-info-circle text-warning'> </i> Navegador no compatible", "<img style='height: 50px !important; float:right; margin-left: 15px !important;' src = 'img/illustrations/warning.gif' />\
    			<p>\
    			La función de generar reportes en formato PDF solamente es compatible con el navegador Google Chrome. Puede generar un reporte mediante el menú imprimir de su navegador.\
    			</p>", "Entendido");
                return;
            }

            var oMWidth = $(".canvas_div_pdf").width();
            $(".canvas_div_pdf").width(oMWidth);

            $('.hideinPDF').hide();
            $('.showinPDF').show();

            var HTML_Width = $(".canvas_div_pdf").width();
            var HTML_Height = $(".canvas_div_pdf").height();
            var top_left_margin = 15;
            var PDF_Width = HTML_Width + (top_left_margin * 2);
            var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
            var canvas_image_width = HTML_Width;
            var canvas_image_height = HTML_Height;

            var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

            html2canvas($(".canvas_div_pdf")[0], {
                logging: false,
                letterRendering: 1,
                allowTaint: true,
                useCORS: true
            }).then(function(canvas) {
                canvas.getContext('2d');
                var imgData = canvas.toDataURL("image/jpeg", 1.0);

                var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
                pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width,
                    canvas_image_height);

                for (var i = 1; i <= totalPDFPages; i++) {
                    pdf.addPage(PDF_Width, PDF_Height);
                    pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height * i) + (top_left_margin * 4),
                        canvas_image_width, canvas_image_height);
                }
                pdf.save("Reporte-COVID19.pdf");
            });

            $('.hideinPDF').show();
            $('.showinPDF').hide();
            $(".canvas_div_pdf").width("100%");

        };

        // Notes for data in this page...
        function showMetod() {
            showInfModal("<i class='fas fa-info-circle text-gray-800'> </i> Nota Metolológica", "\
    					<p class='text-xs'>\
    					<b>Casos positivos</b><br>\
    					Los casos positivos son el conjunto de casos que tengan resultado de muestra positivo o que se clasifiquen positivos por asociación epidemiológica o por dictaminación (solo para defunciones).\
    					Se filtran todos los casos positivos (CLASIFICACION_FINAL valores “1”, “2” y “3”) registrados en la base de datos.\
    					</p>\
    					<p class='text-xs'>\
    					<b>Casos activos</b><br>\
    					Los casos activos son todos aquellos positivos a SARS-CoV-2 con fecha de inicio de síntomas en los últimos 14 días. Las defunciones de casos activos se consideran parte de los casos activos, porque, desde una perspectiva poblacional, contribuyeron a la transmisión del virus. La forma de calcular los casos activos es la siguiente:\
    					<ul class='text-xs'>\
    					<li>Se filtran todos los casos positivos (CLASIFICACION_FINAL valores “1”, “2” y “3”) registrados en la base de datos.</li>\
    					<li>Se cuentan los casos según fecha de inicio de síntomas (FECHA_SINTOMAS) y se consideran solo aquellos con menos de 14 días.</li>\
    					</ul>\
    					</p>\
    					<p class='text-xs'>\
    					<b>Casos recuperados</b><br>\
    					Los casos recuperados son todos aquellos positivos a SARS-CoV-2 no hospitalizados, con fecha de inicio de síntomas con más de 14 días y sin fecha de defunción. La forma de calcular los casos activos es la siguiente:\
    					<ul class='text-xs'>\
    					<li>Se filtran todos los casos positivos (CLASIFICACION_FINAL valores “1” y “2”), ambulatorios (TIPO_PACIENTE valor “1”), sin fecha de defunción (FECHA_DEF valor “99-99-9999”) registrados en la base de datos.</li>\
    					<li>Se cuentan los casos según fecha de inicio de síntomas (FECHA_SINTOMAS) y según fecha de defunción (FECHA_DEF).</li>\
    					<li>Al total de casos registrados se restan todos los casos con fecha de inicio de síntomas anterior a los últimos 14 días y los casos con fecha de defunción establecida.</li>\
    					</ul>\
    					</p>\
    					<p class='text-xs'>\
    					<b>Casos sospechosos</b><br>\
    					Los casos sospechosos son la suma de todos aquellos que, para una semana epidemiológica, que cumplen alguna de las siguientes condiciones:\
    					<ul class='text-xs'>\
    					<li>Se les tomó muestra, pero aún no reciben resultado de la misma.</li>\
    					<li>Se les tomó muestra, pero ésta no tiene posibilidad de resultado y la clasificación final del caso no es de asociación epidemiológica ni de dictaminación.</li>\
    					<li>Pacientes a los que no se les tomó muestra y la clasificación final del caso no es de asociación epidemiológica ni de dictaminación.</li>\
    					</ul>\
    					</p>\
    					<p class='text-xs'>\
    					Para el cálculo de los casos sospechosos se deben seguir la siguiente con sideración:\
    					<ul class='text-xs'>\
    					<li>Se filtran todos los registros cuyo valor de la CLASIFICACION_FINAL final sea 3, 4 y 5.</li>\
    					</ul>\
    					</p>\
    					<p class='text-xs'>\
    					<b>Casos estimados</b><br>\
    					Los casos estimados, sean positivos, activos o defunciones, es la suma de los casos estimados por entidad federativa y semana epidemiológica, los cuales se calculan de la siguiente manera:\
    					<center class='text-xs'><b>Casos Estimados=〖CasosPositivos〗+ ( Casos Sospechosos) × (Indice de positividad)</b></center>\
    					</p>\
    					<p class='text-xs'>\
    					Donde:\
    					<ul class='text-xs'>\
    					<li><b>Casos positivos</b>: Son los casos positivos por entidad de residencia en una determinada semana epidemiológica de inicio de síntomas.</li>\
    					<li><b>Casos sospechosos</b>: son los casos sospechosos por entidad federativa de residencia en una determinada semana epidemiológica de inicio de síntomas</li>\
    					<li><b>Índice de positividad</b>: es la proporción de muestras positivas a SARS-CoV-2 y el total de muestras (positivos más negativos) para una semana epidemiológica de inicio de síntomas, en una entidad federativa determinada de residencia.</li>\
    					</ul>\
    					</p>\
    					<p class='text-xs'>\
    					Adicionalmente, para la representación de la información, se considera lo siguiente:\
    					<ul class='text-xs'>\
    					<li>Las vistas georreferenciadas consideran el lugar de residencia de los pacientes reportados (ENTIDAD_RES y MUNICIPIO_RES).</li>\
    					<li>Las series temporales consideran la fecha de ingreso (FECHA_SINTOMAS) para los casos Confirmados, Sospechosos y Negativos.</li>\
    					<li>Las series temporales consideran la fecha de defunción (FECHA_DEF) para los casos de Defunciones.</li>\
    					</p>", "Entendido");
        }

        // Notes for data in this page...
        function showWarning() {
            showInfModal("<i class='fas fa-info-circle text-gray-800'> </i> <b>Aviso</b>", "\
    					<p>La Dirección General de Epidemiología actualizó la metodología del cálculo de recuperados\
    					que se utiliza en este tablero. El cambio consiste en que se filtran también a los pacientes ambulatorios (los no hospitalizados)\
    					para el cálculo final. El método de cálculo se puede consultar en la <a onclick='showMetod();'><b>nota metodológica</b> en el menú de la izquierda.</a>\
    					</p>", "Entendido");
        }
    </script>

</body>

</html>
