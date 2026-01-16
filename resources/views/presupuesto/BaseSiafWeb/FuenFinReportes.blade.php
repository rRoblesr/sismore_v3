@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => ''])

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Actualizar</button>
                    </div>
                    <h3 class="card-title text-white font-14">EJECUCIÓN PRESUPUESTAL POR FUENTE DE FINANCIAMIENTO Y RUBRO
                    </h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-3 col-md-3 col-sm-4">
                            <h4 class="page-title font-12 m-0">Fuente: SIAF-MEF <br>{{ $actualizado }}</h4>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="anio">Año</label>
                                <select id="anio" name="anio" class="form-control font-11">
                                    @foreach ($anios as $item)
                                        <option value="{{ $item->anio }}" {{ $item->anio == $aniomax ? 'selected' : '' }}>
                                            {{ $item->anio }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="ue">Unidad Ejecutora</label>
                                <select id="ue" name="ue" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="cg">Categoria de Gasto</label>
                                <select id="cg" name="cg" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-32 col-sm-2">
                            <div class="custom-select-container">
                                <label for="g">Generica</label>
                                <select id="g" name="g" class="form-control font-11">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-12 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div class="col-md-12">
                        <div id="pie-chart">
                            <div id="anal1" class="flot-chart" style="height: 320px">
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-sm-6">
                                <small class="text-muted"> PIM <span id="anal1_anio_pim"></span></small>
                                <h5 class="my-1"><span id="anal1_pim" data-plugin="counterup">0</span></h5>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">DEVENGADO <span id="anal1_anio_dev"></span></small>
                                <h5 class="my-1"><span id="anal1_devengado" data-plugin="counterup">0</span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-8">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="text-black text-center font-weight-normal font-12 m-0"></h3>
                </div>
                <div class="card-body p-0">
                    <div class="col-md-12">
                        <div id="anal2" style="height: 370px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla1')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">
                        Ejecución del Gasto Presupuestal por Fuente de Financiamiento
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla1">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla2')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">
                        Ejecución del Gasto Presupuestal por Rubro de Financiamiento
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla2">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetalle" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetalleLabel">Detalle Mensualizado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="ctabla0101">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetalle2" tabindex="-1" role="dialog" aria-labelledby="modalDetalleLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetalle2Label">Detalle Mensualizado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="ctabla0201">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var select_cp = 0;
        var select_rb = 0;
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        const spinners = {
            anal1: ['#anal1'],
            anal2: ['#anal2'],
            tabla1: ['#ctabla1'],
            tabla0101: ['#ctabla0101'],
            tabla2: ['#ctabla2'],
            tabla0201: ['#ctabla0201'],
        };
        // Opciones adicionales
        const opciones = {
            yAxisTitle: '',
            colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
            // colors: ['#43beac', '#ef5350'],
            subtitle: ''
        };

        $(document).ready(function() {
            Object.keys(spinners).forEach(key => {
                SpinnerManager.show(key);
            });
            $('#anio').on('change', function() {
                cargarEjecutora();
            });
            $('#ue').on('change', function() {
                cargarGasto();
            });
            $('#cg').on('change', function() {
                cargarPresupuesto();
            });
            $('#g').on('change', function() {
                cargarCards();
            });

            cargarEjecutora();
        });

        function cargarCards() {
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('presupuesto.rubro.reportes.reporte') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "ue": $('#ue').val(),
                    "cg": $('#cg').val(),
                    "g": $('#g').val(),
                    "ff": select_cp,
                    "rb": select_rb,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    SpinnerManager.show(div);
                },
                success: function(data) {
                    switch (div) {
                        case 'anal1':
                            gDonut1(div, data, {
                                title: 'Ejecución del Gasto Presupuestal por Fuente de Financiamiento'
                            });
                            $('#anal1_pim').text(new Intl.NumberFormat('en-US').format(data.pim)).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            $('#anal1_devengado').text(new Intl.NumberFormat('en-US').format(data.devengado))
                                .counterUp({
                                    delay: 10,
                                    time: 1000
                                });
                            $('#anal1_anio_pim').text($('#anio').val());
                            $('#anal1_anio_dev').text($('#anio').val());
                            break;

                        case 'anal2':
                            gAnidadaColumn2(
                                div,
                                data.info.categorias,
                                data.info.series, {
                                    subtitle: 'Ejecución del Gasto Presupuestal por Fuente de Financiamiento',
                                    primaryAxis: {
                                        title: '',
                                        unit: '',
                                        decimals: 0
                                    },
                                    secondaryAxis: {
                                        title: '',
                                        unit: '%',
                                        decimals: 1,
                                        min: -300,
                                        max: 120
                                    }
                                }
                            );
                            break;

                        case 'tabla1':
                            $('#ctabla1').html(data.excel);
                            $('#tabla1').DataTable({
                                "ordering": true,
                                "paging": false,
                                "searching": false,
                                "info": false,
                            });
                            break;

                        case 'tabla0101':
                            $('#modalDetalleLabel').html(data.nombre);
                            $('#ctabla0101').html(data.excel);
                            break;

                        case 'tabla2':
                            $('#ctabla2').html(data.excel);
                            $('#tabla2').DataTable({
                                "ordering": true,
                                "paging": false,
                                "searching": false,
                                "info": false,
                            });
                            break;

                        case 'tabla0201':
                            $('#modalDetalle2Label').html(data.nombre);
                            $('#ctabla0201').html(data.excel);
                            break;
                        default:
                            break;
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargarModalDetalle(id) {
            select_cp = id;
            panelGraficas('tabla0101');
            $('#modalDetalle').modal('show');
        }

        function cargarModalDetalle2(id) {
            select_rb = id;
            panelGraficas('tabla0201');
            $('#modalDetalle2').modal('show');
        }

        function cargarEjecutora() {
            $.ajax({
                url: "{{ route('presupuesto.saifweb.detalle.select.ue', ['anio' => ':anio']) }}"
                    .replace(':anio', $('#anio').val()),
                type: 'GET',
                success: function(data) {
                    $('#ue').empty();
                    if (Object.keys(data).length > 1)
                        $('#ue').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#ue').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarGasto();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarGasto() {
            $.ajax({
                url: "{{ route('presupuesto.saifweb.detalle.select.cg', ['anio' => ':anio', 'ue' => ':ue']) }}"
                    .replace(':anio', $('#anio').val())
                    .replace(':ue', $('#ue').val()),
                type: 'GET',
                success: function(data) {
                    $('#cg').empty();
                    if (Object.keys(data).length > 1)
                        $('#cg').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#cg').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarPresupuesto();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarPresupuesto() {
            $.ajax({
                url: "{{ route('presupuesto.saifweb.detalle.select.g', ['anio' => ':anio', 'ue' => ':ue', 'cg' => ':cg']) }}"
                    .replace(':anio', $('#anio').val())
                    .replace(':ue', $('#ue').val())
                    .replace(':cg', $('#cg').val()),
                type: 'GET',
                success: function(data) {
                    $('#g').empty();
                    if (Object.keys(data).length > 1)
                        $('#g').append('<option value="0">TODOS</option>');
                    $.each(data, function(index, value) {
                        $('#g').append(`<option value='${index}'>${value}</option>`);
                    });
                    cargarCards();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function descargarExcel(div) {
            window.open(
                "{{ route('presupuesto.FuenFin.reportes.download.excel', ['div' => ':div', 'anio' => ':anio', 'ue' => ':ue', 'cg' => ':cg', 'g' => ':g', 'ff' => ':ff', 'rb' => ':rb']) }}"
                .replace(':div', div)
                .replace(':anio', $('#anio').val())
                .replace(':ue', $('#ue').val())
                .replace(':cg', $('#cg').val())
                .replace(':g', $('#g').val())
                .replace(':ff', select_cp)
                .replace(':rb', select_rb)
            );
        }

        function gDonut1(div, data, options = {}) {
            const height = options.height || 320;
            const innerSize = options.innerSize || '75%';
            const subtitleY = options.subtitleY !== undefined ? options.subtitleY : 18;
            const titleFontSize = options.titleFontSize || '13px';
            const percentFontSize = options.percentFontSize || '24px';
            const labelFontSize = options.labelFontSize || '14px';
            const titleText = options.title || 'Avance de la Ejecución';

            let color = '#ef5350';
            if (data.avance > 95) {
                color = '#43beac';
            } else if (data.avance > 50) {
                color = '#ffc107';
            }

            Highcharts.chart(div, {
                chart: {
                    type: 'pie',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    height: height,
                },
                title: {
                    text: titleText,
                    style: {
                        fontSize: titleFontSize,
                        fontWeight: 'normal'
                    }
                },
                subtitle: {
                    text: `<div style="text-align: center;">
                        <span class="counter" style="font-size: ${percentFontSize}; color: #000; font-weight: bold">${data.avance}</span><span style="font-size: ${percentFontSize}; color: #000; font-weight: bold">%</span><br>
                        <span style="font-size: ${labelFontSize}; color: #666">Ejecución</span>
                    </div>`,
                    align: 'center',
                    verticalAlign: 'middle',
                    y: subtitleY,
                    useHTML: true
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false,
                        },
                        showInLegend: false
                    }
                },
                series: [{
                    name: 'Porcentaje',
                    colorByPoint: true,
                    innerSize: innerSize,
                    data: [{
                        name: 'Ejecución',
                        y: data.avance,
                        color: color
                    }, {
                        name: 'Pendiente',
                        y: 100 - data.avance,
                        color: '#eeeeee'
                    }]
                }],
                exporting: {
                    enabled: false
                },
                credits: {
                    enabled: false
                }
            });

            $(`#${div} .counter`).counterUp({
                delay: 10,
                time: 1000
            });
        }

        function formatNumberAbbr(num, decimals = 1) {
            if (num === null || num === undefined || isNaN(num)) return '0';
            if (Math.abs(num) < 1000) return num.toString();
            const units = ['', 'K', 'M', 'G', 'T'];
            let unitIndex = 0;
            let scaled = num;
            while (Math.abs(scaled) >= 1000 && unitIndex < units.length - 1) {
                scaled /= 1000;
                unitIndex++;
            }
            let str = scaled.toFixed(decimals);
            if (str.endsWith('.0')) str = str.slice(0, -2);
            return str + units[unitIndex];
        }

        function gAnidadaColumn2(div, categories, series, options = {}) {
            const defaultOptions = {
                title: '',
                subtitle: '',
                primaryAxis: {
                    title: '',
                    unit: '',
                    decimals: 1, // ahora 1 por defecto (mejor para abreviaturas)
                    enabledTitle: false,
                    max: null,
                    paddingMax: 0.2,
                },
                secondaryAxis: {
                    title: '',
                    unit: '',
                    decimals: 1,
                    min: -300,
                    max: 120,
                    enabledTitle: false,
                },
                tooltip: {
                    shared: true,
                    valueDecimalsPrimary: 1,
                    valueDecimalsSecondary: 1,
                    useThousandsSeparator: true,
                },
                colors: ['#5eb9aa', '#ef5350', '#f5bd22', '#7e85f2', '#ff9800', '#9c27b0'],
                legendFontSize: '12px',
                dataLabels: {
                    enabled: true,
                    fontSize: '11px',
                    fontWeight: 'normal',
                },
                zoomType: 'xy',
                creditsEnabled: false,
                exportingEnabled: true,
            };

            const opts = {
                ...defaultOptions,
                ...options
            };

            let maxPrimary = opts.primaryAxis.max;
            if (maxPrimary === null || maxPrimary <= 0) {
                const primaryData = series
                    .filter(s => s.yAxis === 0 && Array.isArray(s.data))
                    .flatMap(s => s.data)
                    .filter(val => typeof val === 'number' && !isNaN(val));
                const maxValue = primaryData.length ? Math.max(...primaryData) : 0;
                maxPrimary = maxValue * (1 + opts.primaryAxis.paddingMax);
            }

            Highcharts.chart(div, {
                chart: {
                    zoomType: opts.zoomType,
                    style: {
                        fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
                    }
                },
                colors: opts.colors,
                title: {
                    text: opts.title,
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: opts.subtitle,
                    style: {
                        fontSize: '12px',
                        color: '#666'
                    }
                },
                xAxis: [{
                    categories: categories,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                }],
                yAxis: [{ // Primary yAxis
                        title: {
                            text: opts.primaryAxis.title,
                            style: {
                                fontSize: '11px'
                            },
                            enabled: opts.primaryAxis.enabledTitle
                        },
                        labels: {
                            enabled: true,
                            style: {
                                fontSize: '10px'
                            },
                            formatter: function() {
                                const formatted = formatNumberAbbr(this.value, opts.primaryAxis.decimals);
                                return opts.primaryAxis.unit ? `${formatted} ${opts.primaryAxis.unit}` :
                                    formatted;
                            }
                        },
                        max: maxPrimary,
                        gridLineWidth: 1
                    },
                    { // Secondary yAxis
                        title: {
                            text: opts.secondaryAxis.title,
                            style: {
                                fontSize: '11px'
                            },
                            enabled: opts.secondaryAxis.enabledTitle
                        },
                        labels: {
                            enabled: true,
                            style: {
                                fontSize: '10px'
                            },
                            enabled: opts.secondaryAxis.enabledTitle,
                            formatter: function() {
                                return opts.secondaryAxis.unit ?
                                    Highcharts.numberFormat(this.value, opts.secondaryAxis.decimals) + ' ' +
                                    opts.secondaryAxis.unit :
                                    Highcharts.numberFormat(this.value, opts.secondaryAxis.decimals);
                            }
                        },
                        min: opts.secondaryAxis.min,
                        max: opts.secondaryAxis.max,
                        opposite: true,
                        gridLineWidth: 0
                    }
                ],
                series: series,
                plotOptions: {
                    series: {
                        showInLegend: true,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: opts.dataLabels.enabled,
                            style: {
                                fontSize: opts.dataLabels.fontSize,
                                fontWeight: opts.dataLabels.fontWeight,
                                textOutline: 'none'
                            },
                            formatter: function() {
                                const axisIndex = this.series.yAxis.index;
                                if (axisIndex === 0) {
                                    const formatted = formatNumberAbbr(this.y, opts.primaryAxis.decimals);
                                    return opts.primaryAxis.unit ? `${formatted} ${opts.primaryAxis.unit}` :
                                        formatted;
                                } else {
                                    const val = Highcharts.numberFormat(this.y, opts.secondaryAxis.decimals);
                                    return opts.secondaryAxis.unit ? `${val} ${opts.secondaryAxis.unit}` : val;
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    shared: opts.tooltip.shared,
                    useHTML: true,
                    backgroundColor: 'rgba(255,255,255,0.95)',
                    borderColor: '#ddd',
                    borderRadius: 4,
                    shadow: true,
                    style: {
                        fontSize: '11px'
                    },
                    headerFormat: '<b>{point.key}</b><br/>',
                    pointFormatter: function() {
                        const axisIndex = this.series.yAxis.index;
                        let valueStr;
                        let suffix = '';

                        if (axisIndex === 0) {
                            // Primary Axis: Full number
                            valueStr = Highcharts.numberFormat(this.y, opts.primaryAxis.decimals, '.', ',');
                            suffix = opts.primaryAxis.unit ? ` ${opts.primaryAxis.unit}` : '';
                        } else {
                            // Secondary Axis: Percentage
                            valueStr = Highcharts.numberFormat(this.y, opts.tooltip.valueDecimalsSecondary, '.',
                                ',');
                            // Percentage without space
                            suffix = opts.secondaryAxis.unit === '%' ? '%' : (opts.secondaryAxis.unit ?
                                ` ${opts.secondaryAxis.unit}` : '');
                        }

                        return `<span style="color:${this.color}">\u25CF</span> ${this.series.name}: <b>${valueStr}${suffix}</b><br/>`;
                    }
                },
                legend: {
                    itemStyle: {
                        cursor: 'pointer',
                        fontSize: opts.legendFontSize,
                        fontWeight: 'normal',
                        textOverflow: 'ellipsis'
                    },
                    itemHoverStyle: {
                        color: '#000'
                    }
                },
                exporting: {
                    enabled: opts.exportingEnabled,
                    buttons: {
                        contextButton: {
                            menuItems: ['viewFullscreen', 'printChart', 'separator', 'downloadPNG', 'downloadJPEG',
                                'downloadPDF', 'downloadSVG'
                            ]
                        }
                    }
                },
                credits: {
                    enabled: opts.creditsEnabled
                }
            });
        }
    </script>

    {{-- jrmt-mapero --}}
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>

    <script src="{{ asset('/') }}public/us-ct-ally.js"></script>
    <script src="{{ asset('/') }}public/us-ct-allz.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
@endsection
