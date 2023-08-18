
<script>


Highcharts.chart('{{$nombreGraficoBarra}}', {
  chart: {
    type: 'bar'
  },
  title: {
    text: '{{$titulo}}'
  },
  subtitle: {
    text: '{{$subTitulo}}'
  },
  xAxis: {
    categories:  <?=$categoria_nombres?>,
    title: {
      text: null
    }
  },
  yAxis: {
    min: 0,
    title: {
      text: '{{$titulo_y}}',
      align: 'high'
    },
    labels: {
      overflow: 'justify'
    }
  },
  tooltip: {
    // valueSuffix: ' millions'
  },
  plotOptions: {
    bar: {
      dataLabels: {
        enabled: true
      }
    }
  },
  legend: {
    layout: 'vertical',
    align: 'right',
    // verticalAlign: 'top',
    x: -40,
    y: 80,
    floating: true,
    borderWidth: 1,
    backgroundColor:
      Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
    shadow: true
  },
  credits: {
    enabled: false
  },
  series: <?=$data?>
});
</script>