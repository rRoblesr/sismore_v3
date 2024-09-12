<!DOCTYPE html>
<html>

<head>
    <title>Highcharts Population Chart</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/boost.js"></script>
</head>

<body>
  <div class="row">
    <col-md-6>
    <div id="container" style="width:100%; height:400px;"></div>
  </col-md-6>
  </div>
  

    <script>
        // Generar valores aleatorios de población
        let poblacion = [];
        for (let i = 1995; i <= 2030; i++) {
            poblacion.push(Math.floor(Math.random() * (1000000 - 500000 + 1)) +
            500000); // Población entre 500,000 y 1,000,000
        }

        Highcharts.chart('container', {
            title: {
                text: 'Población desde 1995 hasta 2030'
            },
            subtitle: {
                text: 'Valores de población simulados aleatoriamente'
            },
            xAxis: {
                title: {
                    text: 'Año'
                },
                categories: [
                    '1995', '1996', '1997', '1998', '1999', '2000',
                    '2001', '2002', '2003', '2004', '2005', '2006',
                    '2007', '2008', '2009', '2010', '2011', '2012',
                    '2013', '2014', '2015', '2016', '2017', '2018',
                    '2019', '2020', '2021', '2022', '2023', '2024',
                    '2025', '2026', '2027', '2028', '2029', '2030'
                ]
            },
            yAxis: {
                title: {
                    text: 'Población'
                }
            },
            series: [{
                name: 'Población',
                data: poblacion
            }],
            boost: {
                useGPUTranslations: true
            }
        });
    </script>
</body>

</html>
