<script>

    /*GRAFICO COLORES DEGRADADO
    Highcharts.chart('{{$nombreGraficoBarra}}', {
    
                colors: Highcharts.map(Highcharts.getOptions().colors, function(color) {
                                return {
                                    radialGradient: {
                                        cx: 0.5,
                                        cy: 0.3,
                                        r: 0.7
                                    },
                                    stops: [
                                        [0, color],
                                        [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
                                    ]
                                };
                            }),
    
    
                chart: {
                    type: 'column'
                },
                credits:false,
                title: {
                    text: '{{$titulo}}'
                },
                subtitle: {
                    text: '{{$subTitulo}}'
                },
                xAxis: {
                    categories: 
                         <?=$categoria_nombres?>
                    ,
                    
                },
                yAxis: {
                    
                    title: {
                        text: '{{$titulo_y}}'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:12px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                        '<td style="padding:0"><b>{point.y} </b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
    
                plotOptions: {
                    column: {                       
                        borderWidth: 0,
                        dataLabels: {
                                    enabled: true,
                                 
                                    format: '{y}'
                                }    
                    }
                },
                series: <?=$data?>
            });
    
            */


            
            Highcharts.chart('{{$nombreGraficoBarra}}', {
            chart: {
                type: 'column'
            },
            credits:false,
            title: {
                text: '{{$titulo}}'
            },
            subtitle: {
                text: '{{$subTitulo}}'
            },
            xAxis: {
                categories: 
                         <?=$categoria_nombres?>
            },
            yAxis: {
                title: {
                        text: '{{$titulo_y}}'
                    }
            },
            tooltip: {
                headerFormat: '<span style="font-size:12px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                                        '<td style="padding:0"><b> <b>{point.y:.2f}%</b></b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
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
       
            series: <?=$data?>
            });





/*
Highcharts.chart('{{$nombreGraficoBarra}}', {
  chart: {
    type: 'column'
  },
  title: {
    text: '{{$titulo}}'
  },
  subtitle: {
    text: 'Source: ' +
      '<a href="https://www.ssb.no/en/statbank/table/08940/" ' +
      'target="_blank">SSB</a>'
  },
  xAxis: {
    categories: [
      
      
      '2019',
      '2020',
      '2021'
    ],
    crosshair: true
  },
  yAxis: {
    title: {
      useHTML: true,
      text: 'Million tonnes CO<sub>2</sub>-equivalents'
    }
  },
  tooltip: {
    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
      '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
    footerFormat: '</table>',
    shared: true,
    useHTML: true
  },
  plotOptions: {
    column: {
      pointPadding: 0.2,
      borderWidth: 0
    }
  },
  series: [ {
    name: 'Manufacturing industries and mining',
    data: [ 11.59, 11.42, 11.76]

  }, {
    name: 'Road traffic',
    data: [  8.72, 8.38, 8.69]

  }, {
    name: 'Agriculture',
    data: [  4.51, 4.49, 4.57]

  }]
});


*/


           </script>
    
    