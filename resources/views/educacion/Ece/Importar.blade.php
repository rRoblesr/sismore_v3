@extends('layouts.main',['titlePage'=>'IMPORTAR DATOS - EXCEL DE INDICADORES'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection
@section('content')
    <div class="content">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                Error al Cargar Archivo <br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Datos de importación</h3>
                    </div>

                    <div class="card-body">
                        @if (Session::has('message'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ Session::get('message') }}.
                            </div>
                        @endif
                        @if (Session::has('messageError'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ Session::get('messageError') }}.
                            </div>
                        @endif
                        <div class="form">

                            <form action="{{ route('ece.importar.store') }}" method="post" enctype='multipart/form-data'
                                class="cmxform form-horizontal tasi-form" id="form_importar_indicador">
                                @csrf
                                <input type="hidden" name="fuenteImportacion" value="3">
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Fuente de datos</label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" readonly="readonly" value="ECE">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Fecha Versión</label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" name="fechaActualizacion"
                                                id="fechaActualizacion" placeholder="Ingrese fecha actualizacion" autofocus
                                                required>
                                        </div>

                                        <label class="col-md-2 col-form-label">Comentario</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" placeholder="comentario opcional"
                                                id="comentario" name="comentario"></textarea>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Año</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="anio" id="anio" required>
                                                <option value="">Seleccionar</option>
                                                @foreach ($anios as $item)
                                                    <option value="{{ $item->id }}">{{ $item->anio }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <label class="col-md-2 col-form-label">Alumno EIB</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="tipo" id="tipo" required>
                                                <option value="0">NO</option>
                                                <option value="1">SI</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Nivel</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="nivel" id="nivel" onchange="cargargrados()"
                                                required>
                                                <option value="">Seleccionar</option>
                                                @foreach ($nivels as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <label class="col-md-2 col-form-label">Grado</label>
                                        <div class="col-md-4">
                                            <select class="form-control" name="grado" id="grado" required>
                                                <option value="">Seleccionar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Archivo</label>
                                        <div class="col-md-10">
                                            <input type="file" name="file" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="offset-lg-2 col-lg-10">
                                        <button class="btn btn-success waves-effect waves-light mr-1"
                                            type="submit">Importar</button>
                                        <button class="btn btn-secondary waves-effect" type="button">Cancelar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- .form -->
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
                                                <th>Id</th>
                                                <th>Fecha Version</th>
                                                <th>Año</th>
                                                <th>Alumno EIB</th>
                                                <th>Grado</th>
                                                <th>Nivel</th>
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
    <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
    <!-- Validation init js-->
    <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                "ajax": "{{ route('ece.listar.importados') }}",
                "columns": [{
                        data: 'id'
                    },
                    {
                        data: 'fecha'
                    },
                    {
                        data: 'anio'
                    },
                    {
                        data: 'tipo'
                    },
                    {
                        data: 'grado'
                    },
                    {
                        data: 'nivel'
                    },
                    {
                        data: 'estado'
                    },
                    {
                        data: 'acciones'
                    },
                ],
                "responsive": true,
                "autoWidth": false,
                "order": false,
                "language": table_language,/* {
                    "lengthMenu": "Mostrar " +
                        `<select class="custom-select custom-select-sm form-control form-control-sm">
                        <option value = '10'> 10</option>
                        <option value = '25'> 25</option>
                        <option value = '50'> 50</option>
                        <option value = '100'>100</option>
                        <option value = '-1'>Todos</option>
                        </select>` + " registros por página",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(Filtrado de _MAX_ registros totales)",
                    "emptyTable": "No hay datos disponibles en la tabla.",
                    "info": "Del _START_ al _END_ de _TOTAL_ registros ",
                    "infoEmpty": "Mostrando 0 registros de un total de 0. registros",
                    "infoFiltered": "(filtrados de un total de _MAX_ )",
                    "infoPostFix": "",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "searchPlaceholder": "Dato para buscar",
                    "zeroRecords": "No se han encontrado coincidencias.",
                    "paginate": {
                        "next": "siguiente",
                        "previous": "anterior"
                    }
                } */
            });
        });

        function cargargrados() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                //url:"{{ url('/') }}/ECE/CargarGrados",
                url: "{{ route('ece.ajax.cargargrados') }}",
                type: 'post',
                dataType: 'JSON',
                data: {
                    'nivel': $('#nivel').val()
                },
                success: function(data) {
                    $("#grado option").remove();
                    var options = '<option value="">SELECCIONAR</option>';
                    $.each(data.grados, function(index, value) {
                        options += "<option value='" + value.id + "'>" + value.descripcion + "</option>"
                    });
                    $("#grado").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function eliminarImportacion(id) {
            if (confirm("¿Desea eliminar el registro seleccionado?")) {
                $.ajax({
                    url: "{{ url('/') }}/ECE/Eliminar/ImportarDT/" + id,
                    success: function(data) {
                        if (data.status) {
                            toastr.success('El registro fue elimino correctamente');
                            $('#datatable').DataTable().ajax.reload();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                    },
                });
            }

        }
    </script>
@endsection
