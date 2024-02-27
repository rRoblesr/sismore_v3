<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- App favicon -->
    <link rel="shortcut icon" href="http://localhost/_laravel/git/sismore_v3/public/assets/images/favicon.ico">
    <!-- Plugins css-->
    <link href="http://localhost/_laravel/git/sismore_v3/public/assets/libs/sweetalert2/sweetalert2.min.css"
        rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="http://localhost/_laravel/git/sismore_v3/public/assets/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" id="bootstrap-stylesheet" />
    <link href="http://localhost/_laravel/git/sismore_v3/public/assets/css/icons.min.css" rel="stylesheet"
        type="text/css" />

    <link href="http://localhost/_laravel/git/sismore_v3/public/assets/css/app.min.css" rel="stylesheet" type="text/css"
        id="app-stylesheet" />
    <!-- estilos personalizados XD-->
    <link rel="stylesheet" href="http://localhost/_laravel/git/sismore_v3/public/assets/css/otros/personalizado.css"
        type='text/css'>
    <link rel="stylesheet"
        href="http://localhost/_laravel/git/sismore_v3/public/assets/css/otros/pretty-checkbox.min.css" type='text/css'>


</head>

<body>

    <div class="row">

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success bg-transparent pb-0 pt-2">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <figure class="highcharts-figure p-0">
                        <div id="gra01" style="height: 20rem"></div>
                    </figure>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success bg-transparent pb-0 pt-2">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <figure class="highcharts-figure p-0">
                        <div id="gra02" style="height: 20rem"></div>
                    </figure>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success bg-transparent pb-0 pt-2">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <figure class="highcharts-figure p-0">
                        <div id="gra03" style="height: 20rem"></div>
                    </figure>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success bg-transparent pb-0 pt-2">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <figure class="highcharts-figure p-0">
                        <div id="gra04" style="height: 20rem"></div>
                    </figure>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success bg-transparent pb-0 pt-2">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <figure class="highcharts-figure p-0">
                        <div id="gra05" style="height: 20rem"></div>
                    </figure>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success bg-transparent pb-0 pt-2">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <figure class="highcharts-figure p-0">
                        <div id="gra06" style="height: 20rem"></div>
                    </figure>
                </div>
            </div>
        </div>

    </div>


    <div class="row">

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success bg-transparent pb-0 pt-2">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <figure class="highcharts-figure p-0">
                        <div id="gra07" style="height: 20rem"></div>
                    </figure>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success bg-transparent pb-0 pt-2">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <figure class="highcharts-figure p-0">
                        <div id="gra08" style="height: 20rem"></div>
                    </figure>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script type="text/javascript">
        // showInLegend: false,//quita leyenda de la serie
        /* bar01('gra01');
        bar02('gra02');
        bar03('gra03');
        bar04('gra04');
        bar05('gra05');
        bar06('gra06');
        bar07('gra07');
        bar08('gra08');
        bar09('gra09');
        bar10('gra10'); */

        column01('gra01');
        column02('gra02');
        column03('gra03');
        column04('gra04');
        column05('gra05');
        column06('gra06');
        column07('gra07');
        column08('gra08');
        column09('gra09');
        column10('gra10');

        /* Highcharts.chart('gra01', {
            chart: {
                type: 'column'
            },

            title: {
                text: 'Olympic Games all-time medal table, grouped by continent',
                align: 'left'
            },

            xAxis: {
                categories: ['Gold', 'Silver', 'Bronze']
            },

            yAxis: {
                allowDecimals: false,
                min: 0,
                title: {
                    text: 'Count medals'
                }
            },

            tooltip: {
                format: '<b>{key}</b><br/>{series.name}: {y}<br/>' +
                    'Total: {point.stackTotal}'
            },

            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            },

            series: [{
                name: 'Norway',
                data: [148, 133, 124],
                stack: 'Europe'
            }, {
                name: 'Germany',
                data: [102, 98, 65],
                stack: 'Europe'
            }, {
                name: 'United States',
                data: [113, 122, 95],
                stack: 'North America'
            }, {
                name: 'Canada',
                data: [77, 72, 80],
                stack: 'North America'
            }]
        }); */

        function gSimpleColumn(div, datax, titulo, subtitulo, tituloserie) {

            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    enabled: false,
                    text: titulo,
                },
                subtitle: {
                    enabled: false,
                    //text: subtitulo,
                },
                xAxis: {
                    type: 'category',
                },
                yAxis: {
                    /* max: 100, */
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    }
                },
                /* colors: [
                    '#8085e9',
                    '#2b908f',
                ], */
                series: [{
                    showInLegend: tituloserie != '',
                    name: tituloserie,
                    label: {
                        enabled: false
                    },
                    colorByPoint: false,
                    data: datax,
                }],
                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                        },
                    }
                },
                exporting: {
                    enabled: false
                },
                credits: false,
            });
        }

        function gPie(div, datos, titulo, subtitulo, tituloserie) {
            Highcharts.chart(div, {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    enabled: false,
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    enabled: false,
                    //text: subtitulo,
                },
                tooltip: {
                    //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            connectorColor: 'silver'
                        }
                    }
                },
                /* plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            format: '{point.percentage:.1f}% ({point.y})',
                            connectorColor: 'silver'
                        }
                    }
                }, */
                series: [{
                    showInLegend: true,
                    //name: 'Share',
                    data: datos,
                }],
                exporting: {
                    enabled: false
                },
                credits: false,
            });
        }

        function pie01(div) {
            // Data retrieved from https://netmarketshare.com/
            // Make monochrome colors
            const colors = Highcharts.getOptions().colors.map((c, i) =>
                // Start out with a darkened base color (negative brighten), and end
                // up with a much brighter color
                Highcharts.color(Highcharts.getOptions().colors[0])
                .brighten((i - 3) / 7)
                .get()
            );

            // Build the chart
            Highcharts.chart(div, {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Browser market shares in February, 2022',
                    align: 'left'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        colors,
                        borderRadius: 5,
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b><br>{point.percentage:.1f} %',
                            distance: -50,
                            filter: {
                                property: 'percentage',
                                operator: '>',
                                value: 4
                            }
                        }
                    }
                },
                series: [{
                    name: 'Share',
                    data: [{
                            name: 'Chrome',
                            y: 74.03
                        },
                        {
                            name: 'Edge',
                            y: 12.66
                        },
                        {
                            name: 'Firefox',
                            y: 4.96
                        },
                        {
                            name: 'Safari',
                            y: 2.49
                        },
                        {
                            name: 'Internet Explorer',
                            y: 2.31
                        },
                        {
                            name: 'Other',
                            y: 3.398
                        }
                    ]
                }]
            });
        }

        function gBasicColumn(div, categorias, datos, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                xAxis: {
                    categories: categorias,
                },
                yAxis: {

                    min: 0,
                    title: {
                        text: 'Rainfall (mm)',
                        enabled: false
                    }
                },

                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                        },
                    }
                },
                series: datos,
                credits: false,
            });
        }

        function gsemidona(div, valor, colors) {
            Highcharts.chart(div, {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: 0,
                    plotShadow: false,
                    height: 200,
                },
                title: {
                    text: valor + '%', // 'Browser<br>shares<br>January<br>2022',
                    align: 'center',
                    verticalAlign: 'middle',
                    y: 15, //60,
                    style: {
                        //fontWeight: 'bold',
                        //color: 'orange',
                        fontSize: '30'
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            enabled: true,
                            distance: -50,
                            style: {
                                fontWeight: 'bold',
                                color: 'white'
                            },

                        },
                        startAngle: -90,
                        endAngle: 90,
                        center: ['50%', '50%'], //['50%', '75%'],
                        size: '120%',
                        borderColor: '#98a6ad',
                        colors: colors,
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Avance',
                    innerSize: '65%',
                    data: [
                        ['', valor],
                        //['Edge', 11.97],
                        //['Firefox', 5.52],
                        //['Safari', 2.98],
                        //['Internet Explorer', 1.90],
                        {
                            name: '',
                            y: 100 - valor,
                            dataLabels: {
                                enabled: false
                            }
                        }
                    ]
                }],
                exporting: {
                    enabled: false
                },
                credits: false
            });
        }

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo, maxBar) {
            var rango = categoria.length;
            var posPorcentaje = rango * 2 + 1;
            var cont = 0;
            var porMaxBar = maxBar * 0.5;
            console.log(porMaxBar);
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true,
                }],
                yAxis: [{ // Primary yAxis
                        max: maxBar > 0 ? maxBar + porMaxBar : null,
                        labels: {
                            enabled: false,
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
                            text: 'Numerador y Denomidor',
                            //style: {
                            //    color: Highcharts.getOptions().colors[2]
                            //}
                        },
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
                            //text: 'Rainfall',
                            text: '%Indicador',
                            //style: {
                            //    color: Highcharts.getOptions().colors[0]
                            //}
                        },
                        labels: {
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
                                cont++;
                                //console.log(cont);
                                //console.log(div + " - " + this.points);
                                /* if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else if (this.y < 101) {
                                    return this.y + "%";
                                } else {
                                    return this.y;
                                } */
                                if (cont >= posPorcentaje)
                                    return this.y + " %";
                                else
                                    return Highcharts.numberFormat(this.y, 0);
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

        function gAnidadaColumnxx(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true
                }],
                yAxis: [{ // Primary yAxis
                        //max: 2000000000,
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
                        min: -200,
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
                        //showInLegend: false,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            /* formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else if (this.y < 101) {
                                    return this.y + "%";
                                } else {
                                    return this.y;
                                }
                            }, */
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
                        //"color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal",
                        "textOverflow": "ellipsis"
                    },
                },
                exporting: {
                    enabled: false
                },
                credits: false,
            });
        }

        function linea01(div) {
            Highcharts.chart(div, {

                chart: {
                    styledMode: true
                },

                title: {
                    text: 'Styling data labels by CSS'
                },

                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr']
                },

                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            borderRadius: 2,
                            y: -10,
                            shape: 'callout'
                        }
                    }
                },

                series: [{
                    data: [{
                        y: 100,
                        dataLabels: {
                            align: 'right',
                            rotation: 45,
                            shape: null
                        }
                    }, {
                        y: 300
                    }, {
                        y: 500,
                        dataLabels: {
                            className: 'highlight'
                        }
                    }, {
                        y: 400
                    }]
                }]

            });
        }

        function linea02(div) {
            Highcharts.chart(div, {
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },

                plotOptions: {
                    series: {
                        dataLabels: {
                            align: 'left',
                            enabled: true
                        }
                    }
                },

                series: [{
                    data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
                }]
            });
        }

        function linea03(div) {
            Highcharts.chart(div, {

                chart: {
                    styledMode: true
                },

                title: {
                    text: 'Styling data labels by CSS'
                },

                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr']
                },

                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            borderRadius: 2,
                            y: -10,
                            shape: 'callout'
                        }
                    }
                },

                series: [{
                    data: [{
                        y: 100,
                        dataLabels: {
                            align: 'right',
                            rotation: 45,
                            shape: null
                        }
                    }, {
                        y: 300
                    }, {
                        y: 500,
                        dataLabels: {
                            className: 'highlight'
                        }
                    }, {
                        y: 400
                    }]
                }]

            });
        }

        function linea04(div) {
            Highcharts.chart(div, {

                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },

                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '{y} mm'
                        }
                    }
                },

                series: [{
                    data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
                }]
            });
        }

        function linea05(div) {
            Highcharts.chart(div, {

                title: {
                    text: 'Format subexpressions'
                },

                subtitle: {
                    text: 'Conversion from Celsius to Fahrenheit'
                },

                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul',
                        'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ]
                },

                yAxis: {
                    title: {
                        text: 'Degrees Celsius'
                    }
                },

                plotOptions: {
                    series: {
                        dataLabels: [{
                            enabled: true,
                            format: '{(add (multiply y (divide 9 5)) 32):.1f}℉',
                            style: {
                                fontWeight: 'normal'
                            }
                        }, {
                            enabled: true,
                            verticalAlign: 'top',
                            format: '{y}℃',
                            style: {
                                fontWeight: 'normal'
                            }
                        }]
                    }
                },

                series: [{
                    name: 'Temperature',
                    type: 'spline',
                    data: [-13.6, -14.9, -5.8, -0.7, 3.1, 13.0, 14.5, 10.8, 5.8,
                        -0.7, -11.0, -16.4
                    ],
                    tooltip: {
                        valueSuffix: '°C'
                    }
                }]
            });
        }

        function linea06(div) {
            Highcharts.chart(div, {
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },

                plotOptions: {
                    series: {
                        dataLabels: {
                            align: 'left',
                            enabled: true,
                            rotation: 270,
                            x: 2,
                            y: -10
                        }
                    }
                },

                series: [{
                    data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
                }]
            });
        }

        function gbar(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: titulo, // 'Historic World Population by Region'
                },
                subtitle: {
                    text: subtitulo,
                    /*  'Source: <a ' +
                                            'href="https://en.wikipedia.org/wiki/List_of_continents_and_continental_subregions_by_population"' +
                                            'target="_blank">Wikipedia.org</a>' */
                },
                xAxis: {
                    //categories:categoria,// ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
                    type: "category",
                    title: {
                        text: '', // null
                    },
                    enabled: false,
                },
                yAxis: {
                    //min: 0,
                    title: {
                        text: '', // 'Population (millions)',
                        align: 'high'
                    },
                    /* labels: {
                        overflow: 'justify'
                    } */
                },
                tooltip: {
                    valueSuffix: ' %'
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}',
                            //format: '{point.y} %'//
                        }
                    }
                },
                legend: {
                    enabled: false, //
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 80,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                    shadow: true
                },

                //series: series,
                /*  [{
                                    name: 'Year 1990',
                                    data: [631, 727, 3202, 721, 26]
                                }, {
                                    name: 'Year 2000',
                                    data: [814, 841, 3714, 726, 31]
                                }, {
                                    name: 'Year 2010',
                                    data: [1044, 944, 4170, 735, 40]
                                }, {
                                    name: 'Year 2018',
                                    data: [1276, 1007, 4561, 746, 42]
                                }] */
                /* showInLegend: tituloserie != '',
                        name: tituloserie,
                        label: {
                            enabled: false
                        },
                        colorByPoint: false, */
                series: [{
                    name: 'Ejecución',
                    showInLegend: false,
                    label: {
                        enabled: false
                    },
                    data: series,
                    /* [{
                                                name: "Chrome",
                                                y: 63.06,
                                            },
                                            {
                                                name: "Safari",
                                                y: 19.84,
                                            },
                                            {
                                                name: "Firefox",
                                                y: 4.18,
                                            },
                                            {
                                                name: "Edge",
                                                y: 4.12,
                                            },
                                            {
                                                name: "Opera",
                                                y: 2.33,
                                            },
                                            {
                                                name: "Internet Explorer",
                                                y: 0.45,
                                            },
                                            {
                                                name: "Other",
                                                y: 1.582,
                                            }
                                        ] */
                }],
                credits: {
                    enabled: false
                },
            });
        }

        function bar01(div) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar',
                    marginLeft: 50,
                    marginBottom: 90
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },

                plotOptions: {
                    series: {
                        stacking: 'percent'
                    }
                },

                series: [{
                    data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
                }, {
                    data: [144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4, 29.9, 71.5, 106.4, 129.2]
                }]
            });
        }

        function bar02(div) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Historic World Population by Region',
                    align: 'left'
                },
                subtitle: {
                    text: 'Source: <a ' +
                        'href="https://en.wikipedia.org/wiki/List_of_continents_and_continental_subregions_by_population"' +
                        'target="_blank">Wikipedia.org</a>',
                    align: 'left'
                },
                xAxis: {
                    categories: ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
                    title: {
                        text: null
                    },
                    gridLineWidth: 1,
                    lineWidth: 0
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Population (millions)',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    },
                    gridLineWidth: 0
                },
                tooltip: {
                    valueSuffix: ' millions'
                },
                plotOptions: {
                    bar: {
                        borderRadius: '50%',
                        dataLabels: {
                            enabled: true,
                            inside: true,
                        },
                        groupPadding: 0.1
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 80,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                    shadow: true
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Year 1990',
                    data: [631, 727, 3202, 721, 26]
                }, {
                    name: 'Year 2000',
                    data: [814, 841, 3714, 726, 31]
                }, {
                    name: 'Year 2010',
                    data: [1044, 944, 4170, 735, 40]
                }, {
                    name: 'Year 2018',
                    data: [1276, 1007, 4561, 746, 42]
                }]
            });
        }

        function bar03(div) {
            Highcharts.chart(div, {

                chart: {
                    type: 'bar'
                },

                title: {
                    text: 'Multiple datalabels per point'
                },

                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            inside: true,
                            style: {
                                fontWeight: 'normal'
                            }
                        }
                    }
                },

                xAxis: {
                    type: 'category',
                    lineWidth: 0, //control de la linea 
                    tickWidth: 0 //control del tamaño de lalinea
                },

                yAxis: {
                    title: {
                        text: ''
                    }
                },

                series: [{
                    dataLabels: [{
                        align: 'left',
                        format: '({point.age})'
                    }, {
                        align: 'right',
                        format: '{y} points'
                    }],
                    data: [{
                        y: 123,
                        name: 'Gabriel',
                        age: 12,
                        dataLabels: {
                            color: '#ec0000'
                        }
                    }, {
                        y: 121,
                        name: 'Marie',
                        age: 14,
                        group: ''
                    }, {
                        y: 111,
                        name: 'Adam',
                        age: 13
                    }, {
                        y: 127,
                        name: 'Camille',
                        age: 11
                    }, {
                        y: 116,
                        name: 'Paul',
                        age: 12
                    }, {
                        y: 119,
                        name: 'Laura',
                        age: 14
                    }, {
                        y: 124,
                        name: 'Louis',
                        age: 14
                    }],
                    showInLegend: false
                }]

            });
        }

        function bar04(div) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar'
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },

                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            align: 'right',
                            color: '#FFFFFF',
                            x: -10,
                            borderWidth: 0,
                            style: {
                                fontWeight: 'normal'
                            }
                        },
                        pointPadding: 0.1,
                        groupPadding: 0
                    }
                },

                series: [{
                    data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
                }]
            });
        }

        function bar05(div) {
            Highcharts.chart(div, {

                title: {
                    text: 'Animation defer options'
                },

                plotOptions: {
                    column: {
                        stacking: 'normal',
                        animation: {
                            defer: 2000
                        }
                    },
                    series: {
                        // series labels will be shown after defer set in series.animation
                        animation: {
                            defer: 4000
                        },
                        dataLabels: {
                            enabled: true,
                            animation: {
                                defer: 6000
                            }
                        }
                    }
                },

                yAxis: {
                    stackLabels: {
                        enabled: true,
                        animation: {
                            defer: 4000
                        }
                    }
                },

                series: [{
                    type: 'column',
                    data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
                }, {
                    type: 'column',
                    data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
                }, {
                    data: [11744, 17722, 16005, 59771, 60185, 74377, 82147, 99387]
                }, {
                    data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
                }]
            });
        }

        function bar06(div) {
            var contador = 0;
            var contador2 = 0;
            var arreglo = [];
            Highcharts.chart(div, {
                colors: ['#01579B', '#FFFF00', '#006064'],
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Ingreso'
                },
                subtitle: {},
                xAxis: {
                    categories: ['Empresa'],
                    title: {
                        text: null
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Toneladas',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                tooltip: {
                    valueSuffix: ' Tons'
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            borderRadius: 1,
                            backgroundColor: '#BDBDBD',
                            borderWidth: 1,
                            borderColor: '#AAA',
                            y: -6
                        }
                    },
                    bar: {
                        dataLabels: {
                            enabled: true,
                            style: {
                                textOutline: false
                            },
                            formatter: function() {
                                var color = 'Black';
                                if (this.series.name == "Cuota Por Hora") {
                                    arreglo[contador] = this.y;
                                    contador = contador + 1;
                                }
                                if (this.series.name == "Cuota Ingresada") {
                                    var meta = arreglo[contador2];
                                    var cuota = this.y;
                                    if (cuota > meta) {
                                        color = 'green';
                                    } else if (cuota < meta / 100 * 80) {
                                        color = 'red';
                                    } else if (cuota < meta) {
                                        color = '#FFD600';
                                    }
                                    contador2 = contador2 + 1;
                                }
                                return '<span style="color: ' + color + '">' + Highcharts.numberFormat(this.y,
                                    2, '.', ',') + ' </span>';
                            },
                        }
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 50,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#ECEFF1'),
                    shadow: true
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Meta Del Día',
                    data: [13900.00]
                }, {
                    name: 'Cuota Por Hora',
                    data: [2316.67]
                }, {
                    name: 'Cuota Ingresada',
                    data: [2049.75]
                }]
            });
        }

        function bar07(div) { // es columna
            Highcharts.chart(div, {

                chart: {
                    type: 'column',
                    //styledMode: true
                },

                title: {
                    text: 'Data labels with contrast'
                },

                subtitle: {
                    text: 'Adjust data label color and shadow to underlying column'
                },

                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },

                series: [{
                    data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                    dataLabels: {
                        enabled: true,
                        inside: true
                    },
                    colorByPoint: true
                }]
            });
        }

        function bar07(div) { //no es bar
            Highcharts.chart(div, {
                chart: {
                    renderTo: "graph",
                    zoomType: "x",
                },
                tooltip: {
                    useHTML: true,
                    split: false,
                    shared: true,
                    backgroundColor: "#FFF",
                    borderColor: "#000",
                    formatter: function() {
                        let start = this.points[0].point.dataGroup.start,
                            length = this.points[0].point.dataGroup.length,
                            data = this.points[0].point.series.options.data,
                            sum = 0;
                        for (let i = start; i < start + length; i++) {
                            sum += data[i].z
                        }

                        return 'Z: ' + sum / length
                    }
                },
                xAxis: {
                    type: "datetime",
                    title: {
                        text: null
                    },
                    labels: {
                        y: 30,
                        useHTML: true,
                    }
                },

                series: [{
                    dataGrouping: {
                        enabled: true,
                        forced: true,
                        groupPixelWidth: 20
                    },
                    keys: ['x', 'y', 'z'],
                    data: [{
                            x: 1589368592000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368593000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368594000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368595000,
                            y: 8,
                            z: 4
                        },
                        {
                            x: 1589368596000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368597000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368598000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368599000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368600000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368601000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368602000,
                            y: 1,
                            z: 3
                        },
                        {
                            x: 1589368603000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368604000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368605000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368606000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368607000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368608000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368609000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368610000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368611000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368612000,
                            y: 6,
                            z: 1
                        },
                        {
                            x: 1589368613000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368614000,
                            y: 3,
                            z: 3
                        },
                        {
                            x: 1589368615000,
                            y: 2,
                            z: 4
                        },
                        {
                            x: 1589368616000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368617000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368618000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368619000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368620000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368621000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368622000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368623000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368624000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368625000,
                            y: 8,
                            z: 2
                        },
                        {
                            x: 1589368626000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368627000,
                            y: 8,
                            z: 4
                        },
                        {
                            x: 1589368628000,
                            y: 3,
                            z: 1
                        },
                        {
                            x: 1589368629000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368630000,
                            y: 3,
                            z: 3
                        },
                        {
                            x: 1589368631000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368632000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368633000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368634000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368635000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368636000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368637000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368638000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368639000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368640000,
                            y: 3,
                            z: 1
                        },
                        {
                            x: 1589368641000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368642000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368643000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368644000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368645000,
                            y: 3,
                            z: 2
                        },
                        {
                            x: 1589368646000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368647000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368648000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368649000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368650000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368651000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368652000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368653000,
                            y: 3,
                            z: 2
                        },
                        {
                            x: 1589368654000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368655000,
                            y: 2,
                            z: 4
                        },
                        {
                            x: 1589368656000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368657000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368658000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368659000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368660000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368661000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368662000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368663000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368664000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368665000,
                            y: 8,
                            z: 2
                        },
                        {
                            x: 1589368666000,
                            y: 5,
                            z: 3
                        },
                        {
                            x: 1589368667000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368668000,
                            y: 5,
                            z: 1
                        },
                        {
                            x: 1589368669000,
                            y: 3,
                            z: 2
                        },
                        {
                            x: 1589368670000,
                            y: 2,
                            z: 3
                        },
                        {
                            x: 1589368671000,
                            y: 4,
                            z: 4
                        },
                        {
                            x: 1589368672000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368673000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368674000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368675000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368676000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368677000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368678000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368679000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368680000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368681000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368682000,
                            y: 5,
                            z: 3
                        },
                        {
                            x: 1589368683000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368684000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368685000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589368686000,
                            y: 2,
                            z: 3
                        },
                        {
                            x: 1589368687000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368688000,
                            y: 3,
                            z: 1
                        },
                        {
                            x: 1589368689000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368690000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368691000,
                            y: 4,
                            z: 4
                        },
                        {
                            x: 1589368692000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368693000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589368694000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368695000,
                            y: 2,
                            z: 4
                        },
                        {
                            x: 1589368696000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368697000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368698000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368699000,
                            y: 8,
                            z: 4
                        },
                        {
                            x: 1589368700000,
                            y: 5,
                            z: 1
                        },
                        {
                            x: 1589368701000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368702000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368703000,
                            y: 2,
                            z: 4
                        },
                        {
                            x: 1589368704000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368705000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368706000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368707000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368708000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368709000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368710000,
                            y: 8,
                            z: 3
                        },
                        {
                            x: 1589368711000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368712000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368713000,
                            y: 8,
                            z: 2
                        },
                        {
                            x: 1589368714000,
                            y: 2,
                            z: 3
                        },
                        {
                            x: 1589368715000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368716000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368717000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368718000,
                            y: 1,
                            z: 3
                        },
                        {
                            x: 1589368719000,
                            y: 8,
                            z: 4
                        },
                        {
                            x: 1589368720000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368721000,
                            y: 1,
                            z: 2
                        },
                        {
                            x: 1589368722000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368723000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368724000,
                            y: 5,
                            z: 1
                        },
                        {
                            x: 1589368725000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368726000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368727000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368728000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368729000,
                            y: 3,
                            z: 2
                        },
                        {
                            x: 1589368730000,
                            y: 5,
                            z: 3
                        },
                        {
                            x: 1589368731000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368732000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368733000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368734000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368735000,
                            y: 8,
                            z: 4
                        },
                        {
                            x: 1589368736000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368737000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368738000,
                            y: 2,
                            z: 3
                        },
                        {
                            x: 1589368739000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368740000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368741000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368742000,
                            y: 2,
                            z: 3
                        },
                        {
                            x: 1589368743000,
                            y: 1,
                            z: 4
                        },
                        {
                            x: 1589368744000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368745000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368746000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368747000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368748000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368749000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368750000,
                            y: 2,
                            z: 3
                        },
                        {
                            x: 1589368751000,
                            y: 4,
                            z: 4
                        },
                        {
                            x: 1589368752000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368753000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368754000,
                            y: 1,
                            z: 3
                        },
                        {
                            x: 1589368755000,
                            y: 8,
                            z: 4
                        },
                        {
                            x: 1589368756000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368757000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368758000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368759000,
                            y: 8,
                            z: 4
                        },
                        {
                            x: 1589368760000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368761000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368762000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368763000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368764000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368765000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368766000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368767000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368768000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368769000,
                            y: 1,
                            z: 2
                        },
                        {
                            x: 1589368770000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368771000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368772000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368773000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368774000,
                            y: 8,
                            z: 3
                        },
                        {
                            x: 1589368775000,
                            y: 1,
                            z: 4
                        },
                        {
                            x: 1589368776000,
                            y: 6,
                            z: 1
                        },
                        {
                            x: 1589368777000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368778000,
                            y: 5,
                            z: 3
                        },
                        {
                            x: 1589368779000,
                            y: 4,
                            z: 4
                        },
                        {
                            x: 1589368780000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368781000,
                            y: 3,
                            z: 2
                        },
                        {
                            x: 1589368782000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368783000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368784000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368785000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368786000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368787000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368788000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368789000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589368790000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368791000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368792000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368793000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368794000,
                            y: 3,
                            z: 3
                        },
                        {
                            x: 1589368795000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368796000,
                            y: 3,
                            z: 1
                        },
                        {
                            x: 1589368797000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368798000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368799000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368800000,
                            y: 6,
                            z: 1
                        },
                        {
                            x: 1589368801000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589368802000,
                            y: 1,
                            z: 3
                        },
                        {
                            x: 1589368803000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368804000,
                            y: 5,
                            z: 1
                        },
                        {
                            x: 1589368805000,
                            y: 3,
                            z: 2
                        },
                        {
                            x: 1589368806000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368807000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368808000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368809000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589368810000,
                            y: 1,
                            z: 3
                        },
                        {
                            x: 1589368811000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368812000,
                            y: 5,
                            z: 1
                        },
                        {
                            x: 1589368813000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368814000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368815000,
                            y: 1,
                            z: 4
                        },
                        {
                            x: 1589368816000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368817000,
                            y: 3,
                            z: 2
                        },
                        {
                            x: 1589368818000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368819000,
                            y: 4,
                            z: 4
                        },
                        {
                            x: 1589368820000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368821000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368822000,
                            y: 5,
                            z: 3
                        },
                        {
                            x: 1589368823000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368824000,
                            y: 3,
                            z: 1
                        },
                        {
                            x: 1589368825000,
                            y: 1,
                            z: 2
                        },
                        {
                            x: 1589368826000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368827000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368828000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368829000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368830000,
                            y: 1,
                            z: 3
                        },
                        {
                            x: 1589368831000,
                            y: 1,
                            z: 4
                        },
                        {
                            x: 1589368832000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368833000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368834000,
                            y: 2,
                            z: 3
                        },
                        {
                            x: 1589368835000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368836000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368837000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368838000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368839000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368840000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368841000,
                            y: 1,
                            z: 2
                        },
                        {
                            x: 1589368842000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368843000,
                            y: 5,
                            z: 4
                        },
                        {
                            x: 1589368844000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368845000,
                            y: 8,
                            z: 2
                        },
                        {
                            x: 1589368846000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368847000,
                            y: 2,
                            z: 4
                        },
                        {
                            x: 1589368848000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368849000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368850000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368851000,
                            y: 4,
                            z: 4
                        },
                        {
                            x: 1589368852000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368853000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368854000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368855000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368856000,
                            y: 5,
                            z: 1
                        },
                        {
                            x: 1589368857000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368858000,
                            y: 5,
                            z: 3
                        },
                        {
                            x: 1589368859000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368860000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368861000,
                            y: 1,
                            z: 2
                        },
                        {
                            x: 1589368862000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368863000,
                            y: 4,
                            z: 4
                        },
                        {
                            x: 1589368864000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368865000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368866000,
                            y: 2,
                            z: 3
                        },
                        {
                            x: 1589368867000,
                            y: 8,
                            z: 4
                        },
                        {
                            x: 1589368868000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368869000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368870000,
                            y: 8,
                            z: 3
                        },
                        {
                            x: 1589368871000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368872000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368873000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368874000,
                            y: 8,
                            z: 3
                        },
                        {
                            x: 1589368875000,
                            y: 8,
                            z: 4
                        },
                        {
                            x: 1589368876000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368877000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589368878000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368879000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368880000,
                            y: 5,
                            z: 1
                        },
                        {
                            x: 1589368881000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368882000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368883000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368884000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368885000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368886000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368887000,
                            y: 2,
                            z: 4
                        },
                        {
                            x: 1589368888000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368889000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368890000,
                            y: 5,
                            z: 3
                        },
                        {
                            x: 1589368891000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368892000,
                            y: 5,
                            z: 1
                        },
                        {
                            x: 1589368893000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368894000,
                            y: 5,
                            z: 3
                        },
                        {
                            x: 1589368895000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368896000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368897000,
                            y: 8,
                            z: 2
                        },
                        {
                            x: 1589368898000,
                            y: 8,
                            z: 3
                        },
                        {
                            x: 1589368899000,
                            y: 8,
                            z: 4
                        },
                        {
                            x: 1589368900000,
                            y: 5,
                            z: 1
                        },
                        {
                            x: 1589368901000,
                            y: 3,
                            z: 2
                        },
                        {
                            x: 1589368902000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368903000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368904000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368905000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589368906000,
                            y: 8,
                            z: 3
                        },
                        {
                            x: 1589368907000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368908000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368909000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368910000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368911000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368912000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368913000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589368914000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368915000,
                            y: 5,
                            z: 4
                        },
                        {
                            x: 1589368916000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368917000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368918000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368919000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368920000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368921000,
                            y: 6,
                            z: 2
                        },
                        {
                            x: 1589368922000,
                            y: 5,
                            z: 3
                        },
                        {
                            x: 1589368923000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368924000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368925000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368926000,
                            y: 8,
                            z: 3
                        },
                        {
                            x: 1589368927000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368928000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368929000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368930000,
                            y: 1,
                            z: 3
                        },
                        {
                            x: 1589368931000,
                            y: 4,
                            z: 4
                        },
                        {
                            x: 1589368932000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368933000,
                            y: 8,
                            z: 2
                        },
                        {
                            x: 1589368934000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368935000,
                            y: 4,
                            z: 4
                        },
                        {
                            x: 1589368936000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368937000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368938000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368939000,
                            y: 2,
                            z: 4
                        },
                        {
                            x: 1589368940000,
                            y: 3,
                            z: 1
                        },
                        {
                            x: 1589368941000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589368942000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589368943000,
                            y: 5,
                            z: 4
                        },
                        {
                            x: 1589368944000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368945000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589368946000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368947000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368948000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368949000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368950000,
                            y: 1,
                            z: 3
                        },
                        {
                            x: 1589368951000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368952000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368953000,
                            y: 3,
                            z: 2
                        },
                        {
                            x: 1589368954000,
                            y: 8,
                            z: 3
                        },
                        {
                            x: 1589368955000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368956000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368957000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589368958000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368959000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368960000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368961000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589368962000,
                            y: 1,
                            z: 3
                        },
                        {
                            x: 1589368963000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589368964000,
                            y: 6,
                            z: 1
                        },
                        {
                            x: 1589368965000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368966000,
                            y: 8,
                            z: 3
                        },
                        {
                            x: 1589368967000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368968000,
                            y: 5,
                            z: 1
                        },
                        {
                            x: 1589368969000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368970000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368971000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368972000,
                            y: 1,
                            z: 1
                        },
                        {
                            x: 1589368973000,
                            y: 8,
                            z: 2
                        },
                        {
                            x: 1589368974000,
                            y: 8,
                            z: 3
                        },
                        {
                            x: 1589368975000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368976000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589368977000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368978000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589368979000,
                            y: 9,
                            z: 4
                        },
                        {
                            x: 1589368980000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589368981000,
                            y: 4,
                            z: 2
                        },
                        {
                            x: 1589368982000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589368983000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589368984000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368985000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368986000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368987000,
                            y: 1,
                            z: 4
                        },
                        {
                            x: 1589368988000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589368989000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368990000,
                            y: 5,
                            z: 3
                        },
                        {
                            x: 1589368991000,
                            y: 2,
                            z: 4
                        },
                        {
                            x: 1589368992000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589368993000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589368994000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589368995000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589368996000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589368997000,
                            y: 2,
                            z: 2
                        },
                        {
                            x: 1589368998000,
                            y: 5,
                            z: 3
                        },
                        {
                            x: 1589368999000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589369000000,
                            y: 9,
                            z: 1
                        },
                        {
                            x: 1589369001000,
                            y: 9,
                            z: 2
                        },
                        {
                            x: 1589369002000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589369003000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589369004000,
                            y: 3,
                            z: 1
                        },
                        {
                            x: 1589369005000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589369006000,
                            y: 4,
                            z: 3
                        },
                        {
                            x: 1589369007000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589369008000,
                            y: 3,
                            z: 1
                        },
                        {
                            x: 1589369009000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589369010000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589369011000,
                            y: 7,
                            z: 4
                        },
                        {
                            x: 1589369012000,
                            y: 2,
                            z: 1
                        },
                        {
                            x: 1589369013000,
                            y: 8,
                            z: 2
                        },
                        {
                            x: 1589369014000,
                            y: 1,
                            z: 3
                        },
                        {
                            x: 1589369015000,
                            y: 4,
                            z: 4
                        },
                        {
                            x: 1589369016000,
                            y: 5,
                            z: 1
                        },
                        {
                            x: 1589369017000,
                            y: 3,
                            z: 2
                        },
                        {
                            x: 1589369018000,
                            y: 7,
                            z: 3
                        },
                        {
                            x: 1589369019000,
                            y: 6,
                            z: 4
                        },
                        {
                            x: 1589369020000,
                            y: 4,
                            z: 1
                        },
                        {
                            x: 1589369021000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589369022000,
                            y: 9,
                            z: 3
                        },
                        {
                            x: 1589369023000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589369024000,
                            y: 8,
                            z: 1
                        },
                        {
                            x: 1589369025000,
                            y: 5,
                            z: 2
                        },
                        {
                            x: 1589369026000,
                            y: 6,
                            z: 3
                        },
                        {
                            x: 1589369027000,
                            y: 3,
                            z: 4
                        },
                        {
                            x: 1589369028000,
                            y: 7,
                            z: 1
                        },
                        {
                            x: 1589369029000,
                            y: 7,
                            z: 2
                        },
                        {
                            x: 1589369030000,
                            y: 4,
                            z: 3
                        }
                    ]
                }]
            });
        }

        function bar08(div) {
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy'
                },
                title: {
                    text: 'Average Monthly Weather Data for Tokyo',
                    align: 'left'
                },
                subtitle: {
                    text: 'Source: WorldClimate.com',
                    align: 'left'
                },
                xAxis: [{
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ],
                    crosshair: true
                }],
                yAxis: [{ // Primary yAxis
                    labels: {
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
                    },
                    opposite: true

                }, { // Secondary yAxis
                    gridLineWidth: 0,
                    title: {
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
                    }

                }, { // Tertiary yAxis
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
                }],
                tooltip: {
                    shared: true
                },
                legend: {
                    layout: 'vertical',
                    align: 'left',
                    x: 80,
                    verticalAlign: 'top',
                    y: 55,
                    floating: true,
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || // theme
                        'rgba(255,255,255,0.25)'
                },
                series: [{
                    name: 'Rainfall',
                    type: 'column',
                    yAxis: 1,
                    data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                    tooltip: {
                        valueSuffix: ' mm'
                    }

                }, {
                    name: 'Sea-Level Pressure',
                    type: 'spline',
                    yAxis: 2,
                    data: [1016, 1016, 1015.9, 1015.5, 1012.3, 1009.5, 1009.6, 1010.2, 1013.1, 1016.9,
                        1018.2, 1016.7
                    ],
                    marker: {
                        enabled: false
                    },
                    dashStyle: 'shortdot',
                    tooltip: {
                        valueSuffix: ' mb'
                    }

                }, {
                    name: 'Temperature',
                    type: 'spline',
                    data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6],
                    tooltip: {
                        valueSuffix: ' °C'
                    }
                }],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                floating: false,
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom',
                                x: 0,
                                y: 0
                            },
                            yAxis: [{
                                labels: {
                                    align: 'right',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                labels: {
                                    align: 'left',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                visible: false
                            }]
                        }
                    }]
                }
            });
        }

        function column01(div) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar'
                },

                /* title: {
                    text: 'Olympic Games all-time medal table, grouped by continent',
                    align: 'left'
                }, */

                xAxis: {
                    categories: ['Gold', 'Silver', 'Bronze']
                },

                yAxis: {
                    allowDecimals: false,
                    min: 0,
                    //max:1000,           
                    title: {
                        text: 'Count medals'
                    }
                },

                tooltip: {
                    format: '<b>{key}</b><br/>{series.name}: {y}<br/>' +
                        'Total: {point.stackTotal}'
                },

                plotOptions: {
                    column: {
                        stacking: 'percent', //stacking: "normal", "overlap", "percent", "stream"
                        //pointPadding: 0.2, //size de colunma
                        //borderWidth: 0    //borde de columna
                    }
                },

                series: [{
                    name: 'Norway',
                    data: [148, 133, 124],
                    stack: 'Europe'
                }, {
                    name: 'Germany',
                    data: [102, 98, 65],
                    stack: 'Europe'
                }, {
                    name: 'United States',
                    data: [113, 122, 95],
                    stack: 'North America'
                }, {
                    name: 'Canada',
                    data: [77, 72, 80],
                    stack: 'North America'
                }]
            });

        }

        function column02(div) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Corn vs wheat estimated production for 2020',
                    align: 'left'
                },
                subtitle: {
                    text: 'Source: <a target="_blank" ' +
                        'href="https://www.indexmundi.com/agriculture/?commodity=corn">indexmundi</a>',
                    align: 'left'
                },
                xAxis: {
                    categories: ['USA', 'China', 'Brazil', 'EU', 'India', 'Russia'],
                    crosshair: true,
                    accessibility: {
                        description: 'Countries'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '1000 metric tons (MT)'
                    }
                },
                tooltip: {
                    valueSuffix: ' (1000 MT)'
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                        name: 'Corn',
                        data: [406292, 260000, 107000, 68300, 27500, 14500]
                    },
                    {
                        name: 'Wheat',
                        data: [51086, 136000, 5500, 141000, 107180, 77000]
                    }, {
                        name: 'Corn2',
                        data: [406292, 260000, 107000, 68300, 27500, 14500]
                    },
                    {
                        name: 'Wheat2',
                        data: [51086, 136000, 5500, 141000, 107180, 77000]
                    }
                ]
            });
        }

        function column03(div) {
            Highcharts.chart(div, {

                chart: {
                    type: 'column'
                },

                title: {
                    text: 'Annual precipitation'
                },

                subtitle: {
                    text: 'Highcharts data labels with callout shape'
                },

                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },

                plotOptions: {
                    series: {
                        dataLabels: {
                            shape: 'callout',
                            backgroundColor: 'rgba(0, 0, 0, 0.75)',
                            style: {
                                color: '#FFFFFF',
                                textOutline: 'none'
                            }
                        }
                    }
                },

                series: [{
                    data: [{
                        y: 29.9,
                        dataLabels: {
                            enabled: true,
                            format: 'January<br><span style="font-size: 1.3em">Dryest</span>',
                            verticalAlign: 'bottom',
                            y: -10
                        }
                    }, {
                        y: 71.5
                    }, {
                        y: 106.4
                    }, {
                        y: 129.2
                    }, {
                        y: 144.0
                    }, {
                        y: 176.0
                    }, {
                        y: 135.6
                    }, {
                        y: 148.5
                    }, {
                        y: 216.4,
                        dataLabels: {
                            enabled: true,
                            format: 'September<br><span style="font-size: 1.3em">Wettest</span>',
                            align: 'right',
                            verticalAlign: 'middle',
                            x: -35

                        }
                    }, {
                        y: 194.1
                    }, {
                        y: 95.6
                    }, {
                        y: 54.4
                    }]
                }]
            });
        }

        function column04(div) {
            Highcharts.chart(div, {
                colors: ['#00ff6c', '#03deec', '#f5940a'],
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'RENDIMIENTO'
                },
                subtitle: {
                    text: 'Acumulado'
                },
                xAxis: {
                    categories: ['190 cantidad: 6', '240 cantidad: 13', '320 cantidad: 5'],
                    crosshair: true,

                    labels: {
                        style: {
                            color: 'networking',
                            fontSize: '15px'
                        }
                    }
                },
                yAxis: [{ // Primary yAxis
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    title: {
                        text: 'Rendimiento (Km)',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    }
                }, { // Secondary yAxis
                    title: {
                        text: '',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value} ',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    opposite: true
                }],
                tooltip: {
                    shared: true
                },


                legend: {
                    enabled: false
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'black',
                        }
                    }
                },
                legend: {
                    align: 'center',
                    borderWidth: 0
                },


                series: [{
                    name: 'Rendimiento',
                    colorByPoint: true,
                    data: [{
                        name: '190',
                        y: 112108,
                    }, {
                        name: '240',
                        y: 84289,
                    }, {
                        name: '320',
                        y: 70249,
                    }]
                }, {
                    type: 'line',
                    name: 'Meta',
                    color: 'transparent',
                    data: [83712, 72341, 69638],
                    marker: {
                        lineWidth: 5,
                        lineColor: Highcharts.getOptions().colors[5],
                        fillColor: 'white'
                    },
                    dataLabels: {
                        enabled: true,
                        color: 'red'
                    }
                }],
            });
        }

        function column05(div) {
            Highcharts.chart(div, {
                colors: ['#00ff6c', '#03deec', '#f5940a'],
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'RENDIMIENTO'
                },
                subtitle: {
                    text: 'Acumulado'
                },
                xAxis: {
                    categories: ['190 cantidad: 6', '240 cantidad: 13', '320 cantidad: 5'],
                    crosshair: true,

                    labels: {
                        style: {
                            color: 'networking',
                            fontSize: '15px'
                        }
                    }
                },
                yAxis: [{ // Primary yAxis
                    labels: {
                        format: '{value}',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    title: {
                        text: 'Rendimiento (Km)',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    }
                }, { // Secondary yAxis
                    title: {
                        text: '',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value} ',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    opposite: true
                }],
                tooltip: {
                    shared: true
                },


                legend: {
                    enabled: false
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'black',
                        }
                    }
                },
                legend: {
                    align: 'center',
                    borderWidth: 0
                },


                series: [{
                    name: 'Rendimiento',
                    colorByPoint: true,
                    data: [{
                        name: '190',
                        y: 112108,
                    }, {
                        name: '240',
                        y: 84289,
                    }, {
                        name: '320',
                        y: 70249,
                    }]
                }, {
                    type: 'line',
                    name: 'Meta',
                    color: 'transparent',
                    data: [83712, 72341, 69638],
                    marker: {
                        lineWidth: 5,
                        lineColor: Highcharts.getOptions().colors[5],
                        fillColor: 'white'
                    },
                    dataLabels: {
                        enabled: true,
                        color: 'red',
                        useHTML: true,
                        formatter: function() {
                            let dataLabelText = `<div style="text-align: center;">
              <div>${this.y.toLocaleString()}</div>
              <div>Meta</div>
              </div>`;
                            return dataLabelText;
                        }
                    }
                }],
            });
        }

        function column06(div) {

            var Cuota = 2000;
            var CuotaGrupo = 1500;
            var San = 200;
            var Tavil = 700;
            var Otros = 100;
            var Frente = 'Frente xxxx';
            var subtotal = San + Tavil + Otros;
            var contador = 0;
            var contador2 = 0;
            var arreglo = [];
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Ingreso  ' + Frente
                },
                subtitle: {
                    text: 'Subtitulo'
                },
                xAxis: {
                    categories: [Frente, ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'TN '
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} tons</b></td></tr>',
                    footerFormat: '<tr><td><span >(Total: ' + subtotal + ')</span></td></tr></table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                        }
                    }
                },
                legend: {

                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 80,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
                    shadow: true
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Cuota',
                    data: [Cuota]
                }, {
                    name: 'CuotaGrupo',
                    data: [CuotaGrupo]
                }, {
                    name: 'San',
                    data: [San]
                }, {
                    name: 'Tavil',
                    data: [Tavil]
                }, {
                    name: 'Otros',
                    data: [Otros]
                }]
            });
        }

        function column10() {
            const chart = Highcharts.chart('container', {
                chart: {
                    type: 'column'
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },
                plotOptions: {
                    series: {
                        allowPointSelect: true
                    }
                },
                series: [{
                    data: [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
                }]
            });

            document.getElementById('button').addEventListener('click', () => {
                const selectedPoints = chart.getSelectedPoints();

                if (chart.lbl) {
                    chart.lbl.destroy();
                }
                chart.lbl = chart.renderer.label('You selected ' + selectedPoints.length + ' points', 100, 60)
                    .attr({
                        padding: 10,
                        r: 5,
                        fill: Highcharts.getOptions().colors[1],
                        zIndex: 5
                    })
                    .css({
                        color: 'white'
                    })
                    .add();
            });
        }
    </script>

</body>

</html>
