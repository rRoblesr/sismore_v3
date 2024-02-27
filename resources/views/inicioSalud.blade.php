@section('css')
    <style>
        .tablex thead th {
            padding: 2px;
            text-align: center;
        }

        .tablex thead td {
            padding: 2px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tablex tbody td,
        .tablex tbody th,
        .tablex tfoot td,
        .tablex tfoot th {
            padding: 2px;
        }

        .fuentex {
            font-size: 10px;
            font-weight: bold;
        }


        .link {
            color: #000000;
        }

        .link:hover {
            color: #0000FF;
        }
    </style>
@endsection

<div>
    <div id="container-speed" class="chart-container"></div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="form-group row align-items-center vh-5">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <h4 class="page-title font-16">MODULO SALUD</h4>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="provincia" name="provincia" class="form-control btn-xs font-11"
                    onchange="cargarDistritos(),cargarCards();">
                    <option value="0">PROVINCIA</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="distrito" name="distrito" class="form-control btn-xs font-11" onchange="cargarCards();">
                    <option value="0">DISTRITO</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="tipogestion" name="tipogestion" class="form-control btn-xs font-11"
                    onchange="cargarCards();">
                    <option value="0">TIPO DE GESTIÓN</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="ambito" name="ambito" class="form-control btn-xs font-11" onchange="cargarCards();">
                    <option value="0">ÁMBITO</option>
                </select>
            </div>
        </div>

        <!--Widget-4 -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card-box border border-plomo-0">{{--  card-border border border-plomo-0 --}}
                    <div class="media">
                        <div class="text-center">
                            <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                width="70%" height="70%">
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="basico"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">info</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 font-9">
                        <h6 class="">Avance <span class="float-right" id="ibasico">0%</span></h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="90"
                                aria-valuemin="0" aria-valuemax="100" style="width: 100%" id="bbasico">
                                <span class="sr-only">0% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="text-center">
                            <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                width="70%" height="70%">
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="ebr"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">
                                    info
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 font-9">
                        <h6 class="">Avance <span class="float-right" id="iebr">0%</span></h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="60"
                                aria-valuemin="0" aria-valuemax="100" style="width: 100%" id="bebr">
                                <span class="sr-only">0% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="text-center">
                            <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                width="70%" height="70%">
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="ebe"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">
                                    info
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 font-9">
                        <h6 class="">Avance <span class="float-right" id="iebe">0%</span></h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="60"
                                aria-valuemin="0" aria-valuemax="100" style="width: 100%" id="bebe">
                                <span class="sr-only">0% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="text-center">
                            <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                width="70%" height="70%">
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="eba"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">
                                    info
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-0 font-9">
                        <h6 class="">Avance <span class="float-right" id="ieba">0%</span></h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="60"
                                aria-valuemin="0" aria-valuemax="100" style="width: 100%" id="beba">
                                <span class="sr-only">0% Complete</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Widget-4 -->
        <div class="row d-none">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="text-center">
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/servicios.png" alt=""
                                class="" width="70%" height="70%">
                        </div>
                        {{-- <div class="avatar-md bg-success rounded-circle mr-2">
                                <i class=" ion-md-home avatar-title font-26 text-white"></i>
                            </div> --}}
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="servicios"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Servicios Educativos</p>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end card-box-->
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="text-center">
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/locales.png" alt="" class=""
                                width="70%" height="70%">
                        </div>
                        {{-- <div class="avatar-md bg-info rounded-circle mr-2">
                                <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                            </div> --}}
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="locales"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Locales Educativos </p>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end card-box-->
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="text-center">
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/matriculas.png" alt=""
                                class="" width="70%" height="70%">
                        </div>
                        {{-- <div class="avatar-md bg-info rounded-circle mr-2">
                                <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                            </div> --}}
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="matriculados"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Estudiantes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card-box-->
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card-box border border-plomo-0">
                    <div class="media">
                        <div class="text-center">
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                width="70%" height="70%">
                        </div>
                        {{-- <div class="avatar-md bg-info rounded-circle mr-2">
                                <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                            </div> --}}
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="docentes"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Docentes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6">
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                        <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                    </div>
                    <div class="card-body p-0">
                        <figure class="highcharts-figure m-0">
                            <div id="siagie001" style="height: 20rem"></div>
                        </figure>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-siagie001-fuente">Fuente:</span>
                            <span class="float-right" id="span-siagie001-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                        <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                    </div>
                    <div class="card-body p-0">
                        {{-- <div id="container" ></div> --}}
                        <figure class="highcharts-figure m-0">
                            <div id="censodocente001" style="height: 20rem"></div>
                        </figure>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-censodocente001-fuente">Fuente:</span>
                            <span class="float-right" id="span-censodocente001-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-6">
                {{-- <div class="card card-default card-fill"> --}}
                {{-- <div class="card-header"> --}}
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <h3 class="card-title font-12">Matricula Educativa, segun modalidades</h3>
                    </div>
                    <div class="card-body pb-0">
                        <div class="mb-4 skills001">
                            <h6 class="font-12"><a href="{{ route('matriculadetalle.interculturalbilingue') }}"
                                    class="link">Porcentajes de
                                    estudiantes matriculados del modelo de servicio EIB</a><span
                                    class="float-right">0%</span></h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar wow animated progress-animated" role="progressbar"
                                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">60% Complete</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 skills002">
                            <h6 class="font-12"><a href="{{ route('superiorpedagogico.principal') }}"
                                    class="link">Porcentajes de
                                    estudiantes
                                    matriculados en Educacion Superior Pedagogica</a><span
                                    class="float-right">0%</span></h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar wow animated progress-animated" role="progressbar"
                                    aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">90% Complete</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 skills003">
                            <h6 class="font-12"><a href="{{ route('superiortecnologico.principal') }}"
                                    class="link">Porcentajes de
                                    estudiantes
                                    matriculados en Educacion Superior Tecnologica</a><span
                                    class="float-right">0%</span></h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar wow animated progress-animated" role="progressbar"
                                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">80% Complete</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 skills004">
                            <h6 class="font-12"><a href="{{ route('superiorartistico.principal') }}"
                                    class="link">Porcentajes de
                                    estudiantes
                                    matriculados en Educacion Superior Artistica</a><span class="float-right">0%</span>
                            </h6>
                            <div class="progress progress-sm mb-0">
                                <div class="progress-bar wow animated progress-animated" role="progressbar"
                                    aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">95% Complete</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 skills005">
                            <h6 class="font-12"><a href="{{ route('tecnicoproductiva.principal') }}"
                                    class="link">Porcentajes de
                                    estudiantes
                                    matriculado en Educacion Tecnica-Productiva</a><span class="float-right">0%</span>
                            </h6>
                            <div class="progress progress-sm mb-0">
                                <div class="progress-bar wow animated progress-animated" role="progressbar"
                                    aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">95% Complete</span>
                                </div>
                            </div>
                        </div>

                        <div class="font-weight-bold text-muted mb-0 font-9">
                            <span class="float-left" id="span-skills005-fuente">Fuente:</span>
                            <span class="float-right" id="span-skills005-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6">
                {{-- <div class="card card-default card-fill"> --}}
                {{-- <div class="card-header"> --}}
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <h3 class="card-title font-12">Locales Educativos de Educacion Basica con acceso a Servicios
                            Basicos
                        </h3>
                    </div>
                    <div class="card-body pb-0">
                        <div class="mb-4 skills006">
                            <h6 class="font-12"><a href="{{ route('serviciosbasicos.principal') }}"
                                    class="link">Porcentajes de
                                    Locales Educativos
                                    con los tres Servicios
                                    Basicos</a><span class="float-right">0%</span></h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar wow animated progress-animated" role="progressbar"
                                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">60% Complete</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 skills007">
                            <h6 class="font-12"><a href="{{ route('serviciosbasicos.principal') }}"
                                    class="link">Porcentajes de
                                    Locales Educativos
                                    conectados a red de Agua Potable</a><span class="float-right">0%</span></h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar wow animated progress-animated" role="progressbar"
                                    aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">90% Complete</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 skills008">
                            <h6 class="font-12"><a href="{{ route('serviciosbasicos.principal') }}"
                                    class="link">Porcentajes de
                                    Locales Educativos
                                    conectados a red de Desague</a><span class="float-right">0%</span></h6>
                            <div class="progress progress-sm">
                                <div class="progress-bar wow animated progress-animated" role="progressbar"
                                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">80% Complete</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 skills009">
                            <h6 class="font-12"><a href="{{ route('serviciosbasicos.principal') }}"
                                    class="link">Porcentajes de
                                    Locales Educativos
                                    conectados a red de Electricidad</a><span class="float-right">0%</span></h6>
                            <div class="progress progress-sm mb-0">
                                <div class="progress-bar wow animated progress-animated" role="progressbar"
                                    aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">95% Complete</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 skills010">
                            <h6 class="font-12"><a href="{{ route('serviciosbasicos.principal') }}"
                                    class="link">Porcentajes de
                                    Locales Educativos
                                    que cuentan con acceso a Internet</a><span class="float-right">0%</span></h6>
                            <div class="progress progress-sm mb-0">
                                <div class="progress-bar wow animated progress-animated" role="progressbar"
                                    aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">95% Complete</span>
                                </div>
                            </div>
                        </div>

                        <div class="font-weight-bold text-muted mb-0 font-9">
                            <span class="float-left" id="span-skills010-fuente">Fuente:</span>
                            <span class="float-right" id="span-skills010-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                        <div class="card-widgets">
                            <a href="{{ route('panelcontrol.educacion.indicador.nuevos.06') }}"
                                class="waves-effect waves-light"><i class="mdi mdi-file-link text-orange-0"
                                    title="DETALLE"></i></a>

                            <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal"
                                data-target="" onclick="datosIndicador(13)"><i
                                    class="mdi mdi-information text-orange-0" title="INFORMACIÓN"></i></a>
                        </div>
                        <h3 class="text-black text-center font-weight-normal font-11 m-0">
                            Porcentaje de docentes titulados en educación inicial</h3>
                    </div>
                    <div class="card-body p-0">
                        <figure class="highcharts-figure p-0">
                            <div id="dtanal3" style="height: 7rem"></div>
                        </figure>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-dtanal3-fuente">Fuente:</span>
                            <span class="float-right" id="span-dtanal3-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                        <div class="card-widgets">
                            <a href="{{ route('panelcontrol.educacion.indicador.nuevos.05') }}"
                                class="waves-effect waves-light"><i class="mdi mdi-file-link text-orange-0"
                                    title="DETALLE"></i></a>

                            <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal"
                                data-target="" onclick="datosIndicador(14)"><i
                                    class="mdi mdi-information text-orange-0" title="INFORMACIÓN"></i></a>
                        </div>
                        <h3 class="text-black text-center font-weight-normal font-11 m-0">
                            Porcentaje de docentes titulados en educación primaria</h3>
                    </div>
                    <div class="card-body p-0">
                        <figure class="highcharts-figure p-0">
                            <div id="dtanal2" style="height: 7rem"></div>
                        </figure>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-dtanal2-fuente">Fuente:</span>
                            <span class="float-right" id="span-dtanal2-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                        <div class="card-widgets">
                            <a href="{{ route('panelcontrol.educacion.indicador.nuevos.04') }}"
                                class="waves-effect waves-light"><i class="mdi mdi-file-link text-orange-0"
                                    title="DETALLE"></i></a>

                            <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal"
                                data-target="" onclick="datosIndicador(15)"><i
                                    class="mdi mdi-information text-orange-0" title="INFORMACIÓN"></i></a>
                        </div>
                        <h3 class="text-black text-center font-weight-normal font-11 m-0">
                            Porcentaje de docentes titulados en educación
                            secundaria</h3>
                    </div>
                    <div class="card-body p-0">
                        <figure class="highcharts-figure p-0">
                            <div id="dtanal1" style="height: 7rem"></div>
                        </figure>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-dtanal1-fuente">Fuente:</span>
                            <span class="float-right" id="span-dtanal1-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- portles --}}

        <div class="row d-none">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-success-0 py-3 text-white">
                        <div class="card-widgets">
                            {{-- <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a> --}}
                            <a data-toggle="collapse" href="#portles1" role="button" aria-expanded="false"
                                aria-controls="portles1"><i class="mdi mdi-minus"></i></a>
                            {{-- <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a> --}}
                        </div>
                        <h5 class="card-title mb-0 text-white">Cobertura de Matrícula Educativa</h5>
                    </div>
                    <div id="portles1" class="collapse show">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('indicador.nuevos.02') }}"
                                                    class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="" onclick="datosIndicador(1)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Tasa neta de matrícula en educación inicial en la población de 3-5 años
                                                de edad
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            {{-- <div id="container" ></div> --}}
                                            <figure class="highcharts-figure p-0">
                                                <div id="container1" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-container1-fuente">Fuente:</span>
                                                <span class="float-right"
                                                    id="span-container1-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('indicador.nuevos.01') }}"
                                                    class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="" onclick="datosIndicador(7)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Tasa neta de matricula en educación primaria en la población de 6 a 11
                                                años de edad</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            {{-- <div id="container" ></div> --}}
                                            <figure class="highcharts-figure p-0">
                                                <div id="container2" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-container2-fuente">Fuente:</span>
                                                <span class="float-right"
                                                    id="span-container2-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="javascript:void(0)" class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="" onclick="datosIndicador(8)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Tasa neta de matricula en educación secundaria en la población de 12 a
                                                16 años de edad</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            {{-- <div id="container" ></div> --}}
                                            <figure class="highcharts-figure p-0">
                                                <div id="container3" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-container3-fuente">Fuente:</span>
                                                <span class="float-right"
                                                    id="span-container3-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-none">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-success-0 py-3 text-white">
                        <div class="card-widgets">
                            {{-- <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a> --}}
                            <a data-toggle="collapse" href="#portles2" role="button" aria-expanded="false"
                                aria-controls="portles2"><i class="mdi mdi-minus"></i></a>
                            {{-- <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a> --}}
                        </div>
                        <h5 class="card-title mb-0 text-white">Entorno de Enseñanza</h5>
                    </div>
                    <div id="portles2" class="collapse show">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('panelcontrol.educacion.indicador.nuevos.06') }}"
                                                    class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target=""
                                                    onclick="datosIndicador(13)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Porcentaje de docentes titulados en educación inicial</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0">
                                                <div id="dtanal3" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-dtanal3-fuente">Fuente:</span>
                                                <span class="float-right" id="span-dtanal3-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('panelcontrol.educacion.indicador.nuevos.05') }}"
                                                    class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target=""
                                                    onclick="datosIndicador(14)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Porcentaje de docentes titulados en educación primaria</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0">
                                                <div id="dtanal2" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-dtanal2-fuente">Fuente:</span>
                                                <span class="float-right" id="span-dtanal2-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('panelcontrol.educacion.indicador.nuevos.04') }}"
                                                    class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target=""
                                                    onclick="datosIndicador(15)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Porcentaje de docentes titulados en educación
                                                secundaria</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0">
                                                <div id="dtanal1" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-dtanal1-fuente">Fuente:</span>
                                                <span class="float-right" id="span-dtanal1-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            {{-- <div class="row">

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('panelcontrol.educacion.indicador.nuevos.04') }}"
                                                    class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0" title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="#myModal-anal1"><i
                                                        class="mdi mdi-information text-orange-0" title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3
                                                class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                                Porcentaje de Docentes Titulados en Educación
                                                Secundaria</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure m-0">
                                                <div id="anal-1" style="height: 15rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2" style="font-size:9px">
                                                <span class="float-left" id="span-anal1-fuente">Fuente:</span>
                                                <span class="float-right" id="span-anal1-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('panelcontrol.educacion.indicador.nuevos.05') }}"
                                                    class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0" title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="#myModal-anal2"><i
                                                        class="mdi mdi-information text-orange-0" title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3
                                                class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                                Porcentaje de Docentes Titulados en Educación Primaria</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure m-0">
                                                <div id="anal-2" style="height: 15rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-anal2-fuente">Fuente:</span>
                                                <span class="float-right" id="span-anal2-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-none">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-success-0 py-3 text-white">
                        <div class="card-widgets">
                            {{-- <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a> --}}
                            <a data-toggle="collapse" href="#portles1" role="button" aria-expanded="false"
                                aria-controls="portles1"><i class="mdi mdi-minus"></i></a>
                            {{-- <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a> --}}
                        </div>
                        <h5 class="card-title mb-0 text-white">Instituciones Educativas</h5>
                    </div>
                    <div id="portles1" class="collapse show">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('indicador.nuevos.02') }}"
                                                    class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="" onclick="datosIndicador(1)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Indicador 1
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0">
                                                <div id="iiee1" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-iiee1-fuente">Fuente:</span>
                                                <span class="float-right" id="span-iiee1-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('indicador.nuevos.01') }}"
                                                    class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="" onclick="datosIndicador(7)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Indicador 2</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0">
                                                <div id="iiee2" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-iiee2-fuente">Fuente:</span>
                                                <span class="float-right" id="span-iiee2-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="javascript:void(0)" class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="" onclick="datosIndicador(8)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Indicador 3</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0">
                                                <div id="iiee3" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-iiee3-fuente">Fuente:</span>
                                                <span class="float-right" id="span-iiee3-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('indicador.nuevos.02') }}"
                                                    class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="" onclick="datosIndicador(1)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Indicador 4
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0">
                                                <div id="iiee4" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-iiee4-fuente">Fuente:</span>
                                                <span class="float-right" id="span-iiee4-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('indicador.nuevos.01') }}"
                                                    class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="" onclick="datosIndicador(7)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Indicador 7</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0">
                                                <div id="iiee5" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-iiee5-fuente">Fuente:</span>
                                                <span class="float-right" id="span-iiee5-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2"
                                            style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="javascript:void(0)" class="waves-effect waves-light"><i
                                                        class="mdi mdi-file-link text-orange-0"
                                                        title="DETALLE"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light"
                                                    data-toggle="modal" data-target="" onclick="datosIndicador(8)"><i
                                                        class="mdi mdi-information text-orange-0"
                                                        title="INFORMACIÓN"></i></a>
                                            </div>
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0">
                                                Indicador 6</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure p-0">
                                                <div id="iiee6" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-iiee6-fuente">Fuente:</span>
                                                <span class="float-right" id="span-iiee6-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-border border border-plomo-0">
                    <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                        {{-- <div class="card-widgets">
                            <button type="button" class="btn btn-success btn-xs" onclick="descargar1()"><i
                                    class="fa fa-file-excel"></i> Descargar</button>
                        </div> --}}
                        <h3 class="text-black font-14">Matrícula educativa de estudiantes de educación básica por sexo,
                            según UGEL</h3>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive" id="vtabla1">
                                </div>
                            </div>
                        </div>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left vtabla1-fuente">Fuente:</span>
                            <span class="float-right vtabla1-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>

<div id="modal_datosindicador" class="modal fade font-10" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-12" id="myModalLabel">Datos del indicador</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="" id="form_datosindicador" name="form" class="form-horizontal"
                    autocomplete="off">
                    @csrf
                    <input type="hidden" id="indicador" name="indicador" value="">
                    <div class="form-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Indicador</label>
                                    <textarea class="form-control" name="indicadornombre" id="indicadornombre" cols="30" rows="2"
                                        placeholder="Definición del indicador"></textarea>
                                    {{-- <input id="indicadornombre" name="indicadornombre" class="form-control"
                                        type="text" placeholder="Nombre del indicador"> --}}
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Definición</label>
                                    <textarea class="form-control" name="indicadordescripcion" id="indicadordescripcion" cols="30" rows="5"
                                        placeholder="Definición del indicador"></textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Instrumento de gestion</label>
                                    <input id="indicadorinstrumento" name="indicadorinstrumento" class="form-control"
                                        type="text" placeholder="Fuente de datos">
                                    <span class="help-block"></span>
                                </div>
                                <div class="col-md-6">
                                    <label>Tipo de indicador</label>
                                    <input id="indicadortipo" name="indicadortipo" class="form-control"
                                        type="text" placeholder="Fuente de datos">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Fuente de datos</label>
                                    <input id="indicadorfuentedato" name="indicadorfuentedato" class="form-control"
                                        type="text" placeholder="Fuente de datos">
                                    <span class="help-block"></span>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button> --}}
                {{-- <button type="button" class="btn btn-primary btn-xs waves-effect waves-light" onclick="verpdf(8)">Ficha Tecnica</button> --}}
            </div>
        </div>
    </div>
</div>

@section('js')
    <script type="text/javascript">
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        $(document).ready(function() {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });
            cargarCards();
        });

        function cargarCards() {
            $.ajax({
                url: "{{ route('panelcontrol.educacion.head') }}",
                data: {
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "tipogestion": $('#tipogestion').val(),
                    "ambito": $('#ambito').val(),
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
                        .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                        .addClass(data.ind1 > 84 ? 'bg-success-0' : (data.ind1 > 49 ? 'bg-warning-0' :
                            'bg-orange-0'));
                    $('#bebr').css('width', data.ind2 + '%').removeClass(
                            'bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind2 > 84 ? 'bg-success-0' : (data.ind2 > 49 ? 'bg-warning-0' :
                            'bg-orange-0'));
                    $('#bebe').css('width', data.ind3 + '%').removeClass(
                            'bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind3 > 84 ? 'bg-success-0' : (data.ind3 > 49 ? 'bg-warning-0' :
                            'bg-orange-0'));
                    $('#beba').css('width', data.ind4 + '%').removeClass(
                            'bg-success-0 bg-orange-0 bg-warning-0')
                        .addClass(data.ind4 > 84 ? 'bg-success-0' : (data.ind4 > 49 ? 'bg-warning-0' :
                            'bg-orange-0'));
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });


        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('panelcontrol.educacion.graficas') }}",
                data: {
                    'div': div,
                    "anio": 2024,
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "tipogestion": $('#tipogestion').val(),
                    "ambito": $('#ambito').val(),
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    if (div == "siagie001") {
                        $('#' + div).html(
                            '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else if (div == "censodocente001") {
                        $('#' + div).html(
                            '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    } else {
                        // $('#' + div).html(
                        //     '<span><i class="fa fa-spinner fa-spin"></i></span>');
                    }
                },
                success: function(data) {
                    switch (div) {
                        case "siagie001":
                            gAnidadaColumn(div,
                                data.info.cat,
                                data.info.dat,
                                '',
                                'Numero de estudiantes matriculados en educacion basica regular, periodo 2018 - 2023',
                                data.info.maxbar
                            );
                            $('#span-siagie001-fuente').html("Fuente: " + data.reg.fuente);
                            $('#span-siagie001-fecha').html("Actualizado: " + data.reg.fecha);
                            break;
                        case "censodocente001":
                            gAnidadaColumn(div,
                                data.info.cat,
                                data.info.dat,
                                '',
                                'Numero de docentes en educacion basica regular, periodo 2018 - 2023',
                                data.info.maxbar
                            );
                            $('#span-censodocente001-fuente').html("Fuente: " + data.reg.fuente);
                            $('#span-censodocente001-fecha').html("Actualizado: " + data.reg.fecha);
                            break;
                        case "container1":
                            gsemidona(div, 0, ['#5eb9aa', '#F9FFFE']);
                            $('#span-container1-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-container1-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "container2":
                            gsemidona(div, 0, ['#5eb9aa',
                                '#F9FFFE'
                            ]); // ['#f5bd22', '#FDEEC7']);
                            $('#span-container2-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-container2-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "container3":
                            gsemidona(div, 0, ['#5eb9aa', '#F9FFFE']); // ['#e65310', '#FDD1BD']);
                            $('#span-container3-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-container3-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "dtanal1":
                            gsemidona(div, data.info.indicador, ['#5eb9aa', '#F9FFFE']);
                            $('#span-dtanal1-fuente').html("Fuente: " + data.info.fuente);
                            $('#span-dtanal1-fecha').html("Actualizado: " + data.info.fecha);
                            break;
                        case "dtanal2":
                            gsemidona(div, data.info.indicador, ['#5eb9aa', '#F9FFFE']);
                            $('#span-dtanal2-fuente').html("Fuente: " + data.info.fuente);
                            $('#span-dtanal2-fecha').html("Actualizado: " + data.info.fecha);
                            break;
                        case "dtanal3":
                            gsemidona(div, data.info.indicador, ['#5eb9aa', '#F9FFFE']);
                            $('#span-dtanal3-fuente').html("Fuente: " + data.info.fuente);
                            $('#span-dtanal3-fecha').html("Actualizado: " + data.info.fecha);
                            break;
                        case "iiee1":
                            gsemidona(div, 99.1, ['#5eb9aa', '#F9FFFE']);
                            $('#span-iiee1-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee1-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "iiee2":
                            gsemidona(div, 76.0, ['#5eb9aa', '#F9FFFE']); // ['#f5bd22', '#FDEEC7']);
                            $('#span-iiee2-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee2-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "iiee3":
                            gsemidona(div, 94.9, ['#5eb9aa', '#F9FFFE']); // ['#e65310', '#FDD1BD']);
                            $('#span-iiee3-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee3-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "iiee4":
                            gsemidona(div, 99.1, ['#5eb9aa', '#F9FFFE']);
                            $('#span-iiee4-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee4-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "iiee5":
                            gsemidona(div, 76.0, ['#5eb9aa', '#F9FFFE']); // ['#f5bd22', '#FDEEC7']);
                            $('#span-iiee5-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee5-fecha').html("Actualizado: " + '31/12/2022');
                            break;
                        case "iiee6":
                            gsemidona(div, 94.9, ['#5eb9aa', '#F9FFFE']); // ['#e65310', '#FDD1BD']);
                            $('#span-iiee6-fuente').html("Fuente: " + 'MINEDU');
                            $('#span-iiee6-fecha').html("Actualizado: " + '31/12/2022');
                            break;

                        case "skills001":
                            $('.skills001 h6 span').html(data.info.indicador + "%");
                            $('.skills001 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills002":
                            $('.skills002 h6 span').html(data.info.indicador + "%");
                            $('.skills002 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                        case "skills003":
                            $('.skills003 h6 span').html(data.info.indicador + "%");
                            $('.skills003 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills004":
                            $('.skills004 h6 span').html(data.info.indicador + "%");
                            $('.skills004 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills005":
                            $('.skills005 h6 span').html(data.info.indicador + "%");
                            $('.skills005 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            $('#span-skills005-fuente').html("Fuente: " + data.reg.fuente);
                            $('#span-skills005-fecha').html("Actualizado: " + data.reg.fecha);
                            break;
                        case "skills006":
                            $('.skills006 h6 span').html(data.info.indicador + "%");
                            $('.skills006 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));

                            break;
                        case "skills007":
                            $('.skills007 h6 span').html(data.info.indicador + "%");
                            $('.skills007 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills008":
                            $('.skills008 h6 span').html(data.info.indicador + "%");
                            $('.skills008 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills009":
                            $('.skills009 h6 span').html(data.info.indicador + "%");
                            $('.skills009 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            break;
                        case "skills010":
                            $('.skills010 h6 span').html(data.info.indicador + "%");
                            $('.skills010 .progress-bar').css('width', data.info.indicador + '%')
                                .removeClass('bg-success-0 bg-orange-0 bg-warning-0') //
                                .addClass(data.info.indicador > 84 ? 'bg-success-0' :
                                    (data.info.indicador > 49 ? 'bg-warning-0' : 'bg-orange-0'));
                            $('#span-skills010-fuente').html("Fuente: " + data.reg.fuente);
                            $('#span-skills010-fecha').html("Actualizado: " + data.reg.fecha);
                            break;
                        case "tabla1":
                            $('#vtabla1').html(data.excel);
                            $('.vtabla1-fuente').html('Fuente: ' + data.reg.fuente);
                            $('.vtabla1-fecha').html('Actualizado: ' + data.reg.fecha);
                            $('#tabla1').DataTable({
                                responsive: true,
                                autoWidth: false,
                                ordered: true,
                                searching: false,
                                bPaginate: false,
                                info: false,
                                language: table_language,
                            });
                            break;
                        default:
                            break;
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
                url: "{{ route('plaza.cargardistritos', '') }}/" + $('#provincia').val(),
                type: 'GET',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data.distritos, function(index, value) {
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

        function datosIndicador(id) {
            $.ajax({
                url: "{{ route('mantenimiento.indicadorgeneral.buscar.1', '') }}/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    if (data.ie) {
                        $('#indicador').val(data.ie.id);
                        $('#indicadornombre').val(data.ie.nombre);
                        $('#indicadordescripcion').val(data.ie.descripcion);
                        $('#indicadorinstrumento').val(data.ie.instrumento);
                        $('#indicadortipo').val(data.ie.tipo);
                        $('#indicadorfuentedato').val(data.ie.fuente_dato);
                        $('#modal_datosindicador .modal-footer').html(
                            '<button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button><button type="button" class="btn btn-primary btn-xs waves-effect waves-light" onclick="verpdf(' +
                            id + ')">Ficha Tecnica</button>');
                        $('#modal_datosindicador').modal('show');
                    } else {
                        toastr.error('ERROR, Indicador no encontrado, consulte al administrador', 'Mensaje');
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR DE INDICADOR");
                    console.log(jqXHR);
                },
            });
        };

        function verpdf(id) {
            window.open("{{ route('mantenimiento.indicadorgeneral.exportar.pdf', '') }}/" + id);
        };

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
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            connectorColor: 'silver'
                        }
                    }
                },
                /* plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            format: '{point.percentage:.1f}% ({point.y})',
                            connectorColor: 'silver'
                        }
                    }
                }, */
                series: [{
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

        function gsemidona(div, valor, colors) {
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
                        colors: colors,
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

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo, maxBar) {
            var rango = categoria.length;
            var posPorcentaje = rango * 2 + 1;
            var cont = 0;
            var porMaxBar = maxBar * 0.5;
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '10px',
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
                        // labels: {
                        //     //format: '{value}°C',
                        //     //style: {
                        //     //    color: Highcharts.getOptions().colors[2]
                        //     //}
                        // },
                        title: {
                            enabled: false,
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
                        min: -600,
                        max: 400,
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
                            /* formatter: function() {
                                if (this.colorIndex == 2)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
                            }, */
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
                    enabled: true
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

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
