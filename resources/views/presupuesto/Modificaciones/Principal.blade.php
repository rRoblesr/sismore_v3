@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'Consulta de Gastos'])

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

    <link href="{{ asset('/') }}public/assets/jquery-ui/jquery-ui.css" rel="stylesheet" />

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
            padding: 5px;
        }

        .ui-autocomplete {
            z-index: 215000000 !important;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        {{-- <form class="">@csrf </form> --}}

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-border">
                    <div class="card-header bg-transparent pb-0">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                        </div>
                        <h3 class="card-title">FILTRO</h3>
                    </div>
                    <div class="card-body pt-2 pb-0">
                        <form class="form-horizontal" id="form-filtro">
                            @csrf
                            <div class="form">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label class=" col-form-label">Año</label>
                                        <div class="">
                                            <select class="form-control" name="fano" id="fano"
                                                onchange="cargarmes();cargarcuadros2();">
                                                {{-- <option value="0">TODOS</option> --}}
                                                @foreach ($opt1 as $item)
                                                    <option value="{{ $item->anio }}">{{ $item->anio }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label">Mes</label>
                                        <div class="">
                                            <select class="form-control" name="fmes" id="fmes"
                                                onchange="cargarcuadros2();">
                                                {{-- <option value="0">TODOS</option> --}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label">Producto/Proyecto </label>
                                        <div class="">
                                            <select class="form-control" name="fproductoproyecto" id="fproductoproyecto"
                                                onchange="cargarcuadros2();">
                                                <option value="0">Todos</option>
                                                @foreach ($opt3 as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->codigo . ' ' . $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label">Unidad Ejecutora </label>
                                        <div class="">
                                            <select class="form-control" name="fgenerica" id="fgenerica"
                                                onchange="cargarcuadros2();">
                                                <option value="0">Todos</option>
                                                @foreach ($opt6 as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->nombre_ejecutora }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label">Tipos de Modificaciones </label>
                                        <div class="">
                                            <select class="form-control" name="ftipomodificacion" id="ftipomodificacion"
                                                onchange="cargarcuadros2();">
                                                <option value="0">Todos</option>
                                                @foreach ($opt4 as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->codigo . ' ' . $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label">Dispositivo Legal </label>
                                        <div class="">
                                            <select class="form-control" name="fdispositivototal" id="fdispositivototal"
                                                onchange="cargarcuadros2();">
                                                <option value="0">Todos</option>
                                                @foreach ($opt5 as $item)
                                                    <option value="{{ $item->dispositivo_legal }}">
                                                        {{ $item->dispositivo_legal }}</option>
                                                @endforeach
                                            </select>
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

        {{-- <div class="row">
            <div class="col-xl-12 principal">
                <div class="card card-border">
                    <div class="card-header border-primary bg-transparent pb-0 mb-0">
                        <h3 class="card-title"></h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive" id="vista1">
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-12 principal">
                <div class="card card-border">
                    <div class="card-header border-primary">{{--  bg-transparent pb-0 mb-0 --}}
                        <div class="card-widgets">
                            <button type="button" class="btn btn-success btn-xs" onclick="descargar()"><i
                                    class="fa fa-file-excel"></i>
                                Excel</button>
                        </div>
                        <h3 class="card-title"></h3>
                    </div>
                    <div class="card-body pb-0 pt-0">
                        <div class="table-responsive">
                            <table id="tabla1-dt" class="table table-striped table-bordered tablex"
                                style="font-size:10px;">
                                <thead>
                                    <tr class="bg-primary text-white text-center">
                                        <th>Unidad Ejecutora</th>
                                        <th>Fecha Aprobacion</th>
                                        <th>Documento</th>
                                        <th>Justificacion</th>
                                        {{-- <th>SecFun</th> --}}
                                        <th>CatPres</th>
                                        <th>ProdProy</th>
                                        <th>ActAccObra</th>
                                        <th>Rb</th>
                                        <th>Especifica Detalle</th>
                                        <th>Anulacion</th>
                                        <th>Credito</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr class="text-right bg-primary text-white">
                                        <th class="text-left" colspan="9">TOTAL</th>
                                        <th> </th>
                                        <th> </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

    </div>
@endsection

@section('js')
    <script src="{{ asset('/') }}public/assets/jquery-ui/jquery-ui.js"></script>

    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}

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

    {{-- highcharts --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

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
            cargarmes();
            //cargarcuadros();
            //cargarcuadros2();
        });

        function cargarcuadros() {
            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA tabla 1
             */
            $.ajax({
                url: "{{ route('modificaciones.tabla01') }}",
                data: {
                    'ano': $('#fano').val(),
                    'mes': $('#fmes').val(),
                    'productoproyecto': $('#fproductoproyecto').val(),
                    'tipomodificacion': $('#ftipomodificacion').val(),
                    'dispositivototal': $('#fdispositivototal').val(),
                    'generica': $('#fgenerica').val(),
                },
                type: "GET",
                beforeSend: function() {
                    $('#vista1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    $('#vista1').html(data);
                    $('#tabla1').DataTable({
                        "language": table_language,
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#vista1').html('No se encontraron Datos para Procesar');
                    console.log(jqXHR);
                },
            });

        }

        function cargarcuadros2() {
            table_principal = $('#tabla1-dt').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": false,
                    "autoWidth": false,
                    "ordered": true,
                    "destroy": true,
                    "language": table_language,
                    "ajax": {
                        "url": "{{ route('modificaciones.dt.tabla01') }}",
                        "data": {
                            'ano': $('#fano').val(),
                            'mes': $('#fmes').val(),
                            'productoproyecto': $('#fproductoproyecto').val(),
                            'tipomodificacion': $('#ftipomodificacion').val(),
                            'dispositivototal': $('#fdispositivototal').val(),
                            'generica': $('#fgenerica').val(),
                        },
                        "type": "GET",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'unidad_ejecutora',
                            name: 'unidad_ejecutora'
                        },
                        {
                            data: 'fecha_aprobacion',
                            name: 'fecha_aprobacion'
                        },
                        {
                            data: 'documento',
                            name: 'documento'
                        },
                        {
                            data: 'justificacion',
                            name: 'justificacion'
                        },
                        /* {
                            data: 'secfun',
                            name: 'secfun'
                        }, */
                        {
                            data: 'catpres',
                            name: 'catpres'
                        },
                        {
                            data: 'prod_proy',
                            name: 'prod_proy'
                        },
                        {
                            data: 'act_acc_obra',
                            name: 'act_acc_obra'
                        },
                        {
                            data: 'rb',
                            name: 'rb'
                        },
                        {
                            data: 'especifica_detalle',
                            name: 'especifica_detalle'
                        },
                        {
                            data: 'anulacion',
                            name: 'anulacion'
                        },
                        {
                            data: 'credito',
                            name: 'credito'
                        },
                    ],
                    "footerCallback": function(tfoot, data, start, end, display) {
                        var api = this.api();
                        $.ajax({
                            url: "{{ route('modificaciones.dt.tabla01.foot') }}",
                            data: {
                                'ano': $('#fano').val(),
                                'mes': $('#fmes').val(),
                                'productoproyecto': $('#fproductoproyecto').val(),
                                'tipomodificacion': $('#ftipomodificacion').val(),
                                'dispositivototal': $('#fdispositivototal').val(),
                                'generica': $('#fgenerica').val(),
                            },
                            type: 'get',
                            success: function(data) {
                                console.log(data)
                                $(api.column(9).footer()).html(data.foot.anulacion);
                                $(api.column(10).footer()).html(data.foot.credito);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(jqXHR);
                            },
                        });


                    },
                }

            );
        }

        function cargarmes() {
            $.ajax({
                url: "{{ route('modificaciones.cargarmes') }}",
                data: {
                    'ano': $('#fano').val(),
                },
                type: 'get',
                success: function(data) {
                    console.log(data.info)
                    $('#fmes option ').remove();
                    var opt = ''; // '<option value="0">Todos</option>';
                    $.each(data.info, function(index, value) {
                        opt += '<option value="' + value.mes + '">' + value.nombre +
                            '</option>';
                    });
                    $('#fmes').append(opt);
                    cargarcuadros2();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function descargar() {
            $.ajax({
                url: "{{ url('/') }}/Modificaciones/ExportarG/excel/tabla01/null/null/null/null/null/null",
                type: "GET",
                success: function(data) {
                    window.open("{{ url('/') }}/Modificaciones/ExportarG/excel/tabla01/" +
                        $('#fano').val() + "/" + $('#fmes').val() + "/" +
                        $('#fproductoproyecto').val() + "/" + $('#ftipomodificacion').val() + "/" +
                        $('#fdispositivototal').val() + "/" + $('#fgenerica').val());
                },
            });
        }
    </script>
    <script>
        function glineal(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'spline'
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                },
                xAxis: {
                    categories: categoria
                },
                yAxis: {
                    title: {
                        enabled: false,
                        text: 'Number of Employees'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                    }
                },
                series: series,
                legend: {
                    align: 'center', //right//left//center
                    verticalAlign: 'bottom', //top//middle//bottom
                    layout: 'horizontal', //horizontal//vertical//proximate
                    itemStyle: {
                        "color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal", //bold
                        "textOverflow": "ellipsis"
                    },
                },
                credits: false,

            });
        }
    </script>
@endsection
