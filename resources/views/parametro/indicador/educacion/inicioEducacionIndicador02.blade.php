@extends('layouts.main', ['titlePage' => 'INDICADOR'])
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

        3 .tablex tbody td,
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

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header bg-success-0">
                            <div class="card-widgets">
                                <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf(8)"><i
                                        class="ion ion-logo-usd"></i> Ficha Técnica</button>
                                <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"><i
                                        class="ion ion-logo-usd"></i> Limpiar</button>
                                <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"><i
                                        class="fa fa-print"></i></button>
                            </div>
                            <h3 class="card-title text-white">Porcentaje De Estudiantes Matriculados En Educación Básica
                            </h3>
                        </div>
                        <div class="card-body pb-0">
                            <div class="form-group row align-items-center vh-5">
                                <div class="col-lg-5 col-md-5 col-sm-5">
                                    <h5 class="page-title font-12">{{ $actualizado }}</h5>
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-1  ">
                                    <select id="anio" name="anio" class="form-control font-11"
                                        onchange="cargartarjetas();">
                                        <option value="0">AÑO</option>
                                        @foreach ($anios as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $item->anio == date('Y') ? 'selected' : '' }}>
                                                {{ $item->anio }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <select id="provincia" name="provincia" class="form-control font-11"
                                        onchange="cargartarjetas();">
                                        <option value="0">PROVINCIA</option>
                                        @foreach ($provincias as $item)
                                            <option value="{{ $item->id }}"> {{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <select id="distrito" name="distrito" class="form-control font-11"
                                        onchange="cargartarjetas();">
                                        <option value="0">DISTRITO</option>
                                        @foreach ($distritos as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    <select id="tipogestions" name="tipogestion" class="form-control font-11"
                                        onchange="cargartarjetas();">
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
                                <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup">
                                            {{ number_format($info['se'], 0) }}
                                        </span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Matriculados</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0 font-9">
                            <h6 class="">Avance <span class="float-right">99.1%</span></h6>
                            <div class="progress progress-sm m-0">
                                <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="60"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 99.1%">
                                    <span class="sr-only">60% Complete</span>
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
                                <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup">
                                            {{ number_format($info['le'], 0) }}
                                        </span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Matricula EBR </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0 font-9">
                            <h6 class="">Avance <span class="float-right">99.1%</span></h6>
                            <div class="progress progress-sm m-0">
                                <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="60"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 99.1%">
                                    <span class="sr-only">60% Complete</span>
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
                                <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup">
                                            {{ number_format($info['tm'], 0) }}
                                        </span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Matricula EBE</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0 font-9">
                            <h6 class="">Avance <span class="float-right">99.1%</span></h6>
                            <div class="progress progress-sm m-0">
                                <div class="progress-bar bg-success-0" role="progressbar" aria-valuenow="60"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 99.1%">
                                    <span class="sr-only">60% Complete</span>
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
                                <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="70%" height="70%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup">
                                            {{ number_format($info['do'], 0) }}
                                        </span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Matricula EBA</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-0 font-9">
                            <h6 class="">Avance <span class="float-right">0%</span></h6>
                            <div class="progress progress-sm m-0">
                                <div class="progress-bar bg-warning-0" role="progressbar" aria-valuenow="60"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                    <span class="sr-only">60% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- portles --}}

            <div class="row">

                <div class="col-lg-6">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                            <div class="card-widgets">
                                {{-- <a href="javascript:void(0)" class="waves-effect waves-light"><i
                                        class=" mdi mdi-download text-orange-0"></i></a> --}}

                                <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal"
                                    data-target="#myModal"><i class="mdi mdi-information text-orange-0"></i></a>
                            </div>
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                Evolución de la Matricula educativa en educación básica, segun periodo 2019-2023 </h3>
                        </div>
                        <div class="card-body p-0">
                            <figure class="highcharts-figure p-0">
                                <div id="sganal1" style="height: 15rem"></div>
                                <p class="highcharts-description d-none">
                                    Pie chart showing a hollow semi-circle. This is a compact
                                    visualization,
                                    often used in dashboards.
                                </p>
                            </figure>
                            <div class="font-weight-bold text-muted ml-2 mr-2" style="font-size:9px">
                                Fuente:
                                <span class="float-right">Actualizado:</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                            <div class="card-widgets">
                                {{-- <a href="javascript:void(0)" class="waves-effect waves-light"><i
                                        class=" mdi mdi-download text-orange-0"></i></a> --}}

                                <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal"
                                    data-target="#myModal"><i class="mdi mdi-information text-orange-0"></i></a>
                            </div>
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                Matricula educativa acumulada mensual en educación básica </h3>
                        </div>
                        <div class="card-body p-0">
                            {{-- <div id="container" ></div> --}}
                            <figure class="highcharts-figure p-0">
                                <div id="sganal2" style="height: 15rem"></div>
                                <p class="highcharts-description d-none">
                                    Pie chart showing a hollow semi-circle. This is a compact
                                    visualization,
                                    often used in dashboards.
                                </p>
                            </figure>
                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">Fuente:
                                <span class="float-right">Actualizado:</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                            <div class="card-widgets">
                                {{-- <a href="javascript:void(0)" class="waves-effect waves-light"><i
                                        class=" mdi mdi-download text-orange-0"></i></a> --}}

                                <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal"
                                    data-target="#myModal"><i class="mdi mdi-information text-orange-0"></i></a>
                            </div>
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                Estudiantes Matriculados Según Sexo</h3>
                        </div>
                        <div class="card-body p-0">
                            {{-- <div id="container" ></div> --}}
                            <figure class="highcharts-figure p-0">
                                <div id="sganal3" style="height: 15rem"></div>
                                <p class="highcharts-description d-none">
                                    Pie chart showing a hollow semi-circle. This is a compact
                                    visualization,
                                    often used in dashboards.
                                </p>
                            </figure>
                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">Fuente:
                                <span class="float-right">Actualizado:</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2" style="height: 4rem">
                            <div class="card-widgets">
                                {{-- <a href="javascript:void(0)" class="waves-effect waves-light"><i
                                        class=" mdi mdi-download text-orange-0"></i></a> --}}

                                <a href="javascript:void(0)" class="waves-effect waves-light" data-toggle="modal"
                                    data-target="#myModal"><i class="mdi mdi-information text-orange-0"></i></a>
                            </div>
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11">
                                Estudiantes Matriculados Según Área Geográfica</h3>
                        </div>
                        <div class="card-body p-0">
                            {{-- <div id="container" ></div> --}}
                            <figure class="highcharts-figure p-0">
                                <div id="sganal4" style="height: 15rem"></div>
                                <p class="highcharts-description d-none">
                                    Pie chart showing a hollow semi-circle. This is a compact
                                    visualization,
                                    often used in dashboards.
                                </p>
                            </figure>
                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">Fuente:
                                <span class="float-right">Actualizado: </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>



            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        {{-- <div class="card-header">
                            <h3 class="card-title">Bordered Table</h3>
                        </div> --}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr class="table-secondary">
                                                    <th>UGEL</th>
                                                    <th>META</th>
                                                    <th>ENE</th>
                                                    <th>FEB</th>
                                                    <th>MAZ</th>
                                                    <th>ABR</th>
                                                    <th>MAY</th>
                                                    <th>JUN</th>
                                                    <th>JUL</th>
                                                    <th>AGO</th>
                                                    <th>SET</th>
                                                    <th>OCT</th>
                                                    <th>NOV</th>
                                                    <th>DIC</th>
                                                    <th>TOTAL</th>
                                                    <th>AVANCE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>UGEL ATALAYA</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>UGEL CORONEL PORTILLA</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>UGEL PADREA ABAD</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>UGEL PURUS</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="table-secondary">
                                                    <td>TOTAL</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
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
                        {{-- <div class="card-header">
                            <h3 class="card-title">Bordered Table</h3>
                        </div> --}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr class="table-secondary">
                                                    <th>MODALIDAD/UGEL</th>
                                                    <th>META</th>
                                                    <th>ENE</th>
                                                    <th>FEB</th>
                                                    <th>MAZ</th>
                                                    <th>ABR</th>
                                                    <th>MAY</th>
                                                    <th>JUN</th>
                                                    <th>JUL</th>
                                                    <th>AGO</th>
                                                    <th>SET</th>
                                                    <th>OCT</th>
                                                    <th>NOV</th>
                                                    <th>DIC</th>
                                                    <th>TOTAL</th>
                                                    <th>AVANCE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="table-warning">
                                                    <td>EBA</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Basica Alternativa Avanzado</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Basica Alternativa Avanzado inicial o intermedio</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="table-warning">
                                                    <td>EBE</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Basica Alternativa Avanzado</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Basica Alternativa Avanzado</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Basica Alternativa Avanzado</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="table-warning">
                                                    <td>EBR</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Basica Alternativa Avanzado</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Basica Alternativa Avanzado</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Basica Alternativa Avanzado</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Basica Alternativa Avanzado</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Basica Alternativa Avanzado</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>

                                                <tr class="table-secondary">
                                                    <td>TOTAL</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            panelGraficas('sganal1', 1);
            panelGraficas('sganal2', 2);
            panelGraficas('sganal3', 3);
            panelGraficas('sganal4', 3);
        });

        function panelGraficas(div, tipo) {
            $.ajax({
                url: "{{ route('panelcontrol.educacion.graficas') }}",
                data: {
                    'div': div,
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (tipo == 1) {
                        gAnidadaColumn(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            ''
                        );
                    } else if (tipo == 2) {
                        gLineaBasica(div, data.data, '', '', '');
                    } else if (tipo == 3) {
                        gPie(div, data.puntos, '', '', '');
                    }

                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

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
                        colors,
                        dataLabels: {
                            enabled: true,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            //format: '{point.percentage:.1f}% ({point.y})',
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            connectorColor: 'silver'
                        }
                    }
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
                    min: 0,
                },
                xAxis: {
                    categories: data.cat,
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

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo) {
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

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
