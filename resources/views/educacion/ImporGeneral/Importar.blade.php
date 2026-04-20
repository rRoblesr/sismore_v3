@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - PADRON WEB DE INSTITUCIONES EDUCATIVAS'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
    <style>
        #modal-siagie-matricula .modal-dialog {
            max-width: 95vw;
        }

        #modal-siagie-matricula .modal-body {
            padding: .75rem 1rem;
        }

        #modal-siagie-matricula table.dataTable thead th,
        #modal-siagie-matricula table.dataTable tbody td {
            vertical-align: middle;
        }

        #modal-siagie-matricula .dataTables_wrapper .row {
            width: 100%;
        }

        #modal-siagie-matricula .dataTables_wrapper .dataTables_length,
        #modal-siagie-matricula .dataTables_wrapper .dataTables_filter {
            margin-bottom: .5rem;
        }

        #modal-siagie-matricula .dataTables_wrapper .dataTables_filter {
            text-align: right;
        }

        #modal-siagie-matricula .dataTables_wrapper .dataTables_info {
            padding-top: .5rem;
        }

        #modal-siagie-matricula .dataTables_wrapper .dataTables_paginate {
            float: none;
            text-align: center;
            padding-top: .5rem;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-border">
                <div class="card-header border-success-0 bg-transparent pb-0">
                    <div class="card-widgets">
                        <button type="button" class="btn btn-warning btn-xs" onclick="location.reload()"><i
                                class="fa fa-redo"></i> Actualizar</button>
                        <button type="button" class="btn btn-success btn-xs waves-effect waves-light" data-toggle="modal"
                            data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><i
                                class="ion ion-md-cloud-upload"></i> Importar</button>
                    </div>
                    @switch($fuente)
                        @case(1)
                            {{-- padron web --}}
                            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DEL PADRON WEB</h3>
                        @break

                        @case(2)
                            {{-- padron nexus --}}
                            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DEL PADRON NEXUS</h3>
                        @break

                        @case(8)
                            {{-- padron siague - matricula --}}
                            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DEL SIAGIE - MATRICULA</h3>
                        @break

                        @case(9)
                            {{-- padron tabletas --}}
                            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DE PADRON DE TABLETAS</h3>
                        @break

                        @case(12)
                            {{-- padron EIB --}}
                            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DE PADRON EIB</h3>
                        @break

                        @case(32)
                            {{-- censo educativo - docente --}}
                            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DEL CENSO EDUCATIVO - DOCENTE</h3>
                        @break

                        @case(33)
                            {{-- censo educativo - matricula --}}
                            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DEL CENSO EDUCATIVO - MATRICULA</h3>
                        @break

                        @case(34)
                            {{-- censo educativo - matricula --}}
                            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DEL SIAGIE - MATRICULA</h3>
                        @break

                        @case(35)
                            {{-- censo educativo - matricula --}}
                            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DE SERVICIOS BASICOS</h3>
                        @break

                        @case(46)
                            {{-- censo educativo - matricula --}}
                            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DE EVALUACIÓN MUESTRAL</h3>
                        @break

                        @case(51)
                            {{-- censo educativo - matricula --}}
                            <h3 class="card-title">HISTORIAL DE IMPORTACIÓN DE LOCALES BENEFICIADOS</h3>
                        @break

                        @default
                    @endswitch

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                                    style="font-size: 12px">
                                    {{-- <thead class="text-white bg-success-0">
                                        <tr>
                                            <th>N°</th>
                                            <th>Fecha Versión</th>
                                            <th>Fuente</th>
                                            <th>Usuario</th>
                                            <th>Área</th>
                                            <th>Registro</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead> --}}
                                    @switch($fuente)
                                        @case(1)
                                            <thead class="text-white bg-success-0">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Fecha Versión</th>
                                                    <th>Fuente</th>
                                                    <th>Usuario</th>
                                                    <th>Área</th>
                                                    <th>Registro</th>
                                                    <th>Estado</th>
                                                    <th>Registros</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                        @break

                                        @case(51)
                                    <thead class="text-white bg-success-0">
                                        <tr>
                                            <th>N°</th>
                                            <th>Fecha Versión</th>
                                            <th>Fuente</th>
                                            <th>Usuario</th>
                                            <th>Área</th>
                                            <th>Registro</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                @break

                                @default
                                            <thead class="text-white bg-success-0">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Fecha Versión</th>
                                                    <th>Fuente</th>
                                                    <th>Usuario</th>
                                                    <th>Área</th>
                                                    <th>Registro</th>
                                                    <th>Estado</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                    @endswitch

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <table id="siagie-matricula" class="table table-striped table-bordered"
                            style="font-size:10px;width:100%;">
                            @switch($fuente)
                                @case(1)
                                    <thead class="text-primary">
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
                                        <th>DIRECCION_CENTRO_EDUCATIVO</th>
                                        <th>CODCP_INEI</th>
                                        <th>COD_CCPP</th>
                                        <th>CENTRO_POBLADO</th>
                                        <th>COD_AREA</th>
                                        <th>AREA_GEOGRAFICA</th>
                                        <th>CODGEO</th>
                                        <th>PROVINCIA</th>
                                        <th>DISTRITO</th>
                                        <th>D_REGION</th>
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

                                    </thead>
                                @break

                                @case(2)
                                    <thead class="text-primary">
                                        <th>UGEL</th>
                                        <th>PROVINCIA</th>
                                        <th>DISTRITO</th>
                                        <th>TIPO_IE</th>
                                        <th>GESTION</th>
                                        <th>ZONA</th>
                                        <th>MODALIDAD</th>
                                        <th>NIVEL_EDUCATIVO</th>
                                        <th>COD_MOD</th>
                                        <th>COD_LOCAL</th>
                                        <th>INSTITUCION_EDUCATIVA</th>
                                        <th>COD_PLAZA</th>
                                        <th>TIPO_TRABAJADOR</th>
                                        <th>SUBTIPO_TRABAJADOR</th>
                                        <th>CARGO</th>
                                        <th>SITUACION_LABORAL</th>
                                        <th>CATEGORIA_REMUNERATIVA</th>
                                        <th>ESCALA_REMUNERATIVA</th>
                                        <th>JEC</th>
                                        <th>JORNADA_LABORAL</th>
                                        <th>ESTADO</th>
                                        <th>TIPO_REGISTRO</th>
                                        <th>NUM_DOCUMENTO</th>
                                        <th>APELLIDO_PATERNO</th>
                                        <th>APELLIDO_MATERNO</th>
                                        <th>NOMBRES</th>
                                        <th>SEXO</th>
                                        <th>FECHA_NACIMIENTO</th>
                                        <th>TIPO_ESTUDIOS</th>
                                        <th>PROFESION</th>
                                        <th>GRADO_OBTENIDO</th>
                                        <th>REGIMEN_PENSIONARIO</th>
                                        <th>AFP</th>
                                        <th>LEY</th>
                                        <th>FECHA_NOMBRAMIENTO</th>
                                    </thead>
                                @break

                                @case(8)
                                    <thead class="text-primary">
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
                                @break

                                @case(9)
                                    <thead class="text-primary">
                                        <th>UGEL</th>
                                        <th>PROVINCIA</th>
                                        <th>DISTRITO</th>
                                        <th>COD_MOD</th>
                                        <th>INSTITUCION_EDUCATIVA</th>
                                        <th>ESTADO</th>
                                        <th>TABLETAS_PROGRAMADAS</th>
                                        <th>CARGADORES_PROGRAMADAS</th>
                                        <th>TABLETAS_CHIP</th>
                                        <th>TABLETAS_PECOSA</th>
                                        <th>CARGADORES_PECOSA</th>
                                        <th>TABLETAS_PECOSA_SIGA</th>
                                        <th>CARGADORES_PECOSA_SIGA</th>
                                        <th>TABLETAS_ENTREGADAS_SIGEMA</th>
                                        <th>CARGADORES_ENTREGADAS_SIGEMA</th>
                                        <th>TABLETAS_RECEPCIONADAS</th>
                                        <th>CARGADORES_RECEPCIONADAS</th>
                                        <th>TABLETAS_ASIGNADAS</th>
                                        <th>TABLETAS_ASIGNADAS_ESTUDIANTES</th>
                                        <th>TABLETAS_ASIGNADAS_DOCENTES</th>
                                        <th>CARGADORES_ASIGNADAS</th>
                                        <th>CARGADORES_ASIGNADAS_ESTUDIANTES</th>
                                        <th>CARGADORES_ASIGNADAS_DOCENTES</th>
                                        <th>TABLETAS_DEVUELTAS</th>
                                        <th>CARGADORES_DEVUELTOS</th>
                                        <th>TABLETAS_PERDIDAS</th>
                                        <th>CARGADORES_PERDIDOS</th>
                                    </thead>
                                @break

                                @case(12)
                                    <thead class="text-primary">
                                        <th>ugel</th>
                                        <th>provincia</th>
                                        <th>distrito</th>
                                        <th>centro_poblado</th>
                                        <th>cod_mod</th>
                                        <th>cod_local</th>
                                        <th>institucion_educativa</th>
                                        <th>cod_nivelmod</th>
                                        <th>nivel_modalidad</th>
                                        <th>forma_atencion</th>
                                        <th>lengua_uno</th>
                                        <th>lengua_dos</th>
                                        <th>lengua_3</th>
                                    </thead>
                                @break

                                @case(32)
                                    <thead class="text-primary">
                                        <th>UGEL</th>
                                        <th>PROVINCIA</th>
                                        <th>DISTRITO</th>
                                        <th>COD_MOD</th>
                                        <th>INSTITUCION_EDUCATIVA</th>
                                        <th>ESTADO</th>
                                        <th>TABLETAS_PROGRAMADAS</th>
                                        <th>CARGADORES_PROGRAMADAS</th>
                                        <th>TABLETAS_CHIP</th>
                                        <th>TABLETAS_PECOSA</th>
                                        <th>CARGADORES_PECOSA</th>
                                        <th>TABLETAS_PECOSA_SIGA</th>
                                        <th>CARGADORES_PECOSA_SIGA</th>
                                        <th>TABLETAS_ENTREGADAS_SIGEMA</th>
                                        <th>CARGADORES_ENTREGADAS_SIGEMA</th>
                                        <th>TABLETAS_RECEPCIONADAS</th>
                                        <th>CARGADORES_RECEPCIONADAS</th>
                                        <th>TABLETAS_ASIGNADAS</th>
                                        <th>TABLETAS_ASIGNADAS_ESTUDIANTES</th>
                                        <th>TABLETAS_ASIGNADAS_DOCENTES</th>
                                        <th>CARGADORES_ASIGNADAS</th>
                                        <th>CARGADORES_ASIGNADAS_ESTUDIANTES</th>
                                        <th>CARGADORES_ASIGNADAS_DOCENTES</th>
                                        <th>TABLETAS_DEVUELTAS</th>
                                        <th>CARGADORES_DEVUELTOS</th>
                                        <th>TABLETAS_PERDIDAS</th>
                                        <th>CARGADORES_PERDIDOS</th>
                                    </thead>
                                @break

                                @case(33)
                                    <thead class="text-primary">
                                        <th>CODOOII</th>
                                        <th>CODGEO</th>
                                        <th>CODLOCAL</th>
                                        <th>COD_MOD</th>
                                        <th>NROCED</th>
                                        <th>CUADRO</th>
                                        <th>TIPDATO</th>
                                        <th>NIV_MOD</th>
                                        <th>GES_DEP</th>
                                        <th>AREA_CENSO</th>
                                        <th>D01</th>
                                        <th>D02</th>
                                        <th>D03</th>
                                        <th>D04</th>
                                        <th>D05</th>
                                        <th>D06</th>
                                        <th>D07</th>
                                        <th>D08</th>
                                        <th>D09</th>
                                        <th>D10</th>
                                        <th>D11</th>
                                        <th>D12</th>
                                        <th>D13</th>
                                        <th>D14</th>
                                        <th>D15</th>
                                        <th>D16</th>
                                        <th>D17</th>
                                        <th>D18</th>
                                        <th>D19</th>
                                        <th>D20</th>
                                        <th>D21</th>
                                        <th>D22</th>
                                        <th>D23</th>
                                        <th>D24</th>
                                        <th>D25</th>
                                        <th>D26</th>
                                        <th>D27</th>
                                        <th>D28</th>
                                        <th>D29</th>
                                        <th>D30</th>
                                        <th>D31</th>
                                        <th>D32</th>
                                        <th>D33</th>
                                        <th>D34</th>
                                        <th>D35</th>
                                        <th>D36</th>
                                        <th>D37</th>
                                        <th>D38</th>
                                        <th>D39</th>
                                        <th>D40</th>
                                    </thead>
                                @break

                                @case(34)
                                    <thead class="text-primary">
                                        <th>ANIO</th>
                                        <th>COD_MOD</th>
                                        <th>MODALIDAD</th>
                                        <th>NIVEL</th>
                                        <th>GESTION</th>
                                        <th>PAIS</th>
                                        <th>FECHA_NAC</th>
                                        <th>SEXO</th>
                                        <th>LENGUA_MAT</th>
                                        <th>SEG_LENGUA</th>
                                        <th>DISCAPACIDAD</th>
                                        <th>SIT_MATRICULA</th>
                                        {{-- <th>EST_MATRICULA</th> --}}
                                        <th>FECHA_MAT</th>
                                        {{-- <th>CONDICION</th> --}}
                                        <th>GRADO</th>
                                        <th>DSC_GRADO</th>
                                        <th>SECCION</th>
                                        <th>DSC_SECCION</th>
                                        <th>FECHA_REG</th>
                                        <th>FECHA_RET</th>
                                        <th>MOTIVO_RET</th>
                                        <th>SF_REGULAR</th>
                                        <th>SF_RECUP</th>
                                    </thead>
                                @break

                                @case(35)
                                    <thead class="text-primary">
                                        <th>CODLOCAL</th>
                                        <th>CODGEO</th>
                                        <th>PROVINCIA</th>
                                        <th>DISTRITO</th>
                                        <th>UGEL</th>
                                        <th>COD_AREA</th>
                                        <th>AREA_CENSO</th>
                                        <th>COD_GEST</th>
                                        <th>GESTION</th>
                                        <th>MODALIDAD</th>
                                        <th>AGUA</th>
                                        <th>DESAGUE</th>
                                        <th>LUZ</th>
                                        <th>INTERNET</th>
                                        <th>TRES SERVICIOS</th>
                                    </thead>
                                @break

                                @case(46)
                                    <thead class="text-primary">
                                        <th>anio</th>
                                        <th>cod_mod</th>
                                        <th>institucion_educativa</th>
                                        <th>nivel</th>
                                        <th>grado</th>
                                        <th>seccion</th>
                                        <th>gestion</th>
                                        <th>caracteristica</th>
                                        <th>codooii</th>
                                        <th>codgeo</th>
                                        <th>area_geografica</th>
                                        <th>sexo</th>
                                        <th>medida_l</th>
                                        <th>grupo_l</th>
                                        <th>peso_l</th>
                                        <th>medida_m</th>
                                        <th>grupo_m</th>
                                        <th>peso_m</th>
                                        <th>medida_cn</th>
                                        <th>grupo_cn</th>
                                        <th>peso_cn</th>
                                        <th>medida_cs</th>
                                        <th>grupo_cs</th>
                                        <th>peso_cs</th>
                                    </thead>
                                @break

                                @case(51)
                                    <thead class="text-primary">
                                        <th>COD_LOCAL</th>
                                        <th>REGION</th>
                                        <th>DEPARTAMENTO</th>
                                        <th>PROVINCIA</th>
                                        <th>DISTRITO</th>
                                        <th>DRE_UGEL</th>
                                        <th>NOMBRE_SERVICIOS</th>
                                        <th>MONTO_ASIGNADO_MANTENIMIENTO_REGULAR</th>
                                        <th>MONTO_ASIGNADO_RUTAS</th>
                                        <th>MONTO_ASIGNADO_TOTAL</th>
                                        <th>NUMERO_SERVICIOS</th>
                                    </thead>
                                @break

                                @default
                            @endswitch

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

    <!--  Modal content for the above example -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Importar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form class="cmxform form-horizontal tasi-form upload_file">
                                @csrf
                                <input type="hidden" id="ccomment" name="comentario" value="">
                                <div class="form-group">
                                    <div class="">
                                        <label class="col-form-label">Fuente de datos</label>
                                        <div class="">
                                            <input type="text" class="form-control" readonly="readonly"
                                                value="ESCALE">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="">
                                        <label class="col-form-label">Fecha Versión</label>
                                        <div class="">
                                            <input type="date" class="form-control" name="fechaActualizacion"
                                                placeholder="Ingrese fecha actualizacion" autofocus required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="">
                                        <label class="col-form-label">Archivo</label>
                                        <div class="">
                                            <input type="file" name="file" class="form-control" accept=".xlsx"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row  mt-0 mb-0">
                                    {{-- <label class="col-md-2 col-form-label"></label> --}}
                                    <div class="col-md-12">
                                        <div class="pwrapper m-0" style="display:none;">
                                            <div class="progress progress_wrapper">
                                                <div class="progress-bar progress-bar-striped bg-info progress-bar-animated progress_bar"
                                                    role="progressbar" style="width:0%">0%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row ">
                                    <div class="col-lg-12 text-center">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit"><i
                                                class="ion ion-md-cloud-upload"></i> Guardar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para procesar cubo -->
    <div id="modal-procesar-cubo" class="modal fade centrarmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Procesando Cubo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p><strong>Ejecutando el procedimiento...</strong></p>
                    <div class="progress mb-3">
                        <div id="progress-bar-cubo"
                            class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar"
                            style="width: 0%">
                            0%
                        </div>
                    </div>
                    <p id="mensaje-cubo"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - Bootstrap 4 -->
    <div class="modal fade" id="modalProcesos" tabindex="-1" role="dialog" aria-labelledby="modalProcesosLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalProcesosLabel">
                        <i class="fa fa-cogs"></i> Ejecutar Procesos de Padrón Web
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    Proceso 1
                                </div>
                                <div class="card-body text-center">
                                    <p>Padrón Web</p>
                                    <button class="btn btn-primary btn-sm w-100" data-proceso="1">
                                        <i class="fa fa-play"></i> Iniciar
                                    </button>
                                    <div class="progress mt-2 d-none" style="height: 5px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                            style="width: 100%"></div>
                                    </div>
                                    <div class="alert mt-2 d-none"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    Proceso 2
                                </div>
                                <div class="card-body text-center">
                                    <p>Cubo Pacto</p>
                                    <button class="btn btn-success btn-sm w-100" data-proceso="2">
                                        <i class="fa fa-play"></i> Iniciar
                                    </button>
                                    <div class="progress mt-2 d-none" style="height: 5px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                            style="width: 100%"></div>
                                    </div>
                                    <div class="alert mt-2 d-none"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    Proceso 3
                                </div>
                                <div class="card-body text-center">
                                    <p>Cubo EIB</p>
                                    <button class="btn btn-info btn-sm w-100" data-proceso="3">
                                        <i class="fa fa-play"></i> Iniciar
                                    </button>
                                    <div class="progress mt-2 d-none" style="height: 5px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                            style="width: 100%"></div>
                                    </div>
                                    <div class="alert mt-2 d-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <small class="text-muted">Los procesos pueden tardar. No cierre esta ventana.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para procesar matricula (Fuente 34) -->
    @if(isset($fuente) && $fuente == 34)
    <div id="modal-procesar-matricula" class="modal fade centrarmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Procesar Importación de SIAGIE Matricula</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="proc_importacion_id" value="">
                    <p class="mb-3">
                        Seleccione el proceso que desea ejecutar para la importación seleccionada.
                    </p>
                    <div class="text-center">
                        <div class="d-inline-block mr-2">
                            <button type="button" id="btn-proc-base-mat" class="btn btn-primary btn-sm">
                                <i class="fa fa-database"></i> Procesar Base
                            </button>
                            <button type="button" id="btn-proc-base-ojito-mat" class="btn btn-outline-primary btn-sm ml-1" title="Ver estado">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        <div class="d-inline-block">
                            <button type="button" id="btn-proc-cubo-mat" class="btn btn-success btn-sm">
                                <i class="fa fa-cube"></i> Procesar Cubo
                            </button>
                            <button type="button" id="btn-proc-cubo-ojito-mat" class="btn btn-outline-success btn-sm ml-1" title="Ver estado">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('js')
    <script>
        var table_principal = '';
        var importPollTimer = null;
        var importHeaderBtnHtml = null;
        function setImportHeaderButtonLoading(isLoading) {
            var btn = $('button[data-target=".bs-example-modal-lg"]');
            if (!btn.length) return;
            if (importHeaderBtnHtml === null) {
                importHeaderBtnHtml = btn.html();
            }
            if (isLoading) {
                btn.prop('disabled', true);
                btn.html('<i class="fa fa-spinner fa-spin"></i> Importando...');
            } else {
                btn.prop('disabled', false);
                btn.html(importHeaderBtnHtml);
            }
        }

        function hasImportPending() {
            return $('#datatable [data-estado="PE"]').length > 0;
        }

        function startImportPolling() {
            if (importPollTimer) return;
            importPollTimer = setInterval(function() {
                if (table_principal) {
                    table_principal.ajax.reload(null, false);
                }
            }, 5000);
        }

        function stopImportPolling() {
            if (!importPollTimer) return;
            clearInterval(importPollTimer);
            importPollTimer = null;
        }

        $(document).ready(function() {
            $('.upload_file').on('submit', upload);

            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                ordering: true,
                order: [[5, 'desc']],
                language: table_language,
                ajax: {
                    url: url_tabla_principal({{ $fuente }}),
                    type: 'GET',
                },
            });

            table_principal.on('draw', function() {
                if (hasImportPending()) {
                    startImportPolling();
                    setImportHeaderButtonLoading(true);
                } else {
                    stopImportPolling();
                    setImportHeaderButtonLoading(false);
                }
            });
            
            @if(isset($fuente) && $fuente == 34)
            $('#btn-proc-base-mat').on('click', function() { ejecutarProcesoMat('base'); });
            $('#btn-proc-cubo-mat').on('click', function() { ejecutarProcesoMat('cubo'); });
            $('#btn-proc-base-ojito-mat').on('click', function() { verificarEstadoMat('base'); });
            $('#btn-proc-cubo-ojito-mat').on('click', function() { verificarEstadoMat('cubo'); });
            @endif

            ///////////////////////////////

            // ✅ Sonido de éxito (sintético, sin archivos)
            function playSuccessBeep() {
                try {
                    var ctx = new(window.AudioContext || window.webkitAudioContext)();
                    var osc = ctx.createOscillator();
                    var gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.value = 800;
                    gain.gain.value = 0.15;
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start();
                    setTimeout(function() {
                        osc.stop();
                        ctx.close();
                    }, 300);
                } catch (e) {
                    console.log('✅ Proceso terminado');
                }
            }

            // ✅ Delegación de eventos (funciona con DataTables)
            $(document).on('click', '.btn-ejecutar-procesos', function(e) {
                e.preventDefault();
                var importacionId = $(this).data('importacion');
                if (!importacionId) {
                    alert('ID de importación no encontrado.');
                    return;
                }

                // Guardar ID en el modal
                $('#modalProcesos').data('importacion-actual', importacionId);

                // ✅ Abrir modal con Bootstrap 4 (jQuery)
                $('#modalProcesos').modal('show');
            });

            // ✅ Manejar clic en botones de proceso DENTRO del modal
            $(document).on('click', '#modalProcesos [data-proceso]', function(e) {
                e.preventDefault();
                var btn = $(this);
                var proceso = btn.data('proceso');
                var importacionId = $('#modalProcesos').data('importacion-actual');
                var card = btn.closest('.card');
                var progressBar = card.find('.progress');
                var alertBox = card.find('.alert');

                if (!importacionId) {
                    alertBox.removeClass('d-none alert-success alert-danger').addClass('alert alert-danger')
                        .text('Error: Importación no definida.');
                    return;
                }

                // Reset UI
                alertBox.addClass('d-none');
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando...');
                progressBar.removeClass('d-none');

                // ✅ Llamada con Axios (compatible con Laravel 8)
                const baseUrl = "{{ url('/') }}";
                axios.post(baseUrl + '/educación/Importar/PadronWeb/PA/' + proceso + '/' + importacionId, {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    })
                    .then(function(response) {
                        var data = response.data;
                        if (!data.error) {
                            alertBox.removeClass('d-none alert-danger').addClass('alert alert-success')
                                .html('<i class="fa fa-check"></i> ✔ Proceso ejecutado correctamente.');
                            btn.html('<i class="fa fa-redo"></i> Iniciar de nuevo');
                            playSuccessBeep();
                        } else {
                            alertBox.removeClass('d-none alert-success').addClass('alert alert-danger')
                                .html('<i class="fa fa-exclamation-circle"></i> ✘ ' + data.mensaje);
                            btn.html('<i class="fa fa-redo"></i> Reintentar');
                        }
                    })
                    .catch(function(error) {
                        var msg = 'Error de conexión.';
                        if (error.response && error.response.data && error.response.data.mensaje) {
                            msg = error.response.data.mensaje;
                        } else if (error.response) {
                            msg = 'Error HTTP ' + error.response.status;
                        }
                        alertBox.removeClass('d-none alert-success').addClass('alert alert-danger')
                            .html('<i class="fa fa-exclamation-circle"></i> ✘ ' + msg);
                        btn.html('<i class="fa fa-redo"></i> Reintentar');
                    })
                    .finally(function() {
                        btn.prop('disabled', false);
                    });
            });

            // ✅ Opcional: limpiar al cerrar el modal
            $('#modalProcesos').on('hidden.bs.modal', function() {
                $(this).removeData('importacion-actual');
                $(this).find('.alert').addClass('d-none');
                $(this).find('.progress').addClass('d-none');
                $(this).find('[data-proceso]').each(function() {
                    var txt = $(this).data('proceso') == 1 ? 'Iniciar' :
                        $(this).data('proceso') == 2 ? 'Iniciar' : 'Iniciar';
                    $(this).html('<i class="fa fa-play"></i> ' + txt);
                });
            });

            ///////////////////////////////
        });

        function procesarCubo(importacion_id) {
            $('#mensaje-cubo').text('');
            $('#progress-bar-cubo')
                .removeClass('bg-success bg-danger')
                .addClass('bg-info')
                .css('width', '0%')
                .text('0%');

            $('#modal-procesar-cubo').modal('show');

            let percent = 0;
            const intervalId = setInterval(() => {
                if (percent < 95) {
                    percent += 2; // Velocidad de simulación
                    $('#progress-bar-cubo')
                        .css('width', percent + '%')
                        .text(percent + '%');
                }
            }, 100); // Actualiza cada 100ms

            $.ajax({
                url: "{{ route('impormatriculageneral.procesar.cubo', 'id_placeholder') }}".replace('id_placeholder', importacion_id),
                method: "POST",
                data: {
                    _token: $('input[name=_token]').val(),
                    // importacion_id: importacion_id
                },
                success: function(response) {
                    clearInterval(intervalId);
                    percent = 100;
                    $('#progress-bar-cubo')
                        .removeClass('bg-info progress-bar-animated')
                        .addClass('bg-success')
                        .css('width', percent + '%')
                        .text('Completado');

                    $('#mensaje-cubo').text('¡El cubo fue procesado correctamente!');
                    setTimeout(() => {
                        $('#modal-procesar-cubo').modal('hide');
                        table_principal.ajax.reload(); // Recargar tabla principal
                    }, 1500);
                },
                error: function(xhr) {
                    clearInterval(intervalId);
                    $('#progress-bar-cubo')
                        .removeClass('bg-info progress-bar-animated')
                        .addClass('bg-danger')
                        .css('width', '100%')
                        .text('Error');

                    $('#mensaje-cubo').text('Ocurrió un fallo: ' + xhr.responseText);
                }
            });
        }

        function procesarCuboxxx(importacion_id) {
            $('#mensaje-cubo').text('');
            $('#progress-bar-cubo').css('width', '0%').text('0%');
            $('#modal-procesar-cubo').modal('show');
            $.ajax({
                url: "{{ route('impormatriculageneral.procesar.cubo', 'id_placeholder') }}".replace('id_placeholder', importacion_id),
                method: "POST",
                data: {
                    _token: $('input[name=_token]').val(),
                },
                xhrFields: {
                    onprogress: function(e) {
                        let percent = Math.round((e.loaded / e.total) * 100);
                        $('#progress-bar-cubo').css('width', percent + '%').text(percent + '%');
                    }
                },
                success: function(response) {
                    if (response.status === 200) {
                        $('#progress-bar-cubo').removeClass('bg-info').addClass('bg-success')
                            .css('width', '100%').text('100%');
                        $('#mensaje-cubo').text('¡El cubo fue procesado correctamente!');
                        setTimeout(() => {
                            $('#modal-procesar-cubo').modal('hide');
                            table_principal.ajax.reload(); // Recargar tabla principal
                        }, 1500);
                    } else {
                        $('#progress-bar-cubo').removeClass('bg-info').addClass('bg-danger')
                            .css('width', '100%').text('Error');
                        $('#mensaje-cubo').text('Hubo un error al procesar el cubo.');
                    }
                },
                error: function(xhr) {
                    $('#progress-bar-cubo').removeClass('bg-info').addClass('bg-danger')
                        .css('width', '100%').text('Error');
                    $('#mensaje-cubo').text('Ocurrió un fallo: ' + xhr.responseText);
                }
            });
        }

        function url_tabla_principal(fuente) {
            switch (fuente) {
                case 1:
                    return "{{ route('ImporPadronWeb.listar.importados') }}";
                case 2:
                    return "{{ route('impornexus.listar.importados.dt') }}";
                case 8:
                    return "{{ route('ImporMatricula.listar.importados') }}";
                case 9:
                    return "{{ route('importableta.listar.importados') }}";
                case 12:
                    return "{{ route('imporpadroneib.listar.importados') }}";
                case 32:
                    return "{{ route('imporcensodocente.listar.importados') }}";
                case 33:
                    return "{{ route('imporcensomatricula.listar.importados') }}";
                case 34:
                    return "{{ route('impormatriculageneral.listar.importados') }}";
                case 35:
                    return "{{ route('imporserviciosbasicos.listar.importados') }}";
                case 46:
                    return "{{ route('imporevaluacionmuestral.listar.importados') }}";
                case 51:
                    return "{{ route('imporlocalesbeneficiados.listar.importados') }}";
                default:
                    return '';
            }
        }

        function upload(e) {
            e.preventDefault();
            let form = $(this),
                wrapper = $('.pwrapper'),
                /* wrapper_f = $('.wrapper_files'), */
                progress_bar = $('.progress_bar'),
                submitBtn = $('button[type="submit"]', form),
                data = new FormData(form.get(0));

            progress_bar.removeClass('bg-success bg-danger').addClass('bg-info');
            progress_bar.css('width', '0%');
            progress_bar.html('Preparando...');

            wrapper.fadeIn();

            $.ajax({
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(e) {
                        if (e.lengthComputable) {
                            let percentComplete = Math.floor((e.loaded / e.total) * 100);
                            progress_bar.css('width', percentComplete + '%');
                            progress_bar.html(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                type: "POST",
                url: url_upload({{ $fuente }}),
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                data: data,
                beforeSend: () => {
                    $('button', form).attr('disabled', true);
                    submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Subiendo...');
                }
            }).done(res => {
                if (res.status === 200) {
                    progress_bar.removeClass('bg-info').addClass('bg-success');
                    progress_bar.html(res.msg ?? 'Listo!');
                    form.trigger('reset');
                    $('.bs-example-modal-lg').modal('hide');
                    wrapper.fadeOut();
                    progress_bar.removeClass('bg-success bg-danger').addClass('bg-info');
                    progress_bar.css('width', '0%');
                    table_principal.ajax.reload(null, true);
                    startImportPolling();
                } else {
                    progress_bar.css('width', '100%');
                    progress_bar.html(res.msg);
                    form.trigger('reset');
                    //alert(res.msg);
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                progress_bar.removeClass('bg-success bg-info').addClass('bg-danger');

                let msg = 'Error: ' + textStatus;

                // Intentar leer el mensaje del servidor (si hay respuesta)
                if (jqXHR.responseJSON && jqXHR.responseJSON.msg) {
                    msg = jqXHR.responseJSON.msg; // ← ¡Este es el mensaje de PHP!
                } else if (jqXHR.responseText) {
                    // ¿Es HTML? (página de error de Laravel)
                    if (jqXHR.responseText.includes('<!DOCTYPE') || jqXHR.responseText.includes('<html')) {
                        msg = 'Error interno del servidor (ver consola)';
                        console.error('Respuesta HTML recibida (error no controlado):', jqXHR.responseText);
                    } else {
                        // Texto plano o JSON inválido
                        msg = 'Respuesta inválida: ' + jqXHR.responseText.substring(0, 100) + '...';
                    }
                } else if (errorThrown) {
                    msg = 'Error: ' + errorThrown;
                }

                progress_bar.html(msg);
                console.warn('AJAX FALLÓ', {
                    jqXHR,
                    textStatus,
                    errorThrown
                });
                // }).fail(err => {
                //     progress_bar.removeClass('bg-success bg-info').addClass('bg-danger');
                //     // progress_bar.html('Hubo un error!!');
                //     progress_bar.html('Archivo desconocido');
            }).always(() => {
                $('button', form).attr('disabled', false);
                submitBtn.html('<i class="ion ion-md-cloud-upload"></i> Guardar');
            });
        }

        function url_upload(fuente) {
            switch (fuente) {
                case 1:
                    return "{{ route('ImporPadronWeb.guardar') }}";
                case 2:
                    return "{{ route('impornexus.guardar') }}";
                case 8:
                    return "{{ route('ImporMatricula.guardar') }}";
                case 9:
                    return "{{ route('importableta.guardar') }}";
                case 12:
                    return "{{ route('imporpadroneib.guardar') }}";
                case 32:
                    return "{{ route('imporcensodocente.guardar') }}";
                case 33:
                    return "{{ route('imporcensomatricula.guardar') }}";
                case 34:
                    return "{{ route('impormatriculageneral.guardar') }}";
                case 35:
                    return "{{ route('imporserviciosbasicos.guardar') }}";
                case 46:
                    return "{{ route('imporevaluacionmuestral.guardar') }}";
                case 51:
                    return "{{ route('imporlocalesbeneficiados.guardar') }}";
                default:
                    return '';
            }
        }

        @if ((int) $fuente === 2)
            function procesar_pa_nexus(importacionId) {
                bootbox.confirm("Seguro desea ejecutar el PA de esta importación?", function(result) {
                    if (result !== true) return;
                    var $btn = $('#pa' + importacionId);
                    var oldHtml = $btn.length ? $btn.html() : null;
                    if ($btn.length) {
                        $btn.prop('disabled', true);
                        $btn.html('<i class="fa fa-spinner fa-spin"></i>');
                    }

                    var dlg = bootbox.dialog({
                        message: '<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Ejecutando PA, espere...</div>',
                        closeButton: false
                    });

                    axios.post("{{ route('impornexus.procesar.pa', ['importacion_id' => ':id']) }}".replace(':id', importacionId), {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        })
                        .then(function(response) {
                            var data = response.data || {};
                            if (data.status === 200) {
                                toastr.success(data.msg || 'Proceso ejecutado.', 'Mensaje');
                                table_principal.ajax.reload(null, false);
                            } else {
                                toastr.error(data.msg || 'No se pudo ejecutar el proceso.', 'Mensaje');
                            }
                        })
                        .catch(function(error) {
                            var msg = 'Error al ejecutar el proceso.';
                            if (error.response && error.response.data && error.response.data.msg) {
                                msg = error.response.data.msg;
                            }
                            toastr.error(msg, 'Mensaje');
                        })
                        .finally(function() {
                            if (dlg) dlg.modal('hide');
                            if ($btn.length) {
                                $btn.prop('disabled', false);
                                if (oldHtml !== null) $btn.html(oldHtml);
                            }
                        });
                });
            }
        @endif

        function geteliminar(id) {
            bootbox.confirm("Seguro desea Eliminar este IMPORTACION?", function(result) {
                if (result === true) {
                    $.ajax({
                        url: url_geteliminar({{ $fuente }}, id),
                        type: "DELETE",
                        dataType: "JSON",
                        headers: {
                            'X-CSRF-TOKEN': $('input[name=_token]').val()
                        },
                        beforeSend: function() {
                            $('#eliminar' + id).html(
                                '<span><i class="fa fa-spinner fa-spin"></i></span>');
                        },
                        success: function(data) {
                            $('#modal_form').modal('hide');
                            table_principal.ajax.reload();
                            toastr.success('El registro fue eliminado exitosamente.', 'Mensaje');
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(
                                'No se puede eliminar este registro por seguridad de su base de datos, Contacte al Administrador del Sistema',
                                'Mensaje');
                        }
                    });
                }
            });
        };

        function url_geteliminar(fuente, id) {
            switch (fuente) {
                case 1:
                    return "{{ route('ImporPadronWeb.eliminar', '') }}/" + id;
                case 2:
                    return "{{ route('impornexus.eliminar', ':id') }}".replace(':id', id);
                case 8:
                    return "{{ url('/') }}/ImporMatricula/eliminar/" + id;
                case 9:
                    return "{{ route('importableta.eliminar', '') }}/" + id;
                case 12:
                    return "{{ route('imporpadroneib.eliminar', ':id') }}".replace(':id', id);
                case 32:
                    return "{{ route('imporcensodocente.eliminar', '') }}/" + id;
                case 33:
                    return "{{ route('imporcensomatricula.eliminar', '') }}/" + id;
                case 34:
                    return "{{ route('impormatriculageneral.eliminar', '') }}/" + id;
                case 35:
                    return "{{ route('imporserviciosbasicos.eliminar', '') }}/" + id;
                case 46:
                    return "{{ route('imporevaluacionmuestral.eliminar', '') }}/" + id;
                case 51:
                    return "{{ route('imporlocalesbeneficiados.eliminar', '') }}/" + id;
                default:
                    return '';
            }
        }

        function monitor(importacion) {
            console.log({{ $fuente }} + ' - ' + importacion);

            switch ({{ $fuente }}) {
                case 1:
                    $('#siagie-matricula').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "responsive": false,
                        "autoWidth": false,
                        "ordered": true,
                        "destroy": true,
                        "language": table_language,
                        "ajax": {
                            "headers": {
                                'X-CSRF-TOKEN': $('input[name=_token]').val()
                            },
                            "url": "{{ route('ImporPadronWeb.listarimportados', '') }}/" + importacion,
                            "type": "POST",
                            "dataType": 'JSON',
                        },
                        "columns": [{
                                data: 'cod_mod',
                                name: 'cod_mod'
                            },
                            {
                                data: 'cod_local',
                                name: 'cod_local'
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
                                data: 'forma',
                                name: 'forma'
                            },
                            {
                                data: 'cod_car',
                                name: 'cod_car'
                            },
                            {
                                data: 'carasteristica',
                                name: 'carasteristica'
                            },
                            {
                                data: 'cod_genero',
                                name: 'cod_genero'
                            },
                            {
                                data: 'genero',
                                name: 'genero'
                            },
                            {
                                data: 'cod_gest',
                                name: 'cod_gest'
                            },
                            {
                                data: 'gestion',
                                name: 'gestion'
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
                                data: 'director',
                                name: 'director'
                            },
                            {
                                data: 'telefono',
                                name: 'telefono'
                            },
                            {
                                data: 'direccion_centro_educativo',
                                name: 'direccion_centro_educativo'
                            },
                            {
                                data: 'codcp_inei',
                                name: 'codcp_inei'
                            },
                            {
                                data: 'cod_ccpp',
                                name: 'cod_ccpp'
                            },
                            {
                                data: 'centro_poblado',
                                name: 'centro_poblado'
                            },
                            {
                                data: 'cod_area',
                                name: 'cod_area'
                            },
                            {
                                data: 'area_geografica',
                                name: 'area_geografica'
                            },
                            {
                                data: 'codgeo',
                                name: 'codgeo'
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
                                data: 'd_region',
                                name: 'd_region'
                            },
                            {
                                data: 'codooii',
                                name: 'codooii'
                            },
                            {
                                data: 'ugel',
                                name: 'ugel'
                            },
                            {
                                data: 'nlat_ie',
                                name: 'nlat_ie'
                            },
                            {
                                data: 'nlong_ie',
                                name: 'nlong_ie'
                            },
                            {
                                data: 'cod_tur',
                                name: 'cod_tur'
                            },
                            {
                                data: 'turno',
                                name: 'turno'
                            },
                            {
                                data: 'cod_estado',
                                name: 'cod_estado'
                            },
                            {
                                data: 'estado',
                                name: 'estado'
                            },
                            {
                                data: 'talum_hom',
                                name: 'talum_hom'
                            },
                            {
                                data: 'talum_muj',
                                name: 'talum_muj'
                            },
                            {
                                data: 'talumno',
                                name: 'talumno'
                            },
                            {
                                data: 'tdocente',
                                name: 'tdocente'
                            },
                            {
                                data: 'tseccion',
                                name: 'tseccion'
                            },
                        ],
                    });
                    break;
                case 2:
                    $('#siagie-matricula').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "responsive": false,
                            "autoWidth": false,
                        "ordering": true,
                        "order": [[0, "asc"]],
                            "destroy": true,
                            "language": table_language,
                            "ajax": {
                                "headers": {
                                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                                },
                                "url": "{{ route('impornexus.listarimportados', '') }}/" + importacion,
                                "type": "POST",
                                "dataType": 'JSON',
                            },
                            "columns": [
                                { data: 'ugel', name: 'ugel' },
                                { data: 'provincia', name: 'provincia' },
                                { data: 'distrito', name: 'distrito' },
                                { data: 'tipo_ie', name: 'tipo_ie' },
                                { data: 'gestion', name: 'gestion' },
                                { data: 'zona', name: 'zona' },
                                { data: 'modalidad', name: 'modalidad' },
                                { data: 'nivel_educativo', name: 'nivel_educativo' },
                                { data: 'cod_mod', name: 'cod_mod' },
                                { data: 'cod_local', name: 'cod_local' },
                                { data: 'institucion_educativa', name: 'institucion_educativa' },
                                { data: 'cod_plaza', name: 'cod_plaza' },
                                { data: 'tipo_trabajador', name: 'tipo_trabajador' },
                                { data: 'subtipo_trabajador', name: 'subtipo_trabajador' },
                                { data: 'cargo', name: 'cargo' },
                                { data: 'situacion_laboral', name: 'situacion_laboral' },
                                { data: 'categoria_remunerativa', name: 'categoria_remunerativa' },
                                { data: 'escala_remunerativa', name: 'escala_remunerativa' },
                                { data: 'jec', name: 'jec' },
                                { data: 'jornada_laboral', name: 'jornada_laboral' },
                                { data: 'estado', name: 'estado' },
                                { data: 'tipo_registro', name: 'tipo_registro' },
                                { data: 'num_documento', name: 'num_documento' },
                                { data: 'apellido_paterno', name: 'apellido_paterno' },
                                { data: 'apellido_materno', name: 'apellido_materno' },
                                { data: 'nombres', name: 'nombres' },
                                { data: 'sexo', name: 'sexo' },
                                { data: 'fecha_nacimiento', name: 'fecha_nacimiento' },
                                { data: 'tipo_estudios', name: 'tipo_estudios' },
                                { data: 'profesion', name: 'profesion' },
                                { data: 'grado_obtenido', name: 'grado_obtenido' },
                                { data: 'regimen_pensionario', name: 'regimen_pensionario' },
                                { data: 'afp', name: 'afp' },
                                { data: 'ley', name: 'ley' },
                                { data: 'fecha_nombramiento', name: 'fecha_nombramiento' }
                            ],
                        }

                    );
                    break;
                case 8:
                    $('#siagie-matricula').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "responsive": false,
                            "autoWidth": false,
                            "ordered": true,
                            "destroy": true,
                            "language": table_language,
                            "ajax": {
                                "headers": {
                                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                                },
                                "url": "{{ route('ImporMatricula.listarimportados') }}",
                                "data": {
                                    "importacion_id": importacion
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
                    break;
                case 9:
                    $('#siagie-matricula').DataTable({
                            "processing": true,
                            "serverSide": true,
                            "responsive": false,
                            "autoWidth": false,
                            "ordered": true,
                            "destroy": true,
                            "language": table_language,
                            "ajax": {
                                "url": "{{ route('importableta.listarimportados', '') }}/" + importacion,
                                "type": "GET",
                                "dataType": 'JSON',
                            },
                            "columns": [{
                                    data: 'ugel',
                                    name: 'ugel'
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
                                    data: 'cod_mod',
                                    name: 'cod_mod'
                                },
                                {
                                    data: 'institucion_educativa',
                                    name: 'institucion_educativa'
                                },
                                {
                                    data: 'estado',
                                    name: 'estado'
                                },
                                {
                                    data: 'tabletas_programadas',
                                    name: 'tabletas_programadas'
                                },
                                {
                                    data: 'cargadores_programadas',
                                    name: 'cargadores_programadas'
                                },
                                {
                                    data: 'tabletas_chip',
                                    name: 'tabletas_chip'
                                },
                                {
                                    data: 'tabletas_pecosa',
                                    name: 'tabletas_pecosa'
                                },
                                {
                                    data: 'cargadores_pecosa',
                                    name: 'cargadores_pecosa'
                                },
                                {
                                    data: 'tabletas_pecosa_siga',
                                    name: 'tabletas_pecosa_siga'
                                },
                                {
                                    data: 'cargadores_pecosa_siga',
                                    name: 'cargadores_pecosa_siga'
                                },
                                {
                                    data: 'tabletas_entregadas_sigema',
                                    name: 'tabletas_entregadas_sigema'
                                },
                                {
                                    data: 'cargadores_entregadas_sigema',
                                    name: 'cargadores_entregadas_sigema'
                                },
                                {
                                    data: 'tabletas_recepcionadas',
                                    name: 'tabletas_recepcionadas'
                                },
                                {
                                    data: 'cargadores_recepcionadas',
                                    name: 'cargadores_recepcionadas'
                                },
                                {
                                    data: 'tabletas_asignadas',
                                    name: 'tabletas_asignadas'
                                },
                                {
                                    data: 'tabletas_asignadas_estudiantes',
                                    name: 'tabletas_asignadas_estudiantes'
                                },
                                {
                                    data: 'tabletas_asignadas_docentes',
                                    name: 'tabletas_asignadas_docentes'
                                },
                                {
                                    data: 'cargadores_asignadas',
                                    name: 'cargadores_asignadas'
                                },
                                {
                                    data: 'cargadores_asignadas_estudiantes',
                                    name: 'cargadores_asignadas_estudiantes'
                                },
                                {
                                    data: 'cargadores_asignadas_docentes',
                                    name: 'cargadores_asignadas_docentes'
                                },
                                {
                                    data: 'tabletas_devueltas',
                                    name: 'tabletas_devueltas'
                                },
                                {
                                    data: 'cargadores_devueltos',
                                    name: 'cargadores_devueltos'
                                },
                                {
                                    data: 'tabletas_perdidas',
                                    name: 'tabletas_perdidas'
                                },
                                {
                                    data: 'cargadores_perdidos',
                                    name: 'cargadores_perdidos'
                                },

                            ],
                        }

                    );
                    break;
                case 12:
                    $('#siagie-matricula').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "responsive": false,
                        "autoWidth": false,
                        "ordering": true,
                        "order": [[1, "asc"]],
                        "destroy": true,
                        "language": table_language,
                        "ajax": {
                            "headers": {
                                'X-CSRF-TOKEN': $('input[name=_token]').val()
                            },
                            "url": "{{ route('imporpadroneib.listarimportados', '') }}/" + importacion,
                            "type": "POST",
                            "dataType": 'JSON',
                        },
                        "columns": [{
                            data: 'ugel',
                            name: 'ugel'
                        }, {
                            data: 'provincia',
                            name: 'provincia'
                        }, {
                            data: 'distrito',
                            name: 'distrito'
                        }, {
                            data: 'centro_poblado',
                            name: 'centro_poblado'
                        }, {
                            data: 'cod_mod',
                            name: 'cod_mod' /*  */
                        }, {
                            data: 'cod_local',
                            name: 'cod_local'
                        }, {
                            data: 'institucion_educativa',
                            name: 'institucion_educativa'
                        }, {
                            data: 'cod_nivelmod',
                            name: 'cod_nivelmod'
                        }, {
                            data: 'nivel_modalidad',
                            name: 'nivel_modalidad'
                        }, {
                            data: 'forma_atencion',
                            name: 'forma_atencion' /*  */
                        }, {
                            data: 'lengua1',
                            name: 'lengua1'
                        }, {
                            data: 'lengua2',
                            name: 'lengua2' /*  */
                        }, {
                            data: 'lengua3',
                            name: 'lengua3'
                        }],
                    });
                    break;
                case 32:
                    $('#siagie-matricula').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "responsive": false,
                        "autoWidth": false,
                        "ordered": true,
                        "destroy": true,
                        "language": table_language,
                        "ajax": {
                            "url": "{{ route('imporcensodocente.listarimportados', '') }}/" + importacion,
                            "type": "GET",
                            "dataType": 'JSON',
                        },
                        "columns": [{
                                data: 'ugel',
                                name: 'ugel'
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
                                data: 'cod_mod',
                                name: 'cod_mod'
                            },
                            {
                                data: 'institucion_educativa',
                                name: 'institucion_educativa'
                            },
                            {
                                data: 'estado',
                                name: 'estado'
                            },
                            {
                                data: 'tabletas_programadas',
                                name: 'tabletas_programadas'
                            },
                            {
                                data: 'cargadores_programadas',
                                name: 'cargadores_programadas'
                            },
                            {
                                data: 'tabletas_chip',
                                name: 'tabletas_chip'
                            },
                            {
                                data: 'tabletas_pecosa',
                                name: 'tabletas_pecosa'
                            },
                            {
                                data: 'cargadores_pecosa',
                                name: 'cargadores_pecosa'
                            },
                            {
                                data: 'tabletas_pecosa_siga',
                                name: 'tabletas_pecosa_siga'
                            },
                            {
                                data: 'cargadores_pecosa_siga',
                                name: 'cargadores_pecosa_siga'
                            },
                            {
                                data: 'tabletas_entregadas_sigema',
                                name: 'tabletas_entregadas_sigema'
                            },
                            {
                                data: 'cargadores_entregadas_sigema',
                                name: 'cargadores_entregadas_sigema'
                            },
                            {
                                data: 'tabletas_recepcionadas',
                                name: 'tabletas_recepcionadas'
                            },
                            {
                                data: 'cargadores_recepcionadas',
                                name: 'cargadores_recepcionadas'
                            },
                            {
                                data: 'tabletas_asignadas',
                                name: 'tabletas_asignadas'
                            },
                            {
                                data: 'tabletas_asignadas_estudiantes',
                                name: 'tabletas_asignadas_estudiantes'
                            },
                            {
                                data: 'tabletas_asignadas_docentes',
                                name: 'tabletas_asignadas_docentes'
                            },
                            {
                                data: 'cargadores_asignadas',
                                name: 'cargadores_asignadas'
                            },
                            {
                                data: 'cargadores_asignadas_estudiantes',
                                name: 'cargadores_asignadas_estudiantes'
                            },
                            {
                                data: 'cargadores_asignadas_docentes',
                                name: 'cargadores_asignadas_docentes'
                            },
                            {
                                data: 'tabletas_devueltas',
                                name: 'tabletas_devueltas'
                            },
                            {
                                data: 'cargadores_devueltos',
                                name: 'cargadores_devueltos'
                            },
                            {
                                data: 'tabletas_perdidas',
                                name: 'tabletas_perdidas'
                            },
                            {
                                data: 'cargadores_perdidos',
                                name: 'cargadores_perdidos'
                            },

                        ]
                    });
                    break;
                case 33:
                    $('#siagie-matricula').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "responsive": false,
                        "autoWidth": false,
                        "ordered": true,
                        "destroy": true,
                        "language": table_language,
                        "ajax": {
                            "headers": {
                                'X-CSRF-TOKEN': $('input[name=_token]').val()
                            },
                            "url": "{{ route('imporcensomatricula.listarimportados', '') }}/" + importacion,
                            "type": "POST",
                            "dataType": 'JSON',
                        },
                        "columns": [{
                                data: 'codooii',
                                name: 'codooii'
                            },
                            {
                                data: 'codgeo',
                                name: 'codgeo'
                            },
                            {
                                data: 'codlocal',
                                name: 'codlocal'
                            },
                            {
                                data: 'cod_mod',
                                name: 'cod_mod'
                            },
                            {
                                data: 'nroced',
                                name: 'nroced'
                            },
                            {
                                data: 'cuadro',
                                name: 'cuadro'
                            },
                            {
                                data: 'tipdato',
                                name: 'tipdato'
                            },
                            {
                                data: 'niv_mod',
                                name: 'niv_mod'
                            },
                            {
                                data: 'ges_dep',
                                name: 'ges_dep'
                            },
                            {
                                data: 'area_censo',
                                name: 'area_censo'
                            },
                            {
                                data: 'd01',
                                name: 'd01'
                            },
                            {
                                data: 'd02',
                                name: 'd02'
                            },
                            {
                                data: 'd03',
                                name: 'd03'
                            },
                            {
                                data: 'd04',
                                name: 'd04'
                            },
                            {
                                data: 'd05',
                                name: 'd05'
                            },
                            {
                                data: 'd06',
                                name: 'd06'
                            },
                            {
                                data: 'd07',
                                name: 'd07'
                            },
                            {
                                data: 'd08',
                                name: 'd08'
                            },
                            {
                                data: 'd09',
                                name: 'd09'
                            },
                            {
                                data: 'd10',
                                name: 'd10'
                            },
                            {
                                data: 'd11',
                                name: 'd11'
                            },
                            {
                                data: 'd12',
                                name: 'd12'
                            },
                            {
                                data: 'd13',
                                name: 'd13'
                            },
                            {
                                data: 'd14',
                                name: 'd14'
                            },
                            {
                                data: 'd15',
                                name: 'd15'
                            },
                            {
                                data: 'd16',
                                name: 'd16'
                            },
                            {
                                data: 'd17',
                                name: 'd17'
                            },
                            {
                                data: 'd18',
                                name: 'd18'
                            },
                            {
                                data: 'd19',
                                name: 'd19'
                            },
                            {
                                data: 'd20',
                                name: 'd20'
                            },
                            {
                                data: 'd21',
                                name: 'd21'
                            },
                            {
                                data: 'd22',
                                name: 'd22'
                            },
                            {
                                data: 'd23',
                                name: 'd23'
                            },
                            {
                                data: 'd24',
                                name: 'd24'
                            },
                            {
                                data: 'd25',
                                name: 'd25'
                            },
                            {
                                data: 'd26',
                                name: 'd26'
                            },
                            {
                                data: 'd27',
                                name: 'd27'
                            },
                            {
                                data: 'd28',
                                name: 'd28'
                            },
                            {
                                data: 'd29',
                                name: 'd29'
                            },
                            {
                                data: 'd30',
                                name: 'd30'
                            },
                            {
                                data: 'd31',
                                name: 'd31'
                            },
                            {
                                data: 'd32',
                                name: 'd32'
                            },
                            {
                                data: 'd33',
                                name: 'd33'
                            },
                            {
                                data: 'd34',
                                name: 'd34'
                            },
                            {
                                data: 'd35',
                                name: 'd35'
                            },
                            {
                                data: 'd36',
                                name: 'd36'
                            },
                            {
                                data: 'd37',
                                name: 'd37'
                            },
                            {
                                data: 'd38',
                                name: 'd38'
                            },
                            {
                                data: 'd39',
                                name: 'd39'
                            },
                            {
                                data: 'd40',
                                name: 'd40'
                            },
                        ],
                    });
                    break;
                case 34:
                    $('#siagie-matricula').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "responsive": false,
                        "autoWidth": false,
                        "ordered": true,
                        "destroy": true,
                        "language": table_language,
                        "ajax": {
                            "headers": {
                                'X-CSRF-TOKEN': $('input[name=_token]').val()
                            },
                            "url": "{{ route('impormatriculageneral.listarimportados', '') }}/" + importacion,
                            "type": "POST",
                            "dataType": 'JSON',
                        },
                        "columns": [{
                                data: 'anio',
                                name: 'anio'
                            },
                            {
                                data: 'cod_mod',
                                name: 'cod_mod'
                            },
                            {
                                data: 'id_mod',
                                name: 'id_mod'
                            },
                            {
                                data: 'id_nivel',
                                name: 'id_nivel'
                            },
                            {
                                data: 'id_gestion',
                                name: 'id_gestion'
                            },
                            {
                                data: 'pais_nacimiento',
                                name: 'pais_nacimiento'
                            },
                            {
                                data: 'fecha_nacimiento',
                                name: 'fecha_nacimiento'
                            },
                            {
                                data: 'id_sexo',
                                name: 'id_sexo'
                            },
                            {
                                data: 'lengua_materna',
                                name: 'lengua_materna'
                            },
                            {
                                data: 'segunda_lengua',
                                name: 'segunda_lengua'
                            },
                            {
                                data: 'discapacidad',
                                name: 'discapacidad'
                            },
                            {
                                data: 'situacion_matricula',
                                name: 'situacion_matricula'
                            },
                            /* {
                                data: 'estado_matricula',
                                name: 'estado_matricula'
                            }, */
                            {
                                data: 'fecha_matricula',
                                name: 'fecha_matricula'
                            },
                            /* {
                                data: 'condicion_matricula',
                                name: 'condicion_matricula'
                            }, */
                            {
                                data: 'id_grado',
                                name: 'id_grado'
                            },
                            {
                                data: 'grado',
                                name: 'grado'
                            },
                            {
                                data: 'id_seccion',
                                name: 'id_seccion'
                            },
                            {
                                data: 'seccion',
                                name: 'seccion'
                            },
                            {
                                data: 'fecha_registro',
                                name: 'fecha_registro'
                            },
                            {
                                data: 'fecha_retiro',
                                name: 'fecha_retiro'
                            },
                            {
                                data: 'motivo_retiro',
                                name: 'motivo_retiro'
                            },
                            {
                                data: 'sf_regular',
                                name: 'sf_regular'
                            },
                            {
                                data: 'sf_recuperacion',
                                name: 'sf_recuperacion'
                            },
                        ],
                    });
                    break;
                case 35:
                    $('#siagie-matricula').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "responsive": false,
                        "autoWidth": false,
                        "ordered": true,
                        "destroy": true,
                        "language": table_language,
                        "scrollX": true,
                        "dom": "<'row align-items-center'<'col-md-6 col-12'l><'col-md-6 col-12'f>>" +
                            "<'row'<'col-12'tr>>" +
                            "<'row align-items-center'<'col-md-5 col-12'i><'col-md-7 col-12'p>>",
                        "ajax": {
                            "headers": {
                                'X-CSRF-TOKEN': $('input[name=_token]').val()
                            },
                            "url": "{{ route('imporserviciosbasicos.listarimportados', '') }}/" + importacion,
                            "type": "POST",
                            "dataType": 'JSON',
                        },
                        "columnDefs": [{
                            "targets": "_all",
                            "className": "text-center"
                        }],
                        "columns": [{
                                data: 'codlocal',
                                name: 'codlocal'
                            },
                            {
                                data: 'codgeo',
                                name: 'codgeo'
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
                                data: 'ugel',
                                name: 'ugel'
                            },
                            {
                                data: 'cod_area',
                                name: 'cod_area'
                            },
                            {
                                data: 'area_censo',
                                name: 'area_censo'
                            },
                            {
                                data: 'cod_gest',
                                name: 'cod_gest'
                            },
                            {
                                data: 'gestion',
                                name: 'gestion'
                            },
                            {
                                data: 'modalidad',
                                name: 'modalidad'
                            },
                            {
                                data: 'agua_final',
                                name: 'agua_final'
                            },
                            {
                                data: 'desague_final',
                                name: 'desague_final'
                            },
                            {
                                data: 'luz_final',
                                name: 'luz_final'
                            },
                            {
                                data: 'internet_final',
                                name: 'internet_final'
                            },
                            {
                                data: 'tres_servicios_final',
                                name: 'tres_servicios_final'
                            },
                        ],
                    });
                    break;
                case 51:
                    $('#siagie-matricula').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "responsive": false,
                        "autoWidth": false,
                        "ordered": true,
                        "destroy": true,
                        "language": table_language,
                        "ajax": {
                            "headers": {
                                'X-CSRF-TOKEN': $('input[name=_token]').val()
                            },
                            "url": "{{ route('imporlocalesbeneficiados.listarimportados', '') }}/" + importacion,
                            "type": "POST",
                            "dataType": 'JSON',
                        },
                        "columns": [{
                                data: 'cod_local',
                                name: 'cod_local'
                            },
                            {
                                data: 'region',
                                name: 'region'
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
                                data: 'dre_ugel',
                                name: 'dre_ugel'
                            },
                            {
                                data: 'nombre_servicios',
                                name: 'nombre_servicios'
                            },
                            {
                                data: 'monto_asignado_mantenimiento_regular',
                                name: 'monto_asignado_mantenimiento_regular'
                            },
                            {
                                data: 'monto_asignado_rutas',
                                name: 'monto_asignado_rutas'
                            },
                            {
                                data: 'monto_asignado_total',
                                name: 'monto_asignado_total'
                            },
                            {
                                data: 'numero_servicios',
                                name: 'numero_servicios'
                            },
                        ],
                        "createdRow": function(row, data, dataIndex) {
                            if (data.ubigeo_id === null || data.ubigeo_id === '' || data.ugel_id === null || data.ugel_id === '') {
                                $(row).addClass('table-danger');
                                $(row).attr('title', 'Falta Ubigeo ID o UGEL ID');
                            }
                        }
                    });
                    break;
                default:
                    break;
            }


            $('#modal-siagie-matricula').modal('show');
            $('#modal-siagie-matricula .modal-title').text('Importado');
        }

        @if(isset($fuente) && $fuente == 34)
        function abrirProcesos(id) {
            $('#proc_importacion_id').val(id);
            $('#modal-procesar-matricula').modal('show');
        }

        function ejecutarProcesoMat(tipo) {
            var importacion_id = $('#proc_importacion_id').val();
            if (!importacion_id) return;

            var url = tipo === 'base'
                ? "{{ route('impormatriculageneral.procesar.base', 'id_placeholder') }}"
                : "{{ route('impormatriculageneral.procesar.cubo', 'id_placeholder') }}";
            url = url.replace('id_placeholder', importacion_id);

            Swal.fire({
                title: '¿Está seguro?',
                text: tipo === 'base'
                    ? 'Se procesará la base de matrícula (normalización).'
                    : 'Se procesará el cubo de matrícula.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, procesar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.value) return;

                Swal.fire({
                    title: 'Procesando...',
                    text: 'Por favor espere.',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                });

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire('¡Éxito!', res.msg, 'success');
                        } else {
                            Swal.fire('Error', res.msg, 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        var msg = 'Ocurrió un error al procesar la importación.';
                        if(jqXHR.responseJSON && jqXHR.responseJSON.msg) {
                            msg = jqXHR.responseJSON.msg;
                        }
                        Swal.fire('Error', msg, 'error');
                    }
                });
            });
        }

        function verificarEstadoMat(tipo) {
            var importacion_id = $('#proc_importacion_id').val();
            if (!importacion_id) return;

            var url = tipo === 'base'
                ? "{{ route('impormatriculageneral.verificar.base', 'id_placeholder') }}"
                : "{{ route('impormatriculageneral.verificar.cubo', 'id_placeholder') }}";
            url = url.replace('id_placeholder', importacion_id);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'JSON',
                success: function(res) {
                    if (tipo === 'base') {
                        var html = `
                            <div class="text-left">
                                <p class="mb-1"><strong>Resumen del proceso:</strong></p>
                                <p class="mb-1">Base generada: ${res.base ? 'Sí' : 'No'}</p>
                                <p class="mb-1">Registros de detalle: ${res.detalle}</p>
                            </div>`;
                        Swal.fire({
                            title: 'Estado del proceso de Base de Matrícula',
                            html: html,
                            type: 'info',
                            confirmButtonText: 'Cerrar',
                            confirmButtonColor: '#3085d6'
                        });
                    } else {
                        var html = `
                            <div class="text-left">
                                <p class="mb-2"><strong>Total de registros en Cubo:</strong> ${res.total}</p>
                            </div>`;
                        Swal.fire({
                            title: 'Estado del proceso de Cubo de Matrícula',
                            html: html,
                            type: 'info',
                            confirmButtonText: 'Cerrar',
                            confirmButtonColor: '#28a745'
                        });
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo obtener el estado.', 'error');
                }
            });
        }
        @endif
    </script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
    <!-- Validation init js-->
    <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <!-- En <head> -->
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <!-- Al final del <body> -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>

    <!-- Si usas FontAwesome -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> --}}
@endsection
