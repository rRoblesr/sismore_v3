@extends('layouts.main', ['titlePage' => 'BASE DE DATOS DE PADRON WEB'])
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
                                onclick="javascript:location=`{{ route('impormatricula.download') }}`"><i
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
                                            <th>DRE</th>
                                            <th>UGEL</th>
                                            <th>DEPARTAMENTO</th>
                                            <th>PROVINCIA</th>
                                            <th>DISTRITO</th>
                                            <th>CENTRO_POBLADO</th>
                                            <th>COD_MOD</th>
                                            <th>INSTITUCION_EDUCATIVA</th>
                                            <th>COD_NIVELMOD</th>
                                            <th>NIVEL_MODALIDAD</th>
                                            <th>COD_GES_DEP</th>
                                            <th>GESTION_DEPENDENCIA</th>
                                            <th>TOTAL_ESTUDIANTES</th>
                                            <th>MATRICULA_DEFINITIVA</th>
                                            <th>MATRICULA_PROCESO</th>
                                            <th>DNI_VALIDADO</th>
                                            <th>DNI_SIN_VALIDAR</th>
                                            <th>REGISTRADO_SIN_DNI</th>
                                            <th>TOTAL_GRADOS</th>
                                            <th>TOTAL_SECCIONES</th>
                                            <th>NOMINAS_GENERADAS</th>
                                            <th>NOMINAS_APROBADAS</th>
                                            <th>NOMINAS_POR_RECTIFICAR</th>
                                            <th>TRES_ANIOS_HOMBRE</th>
                                            <th>TRES_ANIOS_MUJER</th>
                                            <th>CUATRO_ANIOS_HOMBRE</th>
                                            <th>CUATRO_ANIOS_MUJER</th>
                                            <th>CINCO_ANIOS_HOMBRE</th>
                                            <th>CINCO_ANIOS_MUJER</th>
                                            <th>PRIMERO_HOMBRE</th>
                                            <th>PRIMERO_MUJER</th>
                                            <th>SEGUNDO_HOMBRE</th>
                                            <th>SEGUNDO_MUJER</th>
                                            <th>TERCERO_HOMBRE</th>
                                            <th>TERCERO_MUJER</th>
                                            <th>CUARTO_HOMBRE</th>
                                            <th>CUARTO_MUJER</th>
                                            <th>QUINTO_HOMBRE</th>
                                            <th>QUINTO_MUJER</th>
                                            <th>SEXTO_HOMBRE</th>
                                            <th>SEXTO_MUJER</th>
                                            <th>CERO_ANIOS_HOMBRE</th>
                                            <th>CERO_ANIOS_MUJER</th>
                                            <th>UN_ANIO_HOMBRE</th>
                                            <th>UN_ANIO_MUJER</th>
                                            <th>DOS_ANIOS_HOMBRE</th>
                                            <th>DOS_ANIOS_MUJER</th>
                                            <th>MAS_CINCO_ANIOS_HOMBRE</th>
                                            <th>MAS_CINCO_ANIOS_MUJER</th>
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
                        "url": "{{ route('ImporMatricula.listarimportados') }}",
                        "data": {
                            "matricula_id": {{ $mat->id }}
                        },
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'dre',
                            name: 'dre'
                        },
                        {
                            data: 'ugel',
                            name: 'ugel'
                        },
                        {
                            data: 'departamento',
                            name: 'departamento'
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
                            data: 'centro_poblado',
                            name: 'centro_poblado'
                        },
                        {
                            data: 'cod_mod',
                            name: 'cod_mod'
                        },
                        {
                            data: 'institucion_educativa',
                            name: 'institucion_educativa'
                        },
                        {
                            data: 'cod_nivelmod',
                            name: 'cod_nivelmod'
                        },
                        {
                            data: 'nivel_modalidad',
                            name: 'nivel_modalidad'
                        },
                        {
                            data: 'cod_ges_dep',
                            name: 'cod_ges_dep'
                        },
                        {
                            data: 'gestion_dependencia',
                            name: 'gestion_dependencia'
                        },
                        {
                            data: 'total_estudiantes',
                            name: 'total_estudiantes'
                        },
                        {
                            data: 'matricula_definitiva',
                            name: 'matricula_definitiva'
                        },
                        {
                            data: 'matricula_proceso',
                            name: 'matricula_proceso'
                        },
                        {
                            data: 'dni_validado',
                            name: 'dni_validado'
                        },
                        {
                            data: 'dni_sin_validar',
                            name: 'dni_sin_validar'
                        },
                        {
                            data: 'registrado_sin_dni',
                            name: 'registrado_sin_dni'
                        },
                        {
                            data: 'total_grados',
                            name: 'total_grados'
                        },
                        {
                            data: 'total_secciones',
                            name: 'total_secciones'
                        },
                        {
                            data: 'nominas_generadas',
                            name: 'nominas_generadas'
                        },
                        {
                            data: 'nominas_aprobadas',
                            name: 'nominas_aprobadas'
                        },
                        {
                            data: 'nominas_por_rectificar',
                            name: 'nominas_por_rectificar'
                        },
                        {
                            data: 'tres_anios_hombre',
                            name: 'tres_anios_hombre'
                        },
                        {
                            data: 'tres_anios_mujer',
                            name: 'tres_anios_mujer'
                        },
                        {
                            data: 'cuatro_anios_hombre',
                            name: 'cuatro_anios_hombre'
                        },
                        {
                            data: 'cuatro_anios_mujer',
                            name: 'cuatro_anios_mujer'
                        },
                        {
                            data: 'cinco_anios_hombre',
                            name: 'cinco_anios_hombre'
                        },
                        {
                            data: 'cinco_anios_mujer',
                            name: 'cinco_anios_mujer'
                        },
                        {
                            data: 'primero_hombre',
                            name: 'primero_hombre'
                        },
                        {
                            data: 'primero_mujer',
                            name: 'primero_mujer'
                        },
                        {
                            data: 'segundo_hombre',
                            name: 'segundo_hombre'
                        },
                        {
                            data: 'segundo_mujer',
                            name: 'segundo_mujer'
                        },
                        {
                            data: 'tercero_hombre',
                            name: 'tercero_hombre'
                        },
                        {
                            data: 'tercero_mujer',
                            name: 'tercero_mujer'
                        },
                        {
                            data: 'cuarto_hombre',
                            name: 'cuarto_hombre'
                        },
                        {
                            data: 'cuarto_mujer',
                            name: 'cuarto_mujer'
                        },
                        {
                            data: 'quinto_hombre',
                            name: 'quinto_hombre'
                        },
                        {
                            data: 'quinto_mujer',
                            name: 'quinto_mujer'
                        },
                        {
                            data: 'sexto_hombre',
                            name: 'sexto_hombre'
                        },
                        {
                            data: 'sexto_mujer',
                            name: 'sexto_mujer'
                        },
                        {
                            data: 'cero_anios_hombre',
                            name: 'cero_anios_hombre'
                        },
                        {
                            data: 'cero_anios_mujer',
                            name: 'cero_anios_mujer'
                        },
                        {
                            data: 'un_anio_hombre',
                            name: 'un_anio_hombre'
                        },
                        {
                            data: 'un_anio_mujer',
                            name: 'un_anio_mujer'
                        },
                        {
                            data: 'dos_anios_hombre',
                            name: 'dos_anios_hombre'
                        },
                        {
                            data: 'dos_anios_mujer',
                            name: 'dos_anios_mujer'
                        },
                        {
                            data: 'mas_cinco_anios_hombre',
                            name: 'mas_cinco_anios_hombre'
                        },
                        {
                            data: 'mas_cinco_anios_mujer',
                            name: 'mas_cinco_anios_mujer'
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
