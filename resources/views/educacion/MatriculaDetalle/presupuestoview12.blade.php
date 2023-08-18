@extends('layouts.main', ['titlePage' => ''])

@section('content')
    <div class="content">
        <div class="row">

        <iframe title="Proyectos - Proyectos" width="100%" height="650" src="https://app.powerbi.com/view?r=eyJrIjoiYzgyMTU0Y2MtNDQxMi00ZWJhLWJiMjctZTYwMzhhNDg3YmVjIiwidCI6ImJmZTc3ZTYwLWEwY2UtNGI4Yi1hMjc5LWQ2NTQxNTA2MzU1MSJ9" frameborder="0" allowFullScreen="true"></iframe>
            
        </div>
        <!-- end row -->

    </div>
@endsection

@section('js')
    <!-- flot chart -->
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            cargartabla0();
        });

        function cargartabla0() {
            $.ajax({
                url: "{{ route('matriculadetalle.avance.tabla0') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista0').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ route('matriculadetalle.avance.tabla1') }}",
                type: "POST",
                data: $('#form_opciones').serialize(),
                success: function(data) {
                    $('#vista1').html(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }


        function cargardistritos() {
            $('#distrito').val('0');
            $.ajax({
                url: "{{ url('/') }}/Plaza/Distritos/" + $('#provincia').val(),
                dataType: 'JSON',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data.distritos, function(index, value) {
                        options += "<option value='" + value.id + "'>" + value.nombre + "</option>"
                    });
                    $("#distrito").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function historial() {
            $.ajax({
                url: "{{ route('ind01.plaza.dato') }}",
                type: 'post',
                data: $('#form_opciones').serialize(),
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    graficaPie(data.dato.tt);
                    graficarBar(data.dato.tu);
                    /* TABLAS DIVERSAS */
                    /* ---> */
                    piet = '';
                    conteo = 0;
                    total = 0;
                    data.dato.tt.forEach(element => {
                        total += element.y
                    });
                    data.dato.tt.forEach((val, index) => {
                        por = val.y * 100 / total;
                        piet += '<tr><td align=center>' + val.name + '</td><td align=center>' + val.y +
                            '</td><td align=center>' + por.toFixed(1) + '%</td></tr>';
                        conteo += val.y;
                    });
                    piet += '<tr><td align=center><b>TOTAL</b></td><td align=center><b>' + conteo +
                        '</b></td><td align=center><b>100%</b></td></tr>';
                    $('#con1t').html(piet);
                    /* --> */
                    piet = '';
                    conteo = 0;
                    total = 0;
                    data.dato.tu.forEach(element => {
                        total += element.y
                    });
                    data.dato.tu.forEach((val, index) => {
                        por = val.y * 100 / total;
                        piet += '<tr><td align=left>' + val.name + '</td><td align=center>' + val.y +
                            '</td><td align=center>' + por.toFixed(1) + '%</td></tr>';
                        conteo += val.y;
                    });
                    piet += '<tr><td align=left><b>TOTAL</b></td><td align=center><b>' + conteo +
                        '</b></td><td align=center><b>100%</b></td></tr>';
                    $('#con2t').html(piet);
                    /* --> */
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function graficarBar(datax) {
            Highcharts.chart('con2', {
                chart: {
                    type: 'column',
                },
                title: {
                    text: '',
                },
                xAxis: {
                    type: 'category',
                },
                yAxis: {
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    }
                },
                series: [{
                    name: 'UGEL',
                    colorByPoint: true,
                    data: datax,
                    showInLegend: false,
                }],
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}',
                        },
                    }
                },
                credits: false,
            });
        }

        function graficaPie(datos) {
            Highcharts.chart('con1', {
                chart: {
                    type: 'pie',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                },
                title: {
                    text: ''
                },
                tooltip: {
                    pointFormat: '<b>{series.name}:</b>{point.y}',
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}: {point.percentage:.2f}%</b>'
                        },
                    }
                },
                series: [{
                    name: 'Cantidad',
                    colorByPoint: true,
                    data: datos,
                }],
                credits: false,

            });
        }
    </script>
@endsection
