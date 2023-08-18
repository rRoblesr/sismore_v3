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
</style>
@endsection

<div>
    <div id="container-speed" class="chart-container"></div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="form-group row align-items-center vh-5">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <h4 class="page-title font-16">SISMORE EDUCACIÓN</h4>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="provincia" name="provincia" class="form-control btn-xs font-11" onchange="cargarDistritos(),cargarCards();">
                    <option value="0">PROVINCIA</option>
                    @foreach ($provincias as $item)
                    <option value="{{ $item->id }}"> {{ $item->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="distrito" name="distrito" class="form-control btn-xs font-11" onchange="cargarCards();">
                    <option value="0">DISTRITO</option>
                    @foreach ($distritos as $item)
                    <option value="{{ $item->id }}">{{ $item->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="tipogestion" name="tipogestion" class="form-control btn-xs font-11" onchange="cargarCards();">
                    <option value="0">TIPO DE GESTIÓN</option>
                    <option value="12">PUBLICA</option>
                    <option value="3">PRIVADA</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2">
                <select id="ambito" name="ambito" class="form-control btn-xs font-11" onchange="cargarCards();">
                    <option value="0">ÁMBITO</option>
                    @foreach ($ambitos as $item)
                    <option value="{{ $item->id }}">{{ $item->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <!--Widget-4 -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card-box">
                    <div class="media">
                        <div class="text-center">
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/servicios.png" alt="" class="" width="70%" height="70%">
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
                <div class="card-box">
                    <div class="media">
                        <div class="text-center">
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/locales.png" alt="" class="" width="70%" height="70%">
                        </div>
                        {{-- <div class="avatar-md bg-info rounded-circle mr-2">
                                <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                            </div> --}}
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-20 my-0 font-weight-bold">
                                    <span data-plugin="counterup" id="locales"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate">Locales Escolares </p>
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
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/matriculas.png" alt="" class="" width="70%" height="70%">
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
                <div class="card-box">
                    <div class="media">
                        <div class="text-center">
                            {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class="" width="70%" height="70%">
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

        {{-- portles --}}

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-success-0 py-3 text-white">
                        <div class="card-widgets">
                            {{-- <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a> --}}
                            <a data-toggle="collapse" href="#portles1" role="button" aria-expanded="false" aria-controls="portles1"><i class="mdi mdi-minus"></i></a>
                            {{-- <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a> --}}
                        </div>
                        <h5 class="card-title mb-0 text-white">Cobertura de Matrícula Educativa</h5>
                    </div>
                    <div id="portles1" class="collapse show">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('indicador.nuevos.01') }}" class="waves-effect waves-light"><i class="mdi mdi-file-link text-orange-0"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal" data-target="#myModal-container1"><i class="mdi mdi-information text-orange-0"></i></a>
                                            </div>
                                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                                Porcentaje de estudiantes matriculados en educación básica
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            {{-- <div id="container" ></div> --}}
                                            <figure class="highcharts-figure p-0">
                                                <div id="container1" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-container1-fuente">Fuente:</span>
                                                <span class="float-right" id="span-container1-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('indicador.nuevos.01') }}" class="waves-effect waves-light"><i class="mdi mdi-file-link text-orange-0"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal" data-target="#myModal-container2"><i class="mdi mdi-information text-orange-0"></i></a>
                                            </div>
                                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                                Tasa Neta
                                                de Matrícula en Educación Secundaria en la Población de 12 a 16 Años de
                                                Edad</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            {{-- <div id="container" ></div> --}}
                                            <figure class="highcharts-figure p-0">
                                                <div id="container2" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-container2-fuente">Fuente:</span>
                                                <span class="float-right" id="span-container2-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="javascript:void(0)" class="waves-effect waves-light"><i class="mdi mdi-file-link text-orange-0"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal" data-target="#myModal-container3"><i class="mdi mdi-information text-orange-0"></i></a>
                                            </div>
                                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                                Tasa Neta
                                                de Matrícula en Educación Primaria en la Población de 6 - 11 Años de
                                                Edad</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            {{-- <div id="container" ></div> --}}
                                            <figure class="highcharts-figure p-0">
                                                <div id="container3" style="height: 7rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-container3-fuente">Fuente:</span>
                                                <span class="float-right" id="span-container3-fecha">Actualizado:</span>
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
                <div class="card">
                    <div class="card-header bg-success-0 py-3 text-white">
                        <div class="card-widgets">
                            {{-- <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a> --}}
                            <a data-toggle="collapse" href="#portles2" role="button" aria-expanded="false" aria-controls="portles2"><i class="mdi mdi-minus"></i></a>
                            {{-- <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a> --}}
                        </div>
                        <h5 class="card-title mb-0 text-white">Entorno de Enseñanza</h5>
                    </div>
                    <div id="portles2" class="collapse show">
                        <div class="card-body pb-0">

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('panelcontrol.educacion.indicador.nuevos.04') }}" class="waves-effect waves-light"><i class="mdi mdi-file-link text-orange-0"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal" data-target="#myModal-anal1"><i class="mdi mdi-information text-orange-0"></i></a>
                                            </div>
                                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                                Porcentaje de Docentes Titulados en Educación
                                                Secundaria</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure m-0">
                                                <div id="anal1" style="height: 15rem"></div>
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
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="{{ route('panelcontrol.educacion.indicador.nuevos.05') }}" class="waves-effect waves-light"><i class="mdi mdi-file-link text-orange-0"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal" data-target="#myModal-anal2"><i class="mdi mdi-information text-orange-0"></i></a>
                                            </div>
                                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                                Porcentaje de Docentes Titulados en Educación Primaria</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <figure class="highcharts-figure m-0">
                                                <div id="anal2" style="height: 15rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-anal2-fuente">Fuente:</span>
                                                <span class="float-right" id="span-anal2-fecha">Actualizado:</span>
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
                <div class="card">
                    <div class="card-header bg-success-0 py-3 text-white">
                        <div class="card-widgets">
                            {{-- <a href="javascript:;" data-toggle="reload"><i class="mdi mdi-refresh"></i></a> --}}
                            <a data-toggle="collapse" href="#portles3" role="button" aria-expanded="false" aria-controls="portles3"><i class="mdi mdi-minus"></i></a>
                            {{-- <a href="#" data-toggle="remove"><i class="mdi mdi-close"></i></a> --}}
                        </div>
                        <h5 class="card-title mb-0 text-white">Instituciones Educativas</h5>
                    </div>
                    <div id="portles3" class="collapse show">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="javascript:void(0)" class="waves-effect waves-light"><i class="mdi mdi-file-link text-orange-0"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal" data-target="#myModal-anal3"><i class="mdi mdi-information text-orange-0"></i></a>
                                            </div>
                                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                                Porcentaje De Docentes Titulados En Educación Secundaria</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            {{-- <div id="container" ></div> --}}
                                            <figure class="highcharts-figure m-0">
                                                <div id="anal3" style="height: 15rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-anal3-fuente">Fuente:</span>
                                                <span class="float-right" id="span-anal3-fecha">Actualizado:</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                                            <div class="card-widgets">
                                                <a href="javascript:void(0)" class="waves-effect waves-light"><i class="mdi mdi-file-link text-orange-0"></i></a>

                                                <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal" data-target="#myModal-anal4"><i class="mdi mdi-information text-orange-0"></i></a>
                                            </div>
                                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                                Porcentaje De Docentes Titulados En Educación Primaria</h3>
                                        </div>
                                        <div class="card-body p-0">
                                            {{-- <div id="container" ></div> --}}
                                            <figure class="highcharts-figure m-0">
                                                <div id="anal4" style="height: 15rem"></div>
                                            </figure>
                                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                                <span class="float-left" id="span-anal4-fuente">Fuente:</span>
                                                <span class="float-right" id="span-anal4-fecha">Actualizado:</span>
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

    </div>
</div>

<div id="myModal-container1" class="modal fade font-10" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-12" id="myModalLabel">Porcentaje De Estudiantes Matriculados En Educación
                    Básica</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h5 class="font-12">Definición</h5>
                <p>El indicador busca medir el número de estudiantes matriculados en las instituciones educativas en los
                    niveles de inicial, primaria y secundaria de la Educación Basica Regular, Educación Básica Especial
                    y Básica Alternativa de gestión pública y privada en el departamento de Ucayali</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                {{-- <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

<div id="myModal-container2" class="modal fade font-10" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-12" id="myModalLabel">Tasa Neta
                    De Matrícula En Educación Secundaria En La Población De 12 a 16 Años De
                    Edad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h5 class="font-12">Definición</h5>
                <p>El indicador busca medir la población matriculada en un nivel inicial de la Educación Básica Regular,
                    que se encuentran en el grupo de edades establecido para dicho nivel (3-5 años), respecto de la
                    población total de dicho grupo de edades en el departamento de Ucayali.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                {{-- <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

<div id="myModal-container3" class="modal fade font-10" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-12" id="myModalLabel">Tasa Neta
                    De Matrícula En Educación Primaria En La Población De 6 - 11 Años De
                    Edad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h5 class="font-12">Definición</h5>
                <p>El indicador busca medir la población matriculada en un nivel primaria de la Educación Básica
                    Regular, que se encuentran en el grupo de edades establecido para dicho nivel (6-11 años), respecto
                    de la población total de dicho grupo de edades en el departamento de Ucayali.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                {{-- <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

<div id="myModal-anal1" class="modal fade font-10" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-12" id="myModalLabel">Porcentaje De Docentes Titulados En Educación
                    Secundaria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h5 class="font-12">Definición</h5>
                <p>El indicador busca medir el porcentaje de docentes con titulo pedagógico en las especialidades
                    asociadas al nivel secundaria, que laboran en las instituciones educativas públicas y privadas del
                    nivel secundaria del departamento de Ucayali.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                {{-- <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

<div id="myModal-anal2" class="modal fade font-10" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-12" id="myModalLabel">Porcentaje De Docentes Titulados En Educación
                    Primaria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h5 class="font-12">Definición</h5>
                <p>El indicador busca medir el porcentaje de docentes con titulo pedagógico en las especialidades
                    asociadas al nivel primaria, que laboran en las instituciones educativas públicas y privadas del
                    nivel secundaria del departamento de Ucayali.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                {{-- <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

<div id="myModal-anal4" class="modal fade font-10" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-12" id="myModalLabel">Porcentaje De Estudiantes Matriculados En Educación
                    Básica</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h5 class="font-12">Definición</h5>
                <p>El indicador busca medir el número de estudiantes matriculados en las instituciones educativas en los
                    niveles de inicial, primaria y secundaria de la Educación Basica Regular, Educación Básica Especial
                    y Básica Alternativa de gestión pública y privada en el departamento de Ucayali</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                {{-- <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>

<div id="myModal-anal4" class="modal fade font-10" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-12" id="myModalLabel">Porcentaje De Estudiantes Matriculados En Educación
                    Básica</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <h5 class="font-12">Definición</h5>
                <p>El indicador busca medir el número de estudiantes matriculados en las instituciones educativas en los
                    niveles de inicial, primaria y secundaria de la Educación Basica Regular, Educación Básica Especial
                    y Básica Alternativa de gestión pública y privada en el departamento de Ucayali</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-xs btn-danger waves-effect" data-dismiss="modal">Cerrar</button>
                {{-- <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button> --}}
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
        cargarCards();
        /* panelGraficas('container1');
        panelGraficas('container2');
        panelGraficas('container3');
        panelGraficas('anal1');
        panelGraficas('anal2');
        panelGraficas('anal3');
        panelGraficas('anal4'); */
    });

    function cargarCards() {
        $.ajax({
            url: "{{ route('panelcontrol.educacion.head') }}",
            data: {
                "provincia": $('#provincia').val(),
                "distrito": $('#distrito').val(),
                "tipogestion": $('#tipogestion').val(),
                "ambito": $('#ambito').val(),
                "impidpadweb": "{{ $impidpadweb }}",
                "impidsiagie": "{{ $impidsiagie }}",
                "impidnexus": "{{ $impidnexus }}",
            },
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#servicios').text(data.valor1);
                $('#locales').text(data.valor2);
                $('#matriculados').text(data.valor3);
                $('#docentes').text(data.valor4);
            },
            erro: function(jqXHR, textStatus, errorThrown) {
                console.log("ERROR GRAFICA 1");
                console.log(jqXHR);
            },
        });

        panelGraficas('container1');
        panelGraficas('container2');
        panelGraficas('container3');
        panelGraficas('anal1');
        panelGraficas('anal2');
        panelGraficas('anal3');
        panelGraficas('anal4');
    }

    function panelGraficas(div) {
        $.ajax({
            url: "{{ route('panelcontrol.educacion.graficas') }}",
            data: {
                'div': div,
                "provincia": $('#provincia').val(),
                "distrito": $('#distrito').val(),
                "tipogestion": $('#tipogestion').val(),
                "ambito": $('#ambito').val(),
            },
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                switch (div) {
                    case "container1":
                        gsemidona(div, 99.1, ['#5eb9aa', '#F9FFFE']);
                        $('#span-container1-fuente').html("Fuente: " + 'MINEDU');
                        $('#span-container1-fecha').html("Actualizado: " + '31/12/2022');
                        break;
                    case "container2":
                        gsemidona(div, 76.0, ['#5eb9aa', '#F9FFFE']); // ['#f5bd22', '#FDEEC7']);
                        $('#span-container2-fuente').html("Fuente: " + 'MINEDU');
                        $('#span-container2-fecha').html("Actualizado: " + '31/12/2022');
                        break;
                    case "container3":
                        gsemidona(div, 94.9, ['#5eb9aa', '#F9FFFE']); // ['#e65310', '#FDD1BD']);
                        $('#span-container3-fuente').html("Fuente: " + 'MINEDU');
                        $('#span-container3-fecha').html("Actualizado: " + '31/12/2022');
                        break;
                    case "anal1":
                        gAnidadaColumn(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            '', data.info.maxbar);
                        $('#span-anal1-fuente').html("Fuente: " + data.info.fuente);
                        $('#span-anal1-fecha').html("Actualizado: " + data.info.fecha);
                        break;
                    case "anal2":
                        gAnidadaColumn(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            '', data.info.maxbar);
                        $('#span-anal2-fuente').html("Fuente: " + data.info.fuente);
                        $('#span-anal2-fecha').html("Actualizado: " + data.info.fecha);
                        break;
                    case "anal3":
                        gAnidadaColumn(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            '', 19000);
                        $('#span-anal3-fuente').html("Fuente: " + data.info.fuente);
                        $('#span-anal3-fecha').html("Actualizado: " + data.info.fecha);
                        break;
                    case "anal4":
                        gAnidadaColumn(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            '', 19000);
                        $('#span-anal4-fuente').html("Fuente: " + data.info.fuente);
                        $('#span-anal4-fecha').html("Actualizado: " + data.info.fecha);
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
            title: {
                text: titulo, //'Browser market shares in January, 2018'
            },
            subtitle: {
                text: subtitulo,
            },
            xAxis: [{
                categories: categoria,
                crosshair: true,
            }],
            yAxis: [{ // Primary yAxis
                    max: maxBar + porMaxBar,
                    labels: {
                        enabled: false,
                    },
                    title: {
                        enabled: false,
                    },
                    labels: {
                        //format: '{value}°C',
                        //style: {
                        //    color: Highcharts.getOptions().colors[2]
                        //}
                    },
                    title: {
                        text: 'Numerador y Denomidor',
                        /* style: {
                            color: Highcharts.getOptions().colors[2]
                        } */
                    },
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
                        //text: 'Rainfall',
                        text: '%Indicador',
                        //style: {
                        //    color: Highcharts.getOptions().colors[0]
                        //}
                    },
                    labels: {
                        //format: '{value} mm',
                        format: '{value} %',
                        //style: {
                        //   color: Highcharts.getOptions().colors[0]
                        //}
                    }, */
                    //min: -200,
                    min: 0,
                    //max: 150,
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
                            cont++;
                            //console.log(cont);
                            //console.log(div + " - " + this.points);
                            /* if (this.y > 1000000) {
                                return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                            } else if (this.y > 1000) {
                                return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                            } else if (this.y < 101) {
                                return this.y + "%";
                            } else {
                                return this.y;
                            } */
                            if (cont >= posPorcentaje)
                                return this.y + " %";
                            else
                                return this.y + " "
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

{{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
