@extends('layouts.main', ['titlePage' => 'CONTROL DE CALIDAD - PADRON NOMINAL'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
@endsection
@section('content')
    <div class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-warning btn-xs" onclick="retornar_calidad_principal()">
                                <i class="fa fa-arrow-left"></i></button>
                            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                                <i class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel()">
                                <i class="fa fa-file-excel"></i> Descargar</button>
                        </div>
                        <h3 class="card-title">Lista de niños del padrón nominal con observaciones</h3>
                        <h4 class="card-title">{{ $calidad->nombre_calidad }}</h4>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-sm-5 table-striped table-bordered dt-responsive nowrap font-12"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead class="text-white  bg-success-0">
                                            <tr>
                                                <th>N°</th>
                                                <th>cod_padron</th>
                                                <th>cnv</th>
                                                <th>dni</th>
                                                <th>nombres</th>
                                                <th>Fec. Nacimiento</th>
                                                <th>Edad (Años)</th>
                                                <th>Distrito</th>
                                                <th>Cod Renipress</th>
                                                <th>IPRESS</th>
                                                <th>DNI Madre</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tablon as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td><a href='#' class='dni-link'
                                                            data-codigopadron='{{ $item->cod_padron }}'
                                                            data-codigocalidad='{{ $item->codigo_calidad }}'>{{ $item->cod_padron }}</a>
                                                    </td>
                                                    <td>{{ $item->cnv }}</td>
                                                    <td>{{ $item->dni_nino }}</td>
                                                    <td>{{ $item->paterno_nino . ' ' . $item->materno_nino . ', ' . $item->nombre_nino }}
                                                    </td>
                                                    <td>{{ $item->fecha_nacimiento }}</td>
                                                    <td>{{ $item->edad }}</td>
                                                    <td>{{ $item->distrito }}</td>
                                                    <td>{{ $item->cod_eess_atencion }}</td>
                                                    <td>{{ $item->nom_eess_atencion }}</td>
                                                    <td>{{ $item->dni_madre }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- card-body -->

                </div>

            </div> <!-- End col -->
        </div> <!-- End row -->

        <!-- Bootstrap modal -->
        <div id="personaModal" class="modal fade centrarmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title card-title text-primary">NIÑO(A) CON DATOS OBSERVADOS</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group no-margin">
                                        <div class="alert alert-success mb-0 fade show">
                                            <h5 class="text-success">Control de Calidad!</h5>
                                            <p class=""><strong id="nombre_calidad"></strong></p>
                                            <p id="descripcion_calidad">mollis, est non commodo luctus, nisi erat porttitor
                                                ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit
                                                amet fermentum.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="field-1" class="control-label">Cód. Padrón</label>
                                        <input type="text" class="form-control" id="codigo_padron" placeholder="-"
                                            readonly>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="field-1" class="control-label">Documento</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id='tipo_doc'>DNI</span>
                                            </div>
                                            <input type="text" id="num_doc" class="form-control" placeholder="-"
                                                disabled>
                                        </div>
                                    </div>
                                </div>





                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="field-1" class="control-label">Ap. Paterno</label>
                                        <input type="text" class="form-control" id="paterno" placeholder="-" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="field-2" class="control-label">Ap. Materno</label>
                                        <input type="text" class="form-control" id="materno" placeholder="-" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="field-2" class="control-label">Nombres</label>
                                        <input type="text" class="form-control" id="nombre" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="field-1" class="control-label">Fec. Nacimiento</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="text" id="fecha_nacimiento" class="form-control"
                                                placeholder="-" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="field-2" class="control-label">Edad</label>
                                        <input type="text" class="form-control" id="edad" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="field-2" class="control-label">DNI Madre</label>
                                        <input type="text" class="form-control" id="dni_madre" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="field-2" class="control-label">Celular</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            </div>
                                            <input type="text" id="celular" class="form-control" placeholder="-"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="field-3" class="control-label">Distrito</label>
                                        <input type="text" class="form-control" id="distrito" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="field-3" class="control-label">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="field-4" class="control-label">Tipo de Seguro</label>
                                        <input type="text" class="form-control" id="tipo_seguro" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="field-5" class="control-label">Cod. RENIPRESS</label>
                                        <input type="text" class="form-control" id="cod_eess" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="field-6" class="control-label">IPRESS</label>
                                        <input type="text" class="form-control" id="nom_eess" placeholder="-"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->

    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,

                searching: false,
                lengthChange: false,

            });
        });

        function descargarExcel() {
            var url = "{{ route('salud.padron.calidad.exportar', $calidad->codigo_calidad) }}";
            window.location.href = url;
        }

        function retornar_calidad_principal() {
            var url = "{{ route('salud.padron.calidad.index') }}";
            window.location.href = url;
        }

        function completarDatosCalidad(data) {
            $('#codigo_padron').val(data.cod_padron);
            var tipo_doc = (data.dni_nino == '') ? 'CNV' : 'DNI';
            var documento = (data.dni_nino == '') ? data.cnv : data.dni_nino;
            $('#tipo_doc').text(tipo_doc);
            $('#num_doc').val(documento);
            $('#paterno').val(data.paterno_nino);
            $('#materno').val(data.materno_nino);
            $('#nombre').val(data.nombre_nino);
            $('#fecha_nacimiento').val(data.fecha_nacimiento);
            $('#edad').val(data.edad);
            $('#dni_madre').val(data.dni_madre);
            $('#celular').val(data.celular);

            $('#distrito').val(data.distrito);
            $('#direccion').val(data.direccion);

            $('#tipo_seguro').val(data.tipo_seguro);
            $('#cod_eess').val(data.cod_eess_atencion);
            $('#nom_eess').val(data.nom_eess_atencion);

            $('#nombre_calidad').text(data.nombre_calidad);
            $('#descripcion_calidad').html(data.descripcion_calidad);
        }

        $('.dni-link').click(function(e) {
            e.preventDefault();
            var codigoPadron = $(this).data('codigopadron');
            var codigoCalidad = $(this).data('codigocalidad');

            // Obtener la URL utilizando la función route
            var url =
                "{{ route('salud.padron.calidad.mostrardato', ['codigoCalidad' => ':codigoCalidad', 'codigoPadron' => ':codigoPadron']) }}";
            url = url.replace(':codigoPadron', codigoPadron);
            url = url.replace(':codigoCalidad', codigoCalidad);

            // Enviar solicitud AJAX
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    completarDatosCalidad(data);
                    $('#personaModal').modal('show');
                }
            });

        });
    </script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
    <!-- Validation init js-->
    <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
@endsection
