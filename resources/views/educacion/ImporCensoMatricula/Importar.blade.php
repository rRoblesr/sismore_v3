@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - CENSO DOCENTE'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
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
            {{-- <div class="alert alert-danger"> --}}
            <div class="alert alert-{{ $tipo }}">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ $mensaje }}
                {{-- <ul>
                    <li>{{ $mensaje }}</li>
                </ul> --}}
            </div>
        @endif

        

        <div class="row">
            <div class="col-md-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-success btn-xs waves-effect waves-light" data-toggle="modal"
                                data-target=".bs-example-modal-lg" data-backdrop="static"
                                data-keyboard="false"><i class="ion ion-md-cloud-upload"></i> Importar</button>
                        </div>
                        <h3 class="card-title">HISTORIAL DE IMPORTACION</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="font-size: 12px">
                                        <thead class="text-primary">
                                            <tr>
                                                <th>N°</th>
                                                <th>Version</th>
                                                <th>Fuente</th>
                                                <th>Usuario</th>
                                                <th>Area</th>
                                                <th>Registro</th>
                                                <th>Estado</th>
                                                <th>Accion</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                style="font-size:10px;width:5000px;">
                                {{-- width:7200px; --}}
                                <thead class="text-primary">
                                    <th>CODOOII</th>
                                    <th>CODGEO</th>
                                    <th>CODLOCAL</th>
                                    <th>COD_MOD</th>
                                    <th>NROCED</th>
                                    <th>CUADRO</th>
                                    <th>TIPDATO</th>
                                    <th>NIV_MOD</th>
                                    <th>GES_DEP</th>
                                    <th>AREA_CENSO</th>
                                    <th>D01</th>
                                    <th>D02</th>
                                    <th>D03</th>
                                    <th>D04</th>
                                    <th>D05</th>
                                    <th>D06</th>
                                    <th>D07</th>
                                    <th>D08</th>
                                    <th>D09</th>
                                    <th>D10</th>
                                    <th>D11</th>
                                    <th>D12</th>
                                    <th>D13</th>
                                    <th>D14</th>
                                    <th>D15</th>
                                    <th>D16</th>
                                    <th>D17</th>
                                    <th>D18</th>
                                    <th>D19</th>
                                    <th>D20</th>
                                    <th>D21</th>
                                    <th>D22</th>
                                    <th>D23</th>
                                    <th>D24</th>
                                    <th>D25</th>
                                    <th>D26</th>
                                    <th>D27</th>
                                    <th>D28</th>
                                    <th>D29</th>
                                    <th>D30</th>
                                    <th>D31</th>
                                    <th>D32</th>
                                    <th>D33</th>
                                    <th>D34</th>
                                    <th>D35</th>
                                    <th>D36</th>
                                    <th>D37</th>
                                    <th>D38</th>
                                    <th>D39</th>
                                    <th>D40</th>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Bootstrap modal -->

        <!--  Modal content for the above example -->
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Importar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="cmxform form-horizontal tasi-form upload_file">
                                    @csrf
                                    <input type="hidden" id="ccomment" name="comentario" value="">
                                    <div class="form-group">
                                        <div class="">
                                            <label class="col-form-label">Fuente de datos</label>
                                            <div class="">
                                                <input type="text" class="form-control" readonly="readonly"
                                                    value="ESCALE - CENSO EDUCATIVO">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="">
                                            <label class="col-form-label">Fecha Versión</label>
                                            <div class="">
                                                <input type="date" class="form-control"
                                                    name="fechaActualizacion" placeholder="Ingrese fecha actualizacion"
                                                    autofocus required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="">
                                            <label class="col-form-label">Archivo</label>
                                            <div class="">
                                                <input type="file" name="file" class="form-control"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row  mt-0 mb-0">
                                        {{-- <label class="col-md-2 col-form-label"></label> --}}
                                        <div class="col-md-12">
                                            <div class="pwrapper m-0" style="display:none;">
                                                <div class="progress progress_wrapper">
                                                    <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress_bar"
                                                        role="progressbar" style="width:0%">0%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row ">
                                        <div class="col-lg-12 text-center">
                                            <button class="btn btn-primary waves-effect waves-light"
                                                type="submit"><i class="ion ion-md-cloud-upload"></i> Guardar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->

    </div>
@endsection

@section('js')
    <script>
        var table_principal = '';
        $(document).ready(function() {
            /* $('#myLargeModalLabel').modal({
                backdrop: 'static',
                keyboard: false
            }); */
            $('.upload_file').on('submit', upload);

            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                ajax: "{{ route('imporcensomatricula.listar.importados') }}",
                type: "GET",
            });
        });

        function upload(e) {
            e.preventDefault();
            let form = $(this),
                wrapper = $('.pwrapper'),
                /* wrapper_f = $('.wrapper_files'), */
                progress_bar = $('.progress_bar'),
                data = new FormData(form.get(0));

            progress_bar.removeClass('bg-success bg-danger').addClass('bg-info');
            progress_bar.css('width', '0%');
            progress_bar.html('Preparando...');

            wrapper.fadeIn();

            $.ajax({
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(e) {
                        if (e.lengthComputable) {
                            let percentComplete = Math.floor((e.loaded / e.total) * 100);
                            progress_bar.css('width', percentComplete + '%');
                            progress_bar.html(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                type: "POST",
                url: "{{ route('imporcensomatricula.guardar') }}",
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                data: data,
                beforeSend: () => {
                    $('button', form).attr('disabled', true);
                }
            }).done(res => {
                if (res.status === 200) {
                    progress_bar.removeClass('bg-info').addClass('bg-success');
                    progress_bar.html('Listo!');
                    form.trigger('reset');

                    setTimeout(() => {
                        wrapper.fadeOut();
                        progress_bar.removeClass('bg-success bg-danger').addClass('bg-info');
                        progress_bar.css('width', '0%');
                        table_principal.ajax.reload();
                    }, 1500);
                } else {
                    progress_bar.css('width', '100%');
                    progress_bar.html(res.msg);
                    form.trigger('reset');
                    //alert(res.msg);
                }
            }).fail(err => {
                progress_bar.removeClass('bg-success bg-info').addClass('bg-danger');
                //progress_bar.html('Hubo un error!!');
                progress_bar.html('Archivo desconocido');
            }).always(() => {
                $('button', form).attr('disabled', false);
            });
        }

        function geteliminar(id) {
            bootbox.confirm("Seguro desea Eliminar este IMPORTACION?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: "{{ route('imporcensomatricula.eliminar', '') }}/" + id,
                        type: "GET",
                        dataType: "JSON",
                        beforeSend: function() {
                            $('#eliminar' + id).html(
                                '<span><i class="fa fa-spinner fa-spin"></i></span>');
                        },
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

        function monitor(importacion) {
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
                    "url": "{{ route('imporcensomatricula.listarimportados', '') }}/" + importacion,
                    "type": "POST",
                    "dataType": 'JSON',
                },
                "columns": [{
                        data: 'codooii',
                        name: 'codooii'
                    },
                    {
                        data: 'codgeo',
                        name: 'codgeo'
                    },
                    {
                        data: 'codlocal',
                        name: 'codlocal'
                    },
                    {
                        data: 'cod_mod',
                        name: 'cod_mod'
                    },
                    {
                        data: 'nroced',
                        name: 'nroced'
                    },
                    {
                        data: 'cuadro',
                        name: 'cuadro'
                    },
                    {
                        data: 'tipdato',
                        name: 'tipdato'
                    },
                    {
                        data: 'niv_mod',
                        name: 'niv_mod'
                    },
                    {
                        data: 'ges_dep',
                        name: 'ges_dep'
                    },
                    {
                        data: 'area_censo',
                        name: 'area_censo'
                    },
                    {
                        data: 'd01',
                        name: 'd01'
                    },
                    {
                        data: 'd02',
                        name: 'd02'
                    },
                    {
                        data: 'd03',
                        name: 'd03'
                    },
                    {
                        data: 'd04',
                        name: 'd04'
                    },
                    {
                        data: 'd05',
                        name: 'd05'
                    },
                    {
                        data: 'd06',
                        name: 'd06'
                    },
                    {
                        data: 'd07',
                        name: 'd07'
                    },
                    {
                        data: 'd08',
                        name: 'd08'
                    },
                    {
                        data: 'd09',
                        name: 'd09'
                    },
                    {
                        data: 'd10',
                        name: 'd10'
                    },
                    {
                        data: 'd11',
                        name: 'd11'
                    },
                    {
                        data: 'd12',
                        name: 'd12'
                    },
                    {
                        data: 'd13',
                        name: 'd13'
                    },
                    {
                        data: 'd14',
                        name: 'd14'
                    },
                    {
                        data: 'd15',
                        name: 'd15'
                    },
                    {
                        data: 'd16',
                        name: 'd16'
                    },
                    {
                        data: 'd17',
                        name: 'd17'
                    },
                    {
                        data: 'd18',
                        name: 'd18'
                    },
                    {
                        data: 'd19',
                        name: 'd19'
                    },
                    {
                        data: 'd20',
                        name: 'd20'
                    },
                    {
                        data: 'd21',
                        name: 'd21'
                    },
                    {
                        data: 'd22',
                        name: 'd22'
                    },
                    {
                        data: 'd23',
                        name: 'd23'
                    },
                    {
                        data: 'd24',
                        name: 'd24'
                    },
                    {
                        data: 'd25',
                        name: 'd25'
                    },
                    {
                        data: 'd26',
                        name: 'd26'
                    },
                    {
                        data: 'd27',
                        name: 'd27'
                    },
                    {
                        data: 'd28',
                        name: 'd28'
                    },
                    {
                        data: 'd29',
                        name: 'd29'
                    },
                    {
                        data: 'd30',
                        name: 'd30'
                    },
                    {
                        data: 'd31',
                        name: 'd31'
                    },
                    {
                        data: 'd32',
                        name: 'd32'
                    },
                    {
                        data: 'd33',
                        name: 'd33'
                    },
                    {
                        data: 'd34',
                        name: 'd34'
                    },
                    {
                        data: 'd35',
                        name: 'd35'
                    },
                    {
                        data: 'd36',
                        name: 'd36'
                    },
                    {
                        data: 'd37',
                        name: 'd37'
                    },
                    {
                        data: 'd38',
                        name: 'd38'
                    },
                    {
                        data: 'd39',
                        name: 'd39'
                    },
                    {
                        data: 'd40',
                        name: 'd40'
                    },
                ],
            });

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
