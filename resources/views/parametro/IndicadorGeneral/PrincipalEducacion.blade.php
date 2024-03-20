@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => ''])

@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    {{-- <link href="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/scroller.bootstrap4.min.css" rel="stylesheet"
        type="text/css" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

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
            border-color: transparent !important;
        }

        .nav-tabs .nav-item .nav-link {
            background-color: #43beac;
            /* #0080FF; */
            color: #FFF;
        }

        .nav-tabs .nav-item .nav-link.active {
            color: #43beac;
            /* #0080FF; */
        }

        /* .tab-content {
                                                        border: 1px solid #dee2e6;
                                                        border-top: transparent;
                                                        padding: 15px;
                                                    }

                                                    .tab-content .tab-pane {
                                                        background-color: #FFF;
                                                        color: #0080FF;
                                                        min-height: 200px;
                                                        height: auto;
                                                    } */

        /*  */
    </style>
@endsection
@section('content')
    <div class="content">
        <form class="cmxform form-horizontal tasi-form upload_file">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-border">
                        <div class="card-header border-success-0 bg-transparent pb-0">
                            <div class="card-widgets">
                                <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                        class="fa fa-redo"></i> Actualizar</button>
                                <button type="button" class="btn btn-primary btn-xs" onclick="add()"><i
                                        class="fa fa-plus"></i>
                                    Nuevo</button>
                            </div>
                            <h3 class="card-title">Indicadores </h3>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbprincipal" class="table table-striped table-bordered tablex"
                                    style="font-size: 12px">
                                    <thead class="cabecera-dataTable">
                                        <tr class="text-white bg-success-0">
                                            <th>Nº</th>
                                            <th>Código</th>
                                            <th>Nombre del Indicador</th>
                                            <th>Sector</th>
                                            <th>Tipo</th>
                                            <th>Instrumento</th>
                                            <th>Año Base</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div> <!-- End row -->
        </form>
    </div>

    <!-- Bootstrap modal -->
    <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form" name="form" class="form-horizontal" enctype="multipart/form-data"
                        method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id" name="id" value="">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="container p-0">
                                        <ul class="nav nav-tabs nav-justified border-0" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link border border-success-0 border-bottom-0 active"
                                                    id="home1-tab" data-toggle="tab" href="#home1" role="tab"
                                                    aria-controls="home1" aria-selected="true">
                                                    <span class="d-block d-sm-none">
                                                        <i class="mdi mdi-home-variant-outline font-18"></i>
                                                    </span>
                                                    <span class="d-none d-sm-block">Registro 1</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link border border-success-0 border-bottom-0"
                                                    id="profile1-tab" data-toggle="tab" href="#profile1" role="tab"
                                                    aria-controls="profile1" aria-selected="false">
                                                    <span class="d-block d-sm-none">
                                                        <i class="mdi mdi-account-outline font-18"></i>
                                                    </span>
                                                    <span class="d-none d-sm-block">Registro 2</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content p-0">
                                            <div class="tab-pane p-3 border border-success-0 show active" id="home1"
                                                role="tabpanel" aria-labelledby="home1-tab">

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>Indicador<span class="required">*</span></label>
                                                            <input id="nombre" name="nombre" class="form-control"
                                                                type="text" maxlength="300"
                                                                placeholder="Nombre del indicador">
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>Definición<span class="required">*</span></label>
                                                            <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="2"
                                                                placeholder="Definición del indicador"></textarea>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Numerador<span class="required">*</span></label>
                                                            <textarea class="form-control" name="numerador" id="numerador" cols="30" rows="2"
                                                                placeholder="Definición del indicador"></textarea>
                                                            <span class="help-block"></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Denominador<span class="required">*</span></label>
                                                            <textarea class="form-control" name="denominador" id="denominador" cols="30" rows="2"
                                                                placeholder="Definición del indicador"></textarea>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Instrumento de gestion<span
                                                                    class="required">*</span></label>
                                                            <select id="instrumento" name="instrumento"
                                                                class="form-control">
                                                                <option value="">Seleccionar</option>
                                                                @foreach ($instrumento as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Tipo de indicador<span class="required">*</span></label>
                                                            <select id="tipo" name="tipo" class="form-control">
                                                                <option value="">Seleccionar</option>
                                                                @foreach ($tipo as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane p-3 border border-success-0" id="profile1"
                                                role="tabpanel" aria-labelledby="profile1-tab">

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Dimension</label>
                                                            <select id="dimension" name="dimension" class="form-control">
                                                                <option value="">Seleccionar</option>
                                                                @foreach ($dimension as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Unidad de Medida</label>
                                                            <select id="unidad" name="unidad" class="form-control">
                                                                <option value="">Seleccionar</option>
                                                                @foreach ($unidad as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Frecuencia</label>
                                                            <select id="frecuencia" name="frecuencia"
                                                                class="form-control">
                                                                <option value="">Seleccionar</option>
                                                                @foreach ($frecuencia as $item)
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Fuente de datos</label>
                                                            <input id="fuentedato" name="fuentedato" class="form-control"
                                                                type="text" placeholder="Fuente de datos">
                                                            <span class="help-block"></span>
                                                        </div>

                                                    </div>
                                                </div>
                                                {{-- <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Año base</label>
                                                            <input id="aniobase" name="aniobase" class="form-control"
                                                                type="number" placeholder="Año base">
                                                            <span class="help-block"></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Valor base</label>
                                                            <input id="valorbase" name="valorbase" class="form-control"
                                                                type="text" placeholder="Valor base">
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Sector</label>
                                                            <select id="sector" name="sector" class="form-control">
                                                                <option value="">Seleccionar</option>
                                                                @foreach ($sector as $item)
                                                                    <option value="{{ $item->id }}">
                                                                        {{ $item->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Entidad responsable</label>
                                                            {{--  --}}
                                                            <div class="input-group">
                                                                <input id="entidad" name="entidad" type="hidden">
                                                                <input id="entidadn" name="entidadn"
                                                                    class="form-control" type="text">
                                                                <span class="help-block"></span>
                                                                <span class="input-group-append">
                                                                    <button type="button"
                                                                        class="btn waves-effect waves-light btn-primary"
                                                                        onclick="add_entidad();">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                            {{--  --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Oficina Responsable</label>
                                                            {{--  --}}
                                                            <div class="input-group">
                                                                <input id="oficina" name="oficina" type="hidden">
                                                                <input id="oficinan" name="oficinan"
                                                                    class="form-control" type="text">
                                                                <span class="help-block"></span>
                                                                <span class="input-group-append">
                                                                    <button type="button"
                                                                        class="btn waves-effect waves-light btn-primary"
                                                                        onclick="add_oficina();">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                            {{--  --}}
                                                        </div>
                                                        {{-- <div class="col-md-6">
                                                            <label for="fichatecnica">Ficha Tecnica</label><br>
                                                            <label for="fichatecnica" class="btn btn-primary">
                                                                <i class="fas fa-cloud-upload-alt"></i> Cargar</label>
                                                            <input id="fichatecnica" name="fichatecnica"
                                                                class="form-control d-none" type="file"
                                                                accept="application/pdf">
                                                            <span class="help-block"></span>
                                                        </div> --}}

                                                        <div class="col-md-6">
                                                            <label>Ficha Tecnica</label>
                                                            <div class="input-group">
                                                                <input id="fichatecnica" name="fichatecnica"
                                                                    class="form-control d-none" type="file"
                                                                    accept="application/pdf">
                                                                <input id="fichatecnica_nombre" name="fichatecnica_nombre"
                                                                    class="form-control" type="text"
                                                                    placeholder="Seleccione Archivo" readonly>
                                                                <span class="input-group-append">
                                                                    <label for="fichatecnica"
                                                                        class="btn btn-primary btn-file-documento">
                                                                        <i class="fas fa-cloud-upload-alt"></i> </label>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->

    <!-- Bootstrap modal -->
    <div id="modal_ver" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form_ver" name="form" class="form-horizontal"
                        enctype="multipart/form-data" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Indicador<span class="required">*</span></label>
                                        <input id="vnombre" name="vnombre" class="form-control" type="text"
                                            maxlength="300" placeholder="Nombre del indicador" disabled>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Definición<span class="required">*</span></label>
                                        <textarea class="form-control" name="vdescripcion" id="vdescripcion" cols="30" rows="2"
                                            placeholder="Definición del indicador" disabled></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Instrumento de gestion<span class="required">*</span></label>
                                        <select id="vinstrumento" name="vinstrumento" class="form-control" disabled>
                                            <option value="">Seleccionar</option>
                                            @foreach ($instrumento as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Tipo de indicador<span class="required">*</span></label>
                                        <select id="vtipo" name="vtipo" class="form-control" disabled>
                                            <option value="">Seleccionar</option>
                                            @foreach ($tipo as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Dimension</label>
                                        <select id="vdimension" name="vdimension" class="form-control" disabled>
                                            <option value="">Seleccionar</option>
                                            @foreach ($dimension as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Unidad de Medida</label>
                                        <select id="vunidad" name="vunidad" class="form-control" disabled>
                                            <option value="">Seleccionar</option>
                                            @foreach ($unidad as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Frecuencia</label>
                                        <select id="vfrecuencia" name="vfrecuencia" class="form-control" disabled>
                                            <option value="">Seleccionar</option>
                                            @foreach ($frecuencia as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Fuente de datos</label>
                                        <input id="vfuentedato" name="vfuentedato" class="form-control" type="text"
                                            placeholder="Fuente de datos" disabled>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Año base</label>
                                        <input id="vaniobase" name="vaniobase" class="form-control" type="number"
                                            placeholder="Año base" disabled>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Valor base</label>
                                        <input id="vvalorbase" name="vvalorbase" class="form-control" type="text"
                                            placeholder="Valor base" disabled>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Sector</label>
                                        <select id="vsector" name="vsector" class="form-control" disabled>
                                            <option value="">Seleccionar</option>
                                            @foreach ($sector as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Entidad responsable</label>
                                        <input id="ventidad" name="ventidad" type="hidden">
                                        <input id="ventidadn" name="ventidadn" class="form-control" type="text"
                                            disabled>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Oficina Responsable</label>
                                        <input id="voficina" name="voficina" type="hidden">
                                        <input id="voficinan" name="voficinan" class="form-control" type="text"
                                            disabled>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Ficha Tecnica</label>
                                        <input id="vfichatecnica" name="vfichatecnica" class="form-control"
                                            type="text" disabled>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->

    <!-- Bootstrap modal -->
    <div id="modal_meta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0">
                    <form action="" id="form_meta" name="formver" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="idmeta" name="idmeta" value="">
                        <input type="hidden" id="indicadorgeneral" name="indicadorgeneral" value="">
                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Año base<span class="required">*</span></label>
                                        <input id="aniobase" name="aniobase" class="form-control" type="number">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Valor base<span class="required">*</span></label>
                                        <input id="valorbase" name="valorbase" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Provincia<span class="required">*</span></label>
                                        <select name="provincia" id="provincia" class="form-control"
                                            onchange="cargarDistritos(0)">
                                            <option value="0">SELECCIONAR</option>

                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Distrito<span class="required">*</span></label>
                                        <select name="distrito" id="distrito" class="form-control">
                                            <option value="0">SELECCIONAR</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- <div class="col-md-4">
                                        <label>periodo<span class="required">*</span></label>
                                        <input id="periodo" name="periodo" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div> --}}
                                    <div class="col-md-6">
                                        <label>Año esperado<span class="required">*</span></label>
                                        <input id="anioesperado" name="anioesperado" class="form-control"
                                            type="number">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Valor esperado<span class="required">*</span></label>
                                        <input id="valoresperado" name="valoresperado" class="form-control"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-body pt-0" style="text-align:center">
                    <button type="button" class="btn btn-xs btn-primary" id="btnSaveMeta" onclick="savemeta()"><i
                            class="fa fa-plus"></i> Agregar</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tbmeta" class="table table-striped table-bordered tablex" style="font-size: 12px">
                            <thead class="cabecera-dataTable">
                                <tr class="text-white bg-success-0">
                                    <th>Nº</th>
                                    <th>Periodo</th>
                                    <th>Año</th>
                                    <th>Valor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->

    <!-- Bootstrap modal -->
    <div id="modal_meta_dit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0">
                    <form action="" id="form_meta_dit" name="formver" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="idmeta_dit" name="idmeta_dit" value="">
                        <input type="hidden" id="indicadorgeneral_dit" name="indicadorgeneral_dit" value="">
                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Año base<span class="required">*</span></label>
                                        <input id="aniobase_dit" name="aniobase_dit" class="form-control" type="number">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Valor base<span class="required">*</span></label>
                                        <input id="valorbase_dit" name="valorbase_dit" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Provincia<span class="required">*</span></label>
                                        <select name="provincia_dit" id="provincia_dit" class="form-control"
                                            onchange="cargarDistritos(0)">
                                            <option value="0">SELECCIONAR</option>

                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Distrito<span class="required">*</span></label>
                                        <select name="distrito_dit" id="distrito_dit" class="form-control">
                                            <option value="0">SELECCIONAR</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Año esperado<span class="required">*</span></label>
                                        <input id="anioesperado_dit" name="anioesperado_dit" class="form-control"
                                            type="number">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Valor esperado<span class="required">*</span></label>
                                        <input id="valoresperado_dit" name="valoresperado_dit" class="form-control"
                                            type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-body pt-0" style="text-align:center">
                    <button type="button" class="btn btn-xs btn-primary" id="btnSaveMeta_dit" onclick="savemeta_dit()"><i
                            class="fa fa-plus"></i> Agregar</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tbmeta_dit" class="table table-striped table-bordered tablex" style="font-size: 12px">
                            <thead class="cabecera-dataTable">
                                <tr class="text-white bg-success-0">
                                    <th>Nº</th>
                                    <th>Periodo</th>
                                    <th>Año</th>
                                    <th>Valor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->

    <div id="modal_form_entidad" class="modal fade centrarmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="form_entidad" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="dependencia" name="dependencia">
                        <div class="form-group">
                            <label>Entidad<span class="required">*</span></label>
                            <input id="entidad_nombre" name="entidad_nombre" class="form-control" type="text"
                                readonly>
                            <span class="help-block"></span>
                        </div>
                        <div class="form">
                            <div class="form-group">
                                <label>Nombre<span class="required">*</span></label>
                                <input id="descripcion" name="descripcion" class="form-control" type="text"
                                    onkeyup="this.value=this.value.toUpperCase()" maxlength="200">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form">
                            <div class="form-group">
                                <label>Abreviado<span class="required">*</span></label>
                                <input id="apodo" name="apodo" class="form-control" type="text"
                                    onkeyup="this.value=this.value.toUpperCase()" maxlength="10">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSaveEntidad" onclick="saveentidad()"
                        class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->
@endsection

@section('js')
    <!-- third party js -->
    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.js"></script> --}}

    {{-- <script src="{{ asset('/') }}public/assets/libs/jszip/jszip.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/vfs_fonts.js"></script> --}}

    {{-- <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.html5.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.print.min.js"></script> --}}

    {{-- <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.keyTable.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.scroller.min.js"></script> --}}




    <script>
        var save_method = '';
        var table_principal;
        var table_meta;
        var form_entidad = 0;
        $(document).ready(function() {
            $("input").change(function() {
                $(this).parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("textarea").change(function() {
                $(this).parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("select").change(function() {
                $(this).parent().removeClass('has-error');
                $(this).next().empty();
            });

            $('#entidadn').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('entidad.autocomplete') }}",
                        data: {
                            term: request.term,
                            dependencia: 0
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    $('#entidad').val(ui.item.id);
                }
            });

            $('#oficinan').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('entidad.autocomplete') }}",
                        data: {
                            term: request.term,
                            dependencia: $('#entidad').val()
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    $('#oficina').val(ui.item.id);
                }
            });

            $("#fichatecnica").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $('#fichatecnica_nombre').val(fileName);

            });

            table_principal = $('#tbprincipal').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                language: table_language,
                ajax: {
                    "url": "{{ route('mantenimiento.indicadorgeneral.listar') }}",
                    "type": "GET",
                    //"dataType": 'JSON',
                },

            });

        });

        function cargarProvincia(id) {
            $.ajax({
                url: "{{ route('ubigeo.provincia.25') }}",
                type: 'GET',
                success: function(data) {
                    $("#provincia option").remove();
                    var options = '<option value="0">SELECCIONAR</option>';
                    $.each(data, function(index, value) {
                        ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "' " + ss + ">" + value.nombre +
                            "</option>"
                    });
                    $("#provincia").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarDistritos(id) {
            $.ajax({
                url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#provincia').val(),
                type: 'GET',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data, function(index, value) {
                        ss = (id == value.id ? "selected" : "");
                        options += "<option value='" + value.id + "' " + ss + ">" + value.nombre +
                            "</option>"
                    });
                    $("#distrito").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function add() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Crear nuevo indicador');
            $('#id').val("");
        };

        function save() {
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ route('mantenimiento.indicadorgeneral.guardar') }}";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ route('mantenimiento.indicadorgeneral.modificar') }}";
                msgsuccess = "El registro fue actualizado exitosamente.";
                msgerror = "El registro no se pudo actualizar. Verifique la operación";
            }
            $.ajax({
                url: url,
                data: new FormData($('#form')[0]),
                type: "POST",
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    if (data.status) {
                        $('#modal_form').modal('hide');
                        reload_table_principal(); //listarDT();
                        toastr.success(msgsuccess, 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSave').text('Guardar');
                    $('#btnSave').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(msgerror, 'Mensaje');
                    $('#btnSave').text('Guardar');
                    $('#btnSave').attr('disabled', false);
                }
            });
        };

        function edit(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ route('mantenimiento.indicadorgeneral.editar', '') }}/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.ie.id);
                    $('[name="codigo"]').val(data.ie.codigo);
                    $('[name="nombre"]').val(data.ie.nombre);
                    $('[name="descripcion"]').val(data.ie.descripcion);
                    $('[name="instrumento"]').val(data.ie.instrumento_id);
                    $('[name="tipo"]').val(data.ie.tipo_id);
                    $('[name="dimension"]').val(data.ie.dimension_id);
                    $('[name="unidad"]').val(data.ie.unidad_id);
                    $('[name="frecuencia"]').val(data.ie.frecuencia_id);
                    $('[name="fuentedato"]').val(data.ie.fuente_dato);
                    $('[name="aniobase"]').val(data.ie.anio_base);
                    $('[name="valorbase"]').val(data.ie.valor_base);
                    $('[name="sector"]').val(data.ie.sector_id);
                    $('[name="entidad"]').val(data.ie.entidad);
                    $('[name="entidadn"]').val(data.ie.entidadn);
                    $('[name="oficina"]').val(data.ie.oficina_id);
                    $('[name="oficinan"]').val(data.ie.oficinan);
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Modificar indicador [' + data.ie.codigo + ']');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        };

        function borrar(id) {
            bootbox.confirm("Seguro desea Eliminar este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('mantenimiento.indicadorgeneral.eliminar', '') }}/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            $('#modal_form').modal('hide');
                            reload_table_principal(); //listarDT();
                            toastr.success('El registro fue eliminado exitosamente.',
                                'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema',
                                'Mensaje');
                        }
                    });
                }
            });
        };

        function reload_table_principal() {
            table_principal.ajax.reload(null, false);
        }

        /* function estado(id, x) {
            bootbox.confirm("Seguro desea " + (x == 1 ? "desactivar" : "activar") + " este registro?", function(
                result) {
                if (result === true) {
                    $.ajax({
                        url: "" + id,
                        type: "POST",
                        dataType: "JSON",
                        success: function(data) {
                            console.log(data);
                            reload_table_principal(); //listarDT();
                            if (data.estado)
                                toastr.success('El registro fue Activo exitosamente.',
                                    'Mensaje');
                            else
                                toastr.success('El registro fue Desactivado exitosamente.',
                                    'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(
                                'No se puede cambiar estado por seguridad de su base de datos, Contacte al Administrador del Sistema.',
                                'Mensaje');
                        }
                    });
                }
            });
        }; */

        function ver(id) {
            $('#form_ver')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ route('mantenimiento.indicadorgeneral.editar', '') }}/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="vcodigo"]').val(data.ie.codigo);
                    $('[name="vnombre"]').val(data.ie.nombre);
                    $('[name="vdescripcion"]').val(data.ie.descripcion);
                    $('[name="vinstrumento"]').val(data.ie.instrumento_id);
                    $('[name="vtipo"]').val(data.ie.tipo_id);
                    $('[name="vdimension"]').val(data.ie.dimension_id);
                    $('[name="vunidad"]').val(data.ie.unidad_id);
                    $('[name="vfrecuencia"]').val(data.ie.frecuencia_id);
                    $('[name="vfuentedato"]').val(data.ie.fuente_dato);
                    $('[name="vaniobase"]').val(data.ie.anio_base);
                    $('[name="vvalorbase"]').val(data.ie.valor_base);
                    $('[name="vsector"]').val(data.ie.sector_id);
                    $('[name="ventidad"]').val(data.ie.entidad);
                    $('[name="ventidadn"]').val(data.ie.entidadn);
                    $('[name="voficina"]').val(data.ie.oficina_id);
                    $('[name="voficinan"]').val(data.ie.oficinan);
                    $('[name="vfichatecnica"]').val(data.ie.ficha_tecnica ? 'Cargado' : '');
                    $('#modal_ver').modal('show');
                    $('.modal-title').text('Indicador ' + data.ie.codigo);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        };

        function verpdf(id) {
            window.open("{{ route('mantenimiento.indicadorgeneral.exportar.pdf', '') }}/" + id);
        };

        function metas(id) {
            $('#form_meta')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_meta').modal('show');
            $('.modal-title').text('Agregar Metas');
            $('#indicadorgeneral').val(id);
            table_meta = $('#tbmeta').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                language: table_language,
                ajax: {
                    "url": "{{ route('mantenimiento.indicadorgeneralmeta.listar') }}",
                    "data": {
                        "indicadorgeneral": id
                    },
                    "type": "GET",
                    //"dataType": 'JSON',
                },
                destroy: true,
            });
        };

        function metas(id) {
            $('#form_meta_dit')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_meta_dit').modal('show');
            $('.modal-title').text('Agregar Metas');
            $('#indicadorgeneral_dit').val(id);
            table_meta = $('#tbmeta_dit').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                language: table_language,
                ajax: {
                    "url": "{{ route('mantenimiento.indicadorgeneralmeta.listar') }}",
                    "data": {
                        "indicadorgeneral": id
                    },
                    "type": "GET",
                    //"dataType": 'JSON',
                },
                destroy: true,
            });
            cargarProvincia(0);
        };

        function savemeta() {
            $('#btnSaveMeta').text('Guardando...');
            $('#btnSaveMeta').attr('disabled', true);
            $.ajax({
                url: "{{ route('mantenimiento.indicadorgeneralmeta.guardar') }}",
                type: "POST",
                data: $('#form_meta').serialize(),
                dataType: "JSON",
                success: function(data) {
                    console.log(data)
                    if (data.status) {
                        //$('#modal_meta').modal('hide');
                        table_meta.ajax.reload(null, false);
                        $('#form_meta')[0].reset();
                        //toastr.success(msgsuccess, 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSaveMeta').text('Guardar');
                    $('#btnSaveMeta').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(msgerror, 'Mensaje');
                    $('#btnSaveMeta').text('Guardar');
                    $('#btnSaveMeta').attr('disabled', false);
                }
            });
        };

        function savemeta_dit() {
            $('#btnSaveMeta_dit').text('Guardando...');
            $('#btnSaveMeta_dit').attr('disabled', true);
            $.ajax({
                url: "{{ route('mantenimiento.indicadorgeneralmeta.guardar') }}",
                type: "POST",
                data: $('#form_meta_dit').serialize(),
                dataType: "JSON",
                success: function(data) {
                    console.log(data)
                    if (data.status) {
                        //$('#modal_meta').modal('hide');
                        table_meta.ajax.reload(null, false);
                        $('#form_meta_dit')[0].reset();
                        //toastr.success(msgsuccess, 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSaveMeta_dit').text('Guardar');
                    $('#btnSaveMeta_dit').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(msgerror, 'Mensaje');
                    $('#btnSaveMeta_dit').text('Guardar');
                    $('#btnSaveMeta_dit').attr('disabled', false);
                }
            });
        };

        function borrarmeta(id) {
            bootbox.confirm("Seguro desea Eliminar este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('mantenimiento.indicadorgeneralmeta.eliminar', '') }}/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            table_meta.ajax.reload(null, false);
                            toastr.success('El registro fue eliminado exitosamente.',
                                'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema',
                                'Mensaje');
                        }
                    });
                }
            });
        };

        function add_entidad() {
            $('#form_entidad')[0].reset();
            $('#form_entidad .form-group').removeClass('has-error');
            $('#form_entidad .help-block').empty();
            $('#entidad_nombre').parent().hide();
            $('#modal_form_entidad .modal-title').text('Nueva Entidad');
            $('#modal_form_entidad').modal('show');
            form_entidad = 0;
        }

        function saveentidad() {
            $('#btnSaveEntidad').text('guardando...');
            $('#btnSaveEntidad').attr('disabled', true);
            $.ajax({
                url: "{{ route('entidad.ajax.addentidad') }}",
                type: "POST",
                data: $('#form_entidad').serialize(),
                dataType: "JSON",
                success: function(data) {
                    if (data.status) {
                        $('#modal_form_entidad').modal('hide');
                        if (form_entidad == 0) {
                            $('#entidad').val(data.id);
                            $('#entidadn').val(data.nombre);
                        } else if (form_entidad == 1) {
                            $('#oficina').val(data.id);
                            $('#oficinan').val(data.nombre);
                        }
                        toastr.success("El registro fue creado exitosamente.", 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSaveEntidad').text('Guardar');
                    $('#btnSaveEntidad').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#btnSaveEntidad').text('Guardar');
                    $('#btnSaveEntidad').attr('disabled', false);
                    toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                }
            });
        }

        function add_oficina() {
            entidadid = $('#entidad').val();
            if (entidadid == 0) {
                alert('ERROR: seleccionar una Entidad Responsable');
                return false;
            }
            entidadnombre = $('#entidadn').val();
            $('#form_entidad')[0].reset();
            $('#form_entidad .form-group').removeClass('has-error');
            $('#form_entidad .help-block').empty();
            $('#dependencia').val(entidadid);
            $('#entidad_nombre').val(entidadnombre.trim());
            $('#entidad_nombre').parent().show();
            $('#modal_form_entidad .modal-title').text('Nueva Oficina');
            $('#modal_form_entidad').modal('show');
            form_entidad = 1;
        }
    </script>
@endsection
