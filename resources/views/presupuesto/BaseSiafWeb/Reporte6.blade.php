@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'Genérica'])

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
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header bg-success-0 pt-2">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-primary btn-xs"
                                onclick="javascript:alert('Oops.. Sin Detalles')"><i class="fas fa-file-powerpoint"></i>
                                Detalle</button>
                            <button type="button" class="btn btn-success btn-xs" onclick="descargar()"><i
                                    class="fa fa-file-excel"></i>
                                Descargar</button>
                        </div>
                        <h3 class="card-title text-white">Ejecución de Gastos, según Genérica</h3>
                    </div>
                    <div class="card-body pt-2 pb-0">
                        <form class="form-horizontal" id="form-filtro">
                            @csrf
                            <div class="form">
                                <div class="form-group row">
                                    <div class="col-md-6"><span class="font-11">{{ $actualizado }}</span>
                                    </div>
                                    <div class="col-md-1">
                                        {{-- <label class=" col-form-label">Año</label> --}}
                                        <div class="">
                                            <select class="form-control btn-xs font-11" name="ganio" id="ganio"
                                                onchange="cargar_productoproyecto();cargar_unidadejecutora();cargarcuadros2();">
                                                @foreach ($ano as $item)
                                                    <option value="{{ $item->anio }}"
                                                        {{ $item->anio == $anio ? 'selected' : '' }}>{{ $item->anio }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        {{-- <label class="col-form-label">Producto/Proyecto</label> --}}
                                        <div class="">
                                            <select class="form-control btn-xs font-11" name="garticulo" id="garticulo"
                                                onchange="cargar_unidadejecutora();cargarcuadros2();">
                                                <option value="0">PRODUCTO/PROYECTO</option>
                                                @foreach ($articulo as $item)
                                                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        {{-- <label class="col-form-label">Unidad Ejecutora</label> --}}
                                        <div class="">
                                            <select class="form-control btn-xs font-11" name="gue" id="gue"
                                                onchange="cargarcuadros2();">
                                                <option value="0">UNIDAD EJECUTORA</option>
                                                @foreach ($ue as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->codigo . ' ' . $item->nombre }}
                                                    </option>
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


        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent p-0">
                        {{-- <div class="card-widgets">
                            <button type="button" class="btn btn-success btn-xs" onclick="descargar()"><i
                                    class="fa fa-file-excel"></i>
                                    Descargar</button>
                        </div>
                        <h3 class="card-title">Ejecución de Gastos, según Genérica</h3> --}}
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" id="vista3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border border-success-0">
                    {{-- <div class="card-header border-primary bg-transparent p-0">
                        <h3 class="card-title anal2">Grafico</h3>
                    </div> --}}
                    <div class="card-body p-0">
                        <div id="anal2"></div>{{-- style="min-width:100%;height:600px;margin:0 auto;" --}}
                        {{--  style="min-width:400px;height:300px;margin:0 auto;" --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}


        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pt-2 pb-0">
                        <h3 class="card-title anal1">Funcion</h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal1"></div>{{-- style="min-width:100%;height:600px;margin:0 auto;" --}}
                        {{--  style="min-width:400px;height:300px;margin:0 auto;" --}}
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

            cargar_productoproyecto();
            cargar_unidadejecutora();

            cargarcuadros2();

        });


        function cargarcuadros2() {
            /*
             *AJAX PARA LA PRESENTACION DE LA PRIMERA tabla 2
             */
            $.ajax({
                url: "{{ route('basesiafweb.rpt6.tabla01') }}",
                data: {
                    'anio': $('#ganio').val(),
                    'articulo': $('#garticulo').val(),
                    'ue': $('#gue').val(),
                },
                type: "GET",
                beforeSend: function() {
                    $('#vista3').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    $('#vista3').html(data);
                    graficar2();
                    /* $('#tabla1').DataTable({
                        "language": table_language,
                        paging: false,
                        searching: false,
                        //"aLengthMenu":[100]
                    }); */
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    $('#vista3').html('Sin Informacion Disponible');
                },
            });
        }

        function graficar(id, nombre) {
            console.log(id + nombre);
            $.ajax({
                url: "{{ route('basesiafweb.rpt6.gra.1') }}",
                data: {
                    'anio': $('#ganio').val(),
                    'articulo': $('#garticulo').val(),
                    'ue': $('#gue').val(),
                    'generica': id,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    $('.registros').removeClass('table-warning');
                    $('#reg' + id).addClass('table-warning');
                    $('.anal1').html(nombre);
                    gAnidadaColumn('anal1',
                        data.info.categoria,
                        data.info.series,
                        '',
                        'PIM Y DEVENGADO ACUMULADO Y EJECUCIÓN MENSUAL');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function graficar2() {
            $.ajax({
                url: "{{ route('basesiafweb.rpt6.gra.2') }}",
                data: {
                    'anio': $('#ganio').val(),
                    'articulo': $('#garticulo').val(),
                    'ue': $('#gue').val(),
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal2').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    //$('.anal2').html(nombre);
                    gAnidadaColumn('anal2',
                        data.info.categoria,
                        data.info.series,
                        '',
                        'PIM Y DEVENGADO ACUMULADO Y EJECUCIÓN MENSUAL');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargar_productoproyecto() {
            //$("#gue option").remove();
            $.ajax({
                url: "{{ route('basesiafweb.cargar.productoproyecto') }}",
                data: {
                    "anio": $('#ganio').val()
                },
                type: 'get',
                success: function(data) {
                    console.log(data.productoproyecto);
                    $("#garticulo option").remove();
                    var options = '<option value="0">PRODUCTO/PROYECTO</option>';
                    $.each(data.productoproyecto, function(index, value) {
                        options += "<option value='" + value.id + "' >" + value.nombre +
                            "</option>"
                    });
                    $("#garticulo").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargar_unidadejecutora() {
            $.ajax({
                url: "{{ route('basesiafweb.cargar.unidadejecutora') }}",
                data: {
                    "anio": $('#ganio').val()
                },
                type: 'get',
                success: function(data) {
                    $("#gue option").remove();
                    var options = '<option value="0">UNIDAD EJECUTORA</option>';
                    $.each(data.ue, function(index, value) {
                        options += "<option value='" + value.id + "' >" + value.codigo + ' ' +
                            value.nombre +
                            "</option>"
                    });
                    $("#gue").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function descargar() {
            $.ajax({
                url: "{{ url('/') }}/SiafGastos/reportes6/Exportar/excel/0/0/0",
                type: "GET",
                success: function(data) {
                    window.open("{{ url('/') }}/SiafGastos/reportes6/Exportar/excel/" +
                        $('#ganio').val() + "/" + $('#garticulo').val() + "/" + $('#gue').val());

                },
            });
        }


        function gAnidadaColumn(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                }],
                yAxis: [{ // Primary yAxis
                        //max: 1000000000,
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* labels: {
                            format: '{value}°C',
                            style: {
                                color: Highcharts.getOptions().colors[2]
                            }
                        },
                        title: {
                            text: 'Temperature',
                            style: {
                                color: Highcharts.getOptions().colors[2]
                            }
                        }, */
                        //opposite: true,
                    }, { // Secondary yAxis
                        gridLineWidth: 0,
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* title: {
                            text: 'Rainfall',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        },
                        labels: {
                            format: '{value} mm',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        }, */
                        min: -100,
                        max: 150,
                        opposite: true,
                    },
                    /* { // Tertiary yAxis
                                       gridLineWidth: 0,
                                       title: {
                                           text: 'Sea-Level Pressure',
                                           style: {
                                               color: Highcharts.getOptions().colors[1]
                                           }
                                       },
                                       labels: {
                                           format: '{value} mb',
                                           style: {
                                               color: Highcharts.getOptions().colors[1]
                                           }
                                       },
                                       opposite: true
                                   } */
                ],
                series: series,
                plotOptions: {
                    /* columns: {
                        stacking: 'normal'
                    }, */
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else if (this.y < 101) {
                                    return this.y + "%";
                                } else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                    },
                },
                tooltip: {
                    shared: true,
                },
                legend: {
                    itemStyle: {
                        "color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal",
                        "textOverflow": "ellipsis"
                    },
                },
                credits: false,
            });
        }

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
