@extends('layouts.main',['titlePage'=>'IMPORTAR DATOS - PADRON EIB'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
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

        @if ($mensaje != '')
            {{-- <div class="alert alert-danger">
        <ul>
            <li>{{$mensaje}}</li>            
        </ul> --}}
            <div class="alert alert-{{ $tipo }}">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ $mensaje }}
            </div>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Datos de importación</h3>
                    </div>

                    <div class="card-body">
                        <div class="form">

                            <form action="{{ route('PadronEIB.guardar') }}" method="post" enctype='multipart/form-data'
                                class="cmxform form-horizontal tasi-form">
                                @csrf

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Fuente de datos</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" readonly="readonly"
                                            value="REGISTRO NACIONAL EIB - PADRON EIB">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Año Matricula</label>

                                    <div class="col-md-10">
                                        <select id="anio" name="anio" class="form-control form-control-sm">
                                            @foreach ($anios as $item)
                                                <option value="{{ $item->id }}"> {{ $item->anio }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Fecha Versión</label>
                                    <div class="col-md-10">
                                        <input type="date" class="form-control" name="fechaActualizacion"
                                            placeholder="Ingrese fecha actualizacion" autofocus required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Comentario</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" placeholder="comentario opcional" id="ccomment"
                                            name="comentario"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Archivo</label>
                                    <div class="col-md-10">
                                        <input type="file" name="file" class="form-control" required>
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
                                                <th>N°</th>
                                                <th>Fecha Version</th>
                                                <th>Fuente</th>
                                                <th>Año Matricula</th>
                                                <th>Creador</th>
                                                <th>Registro</th>
                                                <th>Aprobo</th>
                                                <th>Estado</th>
                                                {{-- <th>Accion</th> --}}
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

    <script>
        var table_principal = '';
        $(document).ready(function() {
            table_principal = $('#datatable').DataTable({
                "ajax": "{{ route('PadronEIB.listar.importados') }}", //ece.listar.importados
                "columns": [{
                        data: 'fechaActualizacion'
                    }, {
                        data: 'fechaActualizacion'
                    }, {
                        data: 'fuente'
                    }, {
                        data: 'anio'
                    }, {
                        data: 'cnombre'
                    }, {
                        data: 'created_at'
                    }, {
                        data: 'anombre'
                    }, {
                        data: 'estado'
                    },
                    /* {
                        data: 'acciones'
                    }, */
                ],
                "responsive": true,
                "autoWidth": false,
                "order": false,
                "language": table_language,
            });
            table_principal.on('order.dt search.dt', function() {
                table_principal.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });
    </script>
@endsection
