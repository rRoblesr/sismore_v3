@extends('layouts.main', ['titlePage' => 'RESUMEN DE IMPORTADOS'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
@endsection
@section('content')
    <div class="content">

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">FILTRO</h3>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" id="form-filtro">
                            {{-- @csrf --}}

                            <div class="form">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label class=" col-form-label">SISTEMA</label>
                                        <div class="">
                                            <select class="form-control" name="sistema" id="sistema"
                                                onchange="cargarfuenteimportacion()">
                                                <option value="1">EDUCACION</option>
                                                <option value="2">VIVIENDA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label">FUENTE DE IMPORTACION</label>
                                        <div class="">
                                            <select class="form-control" name="fuenteimportacion" id="fuenteimportacion"
                                                onchange="listarimportados()"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- card-body -->
                </div>
                <!-- card -->
            </div>
            <!-- col -->
        </div>
        <!-- End row -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">HISTORIAL DE IMPORTACION </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap">
                                        <thead class="text-primary">
                                            <tr>
                                                <th>N°</th>
                                                <th>Fecha Version</th>
                                                <th>Fuente</th>
                                                <th>Usuario</th>
                                                <th>Registro</th>
                                                <th>Comentario</th>
                                                <th>Estado</th>
                                                <th>Accion</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- card-body -->

                </div>

            </div> <!-- End col -->
        </div> <!-- End row -->

    </div>
@endsection

@section('js')
    <script>
        var table_principal = '';

        $(document).ready(function() {
            cargarfuenteimportacion();
        });

        function listarimportados() {
            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                order: true,
                language: table_language,
                ajax: {
                    url: "{{ route('importacion.listar.importados') }}",
                    type: "POST",
                    data: $('#form-filtro').serialize(),
                    /* dataType:'json', */
                },
            });

            {{--
                ajax: "{{ route('importacion.listar.importados') }}",
                type: "POST",
                 table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                order: true,
                language: table_language,
                ajax: {
                    url: "{{ url('/') }}/Importacion/Importados/",
                    type: "POST",
                    data: $('#form-filtro').serialize(),
                    dataType:'json',
                },

            }); --}}

            /* A */
            {{-- var xx = $('#fuenteimportacion').val();
            console.log($('#fuenteimportacion').val());
            table_principal = $('#datatable').DataTable({
                //"ajax": "{{ route('importacion.listar.importados', ['fuenteimportacion_id' => 2]) }}", //ece.listar.importados
                "ajax": "{{ url('/') }}/Importacion/Importados/dt/" + xx,
                "columns": [{
                        data: 'fechaActualizacion'
                    },
                    {
                        data: 'fechaActualizacion'
                    },
                    {
                        data: 'fuente'
                    },
                    {
                        data: 'cnombre'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'comentario'
                    },
                    {
                        data: 'estado'
                    },
                    /* {
                        data: 'accion'
                    }, */
                ],
                "responsive": true,
                "autoWidth": false,
                "order": false,
                "destroy": true,
                "language": table_language,
            });
            table_principal.on('order.dt search.dt', function() {
                table_principal.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw(); --}}
        }

        function geteliminar(id) {
            bootbox.confirm("Seguro desea Eliminar este IMPORTACION?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ url('/') }}/Importacion/GetEliminar/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                            $('#modal_form').modal('hide');
                            table_principal.ajax.reload();
                            toastr.success('El registro fue eliminado exitosamente.', 'Mensaje');
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

        function cargarfuenteimportacion() {
            $.ajax({
                url: "{{ url('/') }}/FuenteImportacion/cargar/" + $('#sistema').val(),
                type: 'get',
                success: function(data) {
                    console.log(data);
                    $('#fuenteimportacion option').remove();
                    var opt = '<option value="0">TODOS</option>';
                    $.each(data.fuenteimportacions, function(index, value) {
                        opt += "<option value='" + value.id + "'>" + value.nombre + "</option>";
                    });
                    $('#fuenteimportacion').append(opt);
                    listarimportados();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                }
            });
        }
    </script>

    <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
    <!-- Validation init js-->
    <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
@endsection
