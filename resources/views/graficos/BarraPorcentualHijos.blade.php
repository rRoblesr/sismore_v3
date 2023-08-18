




<script>



Highcharts.chart('{{$nombreGraficoBarraHijo}}', {
        chart: {
            type: 'column'
        },
        title: {
            align: 'center',
            text: '{{$titulo}}'
        },
        subtitle: {
            align: 'center',
            text: '{{$subTitulo}}'
        },
        credits:false,
        accessibility: {
            announceNewData: {
            enabled: true
            }
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
            text: 'Porcentaje'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> <br/>'
        },

        series: [
            {
            name: "PROVINCIA",
            colorByPoint: true,
            data: <?=$dataGrafico?>
            }
        ],
        drilldown: {
            breadcrumbs: {
            position: {
                align: 'right'
            }
            },
            series: [
            {
                name: "ATALAYA",
                id: "ATALAYA",
                data: <?=$dataAtalaya?>
            },            
          
            {
                name: "CORONEL PORTILLO",
                id: "CORONEL PORTILLO",
                data: <?=$dataCrnlPortillo?>
            },

            {
                name: "PADRE ABAD",
                id: "PADRE ABAD",
                data: <?=$dataPadreAbad?>
            },

            {
                name: "PURUS",
                id: "PURUS",
                data: <?=$dataPurus?>
            }
            
            ]
        }
        });




 </script>