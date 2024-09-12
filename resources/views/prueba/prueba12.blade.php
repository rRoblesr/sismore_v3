{{-- <!DOCTYPE html>
<html>

<head>
    <title>Highcharts Population Chart</title>
    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 360px;
            /* max-width: 800px; */
            margin: 1em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-data-table th {
            font-weight: 600;
            padding: 0.5em;
        }

        .highcharts-data-table td,
        .highcharts-data-table th,
        .highcharts-data-table caption {
            padding: 0.5em;
        }

        .highcharts-data-table thead tr,
        .highcharts-data-table tr:nth-child(even) {
            background: #f8f8f8;
        }

        .highcharts-data-table tr:hover {
            background: #f1f7ff;
        }
    </style>
</head>

<body>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/boost.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <figure class="highcharts-figure">
        <div id="container"></div>
        <p class="highcharts-description">
            Using the Highcharts Boost module, it is possible to render large amounts
            of data on the client side. This chart shows a line series with 500,000
            data points. The points represent hourly data since 1965. Click and drag
            in the chart to zoom in.
        </p>
    </figure>

    <script>
        function getData(n) {
            const arr = [];
            let i,
                x,
                a,
                b,
                c,
                spike;
            for (
                i = 0, x = Date.UTC(new Date().getUTCFullYear(), 0, 1) - n * 36e5; i < n; i = i + 1, x = x + 36e5
            ) {
                if (i % 100 === 0) {
                    a = 2 * Math.random();
                }
                if (i % 1000 === 0) {
                    b = 2 * Math.random();
                }
                if (i % 10000 === 0) {
                    c = 2 * Math.random();
                }
                if (i % 50000 === 0) {
                    spike = 10;
                } else {
                    spike = 0;
                }
                arr.push([
                    x,
                    2 * Math.sin(i / 100) + a + b + c + spike + Math.random()
                ]);
            }
            return arr;
        }
        const n = 500000,
            data = getData(n);
        console.log(data);


        // console.time('line');
        Highcharts.chart('container', {

            chart: {
                zooming: {
                    type: 'x'
                }
            },

            title: {
                text: 'Highcharts drawing ' + n + ' points',
                align: 'left'
            },

            subtitle: {
                text: 'Using the Boost module',
                align: 'left'
            },

            accessibility: {
                screenReaderSection: {
                    beforeChartFormat: '<{headingTagName}>' +
                        '{chartTitle}</{headingTagName}><div>{chartSubtitle}</div>' +
                        '<div>{chartLongdesc}</div><div>{xAxisDescription}</div><div>' +
                        '{yAxisDescription}</div>'
                }
            },

            tooltip: {
                valueDecimals: 2
            },

            xAxis: {
                type: 'datetime'
            },

            series: [{
                data: data,
                lineWidth: 0.5,
                name: 'Hourly data points'
            }]

        });
        // console.timeEnd('line');
    </script>
</body>

</html> --}}


<!DOCTYPE html>
<html>

<head>
    <title>Highcharts Population Chart</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/boost.js"></script>
</head>

<body>
    <div id="container" style="width:100%; height:400px;"></div>

    <script>
        // Datos de población (años convertidos a timestamp usando Date.UTC)
        const data = [
            [Date.UTC(1995, 0, 1), 24242600],
            [Date.UTC(1996, 0, 1), 24689213],
            [Date.UTC(1997, 0, 1), 25145317],
            [Date.UTC(1998, 0, 1), 25592876],
            [Date.UTC(1999, 0, 1), 26013829],
            [Date.UTC(2000, 0, 1), 26390142],
            [Date.UTC(2001, 0, 1), 26714547],
            [Date.UTC(2002, 0, 1), 26999085],
            [Date.UTC(2003, 0, 1), 27254632],
            [Date.UTC(2004, 0, 1), 27492091],
            [Date.UTC(2005, 0, 1), 27722342],
            [Date.UTC(2006, 0, 1), 27934784],
            [Date.UTC(2007, 0, 1), 28122158],
            [Date.UTC(2008, 0, 1), 28300372],
            [Date.UTC(2009, 0, 1), 28485319],
            [Date.UTC(2010, 0, 1), 28692915],
            [Date.UTC(2011, 0, 1), 28905725],
            [Date.UTC(2012, 0, 1), 29113162],
            [Date.UTC(2013, 0, 1), 29341346],
            [Date.UTC(2014, 0, 1), 29616414],
            [Date.UTC(2015, 0, 1), 29964499],
            [Date.UTC(2016, 0, 1), 30422831],
            [Date.UTC(2017, 0, 1), 30973992],
            [Date.UTC(2018, 0, 1), 31562130],
            [Date.UTC(2019, 0, 1), 32131400],
            [Date.UTC(2020, 0, 1), 32625948],
            [Date.UTC(2021, 0, 1), 33035304],
            [Date.UTC(2022, 0, 1), 33396698],
            [Date.UTC(2023, 0, 1), 33725844],
            [Date.UTC(2024, 0, 1), 34038457],
            [Date.UTC(2025, 0, 1), 34350244],
            [Date.UTC(2026, 0, 1), 34660114],
            [Date.UTC(2027, 0, 1), 34957600],
            [Date.UTC(2028, 0, 1), 35244330],
            [Date.UTC(2029, 0, 1), 35521943],
            [Date.UTC(2030, 0, 1), 35792079]
        ];

        // Creación del gráfico
        Highcharts.chart('container', {
            chart: {
                zooming: {
                    type: 'x'
                }
            },
            title: {
                text: 'Población desde 1995 hasta 2030',
                align: 'left'
            },
            subtitle: {
                text: 'Using the Boost module',
                align: 'left'
            },
            accessibility: {
                screenReaderSection: {
                    beforeChartFormat: '<{headingTagName}>' +
                        '{chartTitle}</{headingTagName}><div>{chartSubtitle}</div>' +
                        '<div>{chartLongdesc}</div><div>{xAxisDescription}</div><div>' +
                        '{yAxisDescription}</div>'
                }
            },
            tooltip: {
                valueDecimals: 0 // Mostrará sin decimales
            },
            xAxis: {
                type: 'datetime',
                title: {
                    text: 'Año'
                }
            },
            yAxis: {
                title: {
                    text: 'Población'
                }
            },
            series: [{
                data: data,
                lineWidth: 0.5,
                name: 'Datos de población',
                boostThreshold: 1 // El boost module activado siempre que haya 1 o más puntos
            }]
        });
    </script>
</body>

</html>
