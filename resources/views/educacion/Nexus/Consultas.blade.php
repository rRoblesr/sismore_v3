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
                    {{-- <div class="card-widgets">
                        <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()">
                            <i class="fa fa-redo"></i> Actualizar</button>
                    </div> --}}
                    <h3 class="card-title text-white font-14">BUSQUEDA DE PERSONAL DE EDUCACIÓN</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        {{-- <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="tipo">Tipo de Personal</label>
                                <select id="tipo" name="tipo" class="form-control font-12">
                                    <option value="0"> TODOS</option>
                                    @foreach ($subtipo as $key => $item)
                                        <option value="{{ $key }}"> {{ $item }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}


                        <div class="col-lg-3 col-md-2 col-sm-2">
                            <div class="custom-select-container">
                                <label for="documento">Documento</label>
                                <input type="text" id="documento" name="documento" class="form-control font-12"
                                    placeholder="Número de Documento" value="02424849">
                            </div>
                        </div>

                        <div class="col-lg-5 col-md-4 col-sm-4">
                            <div class="custom-select-container">
                                <label for="nombres">Apellidos y Nombres</label>
                                <input type="text" id="nombres" name="nombres" class="form-control font-12"
                                    placeholder="ingrese Apellidos y Nombres completos">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 align-self-end">
                            <button type="button" class="btn btn-primary btn-block font-12"
                                onclick="consultar('consulta')">
                                <i class="fa fa-search"></i> Consultar</button>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 align-self-end">
                            <button type="button" class="btn btn-danger btn-block font-12" onclick="location.reload()">
                                <i class="fa fa-redo"></i> Limpiar</button>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row d-none" id="dato_personales">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <h3 class="card-title text-white font-14">DATOS PERSONALES</h3>
                </div>
                <div class="card-body px-0">
                    <table class="table table-bordered table-striped table-hover font-12 m-0 text-dark">
                        <tbody>
                            <tr>
                                <td class="text-left table-cyan">DNI</td>
                                <td class="text-left" id="v01"></td>
                                <td class="text-left table-cyan">APELLIDOS</td>
                                <td class="text-left" id="v02"></td>
                                <td class="text-left table-cyan">NOMBRES</td>
                                <td class="text-left" id="v03"></td>
                            </tr>
                            <tr>
                                <td class="text-left table-cyan">SEXO</td>
                                <td class="text-left" id="v04"></td>
                                <td class="text-left table-cyan">FECHA NACIMIENTO</td>
                                <td class="text-left" id="v05"></td>
                                <td class="text-left table-cyan">EDAD</td>
                                <td class="text-left" id="v06"></td>
                            </tr>
                            <tr>
                                <td class="text-left table-cyan">TIPO DE ESTUDIO</td>
                                <td class="text-left" id="v07"></td>
                                <td class="text-left table-cyan">PROFESIÓN</td>
                                <td class="text-left" id="v08"></td>
                                <td class="text-left table-cyan">GRADO OBTENIDO</td>
                                <td class="text-left" id="v09"></td>
                            </tr>
                            <tr>
                                <td class="text-left table-cyan">REGIMEN PENSIONARIO</td>
                                <td class="text-left" id="v10"></td>
                                <td class="text-left table-cyan">AFP</td>
                                <td class="text-left" id="v11"></td>
                                <td class="text-left table-cyan">LEY</td>
                                <td class="text-left" id="v12"></td>
                            </tr>
                            <tr>
                                <td class="text-left table-cyan">SITUACION LABORAL</td>
                                <td class="text-left" id="v13"></td>
                                <td class="text-left table-cyan">FECHA NOMBRAMIENTO</td>
                                <td class="text-left" id="v14"></td>
                                <td class="text-left table-cyan">ESCALA REMUNERATIVA</td>
                                <td class="text-left" id="v15"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row d-none" id="dato_laborales">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <h3 class="card-title text-white font-14">DATOS LABORALES</h3>
                </div>
                <div class="card-body px-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive" id="ctabla1">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Modal content for the above example -->
    <div class="modal fade" id="modal_iiee" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display:none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Datos de la Institucion Educativa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered font-12 text-dark">
                                    <tbody>
                                        <tr>
                                            <td class="text-left table-cyan">UGEL</td>
                                            <td class="text-left" id="i01"></td>
                                            <td class="text-left table-cyan">PROVINCIA</td>
                                            <td class="text-left" id="i02"></td>
                                            <td class="text-left table-cyan">DISTRITO</td>
                                            <td class="text-left" id="i03"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left table-cyan">INSTITUCION EDUCATIVA</td>
                                            <td class="text-left" id="i04" colspan="3"></td>
                                            <td class="text-left table-cyan">COÓDIGO MODULAR</td>
                                            <td class="text-left" id="i05"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left table-cyan">CÓDIGO LOCAL</td>
                                            <td class="text-left" id="i06"></td>
                                            <td class="text-left table-cyan">TIPO DE I.E</td>
                                            <td class="text-left" id="i07"></td>
                                            <td class="text-left table-cyan">GESTIÓN</td>
                                            <td class="text-left" id="i08"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left table-cyan">ZONA</td>
                                            <td class="text-left" id="i09"></td>
                                            <td class="text-left table-cyan">NIVEL EDUCATIVO</td>
                                            <td class="text-left" id="i10"></td>
                                            <td class="text-left table-cyan">MODALIDAD</td>
                                            <td class="text-left" id="i11"></td>
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
        var iiee_select;
        $(document).ready(function() {});

        document.addEventListener('DOMContentLoaded', function() {
            const inputDoc = document.getElementById('documento');
            const inputNom = document.getElementById('nombres');

            function toggleDisable() {
                if (inputDoc.value.trim() !== '') {
                    inputNom.disabled = true;
                } else {
                    inputNom.disabled = false;
                }

                if (inputNom.value.trim() !== '') {
                    inputDoc.disabled = true;
                } else {
                    inputDoc.disabled = false;
                }
            }
            // Escuchar cambios en ambos campos
            inputDoc.addEventListener('input', toggleDisable);
            inputNom.addEventListener('input', toggleDisable);
        });

        function consultar(div) {
            $.ajax({
                url: "{{ route('educacion.nexus.consultas.reporte') }}",
                data: {
                    'div': div,
                    "tipo": $('#tipo').val(),
                    "dni": $('#documento').val(),
                    "nombre_completo": $('#nombres').val(),
                    'iiee': iiee_select
                },
                type: "GET",
                dataType: "JSON",
                beforeSend: function() {
                    // SpinnerManager.show(div);
                },
                success: function(data) {
                    console.log(data);
                    console.log('div:' + div);
                    switch (div) {
                        case 'consulta':
                            if (data.status == 'OK') {
                                $('#dato_personales').removeClass('d-none');
                                $('#v01').html(data.data.dni);
                                $('#v02').html(data.data.apellidos);
                                $('#v03').html(data.data.nombres);
                                $('#v04').html(data.data.sexo);
                                $('#v05').html(data.data.fn);
                                $('#v06').html(data.data.edad);
                                $('#v07').html(data.data.tipo_estudio);
                                $('#v08').html(data.data.profesion);
                                $('#v09').html(data.data.grado_obtenido);
                                $('#v10').html(data.data.regimen_pensionario);
                                $('#v11').html(data.data.afp);
                                $('#v12').html(data.data.ley);
                                $('#v13').html(data.data.situacion_laboral);
                                $('#v14').html(data.data.fr);
                                $('#v15').html(data.data.escala_remunerativa);

                                $('#dato_laborales').removeClass('d-none');
                                $('#ctabla1').html(data.excel);
                            } else {
                                $('#dato_personales').addClass('d-none');
                                $('#dato_laborales').addClass('d-none');
                                alert('NO SE ENCONTRARON DATOS');
                            }
                            break;
                        case 'tabla2':
                            $('#i01').html(data.base.ugel);
                            $('#i02').html(data.base.provincia);
                            $('#i03').html(data.base.distrito);
                            $('#i04').html(data.base.iiee);
                            $('#i05').html(data.base.modular);
                            $('#i06').html(data.base.local);
                            $('#i07').html(data.base.tipo);
                            $('#i08').html(data.base.gestion);
                            $('#i09').html(data.base.zona);
                            $('#i10').html(data.base.nivel);
                            $('#i11').html(data.base.modalidad);
                            $('#i12').html(data.base.provincia);
                            $('#i13').html(data.base.provincia);
                            break;

                        default:
                            break;
                    }
                },
                erro: function(jqXHR, textStatus, errortdrown) {
                    console.log("ERROR GRAFICA 1");
                    console.log(jqXHR);
                },
            });
        }

        function openiiee(iiee) {
            iiee_select = iiee;
            consultar('tabla2');
            $('#modal_iiee').modal('show');
        }
    </script>

    {{-- jrmt-mapero --}}
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/maps/modules/exporting.js"></script>

    <script src="{{ asset('/') }}public/us-ct-ally.js"></script>
    <script src="{{ asset('/') }}public/us-ct-allz.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
@endsection
