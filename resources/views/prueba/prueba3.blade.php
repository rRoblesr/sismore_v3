{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mapa de Provincias con Highcharts</title>
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/mapdata/countries/pe/pe-all.js"></script>
</head>

<body>
    <div id="container" style="height: 500px; min-width: 600px"></div>

    <script>
        // Datos de ejemplo
        const data = [{
                'hc-key': 'pe-cp',
                value: 351457,
                name: 'CORONEL PORTILLO'
            },
            {
                'hc-key': 'pe-pa',
                value: 100000,
                name: 'PADRE ABAD'
            },
            {
                'hc-key': 'pe-at',
                value: 150000,
                name: 'ATALAYA'
            },
            {
                'hc-key': 'pe-pu',
                value: 50000,
                name: 'PURUS'
            }
        ];

        // Inicialización del mapa
        Highcharts.mapChart('container', {
            chart: {
                map: 'countries/pe/pe-all'
            },
            title: {
                text: 'Población estimada, según provincias y distritos'
            },
            series: [{
                name: 'Población',
                data: data,
                joinBy: 'hc-key',
                states: {
                    hover: {
                        color: '#BADA55'
                    }
                },
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.value}'
                }
            }]
        });
    </script>
</body>

</html> --}}

{{--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mapa de Provincias y Distritos con Highcharts</title>
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
    <!-- Cargar tu GeoJSON con provincias y distritos -->
    <script src="ruta/a/tu/geojson.js"></script>
</head>

<body>
    <div id="container" style="height: 500px; min-width: 600px"></div>

    <script>
        // Datos de ejemplo para provincias y distritos
        const dataProvincias = [{
                'hc-key': 'provincia-1',
                value: 351457,
                name: 'Provincia 1'
            },
            {
                'hc-key': 'provincia-2',
                value: 150000,
                name: 'Provincia 2'
            }
        ];

        const dataDistritos = [{
                'hc-key': 'distrito-1',
                value: 50000,
                name: 'Distrito 1'
            },
            {
                'hc-key': 'distrito-2',
                value: 75000,
                name: 'Distrito 2'
            }
        ];

        Highcharts.mapChart('container', {
            chart: {
                map: 'ruta/a/tu/geojson' // Especifica tu archivo GeoJSON
            },
            title: {
                text: 'Población estimada por Provincias y Distritos'
            },
            series: [{
                    name: 'Provincias',
                    data: dataProvincias,
                    joinBy: 'hc-key',
                    states: {
                        hover: {
                            color: '#BADA55'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.value}'
                    }
                },
                {
                    name: 'Distritos',
                    data: dataDistritos,
                    joinBy: 'hc-key',
                    states: {
                        hover: {
                            color: '#00BFFF'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.value}'
                    }
                }
            ]
        });
    </script>
</body>

</html>
 --}}


{{-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mapa de Ucayali con Provincias y Distritos</title>
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
    <script src="ruta/a/tu/geojson-ucayali.js"></script> <!-- Asegúrate de reemplazar esta ruta -->
</head>
<body>
<div id="container" style="height: 500px; min-width: 600px"></div>

<script>
    // Datos de ejemplo para las provincias de Ucayali
    const dataProvincias = [
        { 'hc-key': 'ucayali-coronel-portillo', value: 351457, name: 'Coronel Portillo' },
        { 'hc-key': 'ucayali-padre-abad', value: 123456, name: 'Padre Abad' },
        { 'hc-key': 'ucayali-atalaya', value: 78910, name: 'Atalaya' },
        { 'hc-key': 'ucayali-purus', value: 45678, name: 'Purus' }
    ];

    // Configuración del mapa
    Highcharts.mapChart('container', {
        chart: {
            map: 'ruta/a/tu/geojson-ucayali' // Asegúrate de que esta ruta sea correcta
        },
        title: {
            text: 'Población estimada en Ucayali, por provincias y distritos'
        },
        series: [{
            name: 'Provincias y Distritos',
            data: dataProvincias,
            joinBy: 'hc-key',
            states: {
                hover: {
                    color: '#BADA55'
                }
            },
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.value}'
            }
        }]
    });
</script>
</body>
</html> --}}

