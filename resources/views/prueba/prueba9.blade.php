{{-- <!DOCTYPE html>
<html>

<head>
    <title>Pirámide Poblacional</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
</head>

<body>
    <div id="container" style="width:100%; height:600px;"></div>

    <script type="text/javascript">
        Highcharts.chart('container', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Pirámide poblacional, según sexo y grupo etario'
            },
            xAxis: [{
                categories: ['0-05', '06-11', '12-17', '18-24', '25-29',
                    '30-34', '35-39', '40-44', '45-49', '50-54',
                    '55-59', '60-64', '65-69', '70-74', '75-79', '80+'
                ],
                reversed: false,
                labels: {
                    step: 1
                }
            }],
            yAxis: {
                title: {
                    text: null
                },
                labels: {
                    formatter: function() {
                        return Math.abs(this.value) + '';
                    }
                }
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + ', edad ' + this.point.category + '</b><br/>' +
                        'Población: ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
                }
            },
            series: [{
                name: 'Hombres',
                data: [-1597861, -1819584, -1771431, -1885995, -1448952,
                    -1352564, -1251619, -1146545, -1029330, -874058,
                    -750415, -609156, -477954, -348419, -247920, -289359
                ],
                color: '#66BB6A'
            }, {
                name: 'Mujeres',
                data: [1543870, 1757072, 1714052, 1847317, 1437795,
                    1344458, 1244901, 1146179, 1041181, 897454,
                    775648, 633270, 514119, 378325, 278172, 378255
                ],
                color: '#388E3C'
            }]
        });
    </script>
</body>

</html> --}}


<!DOCTYPE html>
<html>

<head>
    <title>Pirámide Poblacional con Filtro</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
</head>

<body>
    <label for="ageGroup">Selecciona un grupo etario:</label>
    <select id="ageGroup">
        <option value="all">Todos</option>
        <option value="child">0-17</option>
        <option value="youth">18-34</option>
        <option value="adult">35-64</option>
        <option value="senior">65+</option>
    </select>

    <div id="container" style="width:100%; height:600px;"></div>

    <script type="text/javascript">
        // Datos para los diferentes grupos etarios
        const dataSets = {
            all: {
                categories: ['0-05', '06-11', '12-17', '18-24', '25-29',
                    '30-34', '35-39', '40-44', '45-49', '50-54',
                    '55-59', '60-64', '65-69', '70-74', '75-79', '80+'
                ],
                men: [-1597861, -1819584, -1771431, -1885995, -1448952,
                    -1352564, -1251619, -1146545, -1029330, -874058,
                    -750415, -609156, -477954, -348419, -247920, -289359
                ],
                women: [1543870, 1757072, 1714052, 1847317, 1437795,
                    1344458, 1244901, 1146179, 1041181, 897454,
                    775648, 633270, 514119, 378325, 278172, 378255
                ]
            },
            child: {
                categories: ['0-05', '06-11', '12-17'],
                men: [-1597861, -1819584, -1771431],
                women: [1543870, 1757072, 1714052]
            },
            youth: {
                categories: ['18-24', '25-29', '30-34'],
                men: [-1885995, -1448952, -1352564],
                women: [1847317, 1437795, 1344458]
            },
            adult: {
                categories: ['35-39', '40-44', '45-49', '50-54', '55-59', '60-64'],
                men: [-1251619, -1146545, -1029330, -874058, -750415, -609156],
                women: [1244901, 1146179, 1041181, 897454, 775648, 633270]
            },
            senior: {
                categories: ['65-69', '70-74', '75-79', '80+'],
                men: [-477954, -348419, -247920, -289359],
                women: [514119, 378325, 278172, 378255]
            }
        };

        // Inicialización del gráfico
        const chart = Highcharts.chart('container', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Pirámide poblacional, según sexo y grupo etario'
            },
            xAxis: [{
                categories: dataSets.all.categories,
                reversed: false,
                labels: {
                    step: 1
                }
            }],
            yAxis: {
                title: {
                    text: null
                },
                labels: {
                    formatter: function() {
                        return Math.abs(this.value) + '';
                    }
                }
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.series.name + ', edad ' + this.point.category + '</b><br/>' +
                        'Población: ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
                }
            },
            series: [{
                name: 'Hombres',
                data: dataSets.all.men,
                color: '#66BB6A'
            }, {
                name: 'Mujeres',
                data: dataSets.all.women,
                color: '#388E3C'
            }]
        });

        // Función para actualizar el gráfico según el grupo seleccionado
        document.getElementById('ageGroup').addEventListener('change', function() {
            const selectedGroup = this.value;
            const selectedData = dataSets[selectedGroup];

            // Actualizar las categorías y los datos del gráfico
            chart.xAxis[0].setCategories(selectedData.categories);
            chart.series[0].setData(selectedData.men);
            chart.series[1].setData(selectedData.women);
        });
    </script>
</body>

</html>
