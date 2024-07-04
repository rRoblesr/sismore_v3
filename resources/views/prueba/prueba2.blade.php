<!DOCTYPE html>
<html>
<head>
    <title>Highcharts Example</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
    <div id="container" style="width:100%; height:400px;"></div>
    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'column'  // Cambia el tipo de 'line' a 'column'
            },
            title: {
                text: 'Número de actas de homologación registradas en el sistema de padrón nominal por mes'
            },
            xAxis: {
                categories: ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Número de actas'
                }
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,
                        format: '{y}'
                    }
                }
            },
            tooltip: {
                shared: true,
                headerFormat: '<b>{point.key}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>'
            },
            series: [{
                name: 'Actas Enviadas',
                data: [76, 28, 53, 46, 100, 10, 0, 0, 0, 0, 0, 0]
            }, {
                name: 'Actas Aprobadas',
                data: [10, 8, 9, 9, 14, 10, 0, 0, 0, 0, 0, 0]
            }]
        });
    </script>
</body>
</html>

{{-- <!DOCTYPE html>
<html>
<head>
    <title>Highcharts Example</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
    <div id="container" style="width:100%; height:400px;"></div>
    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'column'  // Cambia el tipo de 'line' a 'column'
            },
            title: {
                text: 'Número de actas de homologación registradas en el sistema de padrón nominal por mes'
            },
            xAxis: {
                categories: ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Número de actas'
                }
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,
                        format: '{y}'
                    }
                }
            },
            series: [{
                name: 'Actas Enviadas',
                data: [76, 28, 53, 46, 100, 10, 0, 0, 0, 0, 0, 0]
            }, {
                name: 'Actas Aprobadas',
                data: [10, 8, 9, 9, 14, 10, 0, 0, 0, 0, 0, 0]
            }]
        });
    </script>
</body>
</html> --}}

{{-- <!DOCTYPE html>
<html>
<head>
    <title>Highcharts Example</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
    <div id="container" style="width:100%; height:400px;"></div>
    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'column'  // Cambia el tipo de 'line' a 'column'
            },
            title: {
                text: 'Número de actas de homologación registradas en el sistema de padrón nominal por mes'
            },
            xAxis: {
                categories: ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Número de actas'
                }
            },
            series: [{
                name: 'Actas Enviadas',
                data: [76, 28, 53, 46, 100, 10, 0, 0, 0, 0, 0, 0],
                dataLabels: {
                    enabled: true,
                    format: '{y}'
                }
            }, {
                name: 'Actas Aprobadas',
                data: [10, 8, 9, 9, 14, 10, 0, 0, 0, 0, 0, 0],
                dataLabels: {
                    enabled: true,
                    format: '{y}'
                }
            }],
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true
                    }
                }
            }
        });
    </script>
</body>
</html> --}}

{{-- <!DOCTYPE html>
<html>
<head>
    <title>Highcharts Example</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
    <div id="container" style="width:100%; height:400px;"></div>
    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'column'  // Cambia el tipo de 'line' a 'column'
            },
            title: {
                text: 'Número de actas de homologación registradas en el sistema de padrón nominal por mes'
            },
            xAxis: {
                categories: ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SET', 'OCT', 'NOV', 'DIC']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Número de actas'
                }
            },
            series: [{
                name: 'Actas Enviadas',
                data: [76, 28, 53, 46, 100, 10, 0, 0, 0, 0, 0, 0]
            }, {
                name: 'Actas Aprobadas',
                data: [10, 8, 9, 9, 14, 10, 0, 0, 0, 0, 0, 0]
            }]
        });
    </script>
</body>
</html> --}}