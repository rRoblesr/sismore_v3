@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'GESTION DE SISTEMAS'])

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent pb-2 pl-0">
                    {{-- <div class="card-widgets"><button type="button" class="btn btn-primary btn-xs" onclick="add()"><i class="fa fa-plus"></i> Nuevo</button></div> --}}
                    <h4 class="card-title">lista de Ingresos </h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="tabla" class="table table-sm table-striped table-bordered font-11">
                            <thead class="cabecera-dataTable table-success-0 text-white">
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">Usuario Responsable</th>
                                    <th class="text-center">Accion</th>
                                    <th class="text-center">Usuario Afectado</th>
                                    <th class="text-center">Fecha modificacion</th>
                                    <th class="text-center">Datos recuperados</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $item)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $item->usuario_responsable_nombre }}</td>
                                        <td class="text-center">{{ $item->accion }}</td>
                                        <td>{{ $item->usuario_id_nombre }}</td>
                                        <td class="text-center">{{ $item->created_at }}</td>
                                        <td class="text-center"><button type="button"
                                                class="btn btn-secondary waves-effect btn-xs"
                                                onclick="abrirModal({{ $item->id }})">Ver
                                                Datos</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div> <!-- End row -->

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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-border">
                                <div class="card-header border-success-0 bg-transparent pb-2 pl-0">
                                    <h4 class="card-title">datos recuperados</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table id="tabladata" class="table table-sm table-striped table-bordered font-11">
                                            <thead class="cabecera-dataTable table-success-0 text-white">
                                                <tr>
                                                    <th class="text-center">Datos Anteriores</th>
                                                    <th class="text-center">Datos Nuevos</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
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
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>


    {{-- DATA TABLE --}}
    <script>
        $(document).ready(function() {
            var save_method = '';
            var table_principal;

            table_principal = $('#tabla').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                destroy: true,
                language: table_language,

            });
        });

        function abrirModal(id) {
            $.ajax({
                url: "{{ route('directoriosauditoria.reporte.find.recuperados', ['id' => ':id']) }}"
                    .replace(':id', id),
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#tabladata tbody').empty();
                    if (data.datos_anteriores) {
                        delete data.datos_anteriores.password;
                    }
                    if (data.datos_nuevos) {
                        delete data.datos_nuevos.password;
                    }
                    let datosAnteriores = data.datos_anteriores ?
                        JSON.stringify(data.datos_anteriores, null, 4) :
                        "No hay datos anteriores";
                    let datosNuevos = data.datos_nuevos ?
                        JSON.stringify(data.datos_nuevos, null, 4) :
                        "No hay datos nuevos";
                    let newRow =
                        `<tr> <td><pre>${datosAnteriores}</pre></td> <td><pre>${datosNuevos}</pre></td> </tr>`;

                    $('#tabladata tbody').append(newRow);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(msgerror, 'Mensaje');
                }
            });
            $('#modal_form').modal('show');
        }
    </script>
@endsection
