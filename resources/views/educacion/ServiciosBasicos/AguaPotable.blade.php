@extends('layouts.main', ['titlePage' => ''])
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>
        .centrarmodal {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000000c9 !important;
        }

        .ui-autocomplete {
            z-index: 215000000 !important;
        }

        /*  formateando nav-tabs  */
        .nav-tabs .nav-link:not(.active) {
            /* border-color: transparent !important; */
        }

        .nav-link {
            /* color: #000; */
            font-weight: bold;
        }

        .nav-tabs .nav-item {
            color: #43beac;

            /* background-color: #43beac; */
            /* #0080FF; */
            /* color: #FFF; */
        }

        .nav-tabs .nav-item .nav-link.active {
            /* color: #43beac; */
            /* #0080FF; */

            background-color: #43beac;
            color: #FFF;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box p-0">
                    <ul class="nav nav-tabs border-0" role="tablist">{{-- nav-justified  --}}
                        <li class="nav-item">
                            <a class="nav-link border border-success-0 border-bottom-0 active" id="vista1-tab"
                                data-toggle="tab" href="#vista1" role="tab" aria-controls="vista1" aria-selected="true">
                                <span class="d-block d-sm-none">
                                    {{-- <i class="mdi mdi-home-variant-outline font-18"></i> --}}
                                    <i class="mdi mdi-shield-plus font-18"></i>
                                </span>
                                <span class="d-none d-sm-block text-center" style="width:180px">
                                    <i class="mdi mdi-shield-plus"></i> SERVICIOS BÁSICOS</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border border-success-0 border-bottom-0" id="vista2-tab" data-toggle="tab"
                                href="#vista2" role="tab" aria-controls="vista2" aria-selected="false">
                                <span class="d-block d-sm-none">
                                    {{-- <i class="mdi mdi-account-outline font-18"></i> --}}
                                    <i class="mdi mdi-school font-18"></i>
                                </span>
                                <span class="d-none d-sm-block text-center" style="width:130px">
                                    <i class="mdi mdi-school"></i> AGUA</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border border-success-0 border-bottom-0" id="vista3-tab" data-toggle="tab"
                                href="#vista3" role="tab" aria-controls="vista3" aria-selected="false">
                                <span class="d-block d-sm-none">
                                    <i class="mdi mdi-home font-18"></i>
                                </span>
                                <span class="d-none d-sm-block text-center" style="width:130px">
                                    <i class="mdi mdi-home"></i> DESAGUE</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border border-success-0 border-bottom-0" id="vista4-tab" data-toggle="tab"
                                href="#vista4" role="tab" aria-controls="vista4" aria-selected="false">
                                <span class="d-block d-sm-none">
                                    <i class="mdi mdi-plus-circle-outline font-18"></i>
                                </span>
                                <span class="d-none d-sm-block text-center" style="width:130px">
                                    <i class="mdi mdi-plus-circle-outline"></i> ELECTRICIDAD</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border border-success-0 border-bottom-0" id="vista5-tab" data-toggle="tab"
                                href="#vista5" role="tab" aria-controls="vista5" aria-selected="false">
                                <span class="d-block d-sm-none">
                                    <i class="mdi mdi-plus-circle-outline font-18"></i>
                                </span>
                                <span class="d-none d-sm-block text-center" style="width:130px">
                                    <i class="mdi mdi-plus-circle-outline"></i> INTERNET</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content p-0">
                        <div class="tab-pane p-3 border border-success-0 show active" id="vista1" role="tabpanel"
                            aria-labelledby="vista1-tab">

                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-success-0 cabeceravista1">
                                            <div class="card-widgets">
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="location.href=`{{ route('matriculageneral.niveleducativo.principal') }}`"
                                                    title=''><i class="fas fa-align-justify"></i> Nivel Educativo</button> --}}
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf(6)"
                                                        title='FICHA TÉCNICA'><i class="fas fa-file"></i> Ficha Técnica</button> --}}
                                                <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="location.reload()" title='ACTUALIZAR'><i
                                                        class=" fas fa-history"></i> Actualizar</button>
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                                        title='IMPRIMIR'><i class="fa fa-print"></i></button> --}}
                                            </div>
                                            <h3 class="card-title text-white"></h3>
                                        </div>
                                        <div class="card-body py-0">
                                            <div class="form-group row align-items-center vh-5">
                                                <div class="col-lg-5 col-md-5 col-sm-5">
                                                    <h5 class="page-title font-11">CENSO EDUCATIVO-MINEDU,
                                                        <br>{{ $actualizado }}
                                                    </h5>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1  ">
                                                    <select id="aniovista1" name="aniovista1"
                                                        class="form-control btn-xs font-11" onchange="cargarCards();">
                                                        <option value="0">AÑO</option>
                                                        @foreach ($anios as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                                                {{ $item->anio }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="provinciavista1" name="provinciavista1"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarDistritos();cargarCards();">
                                                        <option value="0">PROVINCIA</option>
                                                        @foreach ($provincia as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="distritovista1" name="distritovista1"
                                                        class="form-control btn-xs font-11" onchange="cargarCards();">
                                                        <option value="0">DISTRITO</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="areavista1" name="areavista1"
                                                        class="form-control btn-xs font-11" onchange="cargarCards();">
                                                        <option value="0">ÁMBITO GEOGRÁFICO</option>
                                                        @foreach ($area as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- <input type="hidden" id="serviciovista1" name="serviciovista1" --}}
                                                {{-- value="4"> --}}

                                                {{-- <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="servicio" name="servicio" class="form-control btn-xs font-11"
                                                        onchange="cargarCards();">
                                                        <option value="1">AGUA</option>
                                                        <option value="2">DESAGUE</option>
                                                        <option value="3">LUZ</option>
                                                        <option value="4">TRES SERVICIOS</option>
                                                        <option value="5">INTERNET</option>
                                                    </select>
                                                </div> --}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Widget-4 -->
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-box border border-plomo-0">
                                        <div class="media">
                                            <div class="text-center">
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-finance font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card1vista1">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Resultado del Indicador</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class=" mdi mdi-city font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card2vista1">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Total de II.EE</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-thumb-up font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card3vista1">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">II.EE con Agua</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-thumb-down font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card4vista1">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">II.EE sin Agua</p>
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
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal1vista1" style="height: 20rem"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal2vista1" style="height: 20rem"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0" style="height: 47.5rem">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal3vista1" style="height: 100%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0 vtabla1" style="height: 47.5rem">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black font-14 mb-0">Locales Educativos conectados a red de agua
                                                potable, según Distritos
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla1vista1">
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
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <div class="card-widgets">
                                                <button type="button" class="btn btn-success btn-xs"
                                                    onclick="descargar1vista1()"><i class="fa fa-file-excel"></i>
                                                    Descargar</button>
                                            </div>
                                            <h3 class="text-black font-14">Locales Educativos conectados</h3>
                                        </div>
                                        <div class="card-body pt-0 pb-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla3vista1">
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="font-weight-bold text-muted ml-2 mr-2 font-9 p-0">
                                                <span class="float-left vtabxla1-fuente">Fuente: </span>
                                                <span class="float-right vtaxbla1-fecha">Actualizado:</span>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane p-3 border border-success-0" id="vista2" role="tabpanel"
                            aria-labelledby="vista2-tab">

                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-success-0 cabeceravista2">
                                            <div class="card-widgets">
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="location.href=`{{ route('matriculageneral.niveleducativo.principal') }}`"
                                                    title=''><i class="fas fa-align-justify"></i> Nivel Educativo</button> --}}
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf(6)"
                                                        title='FICHA TÉCNICA'><i class="fas fa-file"></i> Ficha Técnica</button> --}}
                                                <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="location.reload()" title='ACTUALIZAR'><i
                                                        class=" fas fa-history"></i> Actualizar</button>
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                                        title='IMPRIMIR'><i class="fa fa-print"></i></button> --}}
                                            </div>
                                            <h3 class="card-title text-white"></h3>
                                        </div>
                                        <div class="card-body py-0">
                                            <div class="form-group row align-items-center vh-5">
                                                <div class="col-lg-5 col-md-5 col-sm-5">
                                                    <h5 class="page-title font-11">CENSO EDUCATIVO-MINEDU,
                                                        <br>{{ $actualizado }}
                                                    </h5>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1  ">
                                                    <select id="aniovista2" name="aniovista2"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista2();">
                                                        <option value="0">AÑO</option>
                                                        @foreach ($anios as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                                                {{ $item->anio }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="provinciavista2" name="provinciavista2"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarDistritosvista2();cargarCardsvista2();">
                                                        <option value="0">PROVINCIA</option>
                                                        @foreach ($provincia as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="distritovista2" name="distritovista2"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista2();">
                                                        <option value="0">DISTRITO</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="areavista2" name="areavista2"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista2();">
                                                        <option value="0">ÁMBITO GEOGRÁFICO</option>
                                                        @foreach ($area as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- <input type="hidden" id="serviciovista2" name="serviciovista2" --}}
                                                {{-- value="4"> --}}

                                                {{-- <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="servicio" name="servicio" class="form-control btn-xs font-11"
                                                        onchange="cargarCards();">
                                                        <option value="1">AGUA</option>
                                                        <option value="2">DESAGUE</option>
                                                        <option value="3">LUZ</option>
                                                        <option value="4">TRES SERVICIOS</option>
                                                        <option value="5">INTERNET</option>
                                                    </select>
                                                </div> --}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Widget-4 -->
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-box border border-plomo-0">
                                        <div class="media">
                                            <div class="text-center">
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-finance font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card1vista2">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Resultado del Indicador</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class=" mdi mdi-city font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card2vista2">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Total de II.EE</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-thumb-up font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card3vista2">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">II.EE con Agua</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-thumb-down font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card4vista2">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">II.EE sin Agua</p>
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
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal1vista2" style="height: 20rem"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal2vista2" style="height: 20rem"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0" style="height: 47.5rem">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal3vista2" style="height: 100%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0 vtabla1" style="height: 47.5rem">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black font-14 mb-0">Locales Educativos conectados a red de agua
                                                potable, según Distritos
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla1vista2">
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
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <div class="card-widgets">
                                                <button type="button" class="btn btn-success btn-xs"
                                                    onclick="descargar1vista2()"><i class="fa fa-file-excel"></i>
                                                    Descargar</button>
                                            </div>
                                            <h3 class="text-black font-14">Locales Educativos conectados</h3>
                                        </div>
                                        <div class="card-body pt-0 pb-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla3vista2">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane p-3 border border-success-0" id="vista3" role="tabpanel"
                            aria-labelledby="vista3-tab">

                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-success-0 cabeceravista3">
                                            <div class="card-widgets">
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="location.href=`{{ route('matriculageneral.niveleducativo.principal') }}`"
                                                    title=''><i class="fas fa-align-justify"></i> Nivel Educativo</button> --}}
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf(6)"
                                                        title='FICHA TÉCNICA'><i class="fas fa-file"></i> Ficha Técnica</button> --}}
                                                <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="location.reload()" title='ACTUALIZAR'><i
                                                        class=" fas fa-history"></i> Actualizar</button>
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                                        title='IMPRIMIR'><i class="fa fa-print"></i></button> --}}
                                            </div>
                                            <h3 class="card-title text-white"></h3>
                                        </div>
                                        <div class="card-body py-0">
                                            <div class="form-group row align-items-center vh-5">
                                                <div class="col-lg-5 col-md-5 col-sm-5">
                                                    <h5 class="page-title font-11">CENSO EDUCATIVO-MINEDU,
                                                        <br>{{ $actualizado }}
                                                    </h5>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1  ">
                                                    <select id="aniovista3" name="aniovista3"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista3();">
                                                        <option value="0">AÑO</option>
                                                        @foreach ($anios as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                                                {{ $item->anio }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="provinciavista3" name="provinciavista3"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarDistritosvista3();cargarCardsvista3();">
                                                        <option value="0">PROVINCIA</option>
                                                        @foreach ($provincia as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="distritovista3" name="distritovista3"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista3();">
                                                        <option value="0">DISTRITO</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="areavista3" name="areavista3"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista3();">
                                                        <option value="0">ÁMBITO GEOGRÁFICO</option>
                                                        @foreach ($area as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- <input type="hidden" id="serviciovista3" name="serviciovista3" --}}
                                                {{-- value="4"> --}}

                                                {{-- <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="servicio" name="servicio" class="form-control btn-xs font-11"
                                                        onchange="cargarCards();">
                                                        <option value="1">AGUA</option>
                                                        <option value="2">DESAGUE</option>
                                                        <option value="3">LUZ</option>
                                                        <option value="4">TRES SERVICIOS</option>
                                                        <option value="5">INTERNET</option>
                                                    </select>
                                                </div> --}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Widget-4 -->
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-box border border-plomo-0">
                                        <div class="media">
                                            <div class="text-center">
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-finance font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card1vista3">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Resultado del Indicador</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class=" mdi mdi-city font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card2vista3">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Total de II.EE</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-thumb-up font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card3vista3">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">II.EE con Agua</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-thumb-down font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card4vista3">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">II.EE sin Agua</p>
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
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal1vista3" style="height: 20rem"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal2vista3" style="height: 20rem"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0" style="height: 47.5rem">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal3vista3" style="height: 100%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0 vtabla1" style="height: 47.5rem">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black font-14 mb-0">Locales Educativos conectados a red de agua
                                                potable, según Distritos
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla1vista3">
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
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <div class="card-widgets">
                                                <button type="button" class="btn btn-success btn-xs"
                                                    onclick="descargar1vista3()"><i class="fa fa-file-excel"></i>
                                                    Descargar</button>
                                            </div>
                                            <h3 class="text-black font-14">Locales Educativos conectados</h3>
                                        </div>
                                        <div class="card-body pt-0 pb-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla3vista3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane p-3 border border-success-0" id="vista4" role="tabpanel"
                            aria-labelledby="vista4-tab">

                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-success-0 cabeceravista4">
                                            <div class="card-widgets">
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="location.href=`{{ route('matriculageneral.niveleducativo.principal') }}`"
                                                    title=''><i class="fas fa-align-justify"></i> Nivel Educativo</button> --}}
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf(6)"
                                                        title='FICHA TÉCNICA'><i class="fas fa-file"></i> Ficha Técnica</button> --}}
                                                <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="location.reload()" title='ACTUALIZAR'><i
                                                        class=" fas fa-history"></i> Actualizar</button>
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                                        title='IMPRIMIR'><i class="fa fa-print"></i></button> --}}
                                            </div>
                                            <h3 class="card-title text-white"></h3>
                                        </div>
                                        <div class="card-body py-0">
                                            <div class="form-group row align-items-center vh-5">
                                                <div class="col-lg-5 col-md-5 col-sm-5">
                                                    <h5 class="page-title font-11">CENSO EDUCATIVO-MINEDU,
                                                        <br>{{ $actualizado }}
                                                    </h5>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1  ">
                                                    <select id="aniovista4" name="aniovista4"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista4();">
                                                        <option value="0">AÑO</option>
                                                        @foreach ($anios as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                                                {{ $item->anio }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="provinciavista4" name="provinciavista4"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarDistritosvista4();cargarCardsvista4();">
                                                        <option value="0">PROVINCIA</option>
                                                        @foreach ($provincia as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="distritovista4" name="distritovista4"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista4();">
                                                        <option value="0">DISTRITO</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="areavista4" name="areavista4"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista4();">
                                                        <option value="0">ÁMBITO GEOGRÁFICO</option>
                                                        @foreach ($area as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- <input type="hidden" id="serviciovista4" name="serviciovista4" --}}
                                                {{-- value="4"> --}}

                                                {{-- <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="servicio" name="servicio" class="form-control btn-xs font-11"
                                                        onchange="cargarCards();">
                                                        <option value="1">AGUA</option>
                                                        <option value="2">DESAGUE</option>
                                                        <option value="3">LUZ</option>
                                                        <option value="4">TRES SERVICIOS</option>
                                                        <option value="5">INTERNET</option>
                                                    </select>
                                                </div> --}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Widget-4 -->
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-box border border-plomo-0">
                                        <div class="media">
                                            <div class="text-center">
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-finance font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card1vista4">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Resultado del Indicador</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class=" mdi mdi-city font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card2vista4">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Total de II.EE</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-thumb-up font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card3vista4">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">II.EE con Agua</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-thumb-down font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card4vista4">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">II.EE sin Agua</p>
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
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal1vista4" style="height: 20rem"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal2vista4" style="height: 20rem"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0" style="height: 47.5rem">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal3vista4" style="height: 100%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0 vtabla1" style="height: 47.5rem">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black font-14 mb-0">Locales Educativos conectados a red de agua
                                                potable, según Distritos
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla1vista4">
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
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <div class="card-widgets">
                                                <button type="button" class="btn btn-success btn-xs"
                                                    onclick="descargar1vista4()"><i class="fa fa-file-excel"></i>
                                                    Descargar</button>
                                            </div>
                                            <h3 class="text-black font-14">Locales Educativos conectados</h3>
                                        </div>
                                        <div class="card-body pt-0 pb-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla3vista4">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane p-3 border border-success-0" id="vista5" role="tabpanel"
                            aria-labelledby="vista5-tab">

                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-success-0 cabeceravista5">
                                            <div class="card-widgets">
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="location.href=`{{ route('matriculageneral.niveleducativo.principal') }}`"
                                                    title=''><i class="fas fa-align-justify"></i> Nivel Educativo</button> --}}
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="verpdf(6)"
                                                        title='FICHA TÉCNICA'><i class="fas fa-file"></i> Ficha Técnica</button> --}}
                                                <button type="button" class="btn btn-orange-0 btn-xs"
                                                    onclick="location.reload()" title='ACTUALIZAR'><i
                                                        class=" fas fa-history"></i> Actualizar</button>
                                                {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                                        title='IMPRIMIR'><i class="fa fa-print"></i></button> --}}
                                            </div>
                                            <h3 class="card-title text-white"></h3>
                                        </div>
                                        <div class="card-body py-0">
                                            <div class="form-group row align-items-center vh-5">
                                                <div class="col-lg-5 col-md-5 col-sm-5">
                                                    <h5 class="page-title font-11">CENSO EDUCATIVO-MINEDU,
                                                        <br>{{ $actualizado }}
                                                    </h5>
                                                </div>
                                                <div class="col-lg-1 col-md-1 col-sm-1  ">
                                                    <select id="aniovista5" name="aniovista5"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista5();">
                                                        <option value="0">AÑO</option>
                                                        @foreach ($anios as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                                                {{ $item->anio }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="provinciavista5" name="provinciavista5"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarDistritosvista5();cargarCardsvista5();">
                                                        <option value="0">PROVINCIA</option>
                                                        @foreach ($provincia as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="distritovista5" name="distritovista5"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista5();">
                                                        <option value="0">DISTRITO</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="areavista5" name="areavista5"
                                                        class="form-control btn-xs font-11"
                                                        onchange="cargarCardsvista5();">
                                                        <option value="0">ÁMBITO GEOGRÁFICO</option>
                                                        @foreach ($area as $item)
                                                            <option value="{{ $item->id }}">{{ $item->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- <input type="hidden" id="serviciovista5" name="serviciovista5" --}}
                                                {{-- value="4"> --}}

                                                {{-- <div class="col-lg-2 col-md-2 col-sm-2">
                                                    <select id="servicio" name="servicio" class="form-control btn-xs font-11"
                                                        onchange="cargarCards();">
                                                        <option value="1">AGUA</option>
                                                        <option value="2">DESAGUE</option>
                                                        <option value="3">LUZ</option>
                                                        <option value="4">TRES SERVICIOS</option>
                                                        <option value="5">INTERNET</option>
                                                    </select>
                                                </div> --}}

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Widget-4 -->
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-box border border-plomo-0">
                                        <div class="media">
                                            <div class="text-center">
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-finance font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card1vista5">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Resultado del Indicador</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class=" mdi mdi-city font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card2vista5">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">Total de II.EE</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-thumb-up font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card3vista5">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">II.EE con Agua</p>
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
                                                {{-- <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                                    class="" width="70%" height="70%"> --}}
                                                <i class="mdi mdi-thumb-down font-35 text-green-0"></i>
                                            </div>
                                            <div class="media-body align-self-center card4vista5">
                                                <div class="text-right">
                                                    <h4 class="font-20 my-0 font-weight-bold">
                                                        <span data-plugin="counterup"></span>
                                                    </h4>
                                                    <p class="mb-0 mt-1 text-truncate">II.EE sin Agua</p>
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
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal1vista5" style="height: 20rem"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal2vista5" style="height: 20rem"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0" style="height: 47.5rem">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black text-center font-weight-normal font-11 m-0"></h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="anal3vista5" style="height: 100%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="card card-border border border-plomo-0 vtabla1" style="height: 47.5rem">
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <h3 class="text-black font-14 mb-0">Locales Educativos conectados a red de agua
                                                potable, según Distritos
                                            </h3>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla1vista5">
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
                                        <div class="card-header border-success-0 bg-transparent p-0">
                                            <div class="card-widgets">
                                                <button type="button" class="btn btn-success btn-xs"
                                                    onclick="descargar1vista5()"><i class="fa fa-file-excel"></i>
                                                    Descargar</button>
                                            </div>
                                            <h3 class="text-black font-14">Locales Educativos conectados</h3>
                                        </div>
                                        <div class="card-body pt-0 pb-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive" id="vtabla3vista5">
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



        <!-- Bootstrap modal -->
        <div id="modal_centropoblado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="text-black font-14">
                            Número de estudiantes matriculados en educación básica regular por
                            centro poblado, según nivel educativo
                        </h5>
                        &nbsp;
                        <button type="button" class="btn btn-success btn-xs text-right" onclick="descargar3()">
                            <i class="fa fa-file-excel"></i> Descargar</button>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive" id="vtabla3">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Bootstrap modal -->
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var distrito_select = 0;
        var distrito_select = 0;
        $(document).ready(function() {
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });
            cargarDistritos();
            cargarCards();
            cargarDistritosvista2();
            cargarCardsvista2();
            cargarDistritosvista3();
            cargarCardsvista3();
            cargarDistritosvista4();
            cargarCardsvista4();
            cargarDistritosvista5();
            cargarCardsvista5();
        });

        function cargarCards() {
            $('.cabeceravista1 h3').html('Porcentajes de Locales Educativos con los tres Servicios Basicos');
            $('#vtabla1vista1').closest('.card').find('h3').text(
                'Locales Educativos con los tres servicios basicos, según Distritos');
            // $('.vtabla2vista1 h3').html('Locales Educativos con los tres servicios basicos, según Provincia');


            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('tabla1');
            panelGraficas('tabla3');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('serviciosbasicos.principal.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#aniovista1').val(),
                    "provincia": $('#provinciavista1').val(),
                    "distrito": $('#distritovista1').val(),
                    "area": $('#areavista1').val(),
                    "servicio": 4,
                    "vista": 1,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {},
                success: function(data) {
                    if (div == 'head') {
                        $('.card1vista1 span').text(data.valor1 + "%");
                        $('.card2vista1 span').text(data.valor2);
                        $('.card3vista1 span').text(data.valor3);
                        $('.card4vista1 span').text(data.valor4);

                        $('.card3vista1 p').text('II.EE con ' + data.tservicio);
                        $('.card4vista1 p').text('II.EE sin ' + data.tservicio);
                    } else if (div == "anal1") {
                        gAnidadaColumn4('anal1vista1',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Numero de Locales Educativos con los tres servicios basicos, Periodo ' + data
                            .rango,
                            data.alto
                        );
                    } else if (div == "anal2") {
                        gAnidadaColumn3('anal2vista1',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos con los tres servicios basicos, según Provincia'
                        );
                    } else if (div == "anal3") {
                        gbar('anal3vista1',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos con los tres servicios basicos, según Distritos'
                        );
                    } else if (div == "tabla1") {
                        $('#vtabla1vista1').html(data.excel);
                        $('#tabla1vista1').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });

                    } else if (div == "tabla2") {
                        $('#vtabla2vista1').html(data.excel);
                        $('#tabla2vista1').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla3") {
                        $('#vtabla3vista1').html(data.excel);
                        $('#tabla3vista1').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            // searching: false,
                            // bPaginate: false,
                            // info: false,
                            language: table_language,
                        });
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargarCardsvista2() {
            $('.cabeceravista2 h3').text('Porcentajes de Locales Escolares Públicos conectados a red de Agua Potable');
            $('#vtabla1vista2').closest('.card').find('h3').text(
                'Locales Educativos conectados a red de agua potable, según Distritos');
            // $('.vtabla1vista2 h3').text('Locales Educativos conectados a red de agua potable, según Distritos');
            $('.vtabla2vista2 h3').text('Locales Educativos conectados a red de agua potable, según Provincia');

            panelGraficasvista2('head');
            panelGraficasvista2('anal1');
            panelGraficasvista2('anal2');
            panelGraficasvista2('anal3');
            panelGraficasvista2('tabla1');
            panelGraficasvista2('tabla3');
        }

        function panelGraficasvista2(div) {
            $.ajax({
                url: "{{ route('serviciosbasicos.principal.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#aniovista2').val(),
                    "provincia": $('#provinciavista2').val(),
                    "distrito": $('#distritovista2').val(),
                    "area": $('#areavista2').val(),
                    "servicio": 1,
                    "vista": 2,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {

                },
                success: function(data) {
                    if (div == 'head') {
                        $('.card1vista2 span').text(data.valor1 + "%");
                        $('.card2vista2 span').text(data.valor2);
                        $('.card3vista2 span').text(data.valor3);
                        $('.card4vista2 span').text(data.valor4);

                        $('.card3vista2 p').text('II.EE con ' + data.tservicio);
                        $('.card4vista2 p').text('II.EE sin ' + data.tservicio);
                    } else if (div == "anal1") {
                        gAnidadaColumn4('anal1vista2',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos conectados a red de agua potable, según Distritos',
                            data.alto
                        );
                    } else if (div == "anal2") {
                        gAnidadaColumn3('anal2vista2',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos conectados a red de agua potable, según Distritos',
                        );
                    } else if (div == "anal3") {
                        gbar('anal3vista2',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos conectados a red de agua potable, según Distritos',
                        );
                    } else if (div == "tabla1") {
                        $('#vtabla1vista2').html(data.excel);
                        $('#tabla1vista2').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla2") {
                        $('#vtabla2vista2').html(data.excel);
                        $('#tabla2vista2').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla3") {
                        $('#vtabla3vista2').html(data.excel);
                        $('#tabla3vista2').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            // searching: false,
                            // bPaginate: false,
                            // info: false,
                            language: table_language,
                        });
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargarCardsvista3() {
            $('.cabeceravista3 h3').text('Porcentajes de Locales Educativos conectados a red de Desague');
            // $('.vtabla1vista3 h3').text('Locales Educativos conectados a red de desague, según Distritos');
            $('#vtabla1vista3').closest('.card').find('h3').text(
                'Locales Educativos conectados a red de desague, según Distritos');
            $('.vtabla2vista3 h3').text('Locales Educativos conectados a red de desague, según Provincia');
            panelGraficasvista3('head');
            panelGraficasvista3('anal1');
            panelGraficasvista3('anal2');
            panelGraficasvista3('anal3');
            panelGraficasvista3('tabla1');
            panelGraficasvista3('tabla3');
        }

        function panelGraficasvista3(div) {
            $.ajax({
                url: "{{ route('serviciosbasicos.principal.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#aniovista3').val(),
                    "provincia": $('#provinciavista3').val(),
                    "distrito": $('#distritovista3').val(),
                    "area": $('#areavista3').val(),
                    "servicio": 2,
                    "vista": 3,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {

                },
                success: function(data) {
                    if (div == 'head') {
                        $('.card1vista3 span').text(data.valor1 + "%");
                        $('.card2vista3 span').text(data.valor2);
                        $('.card3vista3 span').text(data.valor3);
                        $('.card4vista3 span').text(data.valor4);

                        $('.card3vista3 p').text('II.EE con ' + data.tservicio);
                        $('.card4vista3 p').text('II.EE sin ' + data.tservicio);
                    } else if (div == "anal1") {
                        gAnidadaColumn4('anal1vista3',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos conectados a red de desague, según Distritos',
                            data.alto
                        );
                    } else if (div == "anal2") {
                        gAnidadaColumn3('anal2vista3',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos conectados a red de desague, según Distritos',
                        );
                    } else if (div == "anal3") {
                        gbar('anal3vista3',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos conectados a red de desague, según Distritos',
                        );
                    } else if (div == "tabla1") {
                        $('#vtabla1vista3').html(data.excel);
                        $('#tabla1vista3').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla2") {
                        $('#vtabla2vista3').html(data.excel);
                        $('#tabla2vista3').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla3") {
                        $('#vtabla3vista3').html(data.excel);
                        $('#tabla3vista3').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            // searching: false,
                            // bPaginate: false,
                            // info: false,
                            language: table_language,
                        });
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargarCardsvista4() {
            $('.cabeceravista4 h3').text('Porcentajes de Locales Educativos conectados a red de Electricidad');
            // $('.vtabla1vista4 h3').text('Locales Educativos conectados a red de electricidad, según Distritos');
            $('#vtabla1vista4').closest('.card').find('h3').text(
                'Locales Educativos conectados a red de electricidad, según Distritos');
            $('.vtabla2vista4 h3').text('Locales Educativos conectados a red de electricidad, según Provincia');
            panelGraficasvista4('head');
            panelGraficasvista4('anal1');
            panelGraficasvista4('anal2');
            panelGraficasvista4('anal3');
            panelGraficasvista4('tabla1');
            panelGraficasvista4('tabla3');
        }

        function panelGraficasvista4(div) {
            $.ajax({
                url: "{{ route('serviciosbasicos.principal.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#aniovista4').val(),
                    "provincia": $('#provinciavista4').val(),
                    "distrito": $('#distritovista4').val(),
                    "area": $('#areavista4').val(),
                    "servicio": 3,
                    "vista": 4,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {

                },
                success: function(data) {
                    if (div == 'head') {
                        $('.card1vista4 span').text(data.valor1 + "%");
                        $('.card2vista4 span').text(data.valor2);
                        $('.card3vista4 span').text(data.valor3);
                        $('.card4vista4 span').text(data.valor4);

                        $('.card3vista4 p').text('II.EE con ' + data.tservicio);
                        $('.card4vista4 p').text('II.EE sin ' + data.tservicio);
                    } else if (div == "anal1") {
                        gAnidadaColumn4('anal1vista4',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos conectados a red de electricidad, según Distritos',
                            data.alto
                        );
                    } else if (div == "anal2") {
                        gAnidadaColumn3('anal2vista4',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos conectados a red de electricidad, según Distritos',
                        );
                    } else if (div == "anal3") {
                        gbar('anal3vista4',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos conectados a red de electricidad, según Distritos',
                        );
                    } else if (div == "tabla1") {
                        $('#vtabla1vista4').html(data.excel);
                        $('#tabla1vista4').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla2") {
                        $('#vtabla2vista4').html(data.excel);
                        $('#tabla2vista4').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla3") {
                        $('#vtabla3vista4').html(data.excel);
                        $('#tabla3vista4').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            // searching: false,
                            // bPaginate: false,
                            // info: false,
                            language: table_language,
                        });
                    }
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargarCardsvista5() {
            $('.cabeceravista5 h3').text('Porcentajes de Locales Educativos que cuentan con acceso a Internet');
            // $('.vtabla1vista5 h3').text('Locales Educativos que cuentan con acceso a Internet, según Distritos');
            $('#vtabla1vista5').closest('.card').find('h3').text(
                'Locales Educativos que cuentan con acceso a Internet, según Distritos');
            $('.vtabla2vista5 h3').text('Locales Educativos que cuentan con acceso a Internet, según Provincia');
            panelGraficasvista5('head');
            panelGraficasvista5('anal1');
            panelGraficasvista5('anal2');
            panelGraficasvista5('anal3');
            panelGraficasvista5('tabla1');
            panelGraficasvista5('tabla3');
        }

        function panelGraficasvista5(div) {
            $.ajax({
                url: "{{ route('serviciosbasicos.principal.tablas') }}",
                data: {
                    'div': div,
                    "anio": $('#aniovista5').val(),
                    "provincia": $('#provinciavista5').val(),
                    "distrito": $('#distritovista5').val(),
                    "area": $('#areavista5').val(),
                    "servicio": 5,
                    "vista": 5,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {

                },
                success: function(data) {
                    if (div == 'head') {
                        $('.card1vista5 span').text(data.valor1 + "%");
                        $('.card2vista5 span').text(data.valor2);
                        $('.card3vista5 span').text(data.valor3);
                        $('.card4vista5 span').text(data.valor4);

                        $('.card3vista5 p').text('II.EE con ' + data.tservicio);
                        $('.card4vista5 p').text('II.EE sin ' + data.tservicio);
                    } else if (div == "anal1") {
                        gAnidadaColumn4('anal1vista5',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos que cuentan con acceso a Internet, según Distritos',
                            data.alto
                        );
                    } else if (div == "anal2") {
                        gAnidadaColumn3('anal2vista5',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos que cuentan con acceso a Internet, según Distritos',
                        );
                    } else if (div == "anal3") {
                        gbar('anal3vista5',
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Locales Educativos que cuentan con acceso a Internet, según Distritos',
                        );
                    } else if (div == "tabla1") {
                        $('#vtabla1vista5').html(data.excel);
                        $('#tabla1vista5').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla2") {
                        $('#vtabla2vista5').html(data.excel);
                        $('#tabla2vista5').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                    } else if (div == "tabla3") {
                        $('#vtabla3vista5').html(data.excel);
                        $('#tabla3vista5').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            // searching: false,
                            // bPaginate: false,
                            // info: false,
                            language: table_language,
                        });
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
                url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#provinciavista1').val(),
                type: 'GET',
                success: function(data) {
                    $("#distritovista1 option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#distritovista1").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarDistritosvista2() {
            $.ajax({
                url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#provinciavista2').val(),
                type: 'GET',
                success: function(data) {
                    $("#distritovista2 option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#distritovista2").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarDistritosvista3() {
            $.ajax({
                url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#provinciavista3').val(),
                type: 'GET',
                success: function(data) {
                    $("#distritovista3 option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#distritovista3").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarDistritosvista4() {
            $.ajax({
                url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#provinciavista4').val(),
                type: 'GET',
                success: function(data) {
                    $("#distritovista4 option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#distritovista4").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarDistritosvista5() {
            $.ajax({
                url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#provinciavista5').val(),
                type: 'GET',
                success: function(data) {
                    $("#distritovista5 option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#distritovista5").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }


        /* function descargar1() {
            window.open("{{ url('/') }}/MatriculaGeneral/EBR/Excel/tabla1/" + $('#anio').val() + "/" + $('#ugel')
                .val() + "/" + $('#gestion').val() + "/" + $('#area').val() + "/0");
        } */

        function descargar1vista1() {
            window.open("{{ url('/') }}/educación/ServiciosBasicos/Exportar/Excel/tabla3/" +
                $('#aniovista1').val() + "/" + $('#provinciavista1').val() + "/" + $('#distritovista1').val() + "/" +
                $('#areavista1').val() + "/4");
        }

        function descargar1vista2() {
            window.open("{{ url('/') }}/educación/ServiciosBasicos/Exportar/Excel/tabla3/" +
                $('#aniovista1').val() + "/" + $('#provinciavista1').val() + "/" + $('#distritovista1').val() + "/" +
                $('#areavista1').val() + "/1");
        }

        function descargar1vista3() {
            window.open("{{ url('/') }}/educación/ServiciosBasicos/Exportar/Excel/tabla3/" +
                $('#aniovista1').val() + "/" + $('#provinciavista1').val() + "/" + $('#distritovista1').val() + "/" +
                $('#areavista1').val() + "/2");
        }

        function descargar1vista4() {
            window.open("{{ url('/') }}/educación/ServiciosBasicos/Exportar/Excel/tabla3/" +
                $('#aniovista1').val() + "/" + $('#provinciavista1').val() + "/" + $('#distritovista1').val() + "/" +
                $('#areavista1').val() + "/3");
        }

        function descargar1vista5() {
            window.open("{{ url('/') }}/educación/ServiciosBasicos/Exportar/Excel/tabla3/" +
                $('#aniovista1').val() + "/" + $('#provinciavista1').val() + "/" + $('#distritovista1').val() + "/" +
                $('#areavista1').val() + "/5");
        }

        // function descargar3() {
        //     window.open("{{ url('/') }}/ServiciosBasicos/Excel/tabla3/" + $('#anio').val() + "/" + $('#ugel')
        //         .val() + "/" + $('#gestion').val() + "/" + $('#area').val() + "/" + $('#servicio').val());
        // }

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
                    enabled: true,
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                tooltip: {
                    //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                    pointFormat: '<b>{point.percentage:.1f}% ({point.y:,0f})</b>',
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
                            // distance: -20,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            //format: '{point.percentage:.1f}% ({point.y})',
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            // format: '{point.percentage:.1f}%',
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
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        cursor: "pointer",
                        fontSize: "10px",
                        fontWeight: "normal",
                        textOverflow: "ellipsis"
                    },
                },
                exporting: {
                    enabled: true
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
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                yAxis: {
                    title: {
                        text: titulovetical
                    },
                    min: 0,
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                xAxis: {
                    categories: data.cat,
                    /* accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
                    } */
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
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
                    enabled: true,
                },
                credits: false,

            });
        }

        function gLineaMultiple(div, data, titulo, subtitulo, titulovetical) {
            Highcharts.chart(div, {
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                colors: ["#5eb9aa", "#f5bd22", "#e65310"],
                yAxis: {
                    title: {
                        text: titulovetical
                    },
                    min: 0,
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
                xAxis: {
                    categories: data.cat,
                    accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
                    },
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
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
                series: data.dat,
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
                credits: false,

            });
        }

        function gAnidadaColumn4(div, categoria, series, titulo, subtitulo, maxBar) {
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
                            text: '',
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
                            formatter: function() {
                                if (this.colorIndex == 2)
                                    return Highcharts.numberFormat(this.y, 1) + " %";
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
                    enabled: true
                },
                credits: false,
            });
        }

        function gAnidadaColumn3(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                    style: {
                        fontSize: '11px',
                    }
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
                        //max: 2000000000,
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        // labels: {
                        //     style: {
                        //         fontSize: '10px',
                        //     }
                        // }
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
                        //showInLegend: false,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.colorIndex == 3)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
                            },
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                            }
                            // style: {
                            //     fontWeight: 'normal',
                            // }
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
                            },
                            enabled: false
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
                    enabled: true
                },
                credits: false,
            });
        }

        function gAnidadaColumn2(div, categoria, series, titulo, subtitulo, maxBar) {
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
                            },
                            enabled: false
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
                                // if (this.colorIndex == 1)
                                return this.y + " %";
                                // else
                                //     return Highcharts.numberFormat(this.y, 0);
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

        function gbar01(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar',
                    //marginLeft: 50,
                    //marginBottom: 90
                },
                colors: ['#5eb9aa', '#ef5350'],
                title: {
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                xAxis: {
                    categories: categoria,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    }
                },
                yAxis: {
                    labels: {
                        enabled: true,
                        style: {
                            //color: Highcharts.getOptions().colors[2],
                            fontSize: '10px',
                        }
                    },
                    title: {
                        text: '',
                        enabled: false,
                    },
                },
                plotOptions: {
                    series: {
                        stacking: 'normal', //normal, overlap, percent,stream
                        pointPadding: 0, //size de colunma
                        borderWidth: 0 //borde de columna
                    },
                    bar: {
                        dataLabels: {
                            enabled: true,
                            inside: true,
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                                color: 'white',
                                //textShadow:false,//quita sombra//para versiones antiguas
                                textOutline: false, //quita sombra
                            }
                        }
                    },
                },
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        //cursor: "pointer",
                        fontSize: "10px",
                        //fontWeight: "normal",
                        //textOverflow: "ellipsis"
                    },
                },
                series: series,
                tooltip: {
                    shared: true,
                },
                credits: {
                    enabled: false
                },
            });
        }

        function gbar(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar',
                    //marginLeft: 50,
                    //marginBottom: 90
                },
                // colors: ['#5eb9aa', '#ef5350'],
                colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
                title: {
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                xAxis: {
                    categories: categoria,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    }
                },
                yAxis: {
                    labels: {
                        enabled: true,
                        style: {
                            //color: Highcharts.getOptions().colors[2],
                            fontSize: '10px',
                        }
                    },
                    title: {
                        text: '',
                        enabled: false,
                    },
                },
                plotOptions: {
                    series: {
                        showInLegend: false,
                        stacking: 'normal', //normal, overlap, percent,stream
                        pointPadding: 0, //size de colunma
                        borderWidth: 0 //borde de columna
                    },
                    bar: {
                        dataLabels: {
                            enabled: true,
                            inside: true,
                            formatter: function() {
                                // return Highcharts.numberFormat(this.percentage, 1) + '%'; // Mostrar porcentaje con 1 decimal
                                return Highcharts.numberFormat(this.y, 1) +
                                '%'; // Mostrar porcentaje con 1 decimal
                            },
                            // format: '{point.y}%',
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                                color: 'white',
                                //textShadow:false,//quita sombra//para versiones antiguas
                                textOutline: false, //quita sombra
                            }
                        }
                    },
                },
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        //cursor: "pointer",
                        fontSize: "10px",
                        //fontWeight: "normal",
                        //textOverflow: "ellipsis"
                    },
                },
                series: series,
                tooltip: {
                    shared: true,
                    pointFormat: '{series.name}: <b>{point.y}%</b>',
                    // pointFormat: '<span style="color:{series.color}">\u25CF</span> {series.name}: <b>{point.percentage:.1f}%</b><br/>' // Muestra el porcentaje en el tooltip
                },
                credits: {
                    enabled: false
                },
            });
        }

        function gbar02(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar'
                },
                colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
                title: {
                    text: titulo, // 'Historic World Population by Region'
                },
                subtitle: {
                    text: subtitulo,
                    /*  'Source: <a ' +
                                            'href="https://en.wikipedia.org/wiki/List_of_continents_and_continental_subregions_by_population"' +
                                            'target="_blank">Wikipedia.org</a>' */
                },
                xAxis: {
                    //categories:categoria,// ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
                    type: "category",
                    title: {
                        text: '', // null
                    },
                    enabled: false,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    }
                },
                yAxis: {
                    //min: 0,
                    title: {
                        text: '', // 'Population (millions)',
                        align: 'high'
                    },
                    /* labels: {
                        overflow: 'justify'
                    } */
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    }
                },
                // tooltip: {
                //     valueSuffix: ' %'
                // },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}'
                        }
                    }
                },
                legend: {
                    enabled: false, //
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 80,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                    shadow: true
                },

                //series: series,
                /*  [{
                                    name: 'Year 1990',
                                    data: [631, 727, 3202, 721, 26]
                                }, {
                                    name: 'Year 2000',
                                    data: [814, 841, 3714, 726, 31]
                                }, {
                                    name: 'Year 2010',
                                    data: [1044, 944, 4170, 735, 40]
                                }, {
                                    name: 'Year 2018',
                                    data: [1276, 1007, 4561, 746, 42]
                                }] */
                /* showInLegend: tituloserie != '',
                        name: tituloserie,
                        label: {
                            enabled: false
                        },
                        colorByPoint: false, */
                series: [{
                    name: 'Ejecución',
                    showInLegend: false,
                    label: {
                        enabled: false
                    },
                    data: series,
                    /* [{
                                                name: "Chrome",
                                                y: 63.06,
                                            },
                                            {
                                                name: "Safari",
                                                y: 19.84,
                                            },
                                            {
                                                name: "Firefox",
                                                y: 4.18,
                                            },
                                            {
                                                name: "Edge",
                                                y: 4.12,
                                            },
                                            {
                                                name: "Opera",
                                                y: 2.33,
                                            },
                                            {
                                                name: "Internet Explorer",
                                                y: 0.45,
                                            },
                                            {
                                                name: "Other",
                                                y: 1.582,
                                            }
                                        ] */
                }],
                credits: {
                    enabled: false
                },
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

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
