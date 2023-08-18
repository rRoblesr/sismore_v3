<script>
    var gaugeOptions = {
    chart: {
        type: 'solidgauge'
    },

    title: null,

    pane: {
        center: ['50%', '65%'],
        size: '100%',
        startAngle: -90,
        endAngle: 90,
        background: {
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || '#EEE',
            innerRadius: '60%',
            outerRadius: '100%',
            shape: 'arc'
        }
    },

    exporting: {
        enabled: false
    },

    tooltip: {
        enabled: false
    },

    // the value axis
    yAxis: {
        stops: [
            [0.1, '#55BF3B'], // green
            [0.5, '#DDDF0D'], // yellow
            [0.9, '#DF5353'] // red
        ],
        lineWidth: 0,
        tickWidth: 0,
        minorTickInterval: null,
        tickAmount: 2,
        title: {
            y: -70
        },
        labels: {
            y: 16
        }
    },

    plotOptions: {
        solidgauge: {
            dataLabels: {
                y: 5,
                borderWidth: 0,
                useHTML: true
            }
        }
    }
};

// The speed gauge 01
var chartSpeed = Highcharts.chart('medidor1', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: {{$par_medidor1_max}},
        title: {
            text: 'Recepcionadas'
        }
    },

    credits: {
        enabled: false
    },

    series: [{
        name: 'Recepcionadas',
        data: [{{$par_medidor1_data}}],
        dataLabels: {
            format:
                '<div style="text-align:center">' +
                '<span style="font-size:25px">{y} % </span><br/>' +
                '<span style="font-size:16px;opacity:0.4"> Tablets Recepcionadas I.E</span>' +
                '</div>'
        },
        tooltip: {
            valueSuffix: ' %'
        }
    }]

}));


// The speed gauge 02

var chartSpeed = Highcharts.chart('medidor2', Highcharts.merge(gaugeOptions, {
    yAxis: {
        min: 0,
        max: {{$par_medidor2_max}},
        title: {
            text: 'Asignadas'
        }
    },

    credits: {
        enabled: false
    },

    series: [{
        name: 'Asignadas',
        data: [{{$par_medidor2_data}}],
        dataLabels: {
            format:
                '<div style="text-align:center">' +
                '<span style="font-size:25px">{y} %</span><br/>' +
                '<span style="font-size:16px;opacity:0.4">Tablets Asignadas</span>' +
                '</div>'
        },
        tooltip: {
            valueSuffix: ' %'
        }
    }]

}));




</script>

