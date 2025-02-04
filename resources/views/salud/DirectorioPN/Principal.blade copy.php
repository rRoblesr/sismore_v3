@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => ''])

@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/scroller.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
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
    </style>

    <style>
        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .btn-ver-mas {
            margin-top: auto;
            /* Empuja el botón hacia abajo */
        }

        /* Estilos del panel lateral */
        .offcanvas {
            position: fixed;
            top: 0;
            right: -350px;
            /* Panel oculto inicialmente */
            width: 350px;
            height: 100%;
            background-color: white;
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
            overflow-y: auto;
            transition: right 0.3s ease-in-out;
            z-index: 1050;
            padding: 20px;
        }

        .offcanvas.show {
            right: 0;
            /* Muestra el panel */
        }

        .offcanvas-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .offcanvas-body {
            padding-top: 10px;
        }

        .offcanvas a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        .offcanvas a:hover {
            background-color: #f8f9fa;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Tarjeta 1 -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card w-100">
                    <img src="https://www.innobing.com/wp-content/uploads/2024/03/una-imagen-400x250.jpg"
                        class="card-img-top" alt="Imagen 1">
                    <div class="card-body text-center">
                        <p class="text-muted">
                            <span class="badge badge-primary">#Salud</span>
                            <span class="badge badge-secondary">#Prevención</span>
                        </p>
                        <h5 class="card-title">Prevención y manejo de condiciones secundarias de salud en personas con
                            discapacidad.</h5>
                        <a href="#" class="btn btn-primary btn-block btn-ver-mas">Ver más</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2 -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card w-100">
                    <img src="https://www.innobing.com/wp-content/uploads/2024/03/una-imagen-400x250.jpg"
                        class="card-img-top" alt="Imagen 2">
                    <div class="card-body text-center">
                        <p class="text-muted">
                            <span class="badge badge-success">#Materno</span>
                            <span class="badge badge-warning">#Neonatal</span>
                        </p>
                        <h5 class="card-title">Salud Materno Neonatal.</h5>
                        <a href="#" class="btn btn-primary btn-block btn-ver-mas">Ver más</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3 -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card w-100">
                    <img src="https://www.innobing.com/wp-content/uploads/2024/03/una-imagen-400x250.jpg"
                        class="card-img-top" alt="Imagen 3">
                    <div class="card-body text-center">
                        <p class="text-muted">
                            <span class="badge badge-info">#Gestión</span>
                            <span class="badge badge-danger">#Control</span>
                        </p>
                        <h5 class="card-title">Tablero de Control</h5>
                        <a href="#" class="btn btn-primary btn-block btn-ver-mas">Ver más</a>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 4 -->
            <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="card w-100">
                    <img src="https://www.innobing.com/wp-content/uploads/2024/03/una-imagen-400x250.jpg"
                        class="card-img-top" alt="Imagen 4">
                    <div class="card-body text-center">
                        <p class="text-muted">
                            <span class="badge badge-dark">#AdultoMayor</span>
                            <span class="badge badge-light">#Salud</span>
                        </p>
                        <h5 class="card-title">Etapa de vida de Adulto Mayor</h5>
                        <a href="#" class="btn btn-primary btn-block btn-ver-mas"  onclick="openPanel()">Ver más</a>
                        {{-- <button class="btn btn-primary btn-block" onclick="openPanel()">Ver más</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas" id="offcanvasPanel">
        <div class="offcanvas-header">
            <h5>Más Información</h5>
            <button class="close-btn" onclick="closePanel()">&times;</button>
        </div>
        <div class="offcanvas-body">
            <a href="#">Monitoreo Vacunas Trazadoras</a>
            <a href="#">Seguimiento de Indicadores</a>
            <a href="#">Evaluación de Programas de Salud</a>
            <a href="#">Control y Vigilancia Epidemiológica</a>
            <a href="#">Gestión de Riesgos en Salud Pública</a>
            <a href="#">Estrategias para la Promoción de la Salud</a>
            <a href="#">Atención Primaria en Comunidades Rurales</a>
            <a href="#">Desarrollo de Políticas Sanitarias</a>
            <a href="#">Iniciativas para el Bienestar Infantil</a>
            <a href="#">Capacitaciones en Salud Preventiva</a>
            <a href="#">Optimización de Recursos en Hospitales</a>
            <a href="#">Monitoreo de Enfermedades Transmisibles</a>
            <a href="#">Planificación Familiar y Salud Reproductiva</a>
            <a href="#">Atención Integral al Paciente Crónico</a>
            <a href="#">Salud Ocupacional y Prevención de Riesgos</a>
            <a href="#">Investigación en Salud Pública</a>
            <a href="#">Desarrollo de Programas de Nutrición</a>
            <a href="#">Seguridad del Paciente en Centros de Salud</a>
            <a href="#">Optimización del Sistema de Referencias Médicas</a>
            <a href="#">Prevención y Manejo de Enfermedades No Transmisibles</a>
            <a href="#">Fortalecimiento de Redes de Salud</a>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-md-12">
            {{-- <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0"> --}}
            <div class="card">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                class="fa fa-redo"></i> Actualizar</button>
                        <button type="button" class="btn btn-primary btn-xs" onclick="add()"><i class="fa fa-plus"></i>
                            Nuevo</button>
                    </div>
                    <h3 class="card-title text-white">Directorio del Padron Nominal</h3>
                </div>
                <div class="card-body pb-2">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            {{-- <h3 class="card-title">Directorio del Padron Nominal </h3> --}}
                        </div>

                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="red">Red</label>
                                <select id="red" name="red" class="form-control font-12"
                                    onchange="cargarMicrored();">
                                    <option value="0">TODOS</option>
                                    @foreach ($red as $item)
                                        <option value="{{ $item->id }}">{{ $item->codigo }} {{ $item->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="micro">Microred</label>
                                <select id="micro" name="micro" class="form-control font-12"
                                    onchange="cargartableprincipal();" data-toggle="codigox">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <form class="cmxform form-horizontal tasi-form upload_file">
        @csrf

        <div class="row">
            <div class="col-md-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent p-0">
                        {{-- <div class="card-widgets">
                            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-primary btn-xs" onclick="add()"><i class="fa fa-plus"></i>
                                Nuevo</button>
                        </div> --}}
                        {{-- <h3 class="card-title"></h3> --}}
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbprincipal" class="table table-striped table-bordered tablex"
                                style="font-size: 12px">
                                <thead class="cabecera-dataTable">
                                    <tr class="bg-success-0 text-white">
                                        <th>Nº</th>
                                        <th>Red</th>
                                        <th>Microred</th>
                                        <th>Código</th>
                                        <th>Establecimiento de salud</th>
                                        <th>Responsable</th>
                                        <th>Cargo</th>
                                        <th>Condición Laboral</th>
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

    <!-- Bootstrap modal -->
    <div id="modal_form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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
                    <form action="" id="form" name="form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <input type="hidden" id="id" name="id" value="">
                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>DNI<span class="required">*</span></label>
                                        <div class="input-group">
                                            <input type="number" id="dni" name="dni" class="form-control"
                                                placeholder="Numero de Documento" maxlength="8">
                                            <span class="help-block"></span>
                                            <span class="input-group-append">
                                                <button type="button" class="btn waves-effect waves-light btn-primary"
                                                    onclick="buscardni();" id="btnbuscardni">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Nombres<span class="required">*</span></label>
                                        <input id="nombres" name="nombres" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" maxlength="150"
                                            placeholder="Ingrese Nombres">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Apellido Paterno</label>
                                        <input id="apellido_paterno" name="apellido_paterno" class="form-control"
                                            type="text" oninput="convertToUppercase(this)" maxlength="100"
                                            placeholder="Imgrese Apellido Paterno">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Apellido Materno</label>
                                        <input id="apellido_materno" name="apellido_materno" class="form-control"
                                            type="text" oninput="convertToUppercase(this)" maxlength="100"
                                            placeholder="Ingrese apellido Materno">
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Sexo</label>
                                        <select id="sexo" name="sexo" class="form-control">
                                            <option value="1">MASCULINO</option>
                                            <option value="2">FEMENINO</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>profesión</label>
                                        <input id="profesion" name="profesion" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" placeholder="Ingrese Profesión">
                                        <span class="help-block"></span>
                                    </div>


                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Cargo</label>
                                        <input id="cargo" name="cargo" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" placeholder="Ingrese Cargo">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Condición Laboral</label>
                                        <input id="condicion_laboral" name="condicion_laboral" class="form-control"
                                            type="text" oninput="convertToUppercase(this)"
                                            placeholder="Ingrese Condición Laboral">
                                        <span class="help-block"></span>
                                    </div>


                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Red</label>
                                        <select id="fred" name="fred" class="form-control">
                                            <option value="0">SELECCIONAR</option>
                                            <option value="2">RED</option>
                                            <option value="3">MICRORED</option>
                                            <option value="4">EE.SS</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Microred</label>
                                        <select id="fmicrored" name="fmicrored" class="form-control">
                                            <option value="0">SELECCIONAR</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>


                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Establecimiento de salud</label>
                                        <input id="eess" name="eess" class="form-control" type="hidden">
                                        <input id="eess" name="eess" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" placeholder="Ingrese Entidad">
                                        <span class="help-block"></span>
                                    </div>


                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Celular</label>
                                        <input id="celular" name="celular" class="form-control" type="number"
                                            placeholder="Ingrese Celular">
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Correo Electronico</label>
                                        <input id="email" name="email" class="form-control" type="email"
                                            placeholder="Ingrese Correo Electronico">
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
                    <form action="" id="formver" name="formver" class="form-horizontal" autocomplete="off">
                        @csrf
                        <div class="form-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>DNI<span class="required">*</span></label>
                                        <div class="input-group">
                                            <input type="number" id="vdni" name="vdni" class="form-control"
                                                placeholder="Numero de Documento" maxlength="12" readonly>
                                            <span class="help-block"></span>
                                            <span class="input-group-append">
                                                <button type="button" class="btn waves-effect waves-light btn-primary"
                                                    id="btnbuscardni">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Nombres<span class="required">*</span></label>
                                        <input id="vnombres" name="vnombres" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" maxlength="150"
                                            placeholder="Ingrese Nombres" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Apellido Paterno</label>
                                        <input id="vapellido_paterno" name="vapellido_paterno" class="form-control"
                                            type="text" oninput="convertToUppercase(this)" maxlength="100"
                                            placeholder="Imgrese Apellido Paterno" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Apellido Materno</label>
                                        <input id="vapellido_materno" name="vapellido_materno" class="form-control"
                                            type="text" oninput="convertToUppercase(this)" maxlength="100"
                                            placeholder="Ingrese apellido Materno" readonly>
                                        <span class="help-block"></span>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Sexo</label>
                                        <select id="vsexo" name="vsexo" class="form-control" disabled>
                                            <option value="1">MASCULINO</option>
                                            <option value="2">FEMENINO</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>profesión</label>
                                        <input id="vprofesion" name="vprofesion" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" placeholder="Ingrese Profesión" readonly>
                                        <span class="help-block"></span>
                                    </div>


                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Cargo</label>
                                        <input id="vcargo" name="vcargo" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" placeholder="Ingrese Cargo" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Condición Laboral</label>
                                        <input id="vcondicion_laboral" name="vcondicion_laboral" class="form-control"
                                            type="text" oninput="convertToUppercase(this)"
                                            placeholder="Ingrese Condición Laboral" readonly>
                                        <span class="help-block"></span>
                                    </div>


                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Tipo Entidad</label>
                                        <select id="vnivel" name="vnivel" class="form-control" disabled>
                                            <option value="0">SELECCIONAR</option>
                                            <option value="2">RED</option>
                                            <option value="3">MICRORED</option>
                                            <option value="4">ESTABLECIMIENTO</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Entidad</label>
                                        <input id="entidad" name="entidad" class="form-control" type="hidden">
                                        <input id="ventidadn" name="ventidadn" class="form-control" type="text"
                                            oninput="convertToUppercase(this)" placeholder="Ingrese Entidad" readonly>
                                        <span class="help-block"></span>
                                    </div>


                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Celular</label>
                                        <input id="vcelular" name="vcelular" class="form-control" type="number"
                                            placeholder="Ingrese Celular" readonly>
                                        <span class="help-block"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Correo Electronico</label>
                                        <input id="vemail" name="vemail" class="form-control" type="email"
                                            placeholder="Ingrese Correo Electronico" readonly>
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
@endsection

@section('js')
    {{-- autocompletar --}}
    <script src="{{ asset('/') }}public/assets/jquery-ui/jquery-ui.js"></script>

    <!-- third party js -->
    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/jszip/jszip.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/vfs_fonts.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.html5.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.print.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.keyTable.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.scroller.min.js"></script>

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



            $('#entidadn').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('eess.autocomplete') }}",
                        data: {
                            term: request.term,
                            dependencia: 0,
                            tipoentidad: $('#nivel').val()
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

            $('#profesion').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('mantenimiento.directorio.autocomplete.profesion') }}",
                        data: {
                            term: request.term,
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    // $('#profesion').val(ui.item.id);
                }
            });

            $('#cargo').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('mantenimiento.directorio.autocomplete.cargo') }}",
                        data: {
                            term: request.term,
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    // $('#profesion').val(ui.item.id);
                }
            });

            $('#condicion_laboral').autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('mantenimiento.directorio.autocomplete.condicion') }}",
                        data: {
                            term: request.term,
                        },
                        dataType: "JSON",
                        success: function(data) {
                            response(data);
                        }
                    })
                },
                select: function(event, ui) {
                    // $('#profesion').val(ui.item.id);
                }
            });

            cargartableprincipal();

        });

        function cargartableprincipal() {
            table_principal = $('#tbprincipal').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                destroy: true,
                ajax: {
                    "headers": {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    },
                    "url": "{{ route('mantenimiento.directorio.listar.importados') }}",
                    "type": "POST",
                    "data": {
                        red: $('#red').val(),
                        micro: $('#micro').val()
                    },
                    //"dataType": 'JSON',
                },

            });
        }

        function openPanel() {
            document.getElementById("offcanvasPanel").classList.add("show");
        }

        function closePanel() {
            document.getElementById("offcanvasPanel").classList.remove("show");
        }

        function convertToUppercase(input) {
            const start = input.selectionStart;
            const end = input.selectionEnd;
            input.value = input.value.toUpperCase();
            input.setSelectionRange(start, end);
        }

        function buscardni() {
            if ($('#dni').val().length == 8) {
                $('#btnbuscardni').html("<i class='fa fa-spinner fa-spin'></i>");
                $.ajax({
                    url: "https://apiperu.dev/api/dni/" + $('#dni').val() +
                        "?api_token={{ TOKEN_APIPERU }}",
                    type: 'GET',
                    beforeSend: function() {
                        $('[name="nombres"]').val("");
                        $('[name="apellido_paterno"]').val("");
                        $('[name="apellido_materno"]').val("");
                    },
                    success: function(data) {
                        if (data.success) {
                            $("#nombres").val(data.data.nombres);
                            $("#apellido_paterno").val(data.data.apellido_paterno);
                            $("#apellido_materno").val(data.data.apellido_materno);
                            //toastr.success("El registro fue creado exitosamente.", 'Mensaje');
                        } else {
                            alert('El DNI no existe');
                            toastr.error('El DNI no existe', 'Mensaje');
                        }
                        $('#btnbuscardni').html('<i class="fa fa-search"></i>');
                    },
                    error: function(data) {
                        $('#btnbuscardni').html('<i class="fa fa-search"></i>');
                        toastr.error('Error en la busqueda, Contacte al Administrador del Sistema', 'Mensaje');
                    }
                });
            } else {
                alert('INGRESE UN DNI DE 8 DIGITOS');
                toastr.error('INGRESE UN DNI DE 8 DIGITOS', 'Mensaje');
            }

        }

        function add() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('#modal_form').modal('show');
            $('.modal-title').text('Crear Directorio ');
            $('#id').val("");
        };

        function save() {
            $('#btnSave').text('guardando...');
            $('#btnSave').attr('disabled', true);
            var url;
            if (save_method == 'add') {
                url = "{{ url('/') }}/Mantenimiento/Directorio/ajax_add";
                msgsuccess = "El registro fue creado exitosamente.";
                msgerror = "El registro no se pudo crear verifique las validaciones.";
            } else {
                url = "{{ url('/') }}/Mantenimiento/Directorio/ajax_update";
                msgsuccess = "El registro fue actualizado exitosamente.";
                msgerror = "El registro no se pudo actualizar. Verifique la operación";
            }
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function(data) {
                    console.log(data)
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
                url: "{{ url('/') }}/Mantenimiento/Directorio/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="id"]').val(data.dpn.id);
                    $('[name="dni"]').val(data.dpn.dni);
                    $('[name="nombres"]').val(data.dpn.nombres);
                    $('[name="apellido_paterno"]').val(data.dpn.apellido_paterno);
                    $('[name="apellido_materno"]').val(data.dpn.apellido_materno);
                    $('[name="sexo"]').val(data.dpn.sexo);
                    $('[name="profesion"]').val(data.dpn.profesion);
                    $('[name="cargo"]').val(data.dpn.cargo);
                    $('[name="condicion_laboral"]').val(data.dpn.condicion_laboral);
                    $('[name="nivel"]').val(data.dpn.nivel);
                    $('[name="entidad"]').val(data.dpn.codigo);
                    $('[name="entidadn"]').val(data.dpn.entidadn);
                    $('[name="celular"]').val(data.dpn.celular);
                    $('[name="email"]').val(data.dpn.email);
                    $('#modal_form').modal('show');
                    $('.modal-title').text('Modificar Directorio');
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
                        url: "{{ url('/') }}/Mantenimiento/Directorio/ajax_delete/" + id,
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

        function estado(id, x) {
            bootbox.confirm("Seguro desea " + (x == '0' ? "desactivar" : "activar") + " este registro?", function(
                result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Mantenimiento/Directorio/ajax_estado/" + id,
                        /* type: "POST", */
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
        };

        function ver(id) {
            $('#form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $.ajax({
                url: "{{ url('/') }}/Mantenimiento/Directorio/ajax_edit/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('[name="vdni"]').val(data.dpn.dni);
                    $('[name="vnombres"]').val(data.dpn.nombres);
                    $('[name="vapellido_paterno"]').val(data.dpn.apellido_paterno);
                    $('[name="vapellido_materno"]').val(data.dpn.apellido_materno);
                    $('[name="vsexo"]').val(data.dpn.sexo);
                    $('[name="vprofesion"]').val(data.dpn.profesion);
                    $('[name="vcargo"]').val(data.dpn.cargo);
                    $('[name="vcondicion_laboral"]').val(data.dpn.condicion_laboral);
                    $('[name="vnivel"]').val(data.dpn.nivel);
                    $('[name="ventidad"]').val(data.dpn.codigo);
                    $('[name="ventidadn"]').val(data.dpn.entidadn);
                    $('[name="vcelular"]').val(data.dpn.celular);
                    $('[name="vemail"]').val(data.dpn.email);
                    $('#modal_ver').modal('show');
                    $('.modal-title').text('Vista General');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        };

        function cargarMicrored() {
            $.ajax({
                url: "{{ route('microred.cargar.find', ['red' => ':red']) }}"
                    .replace(':red', $('#red').val()),
                type: 'GET',
                success: function(data) {
                    $("#micro option").remove();
                    var options = data.length > 1 ? '<option value="0">TODOS</option>' : '';
                    $.each(data, function(index, value) {
                        //ss = (id == value.id ? "selected" : "");
                        options +=
                            `<option value='${value.id}'>${value.codigo} ${value.nombre}</option>`;
                    });
                    $("#micro").append(options);
                    cargartableprincipal();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }
    </script>
@endsection
