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
                                onclick="javascript:location=`{{ route('imporpadronweb.download') }}`"><i
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
                                            <th>COD_MOD</th>
                                            <th>COD_LOCAL</th>
                                            <th>INSTITUCION_EDUCATIVA</th>
                                            <th>COD_NIVELMOD</th>
                                            <th>NIVEL_MODALIDAD</th>
                                            <th>FORMA</th>
                                            <th>COD_CAR</th>
                                            <th>CARACTERISTICA</th>
                                            <th>COD_GENERO</th>
                                            <th>GENERO</th>
                                            <th>COD_GEST</th>
                                            <th>GESTION</th>
                                            <th>COD_GES_DEP</th>
                                            <th>GESTION_DEPENDENCIA</th>
                                            <th>DIRECTOR</th>
                                            <th>TELEFONO</th>
                                            <th>EMAIL</th>
                                            <th>DIRECCION_CENTRO_EDUCATIVO</th>
                                            <th>LOCALIDAD</th>
                                            <th>CODCP_INEI</th>
                                            <th>COD_CCPP</th>
                                            <th>CENTRO_POBLADO</th>
                                            <th>COD_AREA</th>
                                            <th>AREA_GEOGRAFICA</th>
                                            <th>CODGEO</th>
                                            <th>PROVINCIA</th>
                                            <th>DISTRITO</th>
                                            <th>CODOOII</th>
                                            <th>UGEL</th>
                                            <th>NLAT_IE</th>
                                            <th>NLONG_IE</th>
                                            <th>COD_TUR</th>
                                            <th>TURNO</th>
                                            <th>COD_ESTADO</th>
                                            <th>ESTADO</th>
                                            <th>TALUM_HOM</th>
                                            <th>TALUM_MUJ</th>
                                            <th>TALUMNO</th>
                                            <th>TDOCENTE</th>
                                            <th>TSECCION</th>
                                            <th>FECHA_REGISTRO</th>
                                            <th>FECHA_ACT</th>
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

        <!-- Bootstrap modal -->
        <div id="modal-siagie-matricula" class="modal fade centrarmodal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="siagie-matricula" class="table table-striped table-bordered tablex"
                                style="font-size:10px">
                                {{-- width:7200px; --}}
                                <thead class="text-primary">
                                    <th>COD_MOD</th>
                                    <th>COD_LOCAL</th>
                                    <th>INSTITUTO_SUPERIOR</th>
                                    <th>COD_CARRERA</th>
                                    <th>CARRERA_ESPECIALIDAD</th>
                                    <th>TIPO_MATRICULA</th>
                                    <th>SEMESTRE</th>
                                    <th>CICLO</th>
                                    <th>TURNO</th>
                                    <th>SECCION</th>
                                    <th>CODIGO_ESTUDIANTE</th>
                                    <th>APELLIDO_PATERNO</th>
                                    <th>APELLIDO_MATERNO</th>
                                    <th>NOMBRES</th>
                                    <th>GENERO</th>
                                    <th>FECHA_NACIMIENTO</th>
                                    <th>NACIONALIDAD</th>
                                    <th>RAZA_ETNIA</th>
                                    <th>CON_DISCAPACIDAD</th>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Bootstrap modal -->

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
                        "url": "{{ route('imporpadronweb.listarimportados') }}",
                        "data": {
                            "importacion_id": {{ $imp->id }}
                        },
                        "type": "POST",
                        "dataType": 'JSON',
                    },
                    "columns": [{
                            data: 'cod_Mod',
                            name: 'cod_Mod'
                        },
                        {
                            data: 'cod_Local',
                            name: 'cod_Local'
                        },
                        {
                            data: 'cen_Edu',
                            name: 'cen_Edu'
                        },
                        {
                            data: 'niv_Mod',
                            name: 'niv_Mod'
                        },
                        {
                            data: 'd_Niv_Mod',
                            name: 'd_Niv_Mod'
                        },
                        {
                            data: 'd_Forma',
                            name: 'd_Forma'
                        },
                        {
                            data: 'cod_Car',
                            name: 'cod_Car'
                        },
                        {
                            data: 'd_Cod_Car',
                            name: 'd_Cod_Car'
                        },
                        {
                            data: 'TipsSexo',
                            name: 'TipsSexo'
                        },
                        {
                            data: 'd_TipsSexo',
                            name: 'd_TipsSexo'
                        },
                        {
                            data: 'gestion',
                            name: 'gestion'
                        },
                        {
                            data: 'd_Gestion',
                            name: 'd_Gestion'
                        },
                        {
                            data: 'ges_Dep',
                            name: 'ges_Dep'
                        },
                        {
                            data: 'd_Ges_Dep',
                            name: 'd_Ges_Dep'
                        },
                        {
                            data: 'director',
                            name: 'director'
                        },
                        {
                            data: 'telefono',
                            name: 'telefono'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'dir_Cen',
                            name: 'dir_Cen'
                        },
                        {
                            data: 'localidad',
                            name: 'localidad'
                        },
                        {
                            data: 'codcp_Inei',
                            name: 'codcp_Inei'
                        },
                        {
                            data: 'codccpp',
                            name: 'codccpp'
                        },
                        {
                            data: 'cen_Pob',
                            name: 'cen_Pob'
                        },
                        {
                            data: 'area_Censo',
                            name: 'area_Censo'
                        },
                        {
                            data: 'd_areaCenso',
                            name: 'd_areaCenso'
                        },
                        {
                            data: 'codGeo',
                            name: 'codGeo'
                        },
                        {
                            data: 'd_Prov',
                            name: 'd_Prov'
                        },
                        {
                            data: 'd_Dist',
                            name: 'd_Dist'
                        },
                        {
                            data: 'codOOII',
                            name: 'codOOII'
                        },
                        {
                            data: 'd_DreUgel',
                            name: 'd_DreUgel'
                        },
                        {
                            data: 'nLat_IE',
                            name: 'nLat_IE'
                        },
                        {
                            data: 'nLong_IE',
                            name: 'nLong_IE'
                        },
                        {
                            data: 'cod_Tur',
                            name: 'cod_Tur'
                        },
                        {
                            data: 'D_Cod_Tur',
                            name: 'D_Cod_Tur'
                        },
                        {
                            data: 'estado',
                            name: 'estado'
                        },
                        {
                            data: 'd_Estado',
                            name: 'd_Estado'
                        },
                        {
                            data: 'tAlum_Hom',
                            name: 'tAlum_Hom'
                        },
                        {
                            data: 'tAlum_Muj',
                            name: 'tAlum_Muj'
                        },
                        {
                            data: 'tAlumno',
                            name: 'tAlumno'
                        },
                        {
                            data: 'tDocente',
                            name: 'tDocente'
                        },
                        {
                            data: 'tSeccion',
                            name: 'tSeccion'
                        },
                        {
                            data: 'fechaReg',
                            name: 'fechaReg'
                        },
                        {
                            data: 'fecha_Act',
                            name: 'fecha_Act'
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
