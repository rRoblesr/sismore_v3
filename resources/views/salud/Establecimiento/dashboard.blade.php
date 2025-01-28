@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => ''])

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        {{-- <button type="button" class="btn btn-danger btn-xs"
                            onclick="window.location.href=`{{ route('salud.padronnominal.tablerocalidad.consulta', ['anio' => 'anio', 'mes' => 'mes']) }}`.replace('anio',$('#anio').val()).replace('mes',$('#mes').val())">
                            <i class="fas fa-search"></i> Consultas</button> --}}
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Actualizar</button>
                    </div>
                    <h3 class="card-title text-white font-14">Directorio de establecimientos de salud de ucayali</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">Fuente: RENIPRESS - MINSA, {{ $actualizado }}</h4>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">PROVINCIA</label>
                                <select id="provincia" name="provincia" class="form-control btn-xs font-11"
                                    onchange="cargarDistritos();">
                                    <option value="0">TODOS</option>
                                    @foreach ($provincias as $item)
                                        <option value="{{ $item->id }}"> {{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="distrito">DISTRITO</label>
                                <select id="distrito" name="distrito" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="red">RED</label>
                                <select id="red" name="red" class="form-control btn-xs font-11"
                                    onchange="cargarMicrorred();cargarCards();">
                                    <option value="0">TODOS</option>
                                    @foreach ($red as $item)
                                        <option value="{{ $item->id }}"> {{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="microrred">MICRORRED</label>
                                <select id="microrred" name="microrred" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/tableta32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="ion ion-ios-people avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card1"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">EESS MINSA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/tableta32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="far fa-address-card avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card2"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Hospitales</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/cargador32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="mdi mdi-shield-check avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card3"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Centro de Salud</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card-box border border-plomo-0">
                <div class="media">
                    {{-- <div class="text-center">
                        <img src="{{ asset('/') }}public/img/icon/cargador32px.png" alt="" class=""
                            width="100%" height="100%">
                    </div> --}}
                    <div class="avatar-md mr-2">
                        <i class="far fa-hospital avatar-title font-30 text-dark"></i>

                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold">
                                <span data-plugin="counterup" id="card4"></span>
                            </h4>
                            <p class="mb-0 mt-1 text-truncate">Puestos de Salud</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla2')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">
                        Número de establecimientos de salud y centros de apoyo activos por institucion, según categoría a
                        nivel regional
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla1">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla3')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">
                        Número de establecimientos de salud y centros de apoyo activos por distritos, según categoría a
                        nivel regional
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla2">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-header"> --}}
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla3')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">
                        Listado de establecimiento de salud de ucayali
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla3">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Modal content for the above example -->
    <div class="modal fade" id="modal-centropoblado" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="myLargeModalLabel">Población de niños y niñas menos de 6 años por Centro
                        Poblado, segun sexo y edades</h5>
                    <div class="card-widgets">
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla3_1')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="ctabla3_1">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal -->

    <!--  Modal content for the above example -->
    <div class="modal fade" id="modal-consulta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content p-0 b-0">
                <div class="card card-color mb-0">
                    <div class="card-header bg-success-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="card-title text-white mt-1 mb-0">Busqueda de Niños y Niñas Menores de 6 años del Padron
                            Nominal</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="form-group row align-items-center vh-5">
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <div class="custom-select-container">
                                                    <label for="tipodocumento">Tipo de Documento</label>
                                                    <select id="tipodocumento" name="tipodocumento" class="form-control "
                                                        onchange="">
                                                        <option value="1">DNI</option>
                                                        <option value="2">CNV</option>
                                                        <option value="3">CUI</option>
                                                        <option value="4">CÓDIGO PADRÓN</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2">
                                                <div class="custom-select-container">
                                                    <label for="numerodocumento">Documento</label>
                                                    <input type="number" id="numerodocumento" name="numerodocumento"
                                                        class="form-control">
                                                </div>


                                            </div>
                                            <div class="col-lg-4 col-md-2 col-sm-2">
                                                <div class="custom-select-container">
                                                    <label for="apellidosnombres">Apellidos y Nombres</label>
                                                    <input type="text" id="apellidosnombres" name="apellidosnombres"
                                                        class="form-control">
                                                </div>


                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 text-center">
                                                <button type="button" class="btn btn-success" onclick="consultahacer()">
                                                    {{-- <i class="fa fa-redo"></i> --}} Consultar</button>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 text-center">
                                                <button type="button" class="btn btn-warning"
                                                    onclick="consultalimpiar()">
                                                    {{-- <i class="fa fa-redo"></i> --}} Limpiar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="sdsds" class="table table-striped table-bordered font-12 text-dark">
                                        <tbody>
                                            <tr>
                                                <td class="text-right table-secondary">CÓDIGO PADRÓN</td>
                                                <td id="padron"></td>
                                                <td class="text-right table-secondary">TIPO DOCUMENTO</td>
                                                <td id="tipodoc"></td>
                                                <td class="text-right table-secondary">DOCUMENTO</td>
                                                <td id="doc"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right table-secondary">APELLIDO PATERNO</td>
                                                <td id="apepat"></td>
                                                <td class="text-right table-secondary">APELLIDO MATERNO</td>
                                                <td id="apemat"></td>
                                                <td class="text-right table-secondary">NOMBRES</td>
                                                <td id="nom"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right table-secondary">SEXO</td>
                                                <td id="sexo"></td>
                                                <td class="text-right table-secondary">FECHA DE NACIMIENTO</td>
                                                <td id="nacimiento"></td>
                                                <td class="text-right table-secondary">EDAD</td>
                                                <td id="edad"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right table-secondary">DEPARTAMENTO</td>
                                                <td id="dep"></td>
                                                <td class="text-right table-secondary">PROVINCIA</td>
                                                <td id="pro"></td>
                                                <td class="text-right table-secondary">DISTRITO</td>
                                                <td id="dis"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right table-secondary">CENTRO POBLADO</td>
                                                <td id="cp"></td>
                                                <td class="text-right table-secondary">DIRECCIÓN</td>
                                                <td id="dir" colspan="3"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right table-secondary">EESS NACIMIENTO</td>
                                                <td id="esn"></td>
                                                <td class="text-right table-secondary">ULTIMO EESS ATENCIÓN</td>
                                                <td id="esa"></td>
                                                <td class="text-right table-secondary">VISITA DOMICILIARIA</td>
                                                <td id="visita"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right table-secondary">ENCONTRADO</td>
                                                <td id="encontrado"></td>
                                                <td class="text-right table-secondary">TIPO DE SEGURO</td>
                                                <td id="seguro"></td>
                                                <td class="text-right table-secondary">PROGRAMA SOCIAL</td>
                                                <td id="programa"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right table-secondary">INSTITUTCIÓN EDUCATIVA</td>
                                                <td></td>
                                                <td class="text-right table-secondary">NIVEL EDUCATIVO</td>
                                                <td></td>
                                                <td class="text-right table-secondary">GRADO Y SECCIÓN</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right table-secondary">APODERADO</td>
                                                <td id="mapoderado"></td>
                                                <td class="text-right table-secondary">TIPO DOCUMENTO</td>
                                                <td id="mtipodoc"></td>
                                                <td class="text-right table-secondary">DOCUMENTO</td>
                                                <td id="mdoc"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right table-secondary">APELLIDO PATERNO</td>
                                                <td id="mapepat"></td>
                                                <td class="text-right table-secondary">APELLIDO MATERNO</td>
                                                <td id="mapemat"></td>
                                                <td class="text-right table-secondary">NOMBRES</td>
                                                <td id="mnom"></td>
                                            </tr>
                                            <tr>
                                                <td class="text-right table-secondary">CELULAR</td>
                                                <td id="mcel"></td>
                                                <td class="text-right table-secondary">GRADO DE INSTRUCCIÓN</td>
                                                <td id="mgrado"></td>
                                                <td class="text-right table-secondary">LENGUA HABITUAL</td>
                                                <td id="mlengua"></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div><!-- /.modal -->
@endsection

@section('js')
    <script type="text/javascript">
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        var ubigeo_select = '';
        const spinners = {
            head: ['#card1', '#card2', '#card3', '#card4'],
            head1: ['#card1'],
            head2: ['#card2'],
            head3: ['#card3'],
            head4: ['#card4'],
            anal1: ['#anal1'],
            anal2: ['#anal2'],
            anal3: ['#anal3'],
            anal4: ['#anal4'],
            tabla1: ['#ctabla1'],
            tabla2: ['#ctabla2'],
            tabla3: ['#ctabla3'],
            tabla3_1: ['#ctabla3_1']
        };

        $(document).ready(function() {
            cargarMicrorred();
            cargarDistritos();

        });

        function cargarCards() {
            panelGraficas('head');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
            panelGraficas('tabla3');
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('salud.ipress.dashboard.contenido') }}",
                data: {
                    'div': div,
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "red": $('#red').val(),
                    "microrred": $('#microrred').val(),
                    "ubigeo": ubigeo_select,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    // if (spinners[div]) {
                    //     spinners[div].forEach(selector => {
                    //         $(selector).html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                    //     });
                    // }
                },
                success: function(data) {
                    switch (div) {
                        case 'head':
                            $('#card1').text(data.card1).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            $('#card2').text(data.card2).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            $('#card3').text(data.card3).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            $('#card4').text(data.card4).counterUp({
                                delay: 10,
                                time: 1000
                            });
                            break;

                        case 'anal1':
                            GaugeSeries('anal1', data.avance, 'Menores de 1 año Visitados');
                            break;
                        case 'anal2':
                            GaugeSeries('anal2', data.avance, 'Menores de 1 año con DNI');
                            break;
                        case 'anal3':
                            GaugeSeries('anal3', data.avance, 'Menores de 1 año con Seguro Salud');
                            break;
                        case 'anal4':
                            GaugeSeries('anal4', data.avance, 'Menores de 1 año con EESS');
                            break;
                        case 'tabla1':
                            $('#ctabla1').html(data.excel);
                            $('#tabla1').DataTable({
                                responsive: true,
                                autoWidth: false,
                                ordered: true,
                                language: table_language,
                                searching: false,
                                paging: false,
                                info: false,
                                // columnDefs: [{
                                //     targets: 1,
                                //     render: function(data, type, row) {
                                //         return `<a href="#" onclick="abrirmodalcentropoblado(${data})">${data}</a>`;
                                //     }
                                // }],
                                footerCallback: function(row, data, start, end, display) {
                                    var api = this.api();

                                    var intVal = function(i) {
                                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') *
                                            1 :
                                            typeof i === 'number' ? i : 0;
                                    };

                                    var c02 = api.column(2, { page: 'current' }) .data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                                    var c03 = api.column(3, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c04 = api.column(4, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c05 = api.column(5, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c06 = api.column(6, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c07 = api.column(7, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c08 = api.column(8, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c09 = api.column(9, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c10 = api.column(10, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c11 = api.column(11, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c12 = api.column(12, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c13 = api.column(13, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);

                                    $(api.column(2).footer()).html(c02);
                                    $(api.column(3).footer()).html(c03);
                                    $(api.column(4).footer()).html(c04);
                                    $(api.column(5).footer()).html(c05);
                                    $(api.column(6).footer()).html(c06);
                                    $(api.column(7).footer()).html(c07);
                                    $(api.column(8).footer()).html(c08);
                                    $(api.column(9).footer()).html(c09);
                                    $(api.column(10).footer()).html(c10);
                                    $(api.column(11).footer()).html(c11);
                                    $(api.column(12).footer()).html(c12);
                                    $(api.column(13).footer()).html(c13);
                                }
                            });
                            break;
                        case 'tabla2':
                            $('#ctabla2').html(data.excel);
                            $('#tabla2').DataTable({
                                responsive: true,
                                autoWidth: false,
                                ordered: true,
                                language: table_language,
                                searching: false,
                                paging: false,
                                info: false,
                                // columnDefs: [{
                                //     targets: 1,
                                //     render: function(data, type, row) {
                                //         return `<a href="#" onclick="abrirmodalcentropoblado(${data})">${data}</a>`;
                                //     }
                                // }],
                                footerCallback: function(row, data, start, end, display) {
                                    var api = this.api();

                                    var intVal = function(i) {
                                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') *
                                            1 :
                                            typeof i === 'number' ? i : 0;
                                    };

                                    var c02 = api.column(2, { page: 'current' }) .data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                                    var c03 = api.column(3, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c04 = api.column(4, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c05 = api.column(5, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c06 = api.column(6, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c07 = api.column(7, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c08 = api.column(8, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c09 = api.column(9, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c10 = api.column(10, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c11 = api.column(11, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c12 = api.column(12, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);
                                    var c13 = api.column(13, { page: 'current' }) .data() .reduce(function(a, b) { return intVal(a) + intVal(b);}, 0);

                                    $(api.column(2).footer()).html(c02);
                                    $(api.column(3).footer()).html(c03);
                                    $(api.column(4).footer()).html(c04);
                                    $(api.column(5).footer()).html(c05);
                                    $(api.column(6).footer()).html(c06);
                                    $(api.column(7).footer()).html(c07);
                                    $(api.column(8).footer()).html(c08);
                                    $(api.column(9).footer()).html(c09);
                                    $(api.column(10).footer()).html(c10);
                                    $(api.column(11).footer()).html(c11);
                                    $(api.column(12).footer()).html(c12);
                                    $(api.column(13).footer()).html(c13);
                                }
                            });
                            break;
                        case 'tabla3':
                            $('#ctabla3').html(data.excel);
                            $('#tabla3').DataTable({
                                responsive: true,
                                autoWidth: false,
                                ordered: true,
                                language: table_language,
                                // searching: false,
                                // paging: false,
                                // info: false,
                                // columnDefs: [{
                                //     targets: 1,
                                //     render: function(data, type, row) {
                                //         return `<a href="#" onclick="abrirmodalcentropoblado(${data})">${data}</a>`;
                                //     }
                                // }]
                            });
                            break;

                        case 'tabla3_1':
                            $('#ctabla3_1').html(data.excel);
                            $('#tabla3_1').DataTable({
                                responsive: true,
                                autoWidth: false,
                                ordered: true,
                                destroy: true,
                                language: table_language,
                                // searching: false,
                                // paging: false,
                                // info: false,
                            });
                            break;

                        default:
                            break;
                    }

                },
                erro: function(jqXHR, textStatus, errorThrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function cargarDistritos() {
            $.ajax({
                url: "{{ route('ubigeo.distrito.25', '') }}/" + $('#provincia').val(),
                type: 'GET',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data, function(index, value) {
                        options += "<option value='" + value.id + "'>" + value.nombre +
                            "</option>"
                    });
                    $("#distrito").append(options);
                    cargarCards();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarMicrorred() {
            $.ajax({
                url: "{{ route('microred.cargar.find.2', '') }}/" + $('#red').val(),
                type: 'GET',
                success: function(data) {
                    $("#microrred option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data, function(index, value) {
                        options += `<option value='${value.id}'>${value.nombre}</option>`;
                    });
                    $("#microrred").append(options);
                    // cargarCards();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function abrirmodalcentropoblado(ubigeo) {
            ubigeo_select = ubigeo;
            panelGraficas('tabla3_1');

            $('#modal-centropoblado').modal('show');

        }

        function abrirmodalconsultas() {
            $('#modal-consulta').modal('show');
        }

        function descargarExcel(div) {
            window.open(
                "{{ route('salud.padronnominal.tablerocalidad.exportar.excel', ['div' => 'div', 'importacion' => 0, 'anio' => 'anio', 'mes' => 'mes', 'provincia' => 'provincia', 'distrito' => 'distrito', 'ubigeo' => 'ubigeo']) }}"
                .replace('div', div)
                .replace('anio', $('#anio').val())
                .replace('mes', $('#mes').val())
                .replace('provincia', $('#provincia').val())
                .replace('distrito', $('#distrito').val())
                .replace('ubigeo', ubigeo_select)
            );
        }
        // https://www.google.com/maps?q=-8.3928622,-74.5826166 (HOSPITAL AMAZONICO - YARINACOCHA)

        function abrirMapa(la, lo, ipress) {
            let query = encodeURIComponent(ipress);
            window.open(`https://www.google.com/maps?q=${la},${lo} (${query})`, '_blank');
        }

        function abrirPagina(idipress) {
        // window.location.href = 'http://app20.susalud.gob.pe:8080/registro-renipress-webapp/ipress.htm?action=mostrarVer&idipress=' + idipress + '#no-back-button';
        window.open('http://app20.susalud.gob.pe:8080/registro-renipress-webapp/ipress.htm?action=mostrarVer&idipress=' + idipress + '#no-back-button', '_blank');
    }
    </script>


    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}
@endsection
