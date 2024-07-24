<table>
    <thead>
        <tr class="bg-primary">
            <th>DRE</th>
            <th>UGEL</th>
            <th>DEPARTAMENTO</th>
            <th>PROVINCIA</th>
            <th>DISTRITO</th>

            <th>CENTRO_POBLADO</th>
            <th>COD_MOD</th>
            <th>INSTITUCION_EDUCATIVA</th>
            <th>COD_NIVELMOD</th>
            <th>NIVEL_MODALIDAD</th>{{-- 10 --}}

            <th>COD_GES_DEP</th>
            <th>GESTION_DEPENDENCIA</th>
            <th>TOTAL_ESTUDIANTES</th>
            <th>MATRICULA_DEFINITIVA</th>
            <th>MATRICULA_PROCESO</th>

            <th>DNI_VALIDADO</th>
            <th>DNI_SIN_VALIDAR</th>
            <th>REGISTRADO_SIN_DNI</th>
            <th>TOTAL_GRADOS</th>
            <th>TOTAL_SECCIONES</th>{{-- 20 --}}

            <th>NOMINAS_GENERADAS</th>
            <th>NOMINAS_APROBADAS</th>
            <th>NOMINAS_POR_RECTIFICAR</th>
            <th>TRES_ANIOS_HOMBRE</th>
            <th>TRES_ANIOS_MUJER</th>

            <th>CUATRO_ANIOS_HOMBRE</th>
            <th>CUATRO_ANIOS_MUJER</th>
            <th>CINCO_ANIOS_HOMBRE</th>
            <th>CINCO_ANIOS_MUJER</th>
            <th>PRIMERO_HOMBRE</th>{{-- 30 --}}

            <th>PRIMERO_MUJER</th>
            <th>SEGUNDO_HOMBRE</th>
            <th>SEGUNDO_MUJER</th>
            <th>TERCERO_HOMBRE</th>
            <th>TERCERO_MUJER</th>

            <th>CUARTO_HOMBRE</th>
            <th>CUARTO_MUJER</th>
            <th>QUINTO_HOMBRE</th>
            <th>QUINTO_MUJER</th>
            <th>SEXTO_HOMBRE</th>{{-- 40 --}}

            <th>SEXTO_MUJER</th>
            <th>CERO_ANIOS_HOMBRE</th>
            <th>CERO_ANIOS_MUJER</th>
            <th>UN_ANIO_HOMBRE</th>
            <th>UN_ANIO_MUJER</th>

            <th>DOS_ANIOS_HOMBRE</th>
            <th>DOS_ANIOS_MUJER</th>
            <th>MAS_CINCO_ANIOS_HOMBRE</th>
            <th>MAS_CINCO_ANIOS_MUJER</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($padrons as $item)
            <tr>
                <td>{{ $item->dre }}</td>
                <td>{{ $item->ugel }}</td>
                <td>{{ $item->departamento }}</td>
                <td>{{ $item->provincia }}</td>
                <td>{{ $item->distrito }}</td>

                <td>{{ $item->centro_poblado }}</td>
                <td>{{ $item->cod_mod }}</td>
                <td>{{ $item->institucion_educativa }}</td>
                <td>{{ $item->cod_nivelmod }}</td>
                <td>{{ $item->nivel_modalidad }}</td>{{-- 10 --}}

                <td>{{ $item->cod_ges_dep }}</td>
                <td>{{ $item->gestion_dependencia }}</td>
                <td>{{ $item->total_estudiantes }}</td>
                <td>{{ $item->matricula_definitiva }}</td>
                <td>{{ $item->matricula_proceso }}</td>

                <td>{{ $item->dni_validado }}</td>
                <td>{{ $item->dni_sin_validar }}</td>
                <td>{{ $item->registrado_sin_dni }}</td>
                <td>{{ $item->total_grados }}</td>
                <td>{{ $item->total_secciones }}</td>{{-- 20 --}}

                <td>{{ $item->nominas_generadas }}</td>
                <td>{{ $item->nominas_aprobadas }}</td>
                <td>{{ $item->nominas_por_rectificar }}</td>
                <td>{{ $item->tres_anios_hombre }}</td>
                <td>{{ $item->tres_anios_mujer }}</td>

                <td>{{ $item->cuatro_anios_hombre }}</td>
                <td>{{ $item->cuatro_anios_mujer }}</td>
                <td>{{ $item->cinco_anios_hombre }}</td>
                <td>{{ $item->cinco_anios_mujer }}</td>
                <td>{{ $item->primero_hombre }}</td>{{-- 30 --}}

                <td>{{ $item->primero_mujer }}</td>
                <td>{{ $item->segundo_hombre }}</td>
                <td>{{ $item->segundo_mujer }}</td>
                <td>{{ $item->tercero_hombre }}</td>
                <td>{{ $item->tercero_mujer }}</td>

                <td>{{ $item->cuarto_hombre }}</td>
                <td>{{ $item->cuarto_mujer }}</td>
                <td>{{ $item->quinto_hombre }}</td>
                <td>{{ $item->quinto_mujer }}</td>
                <td>{{ $item->sexto_hombre }}</td>{{-- 40 --}}

                <td>{{ $item->sexto_mujer }}</td>
                <td>{{ $item->cero_anios_hombre }}</td>
                <td>{{ $item->cero_anios_mujer }}</td>
                <td>{{ $item->un_anio_hombre }}</td>
                <td>{{ $item->un_anio_mujer }}</td>

                <td>{{ $item->dos_anios_hombre }}</td>
                <td>{{ $item->dos_anios_mujer }}</td>
                <td>{{ $item->mas_cinco_anios_hombre }}</td>
                <td>{{ $item->mas_cinco_anios_mujer }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
