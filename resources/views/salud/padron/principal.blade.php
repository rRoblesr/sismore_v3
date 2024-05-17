@extends('layouts.main', ['titlePage' => 'PADRON NOMINAL'])
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
                            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                        </div>
                        <h3 class="card-title">Lista de Niños de Padron Nominals</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="datatableS" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="font-size: 12px">
                                        <thead class="text-primary">
                                            <tr>
                                                <th>#</th>
                                                <th>Distrito</th>
                                                <th>IPRESS</th>
                                                <th>CNV</th>
                                                <th>DNI</th>
                                                <th>nino</th>
                                                <th>Edad</th>
                                                <th>Fec. Hb1</th>
                                                <th>Res. Hb1</th>
                                                <th>Fec. Hb2</th>
                                                <th>Res. Hb2</th>
                                                <th>Fec. Hb3</th>
                                                <th>Res. Hb3</th>
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

        <!-- Bootstrap modal -->
        <div id="modal-siagie-matricula" class="modal fade centrarmodal" tabindex="-1" role="dialog">
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
                            <table id="siagie-matricula" class="table table-striped table-bordered"
                                style="font-size:12px">
                                {{-- width:7200px; --}}
                                <thead class="text-primary">
                                    <th>UBIGEO</th>
                                    <th>SEXO</th>
                                    <th>EDAD</th>
                                    <th>TOTAL</th>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
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
        var table_principal = '';
        $(document).ready(function() {
            table_principal = $('#datatableS').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                ajax: "{{ route('salud.padron.listar') }}",
                type: "POST",
            });
        });

        /* metodo para eliminar una importacion */
        function geteliminar(id) {
            bootbox.confirm("¿Seguro desea eliminar esta importación?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('imporpoblacion.eliminar', '') }}/" + id,
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

        /* metodo para la vista seleccionada de la importacion */
        function monitor(id) {
            $('#siagie-matricula').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": false,
                    "autoWidth": false,
                    "ordered": true,
                    "destroy": true,
                    "language": table_language,
                    "ajax": {
                        "headers": {
                            'X-CSRF-TOKEN': $('input[name=_token]').val()
                        },
                        "url": "{{ route('imporpoblacion.listarimportados') }}",
                        "data": {
                            "importacion_id": id
                        },
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'codigo',
                            name: 'codigo'
                        },
                        {
                            data: 'sexo',
                            name: 'sexo'
                        },
                        {
                            data: 'edad',
                            name: 'edad'
                        },
                        {
                            data: 'total',
                            name: 'total'
                        },
                    ],
                }

            );

            $('#modal-siagie-matricula').modal('show');
            $('#modal-siagie-matricula .modal-title').text('Importado');
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
