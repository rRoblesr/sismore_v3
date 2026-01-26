@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => ''])

@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

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

        .tab-content--- {
            /* NO SE USA */
            border: 1px solid #dee2e6;
            border-top: transparent;
            padding: 15px;
        }

        .tab-content---- .tab-pane---- {
            /* NO SE USA */
            background-color: #FFF;
            color: #0080FF;
            min-height: 200px;
            height: auto;
        }

        .custom-select-container {
            position: relative;
            margin-bottom: 15px;
            margin-top: 5px;
        }

        .custom-select-container label {
            position: absolute;
            top: -9px;
            left: 12px;
            background: #fff;
            padding: 0 5px;
            font-size: 11px;
            color: #495057;
            z-index: 10;
        }

        .custom-select-container .form-control {
            height: 40px;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div
            class="card-header bg-success-0 text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center p-2">
            <!--h3 class="card-title"></h3-->
            <h6 class="card-title mb-2 mb-md-0 text-center text-white text-md-left text-wrap">
                Instituciones Educativas
            </h6>
            <div class="text-center text-md-right">
                <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i class="fa fa-redo"></i>
                    Actualizar</button>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="row mb-0">
                <div class="col-md-3">
                    <div class="custom-select-container">
                        <label for="ugel">UGEL</label>
                        <select id="ugel" name="ugel" class="form-control" onchange="cargarFiltros('ugel')">
                            <option value="0">TODOS</option>
                            @foreach ($ugels as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-select-container">
                        <label for="provincia">Provincia</label>
                        <select id="provincia" name="provincia" class="form-control"
                            onchange="cargarFiltros('provincia')">
                            <option value="0">TODOS</option>
                            @foreach ($provincias as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-select-container">
                        <label for="distrito">Distrito</label>
                        <select id="distrito" name="distrito" class="form-control" onchange="cargarFiltros('distrito')">
                            <option value="0">TODOS</option>
                            @foreach ($distritos as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-select-container">
                        <label for="nivel">Nivel</label>
                        <select id="nivel" name="nivel" class="form-control" onchange="reload_table_principal()">
                            <option value="0">TODOS</option>
                            @foreach ($nivel as $item)
                                <option value="{{ $item->id }}">{{ $item->codigo }} | {{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-border border border-plomo-0">
        <div
            class="card-header border-success-0 bg-transparent text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center px-2 py-2">
            <h6 class="card-title mb-2 mb-md-0 text-center text-md-left text-wrap">Lista</h6>
            <div class="text-center text-md-right">
                <button type="button" class="btn btn-primary btn-xs" onclick="add()">
                    <i class="fa fa-plus"></i> Nuevo</button>
                {{-- <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i class="fa fa-redo"></i> Actualizar</button> --}}
            </div>
        </div>
        <div class="card-body p-2">

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="tbprincipal" class="table table-striped table-bordered" style="font-size: 14px">
                            <thead class="cabecera-dataTable">
                                <tr class="text-white bg-success-0">
                                    <th>Nº</th>
                                    <th>Cód. Local</th>
                                    <th>Cód. Modular</th>
                                    <th>Institución Educativa</th>
                                    <th>UGEL</th>
                                    <th>Nivel</th>
                                    <th>Gestión</th>
                                    <th>Área</th>
                                    <th>EIB</th>
                                    <th>Estado</th>
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
                    <form action="" id="form" name="form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id" name="id" value="">
                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Codigo Local<span class="required">*</span></label>
                                        <input id="codLocal" name="codLocal" class="form-control" type="text"
                                            maxlength="6">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Codigo Modular<span class="required">*</span></label>
                                        <input id="codModular" name="codModular" class="form-control" type="text"
                                            maxlength="7">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Nombre<span class="required">*</span></label>
                                        <input id="nombreInstEduc" name="nombreInstEduc" class="form-control"
                                            type="text" onkeyup="this.value=this.value.toUpperCase()" maxlength="150">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Nivel Modalidad</label>
                                        <select id="NivelModalidad_id" name="NivelModalidad_id" class="form-control">
                                            <option value="">SELECCIONAR</option>
                                            @foreach ($nivel as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->codigo }} | {{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Forma</label>
                                        <select id="Forma_id" name="Forma_id" class="form-control">
                                            <option value="">SELECCIONAR</option>
                                            @foreach ($forma as $item)
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
                                        <label>Caracteristica</label>
                                        <select id="Caracteristica_id" name="Caracteristica_id" class="form-control">
                                            <option value="">SELECCIONAR</option>
                                            @foreach ($carac as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Genero</label>
                                        <select id="Genero_id" name="Genero_id" class="form-control">
                                            <option value="">SELECCIONAR</option>
                                            @foreach ($gener as $item)
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
                                        <label>Tipo de Gestion Dependencia</label>
                                        <select id="TipoGestion_id" name="TipoGestion_id" class="form-control">
                                            <option value="">SELECCIONAR</option>
                                            @foreach ($tipog as $item)
                                                <option value="{{ $item->id }}">{{ $item->codigo }} |
                                                    {{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Ugel</label>
                                        <select id="Ugel_id" name="Ugel_id" class="form-control">
                                            <option value="">SELECCIONAR</option>
                                            @foreach ($ugels as $item)
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
                                        <label>Area </label>
                                        <select id="Area_id" name="Area_id" class="form-control">
                                            <option value="">SELECCIONAR</option>
                                            @foreach ($areas as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Turno</label>
                                        <select id="Turno_id" name="Turno_id" class="form-control">
                                            <option value="">SELECCIONAR</option>
                                            @foreach ($turno as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
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
    <div id="modal_modulares" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0">
                    <form action="" id="form_modulares" name="form" class="form-horizontal"
                        enctype="multipart/form-data" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" id="idsfl" name="idsfl" value="">
                        <input type="hidden" id="idiiee" name="idiiee" value="">
                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Estado de SFL</label>
                                        <select id="estadomodulares" name="estadomodulares" class="form-control">
                                            <option value="0">SELECCIONAR</option>
                                            <option value="1">SANEADO</option>
                                            <option value="2">NO SANEADO</option>
                                            <option value="3">NO REGISTRADO</option>
                                            <option value="4">EN PROCESO</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Tipo de SFL</label>
                                        <select id="tipomodulares" name="tipomodulares" class="form-control">
                                            <option value="0">SELECCIONAR</option>
                                            <option value="1">AFECTACION EN USO</option>
                                            <option value="2">TITULARIDAD</option>
                                            <option value="3">APORTE REGLAMENTARIO</option>
                                            <option value="4">OTROS</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Partida Electrónica</label>
                                        <input id="partidamodulares" name="partidamodulares" class="form-control"
                                            type="text" placeholder="Ingrese partida electrónica">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Anotación</label>
                                        <select id="anotacionmodulares" name="anotacionmodulares" class="form-control">
                                            <option value="0">SELECCIONAR</option>
                                            <option value="1">PREVENTIVA</option>
                                            <option value="2">DEFINITIVA</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Fecha de Registro</label>
                                        <input id="fechamodulares" name="fechamodulares" class="form-control"
                                            type="date" placeholder="Ingrese fecha de registro">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Fecha de Inscripción</label>
                                        <input id="fechainscripcion" name="fechainscripcion" class="form-control"
                                            type="date" placeholder="Ingrese fecha de registro">
                                        <span class="help-block"></span>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Cargar Documento</label>
                                        <div class="input-group">
                                            <input id="documentomodulares" name="documentomodulares"
                                                class="form-control d-none" type="file" accept="application/pdf">
                                            <input id="documentomodulares_nombre" name="documentomodulares_nombre"
                                                class="form-control" type="text" placeholder="Seleccione Archivo"
                                                readonly>
                                            <span class="input-group-append">
                                                <label for="documentomodulares"
                                                    class="btn btn-primary btn-file-documento">
                                                    <i class="fas fa-cloud-upload-alt"></i> </label>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-body pt-0" style="text-align:center" id="modal-btn">
                    <button type="button" class="btn btn-xs btn-danger" id="btnSaveModularesCancelar"
                        onclick="cancelarsavemodulares()">Cancelar</button>
                    <button type="button" class="btn btn-xs btn-primary" id="btnSaveModulares"
                        onclick="savemodulares()"><i class="fa fa-plus"></i> Guardar</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tbmodulares" class="table table-striped table-bordered tablex"
                            style="font-size: 14px">
                            <thead class="cabecera-dataTable">
                                <tr class="text-white bg-success-0">
                                    <th>Nº</th>
                                    <th>Código Local</th>
                                    <th>Código modular</th>
                                    <th>Institución Educativa</th>
                                    <th>Nivel</th>
                                    <th>Estado SFL</th>
                                    <th>Acción</th>
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
    <div id="modal_ver" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tbver" class="table table-striped table-bordered tablex" style="font-size: 14px">
                            <thead class="cabecera-dataTable">
                                <tr class="text-white bg-success-0">
                                    <th>Nº</th>
                                    <th>Código Local</th>
                                    <th>Código modular</th>
                                    <th>Institución Educativa</th>
                                    <th>Nivel</th>
                                    <th>Estado SFL</th>
                                    <th>Documento</th>
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
                <div class="modal-footer d-none">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSaveEntidad" onclick="saveentidad()"
                        class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap modal -->

    <!-- Bootstrap modal -->
    <div id="modal_vistaprevia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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
                    <div class="form-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Descargar</label>
                                    <input id="descargarplantilla" name="descargarplantilla"
                                        class="form-control btn-primary" type="button" value="Plantilla"
                                        onclick="descargarplantilla()">
                                    {{-- <input class="btn btn-primary" type="but}ton" value="Input"> --}}

                                    <span class="help-block"></span>
                                </div>

                                <div class="col-md-8">
                                    <label>Cargar Plantilla</label>
                                    <div class="input-group">
                                        <input id="cargarplantilla" name="cargarplantilla" class="form-control d-none"
                                            type="file" accept="application/xlsx">
                                        <input id="cargarplantilla_nombre" name="cargarplantilla_nombre"
                                            class="form-control" type="text" placeholder="Seleccione Archivo"
                                            readonly>
                                        <span class="input-group-append">
                                            <label for="cargarplantilla" class="btn btn-primary btn-file-documento">
                                                <i class="fas fa-cloud-upload-alt"></i> </label>
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table id="tbvistaprevia" class="table table-sm table-striped table-bordered"
                                    style="font-size: 14px">
                                    <thead class="cabecera-dataTable">
                                        <tr class="text-white bg-success-0">
                                            {{-- <th>Nº</th> --}}
                                            <th>Código Modular</th>
                                            <th>Estado</th>
                                            <th>Tipo</th>
                                            <th>Partida Electronica</th>
                                            <th>Anotacion</th>
                                            <th>Fecha Registro</th>
                                            <th>Fecha Inscripción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnSavePlantilla" onclick="saveplantilla()"
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

    <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <!-- Sweet alert init js-->
    <script src="assets/js/pages/sweet-alerts.init.js"></script>

    <script>
        var save_method = '';
        var table_principal;
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

            cargarCards();

        });

        function cargarCards() {
            table_principal = $('#tbprincipal').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                language: table_language,
                destroy: true,
                ajax: {
                    "url": "{{ route('mantenimiento.iiee.listar') }}",
                    "type": "GET",
                    data: function(d) {
                        d.ugel = $('#ugel').val();
                        d.provincia = $('#provincia').val();
                        d.distrito = $('#distrito').val();
                        d.nivel = $('#nivel').val();
                    }
                    //"dataType": 'JSON',
                },
                columnDefs: [{
                    className: 'text-center',
                    targets: [0, 1, 2, 6, 7, 8, 9, 10]
                }],
            });
        }

        function cargarFiltros(source) {
            $.ajax({
                url: "{{ route('mantenimiento.iiee.cargarfiltros') }}",
                type: 'GET',
                data: {
                    ugel: $('#ugel').val(),
                    provincia: $('#provincia').val(),
                    distrito: $('#distrito').val(),
                    nivel: $('#nivel').val()
                },
                success: function(data) {
                    if (source == 'ugel') {
                        var selectProv = $('#provincia');
                        selectProv.empty();
                        selectProv.append('<option value="0">TODOS</option>');
                        $.each(data.provincias, function(index, value) {
                            selectProv.append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                        selectProv.val(0);
                    }

                    if (source == 'ugel' || source == 'provincia') {
                        var selectDist = $('#distrito');
                        selectDist.empty();
                        selectDist.append('<option value="0">TODOS</option>');
                        $.each(data.distritos, function(index, value) {
                            selectDist.append('<option value="' + value.id + '">' + value.nombre + '</option>');
                        });
                        selectDist.val(0);
                    }

                    if (source == 'ugel' || source == 'provincia' || source == 'distrito') {
                        var selectNivel = $('#nivel');
                        selectNivel.empty();
                        selectNivel.append('<option value="0">TODOS</option>');
                        $.each(data.niveles, function(index, value) {
                            selectNivel.append('<option value="' + value.id + '">' + value.codigo + ' | ' + value.nombre + '</option>');
                        });
                        selectNivel.val(0);
                    }
                    reload_table_principal();
                }
            });
        }

        function add() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Registro del Saneamiento Físico Legal');
            $('#id').val("");
            $('#nmodular').val(0);
            $('#modular option').remove();
        };

        function save() {
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ route('mantenimiento.iiee.guardar') }}";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ route('mantenimiento.iiee.modificar') }}";
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
                            if (data.inputerror[i] == "local") {
                                $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass(
                                    'has-error');
                                $('[name="' + data.inputerror[i] + '"]').parent().next().text(data.error_string[
                                    i]);
                            } else {
                                $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                                $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                            }
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
            $.get("{{ route('mantenimiento.iiee.editar', '') }}/" + id, function(data) {
                $('[name="id"]').val(data.id);
                $('[name="NivelModalidad_id"]').val(data.NivelModalidad_id);
                $('[name="Forma_id"]').val(data.Forma_id);
                $('[name="Caracteristica_id"]').val(data.Caracteristica_id);
                $('[name="Genero_id"]').val(data.Genero_id);
                $('[name="TipoGestion_id"]').val(data.TipoGestion_id);
                $('[name="Ugel_id"]').val(data.Ugel_id);
                $('[name="Area_id"]').val(data.Area_id);
                $('[name="EstadoInsEdu_id"]').val(data.EstadoInsEdu_id);
                $('[name="Turno_id"]').val(data.Turno_id);
                $('[name="CentroPoblado_id"]').val(data.CentroPoblado_id);
                $('[name="codModular"]').val(data.codModular);
                $('[name="codLocal"]').val(data.codLocal);
                $('[name="nombreInstEduc"]').val(data.nombreInstEduc);
                $('#modal_form').modal('show');
                $('.modal-title').text('Modificar indicador [' + data.codModular + ']');
            });

            // $.ajax({
            //     url: "{{ route('mantenimiento.iiee.editar', '') }}/" + id,
            //     type: "GET",
            //     dataType: "JSON",
            //     success: function(data) {
            //         $('[name="id"]').val(data.ie.id);
            //         $('[name="codigo"]').val(data.ie.codigo);
            //         $('[name="nombre"]').val(data.ie.nombre);
            //         $('[name="descripcion"]').val(data.ie.descripcion);
            //         $('[name="instrumento"]').val(data.ie.instrumento_id);
            //         $('[name="tipo"]').val(data.ie.tipo_id);
            //         $('[name="dimension"]').val(data.ie.dimension_id);
            //         $('[name="unidad"]').val(data.ie.unidad_id);
            //         $('[name="frecuencia"]').val(data.ie.frecuencia_id);
            //         $('[name="fuentedato"]').val(data.ie.fuente_dato);
            //         $('[name="aniobase"]').val(data.ie.anio_base);
            //         $('[name="valorbase"]').val(data.ie.valor_base);
            //         $('[name="sector"]').val(data.ie.sector_id);
            //         $('[name="entidad"]').val(data.ie.entidad);
            //         $('[name="entidadn"]').val(data.ie.entidadn);
            //         $('[name="oficina"]').val(data.ie.oficina_id);
            //         $('[name="oficinan"]').val(data.ie.oficinan);
            //         $('#modal_form').modal('show');
            //         $('.modal-title').text('Modificar indicador [' + data.ie.codigo + ']');
            //     },
            //     error: function(jqXHR, textStatus, errorThrown) {
            //         alert('Error get data from ajax');
            //     }
            // });
        };

        function reload_table_principal() {
            table_principal.ajax.reload(null, false);
        }

        function descargar1() {
            window.open("{{ url('/') }}/Man/SFL/Download/EXCEL/" + $('#ugel').val() + "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#estado').val());
        }

        function descargarplantilla() {
            window.open("{{ route('mantenimiento.sfl.exportar.plantilla') }}", '_blank');
        }

        function estado(id, x) {
            bootbox.confirm("Seguro desea " + (x == "AC" ? "desactivar" : "activar") + " este registro?", function(
                result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('mantenimiento.iiee.estado', ['id' => ':id']) }}"
                            .replace(':id', id),
                        /* type: "POST", */
                        dataType: "JSON",
                        success: function(data) {
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
        };

        function borrar(id) {
            bootbox.confirm("Seguro desea Eliminar este registro?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('mantenimiento.iiee.eliminar', ['id' => ':id']) }}"
                            .replace(':id', id),
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
    </script>
@endsection
