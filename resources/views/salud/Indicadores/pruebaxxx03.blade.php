<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distritos - Tabla y Gráfico de Barras Invertidas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
        .card {
            height: 100%;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .table-sm td,
        .table-sm th {
            padding: 5px;
            font-size: 14px;
        }

        .same-height {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        #chart-container {
            flex-grow: 1;
            /* Hace que el gráfico se expanda automáticamente */
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="row">
            <!-- Tarjeta con la tabla -->
            <div class="col-md-6 mb-4">
                <div class="card same-height">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Población por Distrito</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Distrito</th>
                                    <th>Población</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-distritos">
                                <!-- Se llenará con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tarjeta con la gráfica -->
            <div class="col-md-6 mb-4">
                <div class="card same-height">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Gráfico de Población</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Datos de distritos y población
        const datosDistritos = [{
                nombre: "Distrito 1",
                poblacion: 10000
            },
            {
                nombre: "Distrito 2",
                poblacion: 12000
            },
            {
                nombre: "Distrito 3",
                poblacion: 9000
            },
            {
                nombre: "Distrito 4",
                poblacion: 14000
            },
            {
                nombre: "Distrito 5",
                poblacion: 11000
            },
            {
                nombre: "Distrito 6",
                poblacion: 8000
            },
            {
                nombre: "Distrito 7",
                poblacion: 13000
            },
            {
                nombre: "Distrito 8",
                poblacion: 12500
            },
            {
                nombre: "Distrito 9",
                poblacion: 9500
            },
            {
                nombre: "Distrito 10",
                poblacion: 15000
            },
            {
                nombre: "Distrito 11",
                poblacion: 11000
            },
            {
                nombre: "Distrito 12",
                poblacion: 10500
            },
            {
                nombre: "Distrito 13",
                poblacion: 9700
            },
            {
                nombre: "Distrito 14",
                poblacion: 8800
            },
            {
                nombre: "Distrito 15",
                poblacion: 11500
            },
            {
                nombre: "Distrito 16",
                poblacion: 10200
            },
            {
                nombre: "Distrito 17",
                poblacion: 9900
            },
            {
                nombre: "Distrito 18",
                poblacion: 12200
            },
            {
                nombre: "Distrito 19",
                poblacion: 13300
            },
        ];

        // Llenar la tabla con los datos de los distritos
        const tablaDistritos = document.getElementById("tabla-distritos");
        datosDistritos.forEach(d => {
            let fila = `<tr><td>${d.nombre}</td><td>${d.poblacion.toLocaleString()}</td></tr>`;
            tablaDistritos.innerHTML += fila;
        });

        // Ajustar la altura de la gráfica para igualarla con la tabla
        function ajustarAltura() {
            let alturaTabla = $(".same-height").eq(0).outerHeight();
            $(".same-height").eq(1).css("height", alturaTabla + "px");
        }

        // Configuración de Highcharts para el gráfico de barras invertidas
        Highcharts.chart("chart-container", {
            chart: {
                type: "bar"
            }, // 'bar' es para barras horizontales
            title: {
                text: "Población por Distrito"
            },
            xAxis: {
                categories: datosDistritos.map(d => d.nombre),
                title: {
                    text: "Distritos"
                }
            },
            yAxis: {
                title: {
                    text: "Población"
                },
                allowDecimals: false
            },
            series: [{
                name: "Población",
                data: datosDistritos.map(d => d.poblacion),
                color: "#28a745"
            }],
            tooltip: {
                pointFormat: "<b>{point.y}</b> habitantes"
            },
            plotOptions: {
                bar: { // Configuración específica de barras horizontales
                    dataLabels: {
                        enabled: true,
                        format: "{point.y}"
                    }
                }
            }
        });

        // Ajustar la altura de la gráfica cuando se cargue la página
        $(document).ready(function() {
            ajustarAltura();
            $(window).resize(ajustarAltura);
        });
    </script>

</body>

</html>
