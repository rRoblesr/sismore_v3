@extends('layouts.main', ['titlePage' => 'BASE DE DATOS DE NEXUS'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

    <style>
        .tablex thead th {
            padding: 6px;
            text-align: center;
        }

        .tablex thead td {
            padding: 6px;
            text-align: center;
            vertical-align: middle;
            font-weight: bold;
        }

        .tablex tbody td,
        .tablex tbody th,
        .tablex tfoot td,
        .tablex tfoot th {
            padding: 3px;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <form action="form_llave">
            @csrf
        </form>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-border">
                    <div class="card-header bg-transparent pb-0">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-success btn-xs"
                                onclick="javascript:location=`{{ route('cuadroasigpersonal.download') }}`"><i
                                    class="fa fa-file-excel"></i>
                                Excel</button>
                        </div>
                        <h3 class="card-title">REGISTRO DE LA FECHA {{ date('d/m/Y', strtotime($imp->fechaActualizacion)) }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="table-responsive">
                                    <table id="siagie-matricula" class="table table-striped table-bordered tablex"
                                        style="font-size:12px;width:12600px">
                                        <thead class="bg-primary text-white">
                                            <th>UNIDAD_EJECUTORA</th>
                                            <th>UGEL</th>
                                            <th>PROVINCIA</th>
                                            <th>DISTRITO</th>
                                            <th>TIPO_IE</th>
                                            <th>GESTION</th>
                                            <th>ZONA</th>
                                            <th>CODMOD_IE</th>
                                            <th>CODIGO_LOCAL</th>
                                            <th>CLAVE8</th>
                                            <th>NIVEL_EDUCATIVO</th>
                                            <th>INSTITUCION_EDUCATIVA</th>
                                            <th>CODIGO_PLAZA</th>
                                            <th>TIPO_TRABAJADOR</th>
                                            <th>SUB_TIPO_TRABAJADOR</th>
                                            <th>CARGO</th>
                                            <th>SITUACION_LABORAL</th>
                                            <th>MOTIVO_VACANTE</th>
                                            <th>DOCUMENTO</th>
                                            <th>SEXO</th>
                                            <th>CODMOD_DOCENTE</th>
                                            <th>APELLIDO_PATERNO</th>
                                            <th>APELLIDO_MATERNO</th>
                                            <th>NOMBRES</th>
                                            <th>FECHA_INGRESO</th>
                                            <th>CATEGORIA_REMUNERATIVA</th>
                                            <th>JORNADA_LABORAL</th>
                                            <th>ESTADO</th>
                                            <th>FECHA_NACIMIENTO</th>
                                            <th>FECHA_INICIO</th>
                                            <th>FECHA_TERMINO</th>
                                            <th>TIPO_REGISTRO</th>
                                            <th>LEY</th>
                                            <th>PREVENTIVA</th>
                                            <th>ESPECIALIDAD</th>
                                            <th>TIPO_ESTUDIOS</th>
                                            <th>ESTADO_ESTUDIOS</th>
                                            <th>GRADO</th>
                                            <th>MENCION</th>
                                            <th>ESPECIALIDAD_PROFESIONAL</th>
                                            <th>FECHA_RESOLUCION</th>
                                            <th>NUMERO_RESOLUCION</th>
                                            <th>CENTRO_ESTUDIOS</th>
                                            <th>CELULAR</th>
                                            <th>EMAIL</th>
                                            <th>DESC_SUPERIOR</th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End row -->

    </div>
@endsection

@section('js')
    <script>
        var table_principal = '';
        $(document).ready(function() {
            table_principal = $('#siagie-matricula').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "responsive": false,
                    "autoWidth": false,
                    "ordered": true,
                    //"destroy": true,
                    "language": table_language,
                    "ajax": {
                        "headers": {
                            'X-CSRF-TOKEN': $('input[name=_token]').val()
                        },
                        "url": "{{ route('cuadroasigpersonal.listarimportados') }}",
                        "data": {
                            "importacion_id": {{ $imp->id }}
                        },
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'unidad_ejecutora',
                            name: 'unidad_ejecutora'
                        },
                        {
                            data: 'organo_intermedio',
                            name: 'organo_intermedio'
                        },
                        {
                            data: 'provincia',
                            name: 'provincia'
                        },
                        {
                            data: 'distrito',
                            name: 'distrito'
                        },
                        {
                            data: 'tipo_ie',
                            name: 'tipo_ie'
                        },
                        {
                            data: 'gestion',
                            name: 'gestion'
                        },
                        {
                            data: 'zona',
                            name: 'zona'
                        },
                        {
                            data: 'codmod_ie',
                            name: 'codmod_ie'
                        },
                        {
                            data: 'codigo_local',
                            name: 'codigo_local'
                        },
                        {
                            data: 'clave8',
                            name: 'clave8'
                        },
                        {
                            data: 'nivel_educativo',
                            name: 'nivel_educativo'
                        },
                        {
                            data: 'institucion_educativa',
                            name: 'institucion_educativa'
                        },
                        {
                            data: 'codigo_plaza',
                            name: 'codigo_plaza'
                        },
                        {
                            data: 'tipo_trabajador',
                            name: 'tipo_trabajador'
                        },
                        {
                            data: 'sub_tipo_trabajador',
                            name: 'sub_tipo_trabajador'
                        },
                        {
                            data: 'cargo',
                            name: 'cargo'
                        },
                        {
                            data: 'situacion_laboral',
                            name: 'situacion_laboral'
                        },
                        {
                            data: 'motivo_vacante',
                            name: 'motivo_vacante'
                        },
                        {
                            data: 'documento_identidad',
                            name: 'documento_identidad'
                        },
                        {
                            data: 'sexo',
                            name: 'sexo'
                        },
                        {
                            data: 'codigo_modular',
                            name: 'codigo_modular'
                        },
                        {
                            data: 'apellido_paterno',
                            name: 'apellido_paterno'
                        },
                        {
                            data: 'apellido_materno',
                            name: 'apellido_materno'
                        },
                        {
                            data: 'nombres',
                            name: 'nombres'
                        },
                        {
                            data: 'fecha_ingreso',
                            name: 'fecha_ingreso'
                        },
                        {
                            data: 'categoria_remunerativa',
                            name: 'categoria_remunerativa'
                        },
                        {
                            data: 'jornada_laboral',
                            name: 'jornada_laboral'
                        },
                        {
                            data: 'estado',
                            name: 'estado'
                        },
                        {
                            data: 'fecha_nacimiento',
                            name: 'fecha_nacimiento'
                        },
                        {
                            data: 'fecha_inicio',
                            name: 'fecha_inicio'
                        },
                        {
                            data: 'fecha_termino',
                            name: 'fecha_termino'
                        },
                        {
                            data: 'tipo_registro',
                            name: 'tipo_registro'
                        },
                        {
                            data: 'ley',
                            name: 'ley'
                        },
                        {
                            data: 'preventiva',
                            name: 'preventiva'
                        },
                        {
                            data: 'especialidad',
                            name: 'especialidad'
                        },
                        {
                            data: 'tipo_estudios',
                            name: 'tipo_estudios'
                        },
                        {
                            data: 'estado_estudios',
                            name: 'estado_estudios'
                        },
                        {
                            data: 'grado',
                            name: 'grado'
                        },
                        {
                            data: 'mencion',
                            name: 'mencion'
                        },
                        {
                            data: 'especialidad_profesional',
                            name: 'especialidad_profesional'
                        },
                        {
                            data: 'fecha_resolucion',
                            name: 'fecha_resolucion'
                        },
                        {
                            data: 'numero_resolucion',
                            name: 'numero_resolucion'
                        },
                        {
                            data: 'centro_estudios',
                            name: 'centro_estudios'
                        },
                        {
                            data: 'celular',
                            name: 'celular'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'desc_superior',
                            name: 'desc_superior'
                        },

                    ],
                }

            );
        });

        function exportar() {
            alert('ok')
        }
    </script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
    <!-- Validation init js-->
    <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

    <!-- third party js -->
    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
@endsection
