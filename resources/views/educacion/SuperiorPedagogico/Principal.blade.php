@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => ''])

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
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
            padding: 6px;
        }

        .fuentex {
            font-size: 10px;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="form-group row align-items-center vh-5">
                <div class="col-lg-5 col-md-4 col-sm-4">
                    <h4 class="page-title font-16">EDUCACIÓN SUPERIOR PEDAGÓGICO</h4>
                </div>
                <div class="col-lg-1 col-md-2 col-sm-2">
                    <select id="anio" name="anio" class="form-control btn-xs font-11" onchange="cargarCards();">
                        <option value="0">AÑO</option>
                        @foreach ($anios as $item)
                            <option value="{{ $item->anio }}" {{ $item->anio == $maxAnio ? 'selected' : '' }}>
                                {{ $item->anio }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="col-lg-2 col-md-2 col-sm-2">
                    <select id="ugel" name="ugel" class="form-control btn-xs font-11" onchange="cargarCards();">
                        <option value="0">UGEL</option>
                    </select>
                </div> --}}
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <select id="gestion" name="gestion" class="form-control btn-xs font-11" onchange="cargarCards();">
                        <option value="0">TIPO DE GESTIÓN</option>
                        <option value="12">PUBLICO</option>
                        <option value="3">PRIVADO</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <select id="area" name="area" class="form-control btn-xs font-11" onchange="cargarCards();">
                        <option value="0">ÁREA GEOGRÁFICA</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-2">
                    <select id="iiee" name="iiee" class="form-control btn-xs font-11" onchange="cargarCards();">
                        <option value="0">INSTITUCIÓN EDUCATIVA</option>
                    </select>
                </div>
            </div>



            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card-box border border-plomo-0">
                        <div class="media">
                            <div class="text-center">
                                <img src="{{ asset('/') }}public/img/icon/locales.png" alt="" class=""
                                    width="60%" height="60%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="bono"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate font-14">Instituciones Educativas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card-box border border-plomo-0">
                        <div class="media">
                            <div class="text-center">
                                <img src="{{ asset('/') }}public/img/icon/matriculas.png" alt="" class=""
                                    width="60%" height="60%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="dado"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate font-14">Estudiantes</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card-box border border-plomo-0">
                        <div class="media">
                            <div class="text-center">
                                <img src="{{ asset('/') }}public/img/icon/matriculas.png" alt="" class=""
                                    width="60%" height="60%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="chip"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate font-14">Estudiantes Bilingues</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card-box border border-plomo-0">
                        <div class="media">
                            <div class="text-center">
                                <img src="{{ asset('/') }}public/img/icon/docentes.png" alt="" class=""
                                    width="60%" height="60%">
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup" id="solar"></span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate font-14">Docentes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-6">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                        </div>
                        <div class="card-body p-0">
                            <figure class="highcharts-figure p-0 m-0">
                                <div id="anal1" style="height: 20rem"></div>
                            </figure>
                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                <span class="float-left" id="span-anal1-fuente">Fuente: Censo Educativo - MINEDU</span>
                                <span class="float-right" id="span-anal1-fecha">Actualizado:</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                        </div>
                        <div class="card-body p-0">
                            <figure class="highcharts-figure p-0 m-0">
                                <div id="anal2" style="height: 20rem"></div>
                            </figure>
                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                <span class="float-left" id="span-anal2-fuente">Fuente: Censo Educativo - MINEDU</span>
                                <span class="float-right" id="span-anal2-fecha">Actualizado:</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                        </div>
                        <div class="card-body p-0">
                            <figure class="highcharts-figure p-0 m-0">
                                <div id="anal3" style="height: 20rem"></div>
                            </figure>
                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                <span class="float-left" id="span-anal3-fuente">Fuente: Censo Educativo - MINEDU</span>
                                <span class="float-right" id="span-anal3-fecha">Actualizado:</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                            <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                        </div>
                        <div class="card-body p-0">
                            <figure class="highcharts-figure p-0 m-0">
                                <div id="anal4" style="height: 20rem"></div>
                            </figure>
                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                <span class="float-left" id="span-anal4-fuente">Fuente: Censo Educativo - MINEDU</span>
                                <span class="float-right" id="span-anal4-fecha">Actualizado:</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-border border border-plomo-0">
                        <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                            <div class="card-widgets">
                                <button type="button" class="btn btn-success btn-xs" onclick="descargar()"><i
                                        class="fa fa-file-excel"></i>
                                    Descargar</button>
                            </div>
                            <h3 class="card-title font-12">Número de estudiantes matriculados y docentes por condición
                                laboral,
                                según instituciones educativas</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive" id="ctabla1">

                                    </div>

                                </div>
                            </div>
                            <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                                <span class="float-left" id="span-tabla1-fuente">Fuente: Censo Educativo - MINEDU</span>
                                <span class="float-right" id="span-tabla1-fecha">Actualizado:</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection






@section('js')
    <script type="text/javascript">
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        $(document).ready(function() {
            cargarUgels();
            cargarAreas();
            cargarIIEE();
            cargarCards();

        });

        function cargarCards() {
            $.ajax({
                url: "{{ route('superiorpedagogico.principal.head') }}",
                data: {
                    "anio": $('#anio').val(),
                    "provincia": 0,
                    "distrito": 0,
                    "ugel": 0, // $('#ugel').val(),
                    "area": $('#area').val(),
                    "gestion": $('#gestion').val(),
                    "iiee": $('#iiee').val(),
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#bono').text(data.valor1);
                    $('#dado').text(data.valor2);
                    $('#chip').text(data.valor3);
                    $('#solar').text(data.valor4);

                    panelGraficas('anal1');
                    panelGraficas('anal2');
                    panelGraficas('anal3');
                    panelGraficas('anal4');
                    panelGraficas('tabla1');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargarDistritos() {
            $.ajax({
                url: "{{ route('plaza.cargardistritos', '') }}/" + $('#provincia').val(),
                type: 'GET',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">DISTRITO</option>';
                    $.each(data.distritos, function(index, value) {
                        options += "<option value='" + value.id + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#distrito").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarUgels() {
            $.ajax({
                url: "{{ route('superiorpedagogico.ugel') }}",
                type: 'GET',
                success: function(data) {
                    $("#ugel option").remove();
                    var options = '<option value="0">UGEL</option>';
                    $.each(data.ugel, function(index, value) {
                        options += "<option value='" + value.codigo + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#ugel").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarAreas() {
            $.ajax({
                url: "{{ route('superiorped<agogico.area') }}",
                type: 'GET',
                success: function(data) {
                    $("#area option").remove();
                    var options = '<option value="0">ÁREA GEOGRÁFICA</option>';
                    $.each(data.area, function(index, value) {
                        options += "<option value='" + value.codigo + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#area").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarIIEE() {
            $.ajax({
                url: "{{ route('superiorpedagogico.iiee') }}",
                data: {
                    anio: $('#anio').val(),
                },
                type: 'GET',
                success: function(data) {
                    $("#iiee option").remove();
                    var options = '<option value="0">IIEE</option>';
                    $.each(data.ie, function(index, value) {
                        options += "<option value='" + value.cod_mod + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#iiee").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('superiorpedagogico.principal.tabla') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "provincia": 0,
                    "distrito": 0,
                    "ugel": 0, // $('#ugel').val(),
                    "area": $('#area').val(),
                    "gestion": $('#gestion').val(),
                    "iiee": $('#iiee').val(),
                },
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (div == "anal1") {
                        gAnidadaColumn(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Porcentaje de estudiantes matriculados en educación Superior Pedagógica',
                            data.info.maxbar
                        );
                        $('#span-anal1-fecha').html('Actualizado: ' + data.foot.fecha);
                    }
                    if (div == "anal2") {
                        gcolumn2(div,
                            data.info.categoria,
                            data.info.series,
                            '',
                            'Número de postulantes e ingresantes en educación Superior Pedagógica'
                        );
                        $('#span-anal2-fecha').html('Actualizado: ' + data.foot.fecha);
                    }
                    if (div == "anal3") {
                        gcolumn('anal3',
                            data.categoria,
                            data.series,
                            '',
                            'Número de estudiantes matriculados en educación Superior Pedagógica de Rango de Edades, según sexo',
                        );
                        $('#span-anal3-fecha').html('Actualizado: ' + data.foot.fecha);
                    }
                    if (div == "anal4") {
                        gbar('anal4',
                            data.categoria,
                            data.series,
                            '',
                            'Número de estudiantes matriculados en educación Superior Pedagógica de lengua materna, según sexo',
                        );
                        $('#span-anal4-fecha').html('Actualizado: ' + data.foot.fecha);
                    }
                    if (div == "tabla1") {
                        $('#ctabla1').html(data.excel);
                        $('#tabla1').DataTable({
                            responsive: true,
                            autoWidth: false,
                            ordered: true,
                            searching: false,
                            bPaginate: false,
                            info: false,
                            language: table_language,
                        });
                        $('#span-tabla1-fecha').html('Actualizado: ' + data.foot.fecha);
                    }

                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function descargar() {
            window.open("{{ url('/') }}/SuperiorPedagogico/Exportar/Excel/" + $('#anio').val() +
                "/" + $('#ugel').val() + "/" + $('#area').val() + "/" + $('#gestion').val());
            /* $.ajax({
                url: "{{ url('/') }}/TecnicoProductiva/Exportar/Excel/null/null/null/null",
                type: "GET",
                success: function(data) {
                    window.open("{{ url('/') }}/TecnicoProductiva/Exportar/Excel/" + $('#anio').val() +
                        "/" + $('#ugel').val() + "/" + $('#area').val() + "/" + $('#gestion').val());

                },
            }); */
        }

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo, maxBar) {
            var rango = categoria.length;
            var posPorcentaje = rango * 2 + 1;
            var cont = 0;
            var porMaxBar = maxBar * 0.5;
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                colors: ['#5eb9aa', '#ef5350', '#f5bd22'],
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    }
                }],
                yAxis: [{ // Primary yAxis
                        max: maxBar > 0 ? maxBar + porMaxBar : null,
                        labels: {
                            enabled: true,
                            style: {
                                //color: Highcharts.getOptions().colors[2],
                                fontSize: '10px',
                            }
                        },
                        title: {
                            enabled: false,
                        },
                        /* labels: {
                            //format: '{value}°C',
                            //style: {
                            //    color: Highcharts.getOptions().colors[2]
                            //}
                        }, */
                        title: {
                            text: 'Matriculados',
                            style: {
                                //color: Highcharts.getOptions().colors[2],
                                fontSize: '11px',
                            }
                        },
                        //opposite: true,
                    }, { // Secondary yAxis
                        gridLineWidth: 0, //solo indica el tamaño de la linea
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* title: {
                            //text: 'Rainfall',
                            text: '%Indicador',
                            //style: {
                            //    color: Highcharts.getOptions().colors[0]
                            //}
                        }, */
                        /* labels: {
                            //format: '{value} mm',
                            format: '{value} %',
                            //style: {
                            //   color: Highcharts.getOptions().colors[0]
                            //}
                        }, */
                        //min: -200,
                        min: -600,
                        max: 400,
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
                        showInLegend: true,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.colorIndex == 1)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
                            },
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                            }
                        },
                    },
                },
                tooltip: {
                    shared: true,
                },
                legend: {
                    itemStyle: {
                        //"color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal",
                        "textOverflow": "ellipsis"
                    },
                },
                exporting: {
                    enabled: true
                },
                credits: false,
            });
        }

        function gbar(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar',
                    //marginLeft: 50,
                    //marginBottom: 90
                },
                colors: ['#5eb9aa', '#ef5350'],
                title: {
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                xAxis: {
                    categories: categoria,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    }
                },
                yAxis: {
                    labels: {
                        enabled: true,
                        style: {
                            //color: Highcharts.getOptions().colors[2],
                            fontSize: '10px',
                        }
                    },
                    title: {
                        text: '',
                        enabled: false,
                    },
                },
                plotOptions: {
                    series: {
                        stacking: 'normal', //normal, overlap, percent,stream
                        pointPadding: 0, //size de colunma
                        borderWidth: 0 //borde de columna
                    },
                    bar: {
                        dataLabels: {
                            enabled: true,
                            inside: true,
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                                color: 'white',
                                //textShadow:false,//quita sombra//para versiones antiguas
                                textOutline: false, //quita sombra
                            }
                        }
                    },
                },
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        //cursor: "pointer",
                        fontSize: "10px",
                        //fontWeight: "normal",
                        //textOverflow: "ellipsis"
                    },
                },
                series: series,
                tooltip: {
                    shared: true,
                },
                credits: {
                    enabled: false
                },
            });
        }

        function gcolumn(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                    //marginLeft: 50,
                    //marginBottom: 90
                },
                colors: ['#5eb9aa', '#ef5350'],
                title: {
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                },
                xAxis: {
                    categories: categoria,
                    labels: {
                        style: {
                            fontSize: '10px',
                            //color:'blue',
                        }
                    }
                },
                yAxis: {
                    labels: {
                        enabled: true,
                        style: {
                            //color: Highcharts.getOptions().colors[2],
                            fontSize: '10px',
                        }
                    },
                    title: {
                        text: '',
                        enabled: false,
                    },
                },
                plotOptions: {
                    series: {
                        stacking: 'normal', //normal, overlap, percent,stream
                        pointPadding: 0, //size de colunma
                        borderWidth: 0 //borde de columna
                    },
                    column: {
                        dataLabels: {
                            enabled: true,
                            inside: true,
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                                color: 'white',
                                //textShadow:false,//quita sombra//para versiones antiguas
                                textOutline: false, //quita sombra
                            }
                        }
                    },
                },
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        //cursor: "pointer",
                        fontSize: "10px",
                        //fontWeight: "normal",
                        //textOverflow: "ellipsis"
                    },
                },
                series: series,
                tooltip: {
                    shared: true,
                },
                credits: {
                    enabled: false
                },
            });
        }

        function gcolumn2(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                colors: ['#5eb9aa', '#f5bd22'],
                title: {
                    text: titulo,
                    //align: 'left'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px',
                    }
                    //align: 'left'
                },
                xAxis: {
                    categories: categoria,
                    labels: {
                        style: {
                            fontSize: '10px',
                            //color:'blue',
                        }
                    }
                    //crosshair: true,
                    //accessibility: {
                    //    description: 'Countries'
                    //}
                },
                yAxis: {
                    min: 0,
                    title: {
                        enabled: false,
                        text: '1000 metric tons (MT)'
                    },
                    labels: {
                        enabled: true,
                        style: {
                            //color: Highcharts.getOptions().colors[2],
                            fontSize: '10px',
                        }
                    },

                },
                tooltip: {
                    //valueSuffix: ' (1000 MT)'
                },
                plotOptions: {
                    series: {
                        //stacking: 'normal', //normal, overlap, percent,stream
                        pointPadding: 0.1, //size de colunma
                        borderWidth: 0 //borde de columna
                    },
                    column: {
                        dataLabels: {
                            enabled: true,
                            //inside: true,
                            style: {
                                fontWeight: 'normal',
                                fontSize: '10px',
                                //color: 'white',
                                //textShadow:false,//quita sombra//para versiones antiguas
                                textOutline: false, //quita sombra
                            }
                        }
                    },
                },
                legend: {
                    itemStyle: {
                        //color: "#333333",
                        //cursor: "pointer",
                        fontSize: "10px",
                        //fontWeight: "normal",
                        //textOverflow: "ellipsis"
                    },
                },
                series: series,
                credits: {
                    enabled: false
                },
            });
        }
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
