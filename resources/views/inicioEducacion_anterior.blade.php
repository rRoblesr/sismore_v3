@section('css')
    <style>
        .tablex thead th {
            padding: 2px;
            text-align: center;
        }

        .tablex thead td {
            padding: 2px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tablex tbody td,
        .tablex tbody th,
        .tablex tfoot td,
        .tablex tfoot th {
            padding: 2px;
        }

        .fuentex {
            font-size: 10px;
            font-weight: bold;
        }
    </style>
@endsection

<div>
    <div id="container-speed" class="chart-container"></div>
</div>

<div class="content">
    <div class="container-fluid">
        @if ($importacion_id)
            <!--Widget-4 -->
            <div class="row">
                <div class="col-md-3 col-xl-3">
                    <div class="card-box">
                        <div class="media">
                            <div class="avatar-md rounded-circle mr-2 centrador">
                                {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                                <img src="{{ asset('/') }}public/img/icon/servicios.png" alt=""
                                    class="imagen">
                            </div>
                            {{-- <div class="avatar-md bg-success rounded-circle mr-2">
                                <i class=" ion-md-home avatar-title font-26 text-white"></i>
                            </div> --}}
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup">
                                            {{ number_format($info['se'], 0) }}
                                        </span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Servicios Educativos</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- end card-box-->
                </div>

                <div class="col-md-3 col-xl-3">
                    <div class="card-box">
                        <div class="media">
                            <div class="avatar-md rounded-circle mr-2 centrador">
                                {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                                <img src="{{ asset('/') }}public/img/icon/locales.png" alt="" class="imagen">
                            </div>
                            {{-- <div class="avatar-md bg-info rounded-circle mr-2">
                                <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                            </div> --}}
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup">
                                            {{ number_format($info['le'], 0) }}
                                        </span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Locales Escolares </p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- end card-box-->
                </div>

                <div class="col-md-3 col-xl-3">
                    <div class="card-box">
                        <div class="media">
                            <div class="avatar-md rounded-circle mr-2 centrador">
                                {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                                <img src="{{ asset('/') }}public/img/icon/matriculas.png" alt=""
                                    class="imagen">
                            </div>
                            {{-- <div class="avatar-md bg-info rounded-circle mr-2">
                                <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                            </div> --}}
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup">
                                            {{ number_format($info['tm'], 0) }}
                                        </span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Estudiantes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card-box-->
                </div>

                <div class="col-md-3 col-xl-3">
                    <div class="card-box">
                        <div class="media">
                            <div class="avatar-md rounded-circle mr-2 centrador">
                                {{-- <i class="ion ion-logo-usd avatar-title font-26 text-white"></i> --}}
                                <img src="{{ asset('/') }}public/img/icon/docentes.png" alt=""
                                    class="imagen">
                            </div>
                            {{-- <div class="avatar-md bg-info rounded-circle mr-2">
                                <i class=" ion ion-md-person avatar-title font-26 text-white"></i>
                            </div> --}}
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold">
                                        <span data-plugin="counterup">
                                            {{ number_format($info['do'], 0) }}
                                        </span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Docentes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- grafica 1 --}}
            <div class="row">
                <div class="col-xl-6">
                    <div class="card card-border card-primary">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title text-primary"></h3>
                        </div>
                        <div class="card-body p-0">{{--  style="min-width:100%;height:400px;margin:0 auto;" --}}
                            <div id="anal1"></div>
                            {{-- <div id="pie4" style="min-width:400px;height:300px;margin:0 auto;"></div> --}}
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card card-border card-primary">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title text-primary "></h3>
                        </div>
                        <div class="card-body p-0">
                            <div id="anal2"></div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end  row --}}

            {{-- grafica 2 --}}
            <div class="row">
                <div class="col-xl-6">
                    <div class="card card-border card-primary">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title text-primary "></h3>
                        </div>
                        <div class="card-body p-0">
                            <div id="anal3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card card-border card-primary">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title text-primary "></h3>
                        </div>
                        <div class="card-body p-0">
                            <div id="anal4"></div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end  row --}}

            {{-- grafica 3 --}}
            <div class="row">
                <div class="col-xl-6">
                    <div class="card card-border card-primary">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title text-primary "></h3>
                        </div>
                        <div class="card-body p-0">
                            <div id="anal5"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card card-border card-primary">
                        <div class="card-header border-primary bg-transparent p-0">
                            <h3 class="card-title text-primary "></h3>
                        </div>
                        <div class="card-body p-0">
                            <div id="anal6"></div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end  row --}}

            {{-- tablaa 0 --}}
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 m-0">
                            <h3 class="card-title">Estudiantes Matriculados De Educación Básica Según UGEL</h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive">
                                <table id="tabla0" class="table table-striped table-bordered mb-0 tablex"
                                    style="font-size:11px;">
                                    <thead>
                                        <tr class="bg-primary text-white">
                                            <td rowspan="2">UGEL</td>
                                            <td colspan="2">TOTAL</td>
                                            <td colspan="2">EBE</td>
                                            <td colspan="2">INICIAL</td>
                                            <td colspan="2">PRIMARIA</td>
                                            <td colspan="2">SECUNDARIA</td>
                                        </tr>
                                        <tr class="bg-primary text-white">
                                            <th>PUBLICO</th>
                                            <th>PRIVADO</th>
                                            <th>PUBLICO</th>
                                            <th>PRIVADO</th>
                                            <th>PUBLICO</th>
                                            <th>PRIVADO</th>
                                            <th>PUBLICO</th>
                                            <th>PRIVADO</th>
                                            <th>PUBLICO</th>
                                            <th>PRIVADO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($info['dt0']['body'] as $item2)
                                            <tr>
                                                <td>{{ $item2->ugel }}</td>
                                                <th class="table-primary text-center">
                                                    {{ number_format($item2->pu_t, 0) }}</th>
                                                <th class="table-info text-center">
                                                    {{ number_format($item2->pr_t, 0) }}</th>
                                                <td class="text-center">
                                                    {{ number_format($item2->pu_e, 0) }}</td>
                                                <td class="text-center">
                                                    {{ number_format($item2->pr_e, 0) }}</td>
                                                <td class="text-center">
                                                    {{ number_format($item2->pu_i, 0) }}</td>
                                                <td class="text-center">
                                                    {{ number_format($item2->pr_i, 0) }}</td>
                                                <td class="text-center">
                                                    {{ number_format($item2->pu_p, 0) }}</td>
                                                <td class="text-center">
                                                    {{ number_format($item2->pr_p, 0) }}</td>
                                                <td class="text-center">
                                                    {{ number_format($item2->pu_s, 0) }}</td>
                                                <td class="text-center">
                                                    {{ number_format($item2->pr_s, 0) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-primary text-white">
                                            <th>TOTAL</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt0']['foot']->pu_t, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt0']['foot']->pr_t, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt0']['foot']->pu_e, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt0']['foot']->pr_e, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt0']['foot']->pu_i, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt0']['foot']->pr_i, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt0']['foot']->pu_p, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt0']['foot']->pr_p, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt0']['foot']->pu_s, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt0']['foot']->pr_s, 0) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <p class="text-muted font-13 m-0 p-0 text-right">
                                Fuente: SIAGIE - MINEDU, Actualizado a la fecha {{ $info['dt0']['fecha'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end  row --}}

            {{-- tablaa 1 --}}
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0 m-0">
                            <h3 class="card-title">Total servicios educativos, instituciones educativas y secciones
                            </h3>
                        </div>
                        <div class="card-body pb-0 pt-0">
                            <div class="table-responsive">
                                <table id="tabla1" class="table table-striped table-bordered mb-0 tablex"
                                    style="font-size:11px;">
                                    <thead>
                                        <tr class="bg-primary text-white text-center">
                                            <td rowspan="2">NIVEL_MODALIDAD</td>
                                            <td colspan="3">INSTITUCIONES EDUCATIVAS</td>
                                            <td colspan="3">SERVICIOS EDUCATIVOS</td>
                                            <td colspan="3">SECCIONES</td>
                                        </tr>
                                        <tr class="bg-primary text-white text-center">
                                            <th><span title="HOMBRES">TOTAL</span></th>
                                            <th><span title="MUJERES">PUBLICO</span></th>
                                            <th><span title="HOMBRES">PRIVADO</span></th>
                                            <th><span title="HOMBRES">TOTAL</span></th>
                                            <th><span title="MUJERES">PUBLICO</span></th>
                                            <th><span title="HOMBRES">PRIVADO</span></th>
                                            <th><span title="HOMBRES">TOTAL</span></th>
                                            <th><span title="MUJERES">PUBLICO</span></th>
                                            <th><span title="HOMBRES">PRIVADO</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($info['dt1']['head'] as $item)
                                            <tr class="table-active">
                                                <th>{{ $item->tipo }}</th>
                                                <th class="text-center">{{ number_format($item->ttlc, 0) }}</th>
                                                <th class="text-center">{{ number_format($item->pulc, 0) }}</th>
                                                <th class="text-center">{{ number_format($item->prlc, 0) }}</th>
                                                <th class="text-center">{{ number_format($item->ttsr, 0) }}</th>
                                                <th class="text-center">{{ number_format($item->pusr, 0) }}</th>
                                                <th class="text-center">{{ number_format($item->prsr, 0) }}</th>
                                                <th class="text-center">{{ number_format($item->ttsc, 0) }}</th>
                                                <th class="text-center">{{ number_format($item->pusc, 0) }}</th>
                                                <th class="text-center">{{ number_format($item->prsc, 0) }}</th>

                                            </tr>
                                            @foreach ($info['dt1']['body'] as $item2)
                                                @if ($item2->tipo == $item->tipo)
                                                    <tr>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $item2->nivel }}
                                                        </td>
                                                        <th class="text-center">{{ number_format($item2->ttlc, 0) }}
                                                        </th>
                                                        <td class="text-center">{{ number_format($item2->pulc, 0) }}
                                                        </td>
                                                        <td class="text-center">{{ number_format($item2->prlc, 0) }}
                                                        </td>
                                                        <th class="text-center">{{ number_format($item2->ttsr, 0) }}
                                                        </th>
                                                        <td class="text-center">{{ number_format($item2->pusr, 0) }}
                                                        </td>
                                                        <td class="text-center">{{ number_format($item2->prsr, 0) }}
                                                        </td>
                                                        <th class="text-center">{{ number_format($item2->ttsc, 0) }}
                                                        </th>
                                                        <td class="text-center">{{ number_format($item2->pusc, 0) }}
                                                        </td>
                                                        <td class="text-center">{{ number_format($item2->prsc, 0) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach

                                    </tbody>

                                    <tfoot>
                                        <tr class="bg-primary text-white">
                                            <th>TOTAL</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt1']['foot']->ttlc, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt1']['foot']->pulc, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt1']['foot']->prlc, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt1']['foot']->ttsr, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt1']['foot']->pusr, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt1']['foot']->prsr, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt1']['foot']->ttsc, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt1']['foot']->pusc, 0) }}</th>
                                            <th class="text-center">
                                                {{ number_format($info['dt1']['foot']->prsc, 0) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <p class="text-muted font-13 m-0 p-0 text-right">
                                Fuente: PADRON WEB, actualizado a la fecha {{ $info['dt1']['fecha'] }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>
            {{-- end  row --}}


            {{-- fin --}}
            {{-- <div class="progress progress-sm m-0">
                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                    aria-valuemax="100" style="width: 100%"></div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <p class="titulo_Indicadores  mb-0"></p>
                </div>
                <div class="col-md-9 text-right">
                    <p class="texto_dfuente  mb-0"> Fuente: ESCALE - MINEDU – PADRON WEB, ultima actualizacion del
                        <span
                            id="fechaActualizacion">{{ date('d/m/Y', strtotime($imp['fechaActualizacion'])) }}</span>
                    </p>
                </div>
            </div> --}}
            <!-- end row -->
        @else
            @if ($importables['padron_web'])
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-fill bg-danger">
                            <div class="card-header bg-transparent">
                                <h3 class="card-title text-white">NO HAY IMPORTACION DE PADRON WEB</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($importables['siagie_matricula'])
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-fill bg-danger">
                            <div class="card-header bg-transparent">
                                <h3 class="card-title text-white">NO HAY IMPORTACION DE SIAGIE MATRICULA</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($importables['nexus_minedu'])
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-fill bg-danger">
                            <div class="card-header bg-transparent">
                                <h3 class="card-title text-white">NO HAY IMPORTACION NEXUS MINEDU</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- end row -->
        @endif


    </div>
</div>



@section('js')
    <script type="text/javascript">
        //var paleta_colores = ['#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5', '#64E572', '#9F9655', '#FFF263', '#6AF9C4'];
        $(document).ready(function() {
            //console.log(Highcharts.getOptions().colors)
            Highcharts.setOptions({
                colors: paleta_colores,
                lang: {
                    thousandsSep: ","
                }
            });
            /* Highcharts.setOptions({
                colors: Highcharts.map(paleta_colores, function(color) {
                    return {
                        radialGradient: {
                            cx: 0.5,
                            cy: 0.3,
                            r: 0.7
                        },
                        stops: [
                            [0, color],
                            [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
                        ],
                    };
                }),
                lang: {
                    thousandsSep: ","
                }
            }); */

            $.ajax({
                url: "{{ url('/') }}/Home/gra1",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    gSimpleColumn('anal1', data.info, '',
                        'Estudiantes Matriculados por Años<br><span class="fuentex">Fuente:SIAGIE' +
                        '</span>', '');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ url('/') }}/Home/gra2",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    gSimpleColumn('anal2', data.info,
                        '', 'Personal Docente por Años<br><span class="fuentex">Fuente:NEXUS' +
                        '</span>', '');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 2");
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ url('/') }}/Home/gra3",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    gPie('anal3', data.info['puntos'],
                        '',
                        'Estudiantes Matriculados según Genero<br><span class="fuentex">Fuente:SIAGIE AL ' +
                        data.info[
                            'fecha'] + '</span>', '');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ url('/') }}/Home/gra4",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    gPie('anal4', data.info['puntos'],
                        '',
                        'Personal Docente según Genero<br><span class="fuentex">Fuente:NEXUS AL ' +
                        data.info[
                            'fecha'] + '</span>',
                        '');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 4");
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ url('/') }}/Home/gra5",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    gPie('anal5', data.info['puntos'],
                        '',
                        'Estudiantes Matriculados según Area Geografica<br><span class="fuentex">Fuente:SIAGIE AL ' +
                        data
                        .info['fecha'] + '</span>', '');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 5");
                    console.log(jqXHR);
                },
            });

            $.ajax({
                url: "{{ url('/') }}/Home/gra6",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    gPie('anal6', data.info['puntos'],
                        '',
                        'Personal Docente según Area Geografica<br><span class="fuentex">Fuente:NEXUS AL ' +
                        data.info['fecha'] + '</span>',
                        '');
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 6");
                    console.log(jqXHR);
                },
            });

        });

        function gSimpleColumn(div, datax, titulo, subtitulo, tituloserie) {

            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    enabled: false,
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
                },
                xAxis: {
                    type: 'category',
                },
                yAxis: {
                    /* max: 100, */
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    }
                },
                /* colors: [
                    '#8085e9',
                    '#2b908f',
                ], */
                series: [{
                    showInLegend: tituloserie != '',
                    name: tituloserie,
                    label: {
                        enabled: false
                    },
                    colorByPoint: false,
                    data: datax,
                }],
                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                        },
                    }
                },

                credits: false,
            });
        }

        function gPie(div, datos, titulo, subtitulo, tituloserie) {
            Highcharts.chart(div, {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                },
                tooltip: {
                    //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            format: '{point.y:,0f} ( {point.percentage:.1f}% )',
                            connectorColor: 'silver'
                        }
                    }
                },
                /* plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            //format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            format: '{point.percentage:.1f}% ({point.y})',
                            connectorColor: 'silver'
                        }
                    }
                }, */
                series: [{
                    showInLegend: true,
                    //name: 'Share',
                    data: datos,
                }],
                credits: false,
            });
        }

        function gBasicColumn(div, categorias, datos, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo
                },
                xAxis: {
                    categories: categorias,
                },
                yAxis: {

                    min: 0,
                    title: {
                        text: 'Rainfall (mm)',
                        enabled: false
                    }
                },

                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                        },
                    }
                },
                series: datos,
                credits: false,
            });
        }
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
