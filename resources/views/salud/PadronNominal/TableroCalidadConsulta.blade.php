@extends('layouts.main', ['activePage' => 'importacion', 'titlePage' => ''])

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <style>

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card m-0">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-orange-0 btn-xs" onclick="history.back()" title='Volver'><i
                                class="fas fa-arrow-left"></i> Volver</button>
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Imprimir</button>
                    </div>
                    <h3 class="card-title text-white font-12">
                        Busqueda de Niños y Niñas Menores de 6 años del Padron
                        Nominal
                    </h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="tipodocumento">Tipo de Documento</label>
                                <select id="tipodocumento" name="tipodocumento" class="form-control " onchange="">
                                    <option value="DNI">DNI</option>
                                    <option value="CNV">CNV</option>
                                    <option value="CUI">CUI</option>
                                    <option value="Padron">CÓDIGO PADRÓN</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="numerodocumento">Documento</label>
                                <input type="number" id="numerodocumento" name="numerodocumento" class="form-control">
                            </div>


                        </div>
                        <div class="col-lg-4 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="apellidosnombres">Apellidos y Nombres</label>
                                <input type="text" id="apellidosnombres" name="apellidosnombres" class="form-control">
                            </div>


                        </div>
                        <div class="col-lg-1 col-md-2 col-sm-2 text-center">
                            <button type="button" class="btn btn-success-0" onclick="consultahacer()">
                                {{-- <i class="fa fa-redo"></i> --}} Consultar</button>
                        </div>
                        <div class="col-lg-1 col-md-2 col-sm-2 text-center">
                            <button type="button" class="btn btn-orange-0" onclick="consultalimpiar()">
                                {{-- <i class="fa fa-redo"></i> --}} Limpiar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row d-none">
        <div class="col-lg-12">
            <div class="card m-0">
                {{-- <div class="card-header">
                    <h3 class="card-title">Población de niños y niñas menos de 6 años por distrito, segun sexo y edades
                    </h3>
                </div> --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tabla1" class="table table-sm table-striped table-bordered font-10">
                                    <thead>
                                        <tr class="table-success-0 text-white">
                                            <th class="text-center">N°</th>
                                            <th class="text-center">Cód. Padrón</th>
                                            <th class="text-center">Tipo Doc.</th>
                                            <th class="text-center">Documento</th>
                                            <th class="text-center">Apellidos y Nombre</th>
                                            <th class="text-center">Fecha Nacimiento</th>
                                            <th class="text-center">Distrito</th>
                                            <th class="text-center">Centro Poblado</th>
                                            <th class="text-center">Cod. EESS</th>
                                            <th class="text-center">EESS de Atención</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="formulario" style="display: none">
        <div class="col-lg-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h3 class="card-title">Población de niños y niñas menos de 6 años por distrito, segun sexo y edades
                    </h3>
                </div> --}}
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="sdsds" class="table table-striped table-bordered font-12 text-dark">
                                    <tbody>
                                        <tr>
                                            <td class="text-left table-success-0 text-white" colspan="6">DATOS DEL MENOR
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">CÓDIGO PADRÓN</td>
                                            <td id="padron"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">TIPO DOCUMENTO</td>
                                            <td id="tipodoc"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">DOCUMENTO</td>
                                            <td id="doc"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">APELLIDO PATERNO</td>
                                            <td id="apepat"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">APELLIDO MATERNO</td>
                                            <td id="apemat"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">NOMBRES</td>
                                            <td id="nom"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">SEXO</td>
                                            <td id="sexo"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">FECHA DE NACIMIENTO
                                            </td>
                                            <td id="nacimiento"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">EDAD</td>
                                            <td id="edad"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">DEPARTAMENTO</td>
                                            <td id="dep"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">PROVINCIA</td>
                                            <td id="pro"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">DISTRITO</td>
                                            <td id="dis"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">CENTRO POBLADO</td>
                                            <td id="cp"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">DIRECCIÓN</td>
                                            <td id="dir" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">EESS NACIMIENTO</td>
                                            <td id="esn"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">ULTIMO EESS ATENCIÓN
                                            </td>
                                            <td id="esa"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">VISITA DOMICILIARIA
                                            </td>
                                            <td id="visita"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">ENCONTRADO</td>
                                            <td id="encontrado"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">TIPO DE SEGURO</td>
                                            <td id="seguro"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">PROGRAMA SOCIAL</td>
                                            <td id="programa"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">INSTITUTCIÓN
                                                EDUCATIVA</td>
                                            <td></td>
                                            <td class="text-right" style="background-color: #D4F2F0">NIVEL EDUCATIVO</td>
                                            <td></td>
                                            <td class="text-right" style="background-color: #D4F2F0">GRADO Y SECCIÓN</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left table-success-0 text-white" colspan="6">DATOS DE LA
                                                MADRE</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">APODERADO</td>
                                            <td id="mapoderado"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">TIPO DOCUMENTO</td>
                                            <td id="mtipodoc"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">DOCUMENTO</td>
                                            <td id="mdoc"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">APELLIDO PATERNO</td>
                                            <td id="mapepat"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">APELLIDO MATERNO</td>
                                            <td id="mapemat"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">NOMBRES</td>
                                            <td id="mnom"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-right" style="background-color: #D4F2F0">CELULAR</td>
                                            <td id="mcel"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">GRADO DE INSTRUCCIÓN
                                            </td>
                                            <td id="mgrado"></td>
                                            <td class="text-right" style="background-color: #D4F2F0">LENGUA HABITUAL</td>
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
@endsection

@section('js')
    <script type="text/javascript">
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        var tableprincipal;
        $(document).ready(function() {

        });

        function consultahacer() {

            var tip = $('#tipodocumento').val();
            var doc = $('#numerodocumento').val();
            var ape = $('#apellidosnombres').val();
            if (doc != '' || ape != '') {
                $.ajax({
                    url: "{{ route('salud.padronnominal.tablerocalidad.consulta.find1', ['importacion' => $importacion, 'tipo' => 'tipo', 'documento' => 'documento', 'apellido' => 'apellido']) }}"
                        .replace('tipo', tip).replace('documento', doc == '' ? documento : doc)
                        .replace('apellido', ape == '' ? 'apellido' : ape),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (Object.keys(data).length > 0) {s
                            $('#padron').html(data.padron);
                            $('#tipodoc').html(data.tipo_doc == 'Padron' ? '' : data.tipo_doc);
                            $('#doc').html(data.tipo_doc == 'Padron' ? '' : data.num_doc);
                            $('#apepat').html(data.apellido_paterno);
                            $('#apemat').html(data.apellido_materno);
                            $('#nom').html(data.nombre);
                            $('#sexo').html(data.genero == 'M' ? 'MASCULINO' : 'FEMENINO');
                            $('#nacimiento').html(data.fecha_nacimiento);
                            $('#edad').html(data.edad + ' ' +
                                (data.tipo_edad == 'D' ? 'DIAS' : (data.tipo_edad == 'M' ? 'MESES' :
                                    'AÑOS')));
                            $('#dep').html(data.departamento);
                            $('#pro').html(data.provincia);
                            $('#dis').html(data.distrito);
                            $('#cp').html(data.centro_poblado_nombre);
                            $('#dir').html(data.direccion);
                            $('#esn').html(data.cui_nacimiento);
                            $('#esa').html(data.cui_atencion);
                            $('#visita').html(data.visita);
                            $('#encontrado').html(data.menor_encontrado);
                            $('#seguro').html(data.seguro);
                            $('#programa').html(data.programa_social);
                            $('#mapoderado').html(data.apoderado);
                            $('#mtipodoc').html(data.tipo_doc_madre);
                            $('#mdoc').html(data.num_doc_madre);
                            $('#mapepat').html(data.apellido_paterno_madre);
                            $('#mapemat').html(data.apellido_materno_madre);
                            $('#mnom').html(data.nombres_madre);
                            $('#mcel').html(data.celular_madre);
                            $('#mgrado').html(data.grado_instruccion);
                            $('#mlengua').html(data.lengua_madre);

                            $('#formulario').show();
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                    },
                });

            } else {
                toastr.error('CAMPOS VACIOS', 'Mensaje');
            }

        }

        function consultalimpiar() {
            $('#formulario').hide();
            $('#numerodocumento').val('');
            $('#apellidosnombres').val('');
        }


        function cargarCards() {
            tableprincipal = $('#tabla1').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                language: table_language,
                destroy: true,
                // ajax: "{{ route('salud.padronnominal.tablerocalidad.criterio.listar') }}",
                // type: "get",
                ajax: {
                    url: "{{ route('salud.padronnominal.tablerocalidad.criterio.listar') }}",
                    type: "GET",
                    data: function(d) {
                        d.importacion = {{ $importacion }};
                        d.establecimiento = $('#establecimiento').val();
                        d.microred = $('#microred').val();
                        d.red = $('#red').val();
                        d.desa = 0;
                    }
                },
                columnDefs: [{
                    className: 'text-center',
                    targets: [0, 1, 2, 3, 5, 6, 8]
                }, {
                    targets: 1,
                    render: function(data, type, row) {
                        // return '<a href="/ruta/detalle/' + row + '">' + data + '</a>';
                        return `<a href="#" onclick="abrirmodalpadron(${data})">${data}</a>`;
                    }
                }, {
                    targets: 8,
                    render: function(data, type, row) {
                        // return '<a href="/ruta/detalle/' + row + '">' + data + '</a>';
                        // console.log(parseInt(data, 10));
                        return data ?
                            `<a href="#" onclick="abrirmodaleess(${parseInt(data, 10)})">${data}</a>` :
                            '';
                    }
                }]
            });
        }

        function abrirmodalpadron(padron) {
            $('#modal-nino').modal('show');
            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.criterio.find1', ['importacion' => $importacion, 'padron' => 'padron']) }}"
                    .replace('padron', padron),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#padron').html(data.padron);
                    $('#tipodoc').html(data.tipo_doc == 'Padron' ? '' : data.tipo_doc);
                    $('#doc').html(data.tipo_doc == 'Padron' ? '' : data.num_doc);
                    $('#apepat').html(data.apellido_paterno);
                    $('#apemat').html(data.apellido_materno);
                    $('#nom').html(data.nombre);
                    $('#sexo').html(data.genero == 'M' ? 'MASCULINO' : 'FEMENINO');
                    $('#nacimiento').html(data.fecha_nacimiento);
                    $('#edad').html(data.edad + ' ' + (data.tipo_edad == 'D' ? 'DIAS' : (data.tipo_edad == 'M' ?
                        'MESES' : 'AÑOS')));
                    $('#dep').html(data.departamento);
                    $('#pro').html(data.provincia);
                    $('#dis').html(data.distrito);
                    $('#cp').html(data.centro_poblado_nombre);
                    $('#dir').html(data.direccion);
                    $('#esn').html(data.cui_nacimiento);
                    $('#esa').html(data.cui_atencion);
                    $('#visita').html(data.visita);
                    $('#encontrado').html(data.menor_encontrado);
                    $('#seguro').html(data.seguro);
                    $('#programa').html(data.programa_social);
                    $('#mapoderado').html(data.apoderado);
                    $('#mtipodoc').html(data.tipo_doc_madre);
                    $('#mdoc').html(data.num_doc_madre);
                    $('#mapepat').html(data.apellido_paterno_madre);
                    $('#mapemat').html(data.apellido_materno_madre);
                    $('#mnom').html(data.nombres_madre);
                    $('#mcel').html(data.celular_madre);
                    $('#mgrado').html(data.grado_instruccion);
                    $('#mlengua').html(data.lengua_madre);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }

        function abrirmodaleess(cui) {
            $('#modal-eess').modal('show');
            $.ajax({
                url: "{{ route('salud.padronnominal.tablerocalidad.criterio.find2', ['importacion' => $importacion, 'cui' => 'cui']) }}"
                    .replace('cui', cui),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#eesscui').html(data.codigo_unico);
                    $('#eessnombre').html(data.nombre_establecimiento);
                    $('#eessdisa').html(data.disa);
                    $('#eessred').html(data.red);
                    $('#eessmicro').html(data.micro);
                    $('#eessdep').html(data.departamento);
                    $('#eesspro').html(data.provincia);
                    $('#eessdis').html(data.distrito);
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
                },
                type: "GET",
                dataType: "JSON",
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
                            // console.log(data.avance);
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

        function GaugeSeries(div, data, title) {
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
