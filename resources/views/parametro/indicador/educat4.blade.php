@extends('layouts.main', ['titlePage' => 'INDICADOR'])

@section('content')
    <div class="content">
        <div class="row">

            <div class="col-lg-12">
                <div class="card card-fill bg-primary">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title text-white">{{ $title }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!--div class="card-header">
                                            <h3 class="card-title">Default Buttons</h3>
                                        </div-->
                    <div class="card-body">
                        <form id="form_opciones" name="form_opciones" action="POST">
                            @csrf
                            <input type="hidden" name="nivel" id="nivel" value="{{ $nivel }}">
                            <div class="form-group row">
                                <label class="col-md-1 col-form-label">Fecha</label>
                                <div class="col-md-3">
                                    <select id="fecha" name="fecha" class="form-control" onchange="historial();">
                                        @foreach ($ingresos as $item)
                                            <option value="{{ $item->id }}">
                                                {{ date('d-m-Y', strtotime($item->fechaActualizacion)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-1 col-form-label">Provincia</label>
                                <div class="col-md-3">
                                    <select id="provincia" name="provincia" class="form-control"
                                        onchange="cargardistritos();historial();">
                                        <option value="0">TODOS</option>
                                        @foreach ($provincias as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-1 col-form-label">Distrito</label>
                                <div class="col-md-3">
                                    <select id="distrito" name="distrito" class="form-control" onchange="historial();">
                                        <option value="0">TODOS</option>
                                    </select>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border card-default">
                    <div class="card-header border-default bg-transparent pb-0">
                        <h3 class="card-title text-default">{{ $title }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Titulado</th>
                                                <th>Cantidad</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody id="con1t">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">

                    <div class="card-body">
                        <div id="con1" style="min-width:400px;height:300px;margin:0 auto;"></div>
                    </div>
                </div>
            </div>
        </div><!-- End row -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border card-default">
                    <div class="card-header border-default bg-transparent pb-0">
                        <h3 class="card-title text-default">PROFESORES TITULADOS EN
                            {{ $nivel }}, POR UGEL</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Ugel</th>
                                                <th>Cantidad</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody id="con2t">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">

                    <div class="card-body">
                        <div id="con2" style="min-width:400px;height:300px;margin:0 auto;"></div>
                    </div>
                </div>
            </div>
        </div><!-- End row -->

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
            historial();
        });

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
