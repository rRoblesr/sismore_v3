

<script>
    Highcharts.chart('{{$nombreGraficoLineal}}', {
       chart: {
           type: 'spline'
       },       
       credits:false,
       title: {     
        text: '{{$titulo}}'
       },

       subtitle: {
        align: 'center',
        text: '{{$subTitulo}}'
       },

       xAxis: {
           categories: <?=$categoria_nombres?> 
       },
       yAxis: {
           title: {
               text: '{{$titulo_y}}'
           },
           labels: {
               formatter: function () {
                   return this.value + '°';
               }
           }
       },
       tooltip: {
           crosshairs: true,
           shared: true
       },
       plotOptions: {
           spline: {
               marker: {
                   radius: 4,
                   lineColor: '#666666',
                   lineWidth: 1
               }
           },

           
       },
      
       series: <?=$dataLineal?>

    
   });
   
   
   </script>