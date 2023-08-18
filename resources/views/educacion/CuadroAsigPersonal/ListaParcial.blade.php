<div class="table-responsive">
    <table id="grid" class="table table-striped table-bordered" style="width:12600px">
        <thead class="text-primary">

            {{-- <th style="width:80px">region</th> --}}
            {{-- <th style="width:180px">unidad_ejecutora</th> --}}
            <th style="width:100px">Organo_Intermedio</th>
            <th style="width:200px">Provincia</th>
            <th style="width:100px">Distrito</th>
            <th style="width:200px">Tipo_IE</th>
            <th style="width:160px">Gestion</th>
            <th style="width:200px">Zona</th>
            <th style="width:80px">CodMod_IE</th>
            <th style="width:80px">Codigo_Local</th>
            <th style="width:80px">Clave8</th>
            <th style="width:80px">Nivel_Educativo</th>
            <th style="width:400px">Institucion_Educativa</th>
            <th style="width:80px">Codigo_Plaza</th>
            <th style="width:80px">Tipo_Trabajador</th>
            <th style="width:280px">Sub_Tipo_Trabajador</th>
            <th style="width:300px">Cargo</th>
            <th style="width:80px">Situacion_Laboral</th>
            <th style="width:680px">Motivo_Vacante</th>
            <th style="width:80px">Documento_Identidad</th>
            <th style="width:80px">Codigo_Modular</th>
            <th style="width:80px">Apellido_Paterno</th>
            <th style="width:80px">Apellido_Materno</th>
            <th style="width:160px">Nombres</th>
            <th style="width:80px">Fecha_Ingreso</th>
            <th style="width:80px">Categoria_Remunerativa</th>
            <th style="width:80px">Jornada_Laboral</th>
            <th style="width:460px">Estado</th>
            <th style="width:80px">Fecha_Nacimiento</th>
            <th style="width:80px">Fecha_Inicio</th>
            <th style="width:80px">Fecha_Termino</th>
            <th style="width:80px">Tipo_Registro</th>
            <th style="width:80px">Ley</th>
            <th style="width:80px">Preventiva</th>
            <th style="width:80px">Referencia_Preventiva</th>
            <th style="width:680px">Especialidad</th>
            <th style="width:160px">Tipo_Estudios</th>
            <th style="width:200px">Estado_Estudios</th>
            <th style="width:80px">Grado</th>
            <th style="width:660px">Mencion</th>
            <th style="width:500px">Especialidad_Profesional</th>
            <th style="width:80px">Fecha_Resolucion</th>
            <th style="width:80px">Numero_Resolucion</th>
            <th style="width:300px">Centro_Estudios</th>
            <th style="width:80px">Celular</th>
            <th style="width:80px">Email</th>
        </thead>

    </table>
</div>


@section('js')
    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <script>
        $('#grid').DataTable({
                "ajax": "{{ route('CuadroAsigPersonal.ListaImportada_DataTable', $importacion_id) }}",
                "columns": [
                    // {data:'region'},{data:'unidad_ejecutora'},
                    {
                        data: 'organo_intermedio'
                    }, {
                        data: 'provincia'
                    }, {
                        data: 'distrito'
                    }, {
                        data: 'tipo_ie'
                    }, {
                        data: 'gestion'
                    }, {
                        data: 'zona'
                    }, {
                        data: 'codmod_ie'
                    }, {
                        data: 'codigo_local'
                    }, {
                        data: 'clave8'
                    }, {
                        data: 'nivel_educativo'
                    }, {
                        data: 'institucion_educativa'
                    }, {
                        data: 'codigo_plaza'
                    }, {
                        data: 'tipo_trabajador'
                    }, {
                        data: 'sub_tipo_trabajador'
                    }, {
                        data: 'cargo'
                    }, {
                        data: 'situacion_laboral'
                    }, {
                        data: 'motivo_vacante'
                    }, {
                        data: 'documento_identidad'
                    }, {
                        data: 'codigo_modular'
                    }, {
                        data: 'apellido_paterno'
                    }, {
                        data: 'apellido_materno'
                    }, {
                        data: 'nombres'
                    }, {
                        data: 'fecha_ingreso'
                    }, {
                        data: 'categoria_remunerativa'
                    }, {
                        data: 'jornada_laboral'
                    }, {
                        data: 'estado'
                    }, {
                        data: 'fecha_nacimiento'
                    }, {
                        data: 'fecha_inicio'
                    }, {
                        data: 'fecha_termino'
                    }, {
                        data: 'tipo_registro'
                    }, {
                        data: 'ley'
                    }, {
                        data: 'preventiva'
                    }, {
                        data: 'referencia_preventiva'
                    }, {
                        data: 'especialidad'
                    }, {
                        data: 'tipo_estudios'
                    }, {
                        data: 'estado_estudios'
                    }, {
                        data: 'grado'
                    }, {
                        data: 'mencion'
                    }, {
                        data: 'especialidad_profesional'
                    }, {
                        data: 'fecha_resolucion'
                    }, {
                        data: 'numero_resolucion'
                    }, {
                        data: 'centro_estudios'
                    }, {
                        data: 'celular'
                    }, {
                        data: 'email'
                    },
                ],
                // responsive:true,
                autoWidth: true,
                "language": {
                    "lengthMenu": "Mostrar " +
                        `<select class="custom-select custom-select-sm form-control form-control-sm">
                <option value = '10'> 10</option>
                <option value = '25'> 25</option>
                <option value = '50'> 50</option>
                <option value = '100'>100</option>
                <option value = '-1'>Todos</option>
                </select>` + " registros por página",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(Filtrado de _MAX_ registros totales)",
                    "emptyTable": "No hay datos disponibles en la tabla.",
                    "info": "Del _START_ al _END_ de _TOTAL_ registros ",
                    "infoEmpty": "Mostrando 0 registros de un total de 0. registros",
                    "infoFiltered": "(filtrados de un total de _MAX_ )",
                    "infoPostFix": "",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "searchPlaceholder": "Dato para buscar",

                    "zeroRecords": "No se han encontrado coincidencias.",

                    "paginate": {
                        "next": "siguiente",
                        "previous": "anterior"
                    }
                }
            }

        );
    </script>
@endsection
