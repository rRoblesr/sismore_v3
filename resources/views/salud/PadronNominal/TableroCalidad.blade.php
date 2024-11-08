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
                        <button type="button" class="btn btn-danger btn-xs"
                            onclick="window.location.href=`{{ route('salud.padronnominal.tablerocalidad.consulta') }}`">
                            <i class="fas fa-search"></i> Consultas</button>
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Actualizar</button>
                    </div>
                    <h3 class="card-title text-white font-14">Control de Calidad del padrón nominal de niños y niñas menores
                        de 6 años</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <h4 class="page-title font-12">{{ $actualizado }}</h4>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">AÑO</label>
                                <select id="anio" name="anio" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    @foreach ($anios as $item)
                                        <option value="{{ $item->anio }}"
                                            {{ $item->anio == date('Y') ? 'selected' : '' }}>
                                            {{ $item->anio }}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">MES</label>
                                <select id="mes" name="mes" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    {{-- <option value="0">TODOS</option> --}}
                                    {{-- @foreach ($anios as $item)
                                    <option value="{{ $item->id }}" {{ $item->anio == $maxAnio ? 'selected' : '' }}>
                                        {{ $item->anio }}</option>
                                @endforeach --}}
                                </select>
                            </div>


                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">PROVINCIA</label>
                                <select id="provincia" name="provincia" class="form-control btn-xs font-11"
                                    onchange="cargarDistritos();cargarCards();">
                                    <option value="0">TODOS</option>
                                    @foreach ($provincias as $item)
                                        <option value="{{ $item->id }}"> {{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="provincia">DISTRITO</label>
                                <select id="distrito" name="distrito" class="form-control btn-xs font-11"
                                    onchange="cargarCards();">
                                    <option value="0">TODOS</option>
                                    {{-- @foreach ($distritos as $item)
                                        <option value="{{ $item->id }}">{{ $item->nombre }}
                                        </option>
                                    @endforeach --}}
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
                            <p class="mb-0 mt-1 text-truncate">Total Niños y Niñas</p>
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
                            <p class="mb-0 mt-1 text-truncate">Niños y Niñas con DNI </p>
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
                            <p class="mb-0 mt-1 text-truncate">Niños y Niñas con Seguro</p>
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
                            <p class="mb-0 mt-1 text-truncate">Niños y Niñas con EESS</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal1"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal2"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal3"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card card-border border border-plomo-0">
                <div class="card-header border-success-0 bg-transparent pb-0 pt-0">
                    <h3 class="card-title text-black text-center text-capitalize font-weight-normal font-11"></h3>
                </div>
                <div class="card-body p-0">
                    <div id="anal4"></div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h3 class="card-title">Población de niños y niñas menos de 6 años por distrito, segun sexo y edades
                    </h3>
                </div> --}}
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
                        <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla2')">
                            <i class="fa fa-file-excel"></i> Descargar</button>
                    </div>
                    <h3 class="card-title">Población del Padrón Nominal de niños y niñas menores de 6 años por Seguro De
                        Salud, Según Sexo y Edades
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
                    <h3 class="card-title">Población de niños y niñas menos de 6 años por distrito, segun sexo y edades
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
        $(document).ready(function() {
            cargarMes();
            cargarDistritos();
            cargarCards();

        });

        function cargarCards() {
            panelGraficas('head');
            panelGraficas('anal1');
            panelGraficas('anal2');
            panelGraficas('anal3');
            panelGraficas('anal4');
            panelGraficas('tabla1');
            panelGraficas('tabla2');
            panelGraficas('tabla3');
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
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function cargarMes() {
            $.ajax({
                url: "{{ route('salud.padronnominal.mes', '') }}/" + $('#anio').val(),
                type: 'GET',
                success: function(data) {
                    $("#mes option").remove();
                    var options = ''; // '<option value="0">TODOS</option>';

                    var mesmax = Math.max(...data.map(item => item.id));
                    // console.log("Mes máximo:", mesmax);
                    $.each(data, function(ii, vv) {
                        ss = vv.id == mesmax ? 'selected' : '';
                        options += `<option value='${vv.id}' ${ss}>${vv.mes}</option>`
                    });
                    $("#mes").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function panelGraficas(div) {
            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.reporte') }}",
                data: {
                    'div': div,
                    "anio": $('#anio').val(),
                    "mes": $('#mes').val(),
                    "provincia": $('#provincia').val(),
                    "distrito": $('#distrito').val(),
                    "ubigeo": ubigeo_select,
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    switch (div) {
                        case 'head':
                            $('#card1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            $('#card2').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            $('#card3').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            $('#card4').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            break;
                        case 'anal1':
                            $('#anal1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            break;
                        case 'anal2':
                            $('#anal2').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            break;
                        case 'anal3':
                            $('#anal3').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            break;
                        case 'anal4':
                            $('#anal4').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            break;
                        case 'tabla1':
                            $('#ctabla1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            break;
                        case 'tabla2':
                            $('#ctabla2').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            break;
                        case 'tabla3':
                            $('#ctabla3').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            break;
                        case 'tabla3_1':
                            $('#ctabla3_1').html('<span><i class="fa fa-spinner fa-spin"></i></span>');
                            break;

                        default:
                            break;
                    }
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
                            console.log(data.avance);
                            GaugeSeries('anal1', data.avance, 'Porcentaje de Visitados');
                            break;
                        case 'anal2':
                            GaugeSeries('anal2', data.avance, 'Porcentaje con DNI');
                            break;
                        case 'anal3':
                            GaugeSeries('anal3', data.avance, 'Porcentaje con Seguro Salud');
                            break;
                        case 'anal4':
                            GaugeSeries('anal4', data.avance, 'Porcentaje con EESS de atención');
                            break;
                        case 'tabla1':
                            $('#ctabla1').html(data.excel);
                            break;
                        case 'tabla2':
                            $('#ctabla2').html(data.excel);
                            // $('#tabla2').DataTable({
                            //     responsive: true,
                            //     autoWidth: false,
                            //     ordered: true,
                            //     language: table_language,
                            // });
                            break;
                        case 'tabla3':
                            $('#ctabla3').html(data.excel);
                            $('#tabla3').DataTable({
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

        function abrirmodalcentropoblado(ubigeo) {
            ubigeo_select = ubigeo;
            // console.log(ubigeo_select)]
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

        function GaugeSeriesbbbbb(div, data, title) {
            Highcharts.chart(div, {
                chart: {
                    height: 200,
                    type: 'solidgauge',
                    margin: [10, 10, 10, 10],
                    spacing: [10, 10, 10, 10],
                    events: {
                        load: function() {
                            var chart = this,
                                point = chart.series[0].points[0],
                                startValue = 0,
                                endValue = data,
                                duration = 1000, // Duración en milisegundos
                                increment = endValue / (duration / 20); // Aumento cada 20ms

                            function animateValue() {
                                startValue += increment;
                                if (startValue >= endValue) {
                                    startValue = endValue; // Detiene la animación en el valor final
                                } else {
                                    setTimeout(animateValue, 20); // Continúa la animación cada 20ms
                                }
                                point.update(Math.round(startValue,
                                    1)); // Actualiza tanto el relleno como el valor
                            }

                            animateValue(); // Inicia la animación
                        }
                    }
                },
                title: {
                    text: title,
                    verticalAlign: 'top',
                    style: {
                        fontSize: '12px',
                        fontWeight: 'normal'
                    }
                },
                pane: {
                    startAngle: 0,
                    endAngle: 360,
                    background: [{ // Fondo del anillo
                        outerRadius: '100%',
                        innerRadius: '80%',
                        backgroundColor: Highcharts.color('#E0E0E0').setOpacity(0.3).get(),
                        borderWidth: 0
                    }]
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    lineWidth: 0,
                    tickPositions: []
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            format: '<div style="text-align:center"><span style="font-size:24px">{y}%</span><br/>' +
                                '<span style="font-size:12px;opacity:0.6">Avance</span></div>',
                            y: -25,
                            borderWidth: 0,
                            useHTML: true
                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    data: [{
                        y: 0, // Empieza en 0 y se incrementará hasta `data`
                        color: data >= 95 ? '#5eb9aa' : (data >= 75 ? '#f5bd22' : '#ef5350')
                    }],
                    innerRadius: '80%',
                    radius: '100%',
                }],
                tooltip: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                }
            });
        }

        function GaugeSeries(div, data, title) {
            Highcharts.chart(div, {
                chart: {
                    height: 200,
                    type: 'solidgauge',
                    margin: [10, 10, 10, 10],
                    spacing: [10, 10, 10, 10],
                    events: {
                        load: function() {
                            var chart = this,
                                point = chart.series[0].points[0],
                                startValue = 0.0,
                                endValue = data,
                                duration = 1000, // Duración en milisegundos
                                increment = endValue / (duration / 20); // Aumentar cada 20ms

                            function animateValue() {
                                startValue += increment;
                                if (startValue >= endValue) {
                                    startValue = endValue; // Detiene la animación en el valor final
                                } else {
                                    setTimeout(animateValue, 20); // Continúa la animación cada 20ms
                                }
                                // point.update(startValue);
                                point.update(Math.round(startValue, 2));
                                // point.update(Highcharts.numberFormat(startValue, 1));
                            }

                            animateValue(); // Inicia la animación
                        }
                    }
                },
                title: {
                    text: title,
                    verticalAlign: 'top',
                    style: {
                        fontSize: '12px',
                        fontWeight: 'normal'
                    }
                },
                pane: {
                    startAngle: 0,
                    endAngle: 360,
                    background: [{ // Fondo del anillo
                        outerRadius: '100%',
                        innerRadius: '80%',
                        backgroundColor: Highcharts.color('#E0E0E0').setOpacity(0.3).get(),
                        borderWidth: 0
                    }]
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    lineWidth: 0,
                    tickPositions: []
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            format: '<div style="text-align:center"><span style="font-size:24px">{y}%</span><br/>' +
                                '<span style="font-size:12px;opacity:0.6">Avance</span></div>',
                            y: -25,
                            borderWidth: 0,
                            useHTML: true
                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    data: [{
                        y: 0, // Empieza en 0 y se incrementará a `data`
                        color: data >= 95 ? '#5eb9aa' : (data >= 75 ? '#f5bd22' : '#ef5350')
                    }],
                    innerRadius: '80%',
                    radius: '100%',
                }],
                tooltip: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                }
            });
        }

        function GaugeSeriesxxxx(div, data, title) {
            Highcharts.chart(div, {
                chart: {
                    height: 200,
                    type: 'solidgauge',
                    margin: [10, 10, 10, 10],
                    spacing: [10, 10, 10, 10]
                },
                title: {
                    text: title,
                    verticalAlign: 'top',
                    style: {
                        fontSize: '12px',
                        fontWeight: 'normal'
                    }
                },
                pane: {
                    startAngle: 0,
                    endAngle: 360,
                    background: [{ // Fondo del anillo
                        outerRadius: '100%',
                        innerRadius: '80%',
                        backgroundColor: Highcharts.color('#E0E0E0').setOpacity(0.3).get(),
                        borderWidth: 0
                    }]
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    lineWidth: 0,
                    tickPositions: []
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            format: '<div style="text-align:center"><span style="font-size:24px">{y}%</span><br/>' +
                                '<span style="font-size:12px;opacity:0.6">Avance</span></div>',
                            y: -25,
                            borderWidth: 0,
                            useHTML: true
                        },
                        // linecap: 'round',
                        // rounded: true
                    }
                },
                series: [{
                    name: 'Avance',
                    data: [{
                        y: data,
                        color: data >= 95 ? '#5eb9aa' : (data >= 75 ? '#f5bd22' :
                            '#ef5350')
                    }],
                    innerRadius: '80%',
                    radius: '100%',
                }],
                tooltip: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                }
            });
        }

        function GaugeSeriesyyy(div, data) {
            Highcharts.chart(div, {
                chart: {
                    height: 165,
                    margin: [0, 0, 0, 0],
                    spacing: [0, 0, 0, 0],
                    type: 'solidgauge'
                },
                yAxis: {
                    min: 0,
                    max: 150,
                    dataClasses: [{
                            from: 0,
                            to: 50,
                            color: '#ef5350'
                        },
                        {
                            from: 51,
                            to: 99,
                            color: '#f5bd22'
                        },
                        {
                            from: 100,
                            to: 150,
                            color: '#5eb9aa'
                        }
                    ],
                    labels: {
                        enabled: false
                    },
                    tickLength: 0,
                    lineWidth: 0, // Remueve la línea del borde del gauge
                    gridLineWidth: 0 // Elimina las líneas de división
                },
                pane: {
                    background: [{
                        // Sin borde, remueve el efecto de seccionado
                        outerRadius: '100%',
                        innerRadius: '80%',
                        borderWidth: 0
                    }]
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                title: {
                    text: ''
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            format: '<div style="text-align:center; margin-top: -20px">' +
                                '<div style="font-size:2.5em;">{y}%</div>' +
                                '<div style="font-size:12px; opacity:0.4;">Avance</div>' +
                                '</div>',
                            useHTML: true,
                            borderWidth: 0,
                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    innerRadius: '80%',
                    data: [{
                        y: data,
                        color: data >= 100 ? '#5eb9aa' : (data >= 51 ? '#f5bd22' :
                            '#ef5350')
                    }],
                    radius: '100%',
                }],
                tooltip: {
                    valueSuffix: '%',
                    backgroundColor: '#FFFFFF',
                    borderColor: 'gray',
                    shadow: true,
                    style: {
                        fontSize: '12px'
                    }
                }
            });
        }

        function GaugeSeriesxx(div, data) {
            Highcharts.chart(div, {
                chart: {
                    height: 165,
                    margin: [0, 0, 0, 0],
                    spacing: [0, 0, 0, 0],
                    type: 'solidgauge'
                },
                yAxis: {
                    min: 0,
                    max: 150, // Ajustado al máximo de dataClasses
                    dataClasses: [{
                            from: 0,
                            to: 50,
                            color: '#ef5350'
                        },
                        {
                            from: 51,
                            to: 99,
                            color: '#f5bd22'
                        },
                        {
                            from: 100,
                            to: 150,
                            color: '#5eb9aa'
                        }
                    ],
                    labels: {
                        enabled: false
                    },
                    tickLength: 0,
                    lineColor: 'transparent',
                    gridLineWidth: 0
                },
                pane: {
                    background: {
                        innerRadius: '80%',
                        outerRadius: '100%'
                    }
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                title: {
                    text: ''
                },
                plotOptions: {
                    solidgauge: {
                        dataLabels: {
                            format: '<div style="text-align:center; margin-top: -20px">' +
                                '<div style="font-size:2.5em;">{y}%</div>' +
                                '<div style="font-size:12px; opacity:0.4;">Avance</div>' +
                                '</div>',
                            useHTML: true,
                            borderWidth: 0,
                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    innerRadius: '80%',
                    data: [{
                        y: data,
                        color: data >= 100 ? '#5eb9aa' : (data >= 51 ? '#f5bd22' :
                            '#ef5350') // Ajuste de color por valor
                    }],
                    radius: '100%',
                }],
                tooltip: {
                    valueSuffix: '%',
                    backgroundColor: '#FFFFFF',
                    borderColor: 'gray',
                    shadow: true,
                    style: {
                        fontSize: '12px'
                    }
                }
            });
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
