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
    </style>
@endsection
@section('content')
    <div class="card">
        <div
            class="card-header bg-success-0 text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center p-2">
            <!--h3 class="card-title"></h3-->
            <h6 class="card-title mb-2 mb-md-0 text-center text-white text-md-left text-wrap">
                <!--i class="fas fa-chart-bar"></i--> Saneamiento Físico Legal
            </h6>
            <div class="text-center text-md-right">
                {{-- <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                    <i class="fa fa-redo"></i> Actualizar</button> --}}
                {{-- <button type="button" class="btn btn-primary btn-xs" onclick="add()">
                    <i class="fa fa-plus"></i> Nuevo</button> --}}
                {{-- <button type="button" class="btn btn-primary btn-xs" onclick="ejecutarPacto2()">
                    <i class="fa fa-plus"></i> Actualizar Pacto 2
                </button> --}}
                <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i class="fa fa-redo"></i>
                    Actualizar</button>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="row mb-0">
                {{-- <div class="col-md-8"></div> --}}

                <div class="col-md-3 my-1">
                    <div class="custom-select-container">
                        <label for="ugel">UGEL</label>
                        <select id="ugel" name="ugel" class="form-control font-11" onchange="cargarCards();">
                            <option value="0">TODOS</option>
                            @foreach ($ugel as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 my-1">
                    <div class="custom-select-container">
                        <label for="provincia">Provincia</label>
                        <select id="provincia" name="provincia" class="form-control font-11"
                            onchange="cargar_distrito();cargarCards();">
                            <option value="0">TODOS</option>
                            @foreach ($provincia as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3 my-1">
                    <div class="custom-select-container">
                        <label for="distrito">Distrito</label>
                        <select id="distrito" name="distrito" class="form-control font-11" onchange="cargarCards();">
                            <option value="0">TODOS</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3 my-1">
                    <div class="custom-select-container">
                        <label for="estado">Estado</label>
                        <select id="estado" name="area" class="form-control font-11" onchange="cargarCards();">
                            <option value="0">TODOS</option>
                            <option value="1">SANEADO</option>
                            <option value="2">NO SANEADO</option>
                            <option value="3">NO REGISTRADO</option>
                            <option value="4">EN PROCESO</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="card card-border border border-plomo-0">
        <div
            class="card-header border-success-0 bg-transparent text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center px-2 py-2">
            <h6 class="card-title mb-2 mb-md-0 text-center text-md-left text-wrap">Lista de Locales</h6>
            <div class="text-center text-md-right">
                <button type="button" class="btn btn-success-0 btn-xs" onclick="abrirvistaprevia()"><i
                        class="fa fa-file-excel"></i> Plantilla</button>
                <button type="button" class="btn btn-success-0 btn-xs" onclick="descargar1()"><i
                        class="fa fa-file-excel"></i> Descargar</button>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="tbprincipal" class="table table-sm font-11 table-striped table-bordered">
                            <thead class="cabecera-dataTable">
                                <tr class="text-white bg-success-0">
                                    <th>Nº</th>
                                    <th>Código Local</th>
                                    <th>Total II.EE</th>
                                    <th>UGEL</th>
                                    <th>Provincia</th>
                                    <th>Distrito</th>
                                    <th>Área</th>
                                    <th>Fecha Inscripción</th>
                                    <th>Tipo SFL</th>
                                    <th>Estado SFL</th>
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
                    <form action="" id="form" name="form" class="form-horizontal"
                        enctype="multipart/form-data" method="POST" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id" name="id" value="">
                        <input type="hidden" id="nmodular" name="nmodular" value="0">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Codigo Local</label>
                                                <div class="input-group">
                                                    <input id="local" name="local" class="form-control"
                                                        type="text" placeholder="Ingrese codigo local">
                                                    <span class="help-block"></span>
                                                    <span class="input-group-append">
                                                        <button type="button" id="btnlocal"
                                                            class="btn waves-effect waves-light btn-primary"
                                                            onclick="buscar_modulos();">
                                                            <i class="fas fa-search"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Institución Educativa</label>
                                                <select id="modular" name="modular" class="form-control"
                                                    onchange="cargar_modular()">
                                                    {{-- <option value="0">TODOS</option> --}}
                                                </select>
                                                <span class="help-block"></span>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Estado de SFL</label>
                                                <select id="estado" name="estado" class="form-control">
                                                    <option value="0">SELECCIONAR</option>
                                                    <option value="1">SANEADO</option>
                                                    <option value="2">NO SANEADO</option>
                                                    <option value="3">NO REGISTRADO</option>
                                                    <option value="4">EN PROCESO</option>
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Tipo de SFL</label>
                                                <select id="tipo" name="tipo" class="form-control">
                                                    <option value="0">SELECCIONAR</option>
                                                    <option value="1">AFECTACION EN USO</option>
                                                    <option value="2">TITULARIDAD</option>
                                                    <option value="3">APORTE REGLAMENTARIO</option>
                                                    <option value="4">OTROS</option>
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Partida Electrónica</label>
                                                <input id="partida" name="partida" class="form-control" type="text"
                                                    placeholder="Ingrese partida electrónica">
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Zona Registral</label>
                                                <input id="zona" name="zona" class="form-control" type="text"
                                                    placeholder="Ingrese zona registral">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Fecha de Registro</label>
                                                <input id="fecha" name="fecha" class="form-control" type="date"
                                                    placeholder="Ingrese fecha de registro">
                                            </div>

                                            <div class="col-md-6">
                                                <label>Cargar Documento</label>
                                                <div class="input-group">
                                                    <input id="documento" name="documento" class="form-control d-none"
                                                        type="file" accept="application/pdf">
                                                    <input id="documento_nombre" name="documento_nombre"
                                                        class="form-control" type="text"
                                                        placeholder="Seleccione Archivo" readonly>
                                                    <span class="input-group-append">
                                                        <label for="documento" class="btn btn-primary btn-file-documento">
                                                            <i class="fas fa-cloud-upload-alt"></i> </label>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
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
                            style="font-size: 12px">
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
                        <table id="tbver" class="table table-striped table-bordered tablex" style="font-size: 12px">
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
                                    style="font-size: 11px">
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


    <!-- Modal -->
    <div class="modal fade" id="modalProceso" tabindex="-1" role="dialog" aria-labelledby="modalProcesoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalProcesoLabel">Procesando...</h5>
                </div>
                <div class="modal-body text-center">
                    <div class="progress">
                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                            role="progressbar" style="width: 0%">0%</div>
                    </div>
                    <p id="statusText" class="mt-3">Iniciando proceso...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnCerrarModal" class="btn btn-secondary btn-sm" data-dismiss="modal"
                        disabled>Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Audio (pitido) -->
    <audio id="beepAudio">
        <source src="https://actions.google.com/sounds/v1/alarms/beep_short.ogg" type="audio/ogg">
    </audio>
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
        var table_modulares;
        var table_ver;
        var table_vistaprevia;
        var form_entidad = 0;
        $(document).ready(function() {
            cargar_distrito();
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

            $("#documentomodulares").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $('#documentomodulares_nombre').val(fileName);

            });

            $("#cargarplantilla").on("change", function() {
                var fileInput = $('#cargarplantilla')[0]; // Obtén el input de tipo file
                var fileName = fileInput.files[0].name; // Extrae el nombre del archivo seleccionado
                $('#cargarplantilla_nombre').val(fileName);

                if (fileInput.files.length > 0) {
                    var formData = new FormData();
                    formData.append('archivo', fileInput.files[0]);
                    formData.append('_token', "{{ csrf_token() }}");
                    $.ajax({
                        url: "{{ route('mantenimiento.sfl.exportar.plantilla.cargar') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            table_vistaprevia = $('#tbvistaprevia').DataTable({
                                data: response.data,
                                responsive: true,
                                autoWidth: false,
                                ordered: false,
                                language: table_language,
                                destroy: true,
                                // columns: [{
                                //         data: 'columna1'
                                //     }, // Mapea las columnas según tus datos
                                //     {
                                //         data: 'columna2'
                                //     },
                                //     {
                                //         data: 'columna3'
                                //     },
                                //     // Agrega más columnas según sea necesario
                                // ]
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Error al cargar el archivo:", errorThrown);
                        }
                    });
                    // $.ajax({
                    //     url: "{{ route('mantenimiento.sfl.exportar.plantilla.cargar') }}",
                    //     type: "POST",
                    //     data: formData,
                    //     dataType: "JSON",
                    //     cache: false,
                    //     contentType: false,
                    //     processData: false,
                    //     success: function(data) {
                    //         console.log("Archivo cargado correctamente", data);
                    //     },
                    //     error: function(jqXHR, textStatus, errorThrown) {
                    //         console.error("Error al cargar el archivo:", errorThrown);
                    //     }
                    // });
                } else {
                    console.log("No se ha seleccionado ningún archivo");
                }
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
                    "url": "{{ route('mantenimiento.sfl.listar') }}",
                    "type": "GET",
                    data: {
                        ugel: $('#ugel').val(),
                        provincia: $('#provincia').val(),
                        distrito: $('#distrito').val(),
                        estado: $('#estado').val(),
                    }
                    //"dataType": 'JSON',
                },
                columnDefs: [{
                    className: 'text-center',
                    targets: [0, 1, 2, 6, 7, 8, 9, 10]
                }],
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
                url = "{{ route('mantenimiento.sfl.guardar') }}";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ route('mantenimiento.sfl.modificar') }}";
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
            $.ajax({
                url: "{{ route('mantenimiento.sfl.editar', '') }}/" + id,
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
                        url: "{{ route('mantenimiento.sfl.eliminar', '') }}/" + id,
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

        function buscar_modulos() {
            // $('#btnlocal').text('guardando...');
            // $('#btnlocal').attr('disabled', true);
            $.ajax({
                url: "{{ route('iiee.codmodular.buscar', '') }}/" + $('#local').val(),
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modular option ').remove();
                    var opt = '';
                    if (data.length > 1)
                        opt = '<option value="0">TODOS</option>';
                    $.each(data, function(index, value) {
                        opt += '<option value="' + value.id + '">' + value.modular + " " + value.iiee +
                            '</option>';
                    });
                    $('#nmodular').val(data.length);
                    $('#modular').append(opt);
                    // $('#btnlocal').text('Guardar');
                    // $('#btnlocal').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#nmodular').val(0);
                    // $('#btnlocal').text('Guardar');
                    // $('#btnlocal').attr('disabled', false);
                    toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                }
            });
        }

        function cargar_modular() {
            $.ajax({
                url: "{{ route('mantenimiento.sfl.buscar.modular', '') }}/" + $('#modular').val(),
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // $('[name="id"]').val(data.ie.id);
                    $('[name="estado"]').val(data.sfl.estado);
                    $('[name="tipo"]').val(data.sfl.tipo);
                    $('[name="partida"]').val(data.sfl.partida_electronica);
                    $('[name="zona"]').val(data.sfl.zona_registral);
                    $('[name="fecha"]').val(data.sfl.fecha_registro);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        };

        function reload_table_principal() {
            table_principal.ajax.reload(null, false);
        }

        function open_modular(local) {
            $('#form_modulares')[0].reset();
            $('form_modulares .form-group').removeClass('has-error');
            $('form_modulares .help-block').empty();
            $('#modal_modulares').modal('show');
            $('.modal-title').text('Registro del Saneamiento Físico Legal');
            $('#idsfl').val('');
            $('#modal-btn').hide();

            table_modulares = $('#tbmodulares').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                searching: false,
                bPaginate: false,
                info: false,
                language: table_language,
                destroy: true,
                ajax: {
                    "url": "{{ route('mantenimiento.sfl.modular.listar') }}",
                    "type": "GET",
                    "data": {
                        local: local
                    },
                    //"dataType": 'JSON',
                },

            });
        };

        function edit_modular(id, iiee) {
            $('#form_modulares')[0].reset();
            $('form_modulares .form-group').removeClass('has-error');
            $('form_modulares .help-block').empty();
            $('#modal-btn').show();
            $('[name="idiiee"]').val(iiee);
            $.ajax({
                url: "{{ route('mantenimiento.sfl.editar', '') }}/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="idsfl"]').val(data.sfl.id);
                    $('[name="estadomodulares"]').val(data.sfl.estado);
                    $('[name="tipomodulares"]').val(data.sfl.tipo == null ? 0 : data.sfl
                        .tipo); //anotacionmodulares
                    $('[name="partidamodulares"]').val(data.sfl.partida_electronica);
                    $('[name="anotacionmodulares"]').val(data.sfl.anotacion);
                    // $('[name="zona-modulares"]').val(data.sfl.zona-_registral);
                    $('[name="fechamodulares"]').val(data.sfl.fecha_registro);
                    $('[name="fechainscripcion"]').val(data.sfl.fecha_inscripcion);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        };

        function cancelarsavemodulares(local) {
            $('#form_modulares')[0].reset();
            $('form_modulares .form-group').removeClass('has-error');
            $('form_modulares .help-block').empty();
            $('#idsfl').val('');
            $('#modal-btn').hide();
        };

        function savemodulares() {
            $('#btnSaveModulares').text('guardando...');
            $('#btnSaveModulares').attr('disabled', true);
            var url;
            url = "{{ route('mantenimiento.sfl.modular.modificar') }}";
            msgsuccess = "El registro fue actualizado exitosamente.";
            msgerror = "El registro no se pudo actualizar. Verifique la operación";
            $.ajax({
                url: url,
                data: new FormData($('#form_modulares')[0]),
                type: "POST",
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    if (data.status) {
                        $('#form_modulares')[0].reset();
                        $('form_modulares .form-group').removeClass('has-error');
                        $('form_modulares .help-block').empty();
                        $('#idsfl').val('');
                        $('#modal-btn').hide();
                        table_modulares.ajax.reload(null, false);
                        table_principal.ajax.reload(null, false);
                        toastr.success(msgsuccess, 'Mensaje');
                    } else {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').parent().addClass('has-error');
                            $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
                        }
                    }
                    $('#btnSaveModulares').text('Guardar');
                    $('#btnSaveModulares').attr('disabled', false);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(msgerror, 'Mensaje');
                    $('#btnSaveModulares').text('Guardar');
                    $('#btnSaveModulares').attr('disabled', false);
                }
            });
        };

        function open_ver(local) {
            table_ver = $('#tbver').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                searching: false,
                bPaginate: false,
                info: false,
                language: table_language,
                destroy: true,
                ajax: {
                    "url": "{{ route('mantenimiento.sfl.modular.listar.2') }}",
                    "type": "GET",
                    "data": {
                        local: local
                    },
                    //"dataType": 'JSON',
                },

            });
            $('#modal_ver').modal('show');
            $('.modal-title').text('Saneamiento Físico Legal');
        };

        function verpdf(id) {
            window.open("{{ route('mantenimiento.sfl.exportar.pdf', '') }}/" + id);
        };

        function cargar_distrito() {
            $.ajax({
                url: "{{ route('iiee.cargar.distrito', '') }}/" + $('#provincia').val(),
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#distrito option ').remove();
                    var opt = '<option value="0">TODOS</option>';
                    $.each(data, function(index, vv) {
                        opt += '<option value="' + vv.id + '">' + vv.nombre + '</option>';
                    });
                    $('#distrito').append(opt);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error("El registro no se pudo crear verifique las validaciones.", 'Mensaje');
                }
            });
        }

        function descargar1() {
            window.open("{{ url('/') }}/Man/SFL/Download/EXCEL/" + $('#ugel').val() + "/" + $('#provincia')
                .val() + "/" + $('#distrito').val() + "/" + $('#estado').val());
        }

        function descargarplantilla() {
            window.open("{{ route('mantenimiento.sfl.exportar.plantilla') }}", '_blank');
        }

        function abrirvistaprevia() {
            $('#cargarplantilla').val(null);
            $('#cargarplantilla_nombre').val('');
            table_vistaprevia = $('#tbvistaprevia').DataTable({
                data: [],
                responsive: true,
                autoWidth: false,
                ordered: false,
                language: table_language,
                destroy: true,
            });
            $('#modal_vistaprevia .modal-title').text('PLANTILLA');
            $('#modal_vistaprevia').modal('show');
        }

        function saveplantilla() { //btnSavePlantilla
            $('#btnSavePlantilla').html('<i class="fa fa-spinner fa-pulse"></i> guardando...');
            $('#btnSavePlantilla').attr('disabled', true);
            var fileInput = $('#cargarplantilla')[0]; // Obtén el input de tipo file
            if (fileInput.files.length > 0) {
                var formData = new FormData();
                formData.append('archivo', fileInput.files[0]);
                formData.append('_token', "{{ csrf_token() }}");
                $.ajax({
                    url: "{{ route('mantenimiento.sfl.exportar.plantilla.guardar') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.modular.length > 0) {
                            var mod = '';
                            data.modular.forEach(function(elemento, index) {
                                mod += elemento + '\n';
                            });
                            alert("estos códigos modulares no existen en la base de datos\n" + mod);
                        }
                        table_principal.ajax.reload(null, false);
                        $('#modal_vistaprevia').modal('hide');
                        $('#btnSavePlantilla').html('Guardar');
                        $('#btnSavePlantilla').attr('disabled', false);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error al cargar el archivo:", errorThrown);
                        $('#btnSavePlantilla').html('Guardar');
                        $('#btnSavePlantilla').attr('disabled', false);
                    }
                });
            } else {
                console.log("No se ha seleccionado ningún archivo");
                $('#btnSavePlantilla').html('Guardar');
                $('#btnSavePlantilla').attr('disabled', false);
            }
        }

        function ejecutarPacto2() {
            // Abre modal y resetea estado
            $('#modalProceso').modal('show');
            $('#progressBar').css('width', '0%').text('0%');
            $('#statusText').text('Iniciando proceso...');
            $('#btnCerrarModal').prop('disabled', true);

            // Simula progreso mientras se ejecuta
            let progress = 0;
            let interval = setInterval(function() {
                if (progress < 90) {
                    progress += 10;
                    $('#progressBar').css('width', progress + '%').text(progress + '%');
                }
            }, 500);

            // Llamada AJAX al backend
            $.ajax({
                url: "{{ route('ImporPadronWeb.procedures', ['proceso' => 2, 'importacion' => 1]) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    clearInterval(interval);
                    $('#progressBar').css('width', '100%').text('100%');
                    $('#statusText').text('✅ Proceso completado correctamente');
                    $('#btnCerrarModal').prop('disabled', false);

                    // Pitido de finalización
                    document.getElementById('beepAudio').play();
                },
                error: function(xhr) {
                    clearInterval(interval);
                    $('#progressBar').removeClass('bg-success').addClass('bg-danger')
                        .css('width', '100%').text('Error');
                    $('#statusText').text('❌ Error en el proceso: ' + xhr.responseJSON.mensaje);
                    $('#btnCerrarModal').prop('disabled', false);
                }
            });
        }
    </script>
@endsection
