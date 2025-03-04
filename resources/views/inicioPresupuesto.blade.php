@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/fixedHeader.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}public/assets/libs/datatables/scroller.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <style>
        .tablex thead th {
            padding: 4px;
            text-align: center;
        }

        .tablex thead td {
            padding: 4px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tablex tbody td,
        .tablex tbody th,
        .tablex tfoot td,
        .tablex tfoot th {
            padding: 4px;
        }

        .fuentex {
            font-size: 10px;
            font-weight: bold;
        }

        .centrador {
            position: relative;
            /* width: 400px;
                                                /* background-color: red; */
        }

        .imagen {
            position: absolute;
            /* width: 100px; */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
        }
    </style>
@endsection
{{-- <div>
    <div id="container-speed" class="chart-container"></div>
</div> --}}
@section('content')
    <div class="content">
        {{-- <div class="container-fluid"> --}}
        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="card card-fill bg-primary">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title text-white">Información General</h3>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- end row -->
        <div class="form-group row align-items-center vh-5">
            <div class="col-lg-8">
                <h4 class="page-title font-16">{{ $titulo }}</h4>
            </div>
            <label class="col-lg-1 control-label text-right align-self-end">Año</label>
            <div class="col-lg-1">
                <select id="anio" name="anio" class="form-control font-10" onchange="cargartarjetas();">
                    @foreach ($anios as $item)
                        <option value="{{ $item->anio }}" {{ $item->anio == $anio ? 'selected' : '' }}>
                            {{ $item->anio }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <select id="articulo" name="articulo" class="form-control font-10" onchange="cargartarjetas();">
                    <option value="0">ACTIVIDADES Y PROYECTOS</option>
                    <option value="1">PROYECTOS</option>
                    <option value="2">ACTIVIDADES</option>
                </select>
            </div>
        </div>

        <div class="row">
            {{-- CARD 1 --}}
            <div class="col-md-6 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md rounded-circle mr-2 centrador">
                            <img src="{{ asset('/') }}public/img/icon/dinero32.png" alt="" class="imagen">
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-18 my-0 font-weight-bold" title="" id="vh41">
                                    <span data-plugin="counterup"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate font-12" id="vp1"></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="font-12">Ejecución(<span style="font-weight: normal">DEV/PIA</span>)
                            <span class="float-right" id="vspan1"></span>
                        </h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0"
                                aria-valuemax="100" id="vdiv1">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--FIN CARD-->

            {{-- CARD 2 --}}
            <div class="col-md-6 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md rounded-circle mr-2 centrador">
                            <img src="{{ asset('/') }}public/img/icon/dinero32.png" alt="" class="imagen">
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-18 my-0 font-weight-bold" title="" id="vh42">
                                    <span data-plugin="counterup"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate font-12" id="vp2"></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="font-12">Ejecución(<span style="font-weight: normal">DEV/PIM</span>)
                            <span class="float-right" id="vspan2"></span>
                        </h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0"
                                aria-valuemax="100" id="vdiv2">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--FIN CARD-->

            {{-- CARD 3 --}}
            <div class="col-md-6 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md rounded-circle mr-2 centrador">
                            <img src="{{ asset('/') }}public/img/icon/dinero32.png" alt="" class="imagen">
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-18 my-0 font-weight-bold" title="" id="vh43">
                                    <span data-plugin="counterup"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate font-12" id="vp3"></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="font-12">Ejecución(<span style="font-weight: normal">CERT/PIM</span>)
                            <span class="float-right" id="vspan3"></span>
                        </h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0"
                                aria-valuemax="100" id="vdiv3">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--FIN CARD-->

            {{-- CARD 4 --}}
            <div class="col-md-6 col-xl-3">
                <div class="card-box">
                    <div class="media">
                        <div class="avatar-md rounded-circle mr-2 centrador">
                            <img src="{{ asset('/') }}public/img/icon/dinero32.png" alt="" class="imagen">
                        </div>
                        <div class="media-body align-self-center">
                            <div class="text-right">
                                <h4 class="font-18 my-0 font-weight-bold" title="" id="vh44">
                                    <span data-plugin="counterup"></span>
                                </h4>
                                <p class="mb-0 mt-1 text-truncate font-12" id="vp4"></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h6 class="font-12">Ejecución(<span style="font-weight: normal">DEV/CERT</span>)
                            <span class="float-right" id="vspan4"></span>
                        </h6>
                        <div class="progress progress-sm m-0">
                            <div class="progress-bar" role="progressbar" aria-valuenow="" aria-valuemin="0"
                                aria-valuemax="100" id="vdiv4">
                                <span class="sr-only"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--FIN CARD-->


        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal1" style="min-width:100%;height:600px;margin:0 auto;"></div>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-anal1-fuente">Fuente:</span>
                            <span class="float-right" id="span-anal1-fecha">Actualizado:</span>
                        </div>
                        {{--  style="min-width:400px;height:300px;margin:0 auto;" --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal2" style="min-width:100%;height:600px;margin:0 auto;"></div>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-anal2-fuente">Fuente:</span>
                            <span class="float-right" id="span-anal2-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal3"></div>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-anal3-fuente">Fuente:</span>
                            <span class="float-right" id="span-anal3-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal4"></div>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-anal4-fuente">Fuente:</span>
                            <span class="float-right" id="span-anal4-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}


        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal5"></div>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-anal5-fuente">Fuente:</span>
                            <span class="float-right" id="span-anal5-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal6"></div>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-anal6-fuente">Fuente:</span>
                            <span class="float-right" id="span-anal6-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end  row --}}

        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent p-0">
                        <h3 class="card-title text-primary "></h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="anal7"></div>
                        <div class="font-weight-bold text-muted ml-2 mr-2 font-9">
                            <span class="float-left" id="span-anal7-fuente">Fuente:</span>
                            <span class="float-right" id="span-anal7-fecha">Actualizado:</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@section('js')
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/mapdata/countries/pe/pe-all.js"></script>
    {{-- highcharts --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    {{-- <script src="https://code.highcharts.com/modules/drilldown.js"></script> --}}
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>



    <!-- third party js -->
    {{-- <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/jszip/jszip.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/pdfmake/vfs_fonts.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.html5.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/buttons.print.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.keyTable.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.scroller.min.js"></script>
 --}}




    <script type="text/javascript">
        $(document).ready(function() {
            Highcharts.setOptions({
                colors: paleta_colores,
                lang: {
                    thousandsSep: ","
                }
            });
            cargartarjetas();
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
                        ]
                    };
                }),
                lang: {
                    thousandsSep: ","
                }
            }); */

        });

        function cargartarjetas() {
            var anio = $('#anio').val();
            var articulo = $('#articulo').val();
            $.ajax({
                url: "{{ route('home.presupuesto.cuadros') }}",
                data: {
                    anio: anio,
                    articulo: articulo
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    //$('#anal2').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) { //vp1
                    $('#vh41').attr('title', data.card1.pim);
                    $('#vh41 span').text(data.card1.pim.toLocaleString('en-US'));
                    $('#vp1').text("PIA " + anio);
                    $('#vspan1').text(data.card1.eje + '%');
                    $('#vdiv1').removeClass('bg-danger bg-warning bg-success');
                    $('#vdiv1').addClass(data.card1.eje < 51 ? 'bg-danger' : (data.card1.eje < 76 ?
                        'bg-warning' : 'bg-success'));
                    $('#vdiv1').attr('aria-valuenow', data.card1.eje);
                    $('#vdiv1').css('width', data.card1.eje + '%');
                    $('#vdiv1 span').text(data.card1.eje + "% COMPLETADO");

                    $('#vh42').attr('title', data.card2.pim);
                    $('#vh42 span').text(data.card2.pim.toLocaleString('en-US'));
                    $('#vp2').text("PIM " + anio);
                    $('#vspan2').text(data.card2.eje + '%');
                    $('#vdiv2').removeClass('bg-danger bg-warning bg-success');
                    $('#vdiv2').addClass(data.card2.eje < 51 ? 'bg-danger' : (data.card2.eje < 76 ?
                        'bg-warning' : 'bg-success'));
                    $('#vdiv2').attr('aria-valuenow', data.card2.eje);
                    $('#vdiv2').css('width', data.card2.eje + '%');
                    $('#vdiv2 span').text(data.card2.eje + "% COMPLETADO");

                    $('#vh43').attr('title', data.card3.pim);
                    $('#vh43 span').text(data.card3.pim.toLocaleString('en-US'));
                    $('#vp3').text("CERTIFICADO " + anio);
                    $('#vspan3').text(data.card3.eje + '%');
                    $('#vdiv3').removeClass('bg-danger bg-warning bg-success');
                    $('#vdiv3').addClass(data.card3.eje < 51 ? 'bg-danger' : (data.card3.eje < 76 ?
                        'bg-warning' : 'bg-success'));
                    $('#vdiv3').attr('aria-valuenow', data.card3.eje);
                    $('#vdiv3').css('width', data.card3.eje + '%');
                    $('#vdiv3 span').text(data.card3.eje + "% COMPLETADO");

                    $('#vh44').attr('title', data.card4.pim);
                    $('#vh44 span').text(data.card4.pim.toLocaleString('en-US'));
                    $('#vp4').text("DEVENGADO " + anio);
                    $('#vspan4').text(data.card4.eje + '%');
                    $('#vdiv4').removeClass('bg-danger bg-warning bg-success');
                    $('#vdiv4').addClass(data.card4.eje < 51 ? 'bg-danger' : (data.card4.eje < 76 ?
                        'bg-warning' : 'bg-success'));
                    $('#vdiv4').attr('aria-valuenow', data.card4.eje);
                    $('#vdiv4').css('width', data.card4.eje + '%');
                    $('#vdiv4 span').text(data.card4.eje + "% COMPLETADO");
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
            cargarmapabarra();
            cargargrafica3();
            cargargrafica4();
            cargargrafica5();
            cargargrafica6();
            cargargrafica7();
        }
        /*
         *AJAX PARA LA PRESENTACION DE LA PRIMERA GRAFICA 1
         */
        function cargarmapabarra() {
            var anio = $('#anio').val();
            var articulo = $('#articulo').val();

            $.ajax({
                url: "{{ route('graficas.home.presupuesto.2') }}",
                data: {
                    anio: anio,
                    articulo: articulo,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal2').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    maps01('anal1',
                        data.data,
                        '',
                        'Rankin de la ejecución de gastos por gobiernos regionales');
                    gbar('anal2', [],
                        data.info,
                        '',
                        'Porcentaje de ejecución de gastos por gobiernos regionales',
                    );
                    $('#span-anal1-fuente').text('Fuente: ' + data.reg.fue);
                    $('#span-anal1-fecha').text('Fecha: ' + data.reg.fec);
                    $('#span-anal2-fuente').text('Fuente: ' + data.reg.fue);
                    $('#span-anal2-fecha').text('Fecha: ' + data.reg.fec);
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargargrafica3() {
            var anio = $('#anio').val();
            var articulo = $('#articulo').val();
            $.ajax({
                url: "{{ route('graficas.home.presupuesto.3') }}",
                data: {
                    anio: anio,
                    articulo: articulo,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal3').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gLineaBasica(
                        'anal3',
                        data.info.categoria,
                        data.info.series,
                        'Puesto',
                        '',
                        'Ranking mensual de la Ejecución de Gastos del Gobierno Regional de Ucayali a nivel nacional',
                        '');
                    $('#span-anal3-fuente').text('Fuente: ' + data.reg.fue);
                    $('#span-anal3-fecha').text('Fecha: ' + data.reg.fec);
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 3");
                    console.log(jqXHR);
                },
            });
        }

        function cargargrafica4() {
            var anio = $('#anio').val();
            var articulo = $('#articulo').val();
            $.ajax({
                url: "{{ route('graficas.home.presupuesto.4') }}",
                data: {
                    anio: anio,
                    articulo: articulo,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal4').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gLineaBasica(
                        'anal4',
                        data.info.categoria,
                        data.info.series,
                        'PIM',
                        '',
                        'Variación mensual del PIM del Pliego del Gobierno Regional de Ucayali', '');
                    $('#span-anal4-fuente').text('Fuente: ' + data.reg.fue);
                    $('#span-anal4-fecha').text('Fecha: ' + data.reg.fec);
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 3");
                    console.log(jqXHR);
                },
            });
        }

        function cargargrafica5() {
            var anio = $('#anio').val();
            var articulo = $('#articulo').val();
            $.ajax({
                url: "{{ route('graficas.home.presupuesto.5') }}",
                data: {
                    anio: anio,
                    articulo: articulo,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal5').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gUniqueColumn(
                        'anal5',
                        data.info.categoria,
                        data.info.series,
                        '',
                        'CERTIFICACIÓN mensual del pliego del Gobierno Regional de Ucayali');
                    $('#span-anal5-fuente').text('Fuente: ' + data.reg.fue);
                    $('#span-anal5-fecha').text('Fecha: ' + data.reg.fec);
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 5");
                    console.log(jqXHR);
                },
            });
        }

        function cargargrafica6() {
            var anio = $('#anio').val();
            var articulo = $('#articulo').val();
            $.ajax({
                url: "{{ route('graficas.home.presupuesto.6') }}",
                data: {
                    anio: anio,
                    articulo: articulo,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal6').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gUniqueColumn(
                        'anal6',
                        data.info.categoria,
                        data.info.series,
                        '',
                        'DEVENGADO mensual del pliego del Gobierno Regional de Ucayali');
                    $('#span-anal6-fuente').text('Fuente: ' + data.reg.fue);
                    $('#span-anal6-fecha').text('Fecha: ' + data.reg.fec);
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 6");
                    console.log(jqXHR);
                },
            });
        }

        function cargargrafica7() {
            var anio = $('#anio').val();
            var articulo = $('#articulo').val();
            $.ajax({
                url: "{{ route('graficas.home.presupuesto.7') }}",
                data: {
                    anio: anio,
                    articulo: articulo,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    $('#anal7').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                },
                success: function(data) {
                    gAnidadaColumn('anal7',
                        data.info.categoria,
                        data.info.series,
                        '',
                        'CERTIFICADO y DEVENGADO acumulado mensual del pliego del Gobierno Regional de Ucayali'
                    );
                    $('#span-anal7-fuente').text('Fuente: ' + data.reg.fue);
                    $('#span-anal7-fecha').text('Fecha: ' + data.reg.fec);
                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 7");
                    console.log(jqXHR);
                },
            });
        }
    </script>

    <script type="text/javascript">
        function gColumnDrilldown(div, data1, data2, titulo, subtitulo, tituloserie) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    enabled: false,
                    text: titulo,
                    //align:'left',
                },
                subtitle: {
                    //align:'left',
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                    }
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
                legend: {
                    enabled: false,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}%',
                        },
                    },
                    drilldown: {
                        series: {
                            //borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}',
                            },
                            format: '{point.y}',
                        },
                    }
                    /* series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                        },
                    } */
                },
                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}%</b><br/>',
                    shared: true,
                    /* headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>' */
                },
                series: [{
                    showInLegend: tituloserie != '',
                    name: tituloserie,
                    label: {
                        enabled: false
                    },
                    colorByPoint: false,
                    data: data1,

                }],
                drilldown: {
                    breadcrumbs: {
                        position: {
                            align: 'right',
                        }
                    },
                    series: data2,
                },
                credits: false,
            });
        }

        function gColumnDrilldown2(div, categoria, serie1, data2, titulo, subtitulo, tituloserie) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    enabled: false,
                    text: titulo,
                    //align:'left',
                },
                subtitle: {
                    //align:'left',
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                    }
                },
                xAxis: {
                    //type: 'category',
                    categories: categoria
                },
                yAxis: {
                    /* max: 100, */
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    }
                },
                legend: {
                    enabled: false,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y}%',
                        },
                    },
                    drilldown: {
                        series: {
                            //borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y}',
                            },
                            format: '{point.y}',
                        },
                    }
                    /* series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                        },
                    } */
                },
                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}%</b><br/>',
                    shared: true,
                    /* headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>' */
                },
                series: serie1
                    /* [{
                                        showInLegend: tituloserie != '',
                                        name: tituloserie,
                                        label: {
                                            enabled: false
                                        },
                                        colorByPoint: false,
                                        data: data1,

                                    }] */
                    ,
                drilldown: {
                    breadcrumbs: {
                        position: {
                            align: 'right',
                        }
                    },
                    series: data2,
                },
                credits: false,
            });
        }

        function gSimpleColumn(div, datax, titulo, subtitulo, tituloserie) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    enabled: true,
                    text: titulo,
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: {
                    type: 'category',
                },
                yAxis: {
                    //max: 1600000000,
                    //min:100000,
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    }
                },
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
                    //pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}%</b><br/>',
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else if (this.y < -1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontSize: '10px',
                                fontWeight: 'normal',
                            }
                        },
                        point: {
                            cursor: 'pointer',
                            events: {
                                click: function() {
                                    //alert('Category: ' + this.category + ', value: ' + this.y);
                                    alert(this.options);
                                    //location.href = 'https://en.wikipedia.org/wiki/' +this.options.key;
                                    //alert('hola ronald');
                                },
                            },
                        },


                    }
                    /* series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                        },
                    } */
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
                    style: {
                        fontSize: '11px'
                    }
                },
                tooltip: {
                    //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                    pointFormat: '{series.name}: <b>{point.y:,.0f} ({point.percentage:.1f}%)</b>',
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
                            format: '{point.y:,.0f} ({point.percentage:.1f}%)',
                            //format: '{point.percentage:.1f}%',
                            connectorColor: 'silver',
                            style: {
                                fontWeight: 'normal',
                            }
                        }
                    },
                    series: {
                        //allowPointSelect: true
                        style: {
                            fontSize: '10pt'
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
                legend: {
                    //align: 'center', //right//left//center
                    //verticalAlign: 'bottom', //top//middle//bottom
                    //layout: 'horizontal', //horizontal//vertical//proximate
                    itemStyle: {
                        "color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal",
                        "textOverflow": "ellipsis"
                    },
                },
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
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: {
                    categories: categorias,
                    //categories: ['Arsenal', 'Chelsea', 'Liverpool', 'Manchester United']
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
                    /* series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                        },
                    } */
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else if (this.y < 101) {
                                    return this.y + "%";
                                } else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                    },
                },
                series: datos
                    /* [{
                                           name: 'BPL',
                                           data: [3, 5, 1, 13]
                                       }, {
                                           name: 'FA Cup',
                                           data: [14, 8, 8, 12]
                                       }, {
                                           name: 'CL',
                                           data: [0, 2, 6, 3]
                                       }] */
                    ,

                credits: false,
            });
        }

        function gUniqueColumn(div, categorias, datos, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: {
                    categories: categorias,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                    //categories: ['Arsenal', 'Chelsea', 'Liverpool', 'Manchester United']
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Rainfall (mm)',
                        enabled: false
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                },
                tooltip: {
                    pointFormat: '<span style="color:{point.color}">\u25CF</span> Hay: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    /* series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                        },
                    } */
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                }
                                /* else if (this.y < 101) {
                                                                   return this.y + "%";
                                                               }  */
                                else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                    },
                },
                series: [{
                        showInLegend: false,
                        //name: '',
                        data: datos,
                        /* data: [3, 5, 1, 13] */
                    }]

                    ,
                credits: false,
            });
        }

        function gAnidadaColumn(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    zoomType: 'xy',
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                }],
                yAxis: [{ // Primary yAxis
                        max: 2000000000,
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* labels: {
                            format: '{value}°C',
                            style: {
                                color: Highcharts.getOptions().colors[2]
                            }
                        },
                        title: {
                            text: 'Temperature',
                            style: {
                                color: Highcharts.getOptions().colors[2]
                            }
                        }, */
                        //opposite: true,
                    }, { // Secondary yAxis
                        gridLineWidth: 0,
                        labels: {
                            enabled: false,
                        },
                        title: {
                            enabled: false,
                        },
                        /* title: {
                            text: 'Rainfall',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        },
                        labels: {
                            format: '{value} mm',
                            style: {
                                color: Highcharts.getOptions().colors[0]
                            }
                        }, */
                        min: -100,
                        max: 150,
                        opposite: true,
                    },
                    /* { // Tertiary yAxis
                                       gridLineWidth: 0,
                                       title: {
                                           text: 'Sea-Level Pressure',
                                           style: {
                                               color: Highcharts.getOptions().colors[1]
                                           }
                                       },
                                       labels: {
                                           format: '{value} mb',
                                           style: {
                                               color: Highcharts.getOptions().colors[1]
                                           }
                                       },
                                       opposite: true
                                   } */
                ],
                series: series,
                plotOptions: {
                    /* columns: {
                        stacking: 'normal'
                    }, */
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else if (this.y < 101) {
                                    return this.y + "%";
                                } else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                    },
                },
                tooltip: {
                    shared: true,
                },
                legend: {
                    itemStyle: {
                        "color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal",
                        "textOverflow": "ellipsis"
                    },
                },
                credits: false,
            });
        }

        function gAnidadaColumn_(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'column',
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: [{
                    categories: categoria,
                    crosshair: true
                }],
                /* yAxis: [{
                    allowDecimals: false,
                    min: 0,
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    }
                }, {
                    allowDecimals: false,
                    min: 0,
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    }
                }], */
                yAxis: [{ // Primary yAxis
                    labels: {
                        format: '{value}°C',
                        style: {
                            color: Highcharts.getOptions().colors[2]
                        }
                    },
                    title: {
                        text: 'Temperature',
                        style: {
                            color: Highcharts.getOptions().colors[2]
                        }
                    },
                    opposite: true

                }, { // Secondary yAxis
                    gridLineWidth: 0,
                    title: {
                        text: 'Rainfall',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value} mm',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    }

                }, { // Tertiary yAxis
                    gridLineWidth: 0,
                    title: {
                        text: 'Sea-Level Pressure',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    labels: {
                        format: '{value} mb',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    opposite: true
                }],
                series: series,
                plotOptions: {
                    columns: {
                        stacking: 'normal'
                    },
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                        /* label:{
                            style:{
                                fontWeight:'normal',
                            }
                        } */
                    }
                },
                legend: {
                    //align: 'center', //right//left//center
                    //verticalAlign: 'bottom', //top//middle//bottom
                    //layout: 'horizontal', //horizontal//vertical//proximate
                    itemStyle: {
                        "color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal",
                        "textOverflow": "ellipsis"
                    },
                },
                credits: false,
            });
        }

        function barra(div) { //ejemplo
            Highcharts.chart(div, {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Major trophies for some English teams',
                    align: 'left'
                },
                xAxis: {
                    categories: ['Arsenal', 'Chelsea', 'Liverpool', 'Manchester United']
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count trophies'
                    },
                    stackLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            color: ( // theme
                                Highcharts.defaultOptions.title.style &&
                                Highcharts.defaultOptions.title.style.color
                            ) || 'gray',
                            textOutline: 'none'
                        }
                    }
                },
                legend: {
                    align: 'left',
                    x: 70,
                    verticalAlign: 'top',
                    y: 70,
                    floating: true,
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
                    borderColor: '#CCC',
                    borderWidth: 1,
                    shadow: false
                },
                tooltip: {
                    headerFormat: '<b>{point.x}</b><br/>',
                    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                series: [{
                    name: 'BPL',
                    data: [3, 5, 1, 13]
                }, {
                    name: 'FA Cup',
                    data: [14, 8, 8, 12]
                }, {
                    name: 'CL',
                    data: [0, 2, 6, 3]
                }]
            });
        }

        function glineal(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'spline'
                },
                title: {
                    text: titulo, //'Browser market shares in January, 2018'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                xAxis: {
                    categories: categoria,
                    /* accessibility: {
                        rangeDescription: 'Range: 2015 to 2025'
                    } */
                },
                yAxis: {
                    title: {
                        enabled: false,
                        text: 'Number of Employees'
                    }
                    /* allowDecimals: false,
                    min: 0,
                    title: {
                        enabled: false,
                        text: 'Porcentaje',
                    } */
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    /* series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            //format: '{point.y:.1f}%',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                        },
                    } */
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                        /*  point: {
                             cursor: 'pointer',
                             events: {
                                 click: function() {
                                     //alert('Category: ' + this.category + ', value: ' + this.y);
                                     alert(this.options);
                                     //location.href = 'https://en.wikipedia.org/wiki/' +this.options.key;
                                     //alert('hola ronald');
                                 },
                             },
                         }, */


                    }
                    /* spline: {
                        marker: {
                            radius: 4,
                            lineColor: '#666666',
                            lineWidth: 1
                        }
                    } */
                },
                /*  plotOptions: {
                     series: {
                         label: {
                             connectorAllowed: false
                         },
                         pointStart: 2010
                     }
                 }, */

                series: series,
                legend: {
                    align: 'center', //right//left//center
                    verticalAlign: 'bottom', //top//middle//bottom
                    layout: 'horizontal', //horizontal//vertical//proximate
                    itemStyle: {
                        "color": "#333333",
                        "cursor": "pointer",
                        "fontSize": "10px",
                        "fontWeight": "normal", //bold
                        "textOverflow": "ellipsis"
                    },
                },
                /* responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'vertical', //horizontal
                                align: 'right', //center//right//left
                                verticalAlign: 'top'//bottom//middle
                            }
                        }
                    }]
                }, */
                credits: false,

            });
        }

        function gLineaBasica(div, categoria, series, nameserie, titulo, subtitulo, titulovetical) {
            Highcharts.chart(div, {
                title: {
                    text: titulo
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                },
                yAxis: {
                    title: {
                        text: titulovetical
                    },
                    min: 0,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                },
                xAxis: {
                    categories: categoria,
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                    /* accessibility: {
                        rangeDescription: 'Range: 2010 to 2017'
                    } */
                },
                /* legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                }, */
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            //format: '{point.y:,.0f}',
                            formatter: function() {
                                if (this.y > 1000000) {
                                    return Highcharts.numberFormat(this.y / 1000000, 0) + "M";
                                } else if (this.y > 1000) {
                                    return Highcharts.numberFormat(this.y / 1000, 0) + "K";
                                } else {
                                    return this.y;
                                }
                            },
                            style: {
                                fontWeight: 'normal',
                            }
                        },
                        /* label: {
                            connectorAllowed: false
                        },
                        pointStart: 2010 */
                    }
                },
                series: [{
                    name: nameserie,
                    showInLegend: false,
                    data: series
                }],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                },
                credits: false,

            });
        }

        function gbar(div, categoria, series, titulo, subtitulo) {
            Highcharts.chart(div, {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: titulo, // 'Historic World Population by Region'
                },
                subtitle: {
                    text: subtitulo,
                    style: {
                        fontSize: '11px'
                    }
                    /*  'Source: <a ' +
                                            'href="https://en.wikipedia.org/wiki/List_of_continents_and_continental_subregions_by_population"' +
                                            'target="_blank">Wikipedia.org</a>' */
                },
                xAxis: {
                    //categories:categoria,// ['Africa', 'America', 'Asia', 'Europe', 'Oceania'],
                    type: "category",
                    title: {
                        text: '', // null
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        }
                    },
                    enabled: false,
                },
                yAxis: {
                    //min: 0,
                    title: {
                        text: '', // 'Population (millions)',
                        align: 'high'
                    },
                    labels: {
                        style: {
                            fontSize: '10px',
                        },
                        overflow: 'justify'
                    }
                },
                tooltip: {
                    valueSuffix: ' %'
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.y} %'
                        }
                    }
                },
                legend: {
                    enabled: false, //
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    x: -40,
                    y: 80,
                    floating: true,
                    borderWidth: 1,
                    backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                    shadow: true
                },

                //series: series,
                /*  [{
                                    name: 'Year 1990',
                                    data: [631, 727, 3202, 721, 26]
                                }, {
                                    name: 'Year 2000',
                                    data: [814, 841, 3714, 726, 31]
                                }, {
                                    name: 'Year 2010',
                                    data: [1044, 944, 4170, 735, 40]
                                }, {
                                    name: 'Year 2018',
                                    data: [1276, 1007, 4561, 746, 42]
                                }] */
                /* showInLegend: tituloserie != '',
                        name: tituloserie,
                        label: {
                            enabled: false
                        },
                        colorByPoint: false, */
                series: [{
                    name: 'Ejecución',
                    showInLegend: false,
                    label: {
                        enabled: false
                    },
                    data: series,
                    /* [{
                                                name: "Chrome",
                                                y: 63.06,
                                            },
                                            {
                                                name: "Safari",
                                                y: 19.84,
                                            },
                                            {
                                                name: "Firefox",
                                                y: 4.18,
                                            },
                                            {
                                                name: "Edge",
                                                y: 4.12,
                                            },
                                            {
                                                name: "Opera",
                                                y: 2.33,
                                            },
                                            {
                                                name: "Internet Explorer",
                                                y: 0.45,
                                            },
                                            {
                                                name: "Other",
                                                y: 1.582,
                                            }
                                        ] */
                }],
                credits: {
                    enabled: false
                },
            });
        }
    </script>

    <script>
        function maps01(div, data, titulo, subtitulo) {
            Highcharts.mapChart(div, {
                chart: {
                    map: 'countries/pe/pe-all'
                },

                title: {
                    text: titulo, //'Reportes de Mapa'
                },

                subtitle: {
                    text: subtitulo, //'Un descripción de reportes'
                    style: {
                        fontSize: '11px'
                    }
                },

                mapNavigation: {
                    enabled: true,
                    buttonOptions: {
                        verticalAlign: 'top'
                    }
                },

                colorAxis: {
                    min: 0,
                    max: 30,
                    //tickPixelInterval: 100,
                    maxColor: "#e6ebf5",
                    minColor: "#003399",
                    /*maxColor: '#F1EEF6',
                    minColor: '#900037'*/
                },

                series: [{
                    data: data,
                    name: 'Puesto', //Población
                    states: {
                        hover: {
                            color: '#BADA55'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.value}°'
                    }
                }],
                credits: {
                    enabled: false
                },
            });
        }
    </script>

    {{--  --}}
    {{-- <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script> --}}
    {{-- <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/maps/modules/map.js"></script>
    <script src="https://code.highcharts.com/maps/modules/data.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/maps/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/maps/modules/accessibility.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> --}}
@endsection
