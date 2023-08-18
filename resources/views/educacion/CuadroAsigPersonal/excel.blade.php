<table>
    <thead>
        <tr class="bg-primary">
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
        </tr>
    </thead>
    <tbody>
        @foreach ($padrons as $item)
            <tr>
                <td>{{ $item->unidad_ejecutora }}</td>
                <td>{{ $item->organo_intermedio }}</td>
                <td>{{ $item->provincia }}</td>
                <td>{{ $item->distrito }}</td>
                <td>{{ $item->tipo_ie }}</td>
                <td>{{ $item->gestion }}</td>
                <td>{{ $item->zona }}</td>
                <td>{{ $item->codmod_ie }}</td>
                <td>{{ $item->codigo_local }}</td>
                <td>{{ $item->clave8 }}</td>
                <td>{{ $item->nivel_educativo }}</td>
                <td>{{ $item->institucion_educativa }}</td>
                <td>{{ $item->codigo_plaza }}</td>
                <td>{{ $item->tipo_trabajador }}</td>
                <td>{{ $item->sub_tipo_trabajador }}</td>
                <td>{{ $item->cargo }}</td>
                <td>{{ $item->situacion_laboral }}</td>
                <td>{{ $item->motivo_vacante }}</td>
                <td>{{ $item->documento_identidad }}</td>
                <td>{{ $item->sexo }}</td>
                <td>{{ $item->codigo_modular }}</td>
                <td>{{ $item->apellido_paterno }}</td>
                <td>{{ $item->apellido_materno }}</td>
                <td>{{ $item->nombres }}</td>
                <td>{{ $item->fecha_ingreso }}</td>
                <td>{{ $item->categoria_remunerativa }}</td>
                <td>{{ $item->jornada_laboral }}</td>
                <td>{{ $item->estado }}</td>
                <td>{{ $item->fecha_nacimiento }}</td>
                <td>{{ $item->fecha_inicio }}</td>
                <td>{{ $item->fecha_termino }}</td>
                <td>{{ $item->tipo_registro }}</td>
                <td>{{ $item->ley }}</td>
                <td>{{ $item->preventiva }}</td>
                <td>{{ $item->especialidad }}</td>
                <td>{{ $item->tipo_estudios }}</td>
                <td>{{ $item->estado_estudios }}</td>
                <td>{{ $item->grado }}</td>
                <td>{{ $item->mencion }}</td>
                <td>{{ $item->especialidad_profesional }}</td>
                <td>{{ $item->fecha_resolucion }}</td>
                <td>{{ $item->numero_resolucion }}</td>
                <td>{{ $item->centro_estudios }}</td>
                <td>{{ $item->celular }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->desc_superior }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
