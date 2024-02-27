@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - ' . $fuente->formato])
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
                            <button type="button" class="btn btn-warning btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-success btn-xs"
                                onclick="javascript:window.open('https://docs.google.com/spreadsheets/d/1epN3YPM8LSrvGY-ePxRObFTwyY2CcRml/edit?usp=sharing&ouid=108127328164905589197&rtpof=true&sd=true','_blank');"><i
                                    class="fa fa-file-excel"></i>
                                Plantilla</button>
                            <button type="button" class="btn btn-danger btn-xs"
                                onclick="javascript:window.open('https://1drv.ms/x/s!AgffhPHh-Qgo0AEnoULq3wbXGnu-?e=d81hlQ','_blank');"><i
                                    class="mdi mdi-file-pdf-outline"></i>
                                Manual</button>
                            <button type="button" class="btn btn-primary btn-xs waves-effect waves-light"
                                data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static"
                                data-keyboard="false"><i class="ion ion-md-cloud-upload"></i> Importar</button>
                        </div>
                        <h3 class="card-title">HISTORIAL DE IMPORTACION </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="font-size: 12px">
                                        <thead class="text-primary">
                                            <tr class="bg-success-1 text-white">
                                                <th>N°</th>
                                                <th>Tipo Presupuesto</th>
                                                <th>Fecha Versión</th>
                                                {{-- <th>Fuente</th> --}}
                                                <th>Usuario</th>
                                                <th>Área</th>
                                                <th>Registro</th>
                                                {{-- <th>Comentario</th> --}}
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
                                                <input type="text" class="form-control btn-xs" readonly="readonly"
                                                    value="{{ $fuente->nombre }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="">
                                            <label class="col-form-label">Fecha Versión</label>
                                            <div class="">
                                                <input type="date" class="form-control btn-xs" name="fechaActualizacion"
                                                    placeholder="Ingrese fecha actualizacion" autofocus required>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="form-group">
                                        <div class="">
                                            <label class="col-form-label">Archivo</label>
                                            <div class="">
                                                <input type="file" name="file" class="form-control btn-xs" required>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="form-group">
                                        <label class="col-form-label">Archivo</label>
                                        <div class="input-group">
                                            <input id="file" name="file" class="form-control d-none" type="file"
                                                accept=".xls,.xlsx">
                                            <input id="nfile" name="nfile" class="form-control" type="text"
                                                placeholder="Seleccione Archivo" readonly>
                                            <span class="input-group-append">
                                                <label for="file" class="btn btn-primary btn-file-documento">
                                                    <i class="fas fa-cloud-upload-alt"></i> </label>
                                            </span>
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
                                            <button class="btn btn-primary waves-effect waves-light" type="submit"><i
                                                    class="ion ion-md-cloud-upload"></i> Importar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal -->

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
                                    <th>ANIO</th>
                                    <th>MES</th>
                                    <th>COD_TIPO_GOB</th>
                                    <th>TIPO_GOBIERNO</th>
                                    <th>COD_SECTOR</th>
                                    <th>SECTOR</th>
                                    <th>COD_PLIEGO</th>
                                    <th>PLIEGO</th>
                                    <th>COD_UBIGEO</th>
                                    <th>SEC_EJEC</th>
                                    <th>COD_UE</th>
                                    <th>UNIDAD_EJECUTORA</th>
                                    <th>COD_FUE_FIN</th>
                                    <th>FUENTE_FINANCIAMIENTO</th>
                                    <th>COD_RUB</th>
                                    <th>RUBRO</th>
                                    <th>COD_TIPO_REC</th>
                                    <th>TIPO_RECURSO</th>
                                    <th>COD_TIPO_TRANS</th>
                                    <th>COD_GEN</th>
                                    <th>GENERICA</th>
                                    <th>COD_SUBGEN</th>
                                    <th>SUBGENERICA</th>
                                    <th>COD_SUBGEN_DET</th>
                                    <th>SUBGENERICA_DETALLE</th>
                                    <th>COD_ESP</th>
                                    <th>ESPECIFICA</th>
                                    <th>COD_ESP_DET</th>
                                    <th>ESPECIFICA_DETALLE</th>
                                    <th>PIA</th>
                                    <th>PIM</th>
                                    <th>RECAUDADO</th>
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
            $('.upload_file').on('submit', upload);

            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                order: true,
                language: table_language,
                ajax: "{{ route('imporingresos.listar.importados') }}",
                type: "POST",
            });

            $("#file").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $('#nfile').val(fileName);

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
                url: "{{ route('imporingresos.guardar') }}",
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
                    $('.bs-example-modal-lg').modal('hide');
                    Swal.fire({
                        title: "¡Importación Exitosa!",
                        // text: "You clicked the button!",
                        type: "success",
                        confirmButtonColor: "#348cd4"
                    })
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
                        url: "{{ url('/') }}/IMPORINGRESO/eliminar/" + id,
                        type: "GET",
                        dataType: "JSON",
                        beforeSend: function() {
                            $('#eliminar' + id).html(
                                '<span><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function(data) {
                            table_principal.ajax.reload();
                            toastr.success('El registro fue eliminado exitosamente.', 'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#eliminar' + id).html('<span><i class="fa fa-trash"></i></span>');
                            toastr.error(
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema',
                                'Mensaje');
                        }
                    });
                }
            });
        };

        function monitor(id) {
            var url = "{{ route('imporgastos.listarimportados', 55555) }}";
            url = url.replace('55555', id);
            $('#siagie-matricula').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": false,
                    "autoWidth": false,
                    "order": true,
                    "destroy": true,
                    "language": table_language,
                    "ajax": {
                        "headers": {
                            'X-CSRF-TOKEN': $('input[name=_token]').val()
                        },
                        "url": url,
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'anio',
                            name: 'anio'
                        },
                        {
                            data: 'mes',
                            name: 'mes'
                        },
                        {
                            data: 'cod_tipo_gob',
                            name: 'cod_tipo_gob'
                        },
                        {
                            data: 'tipo_gobierno',
                            name: 'tipo_gobierno'
                        },
                        {
                            data: 'cod_sector',
                            name: 'cod_sector'
                        },
                        {
                            data: 'sector',
                            name: 'sector'
                        },
                        {
                            data: 'cod_pliego',
                            name: 'cod_pliego'
                        },
                        {
                            data: 'pliego',
                            name: 'pliego'
                        },
                        {
                            data: 'cod_ubigeo',
                            name: 'cod_ubigeo'
                        },
                        {
                            data: 'sec_ejec',
                            name: 'sec_ejec'
                        },
                        {
                            data: 'cod_ue',
                            name: 'cod_ue'
                        },
                        {
                            data: 'unidad_ejecutora',
                            name: 'unidad_ejecutora'
                        },
                        {
                            data: 'sec_func',
                            name: 'sec_func'
                        },
                        {
                            data: 'cod_cat_pres',
                            name: 'cod_cat_pres'
                        },
                        {
                            data: 'categoria_presupuestal',
                            name: 'categoria_presupuestal'
                        },
                        {
                            data: 'tipo_prod_proy',
                            name: 'tipo_prod_proy'
                        },
                        {
                            data: 'cod_prod_proy',
                            name: 'cod_prod_proy'
                        },
                        {
                            data: 'producto_proyecto',
                            name: 'producto_proyecto'
                        },
                        {
                            data: 'tipo_act_acc_obra',
                            name: 'tipo_act_acc_obra'
                        },
                        {
                            data: 'cod_act_acc_obra',
                            name: 'cod_act_acc_obra'
                        },
                        {
                            data: 'actividad_accion_obra',
                            name: 'actividad_accion_obra'
                        },
                        {
                            data: 'cod_fun',
                            name: 'cod_fun'
                        },
                        {
                            data: 'funcion',
                            name: 'funcion'
                        },
                        {
                            data: 'cod_div_fun',
                            name: 'cod_div_fun'
                        },
                        {
                            data: 'division_funcional',
                            name: 'division_funcional'
                        },
                        {
                            data: 'cod_gru_fun',
                            name: 'cod_gru_fun'
                        },
                        {
                            data: 'grupo_funcional',
                            name: 'grupo_funcional'
                        },
                        {
                            data: 'meta',
                            name: 'meta'
                        },
                        {
                            data: 'cod_fina',
                            name: 'cod_fina'
                        },
                        {
                            data: 'finalidad',
                            name: 'finalidad'
                        },
                        {
                            data: 'cod_fue_fin',
                            name: 'cod_fue_fin'
                        },
                        {
                            data: 'fuente_financiamiento',
                            name: 'fuente_financiamiento'
                        },
                        {
                            data: 'cod_rub',
                            name: 'cod_rub'
                        },
                        {
                            data: 'rubro',
                            name: 'rubro'
                        },
                        {
                            data: 'cod_tipo_rec',
                            name: 'cod_tipo_rec'
                        },
                        {
                            data: 'tipo_recurso',
                            name: 'tipo_recurso'
                        },
                        {
                            data: 'cod_cat_gas',
                            name: 'cod_cat_gas'
                        },
                        {
                            data: 'categoria_gasto',
                            name: 'categoria_gasto'
                        },
                        {
                            data: 'cod_tipo_trans',
                            name: 'cod_tipo_trans'
                        },
                        {
                            data: 'cod_gen',
                            name: 'cod_gen'
                        },
                        {
                            data: 'generica',
                            name: 'generica'
                        },
                        {
                            data: 'cod_subgen',
                            name: 'cod_subgen'
                        },
                        {
                            data: 'subgenerica',
                            name: 'subgenerica'
                        },
                        {
                            data: 'cod_subgen_det',
                            name: 'cod_subgen_det'
                        },
                        {
                            data: 'subgenerica_detalle',
                            name: 'subgenerica_detalle'
                        },
                        {
                            data: 'cod_esp',
                            name: 'cod_esp'
                        },
                        {
                            data: 'especifica',
                            name: 'especifica'
                        },
                        {
                            data: 'cod_esp_det',
                            name: 'cod_esp_det'
                        },
                        {
                            data: 'especifica_detalle',
                            name: 'especifica_detalle'
                        },
                        {
                            data: 'pia',
                            name: 'pia'
                        },
                        {
                            data: 'pim',
                            name: 'pim'
                        },
                        {
                            data: 'certificado',
                            name: 'certificado'
                        },
                        {
                            data: 'compromiso_anual',
                            name: 'compromiso_anual'
                        },
                        {
                            data: 'compromiso_mensual',
                            name: 'compromiso_mensual'
                        },
                        {
                            data: 'devengado',
                            name: 'devengado'
                        },
                        {
                            data: 'girado',
                            name: 'girado'
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
