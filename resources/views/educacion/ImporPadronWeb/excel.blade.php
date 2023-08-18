<table>
    <thead>
        <tr class="bg-primary">
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
        </tr>
    </thead>
    <tbody>
        @foreach ($padrons as $item)
            <tr>
                <td>{{ $item->cod_Mod }}</td>
                <td>{{ $item->cod_Local }}</td>
                <td>{{ $item->cen_Edu }}</td>
                <td>{{ $item->niv_Mod }}</td>
                <td>{{ $item->d_Niv_Mod }}</td>
                <td>{{ $item->d_Forma }}</td>
                <td>{{ $item->cod_Car }}</td>
                <td>{{ $item->d_Cod_Car }}</td>
                <td>{{ $item->TipsSexo }}</td>
                <td>{{ $item->d_TipsSexo }}</td>
                <td>{{ $item->gestion }}</td>
                <td>{{ $item->d_Gestion }}</td>
                <td>{{ $item->ges_Dep }}</td>
                <td>{{ $item->d_Ges_Dep }}</td>
                <td>{{ $item->director }}</td>
                <td>{{ $item->telefono }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->dir_Cen }}</td>
                <td>{{ $item->localidad }}</td>
                <td>{{ $item->codcp_Inei }}</td>
                <td>{{ $item->codccpp }}</td>
                <td>{{ $item->cen_Pob }}</td>
                <td>{{ $item->area_Censo }}</td>
                <td>{{ $item->d_areaCenso }}</td>
                <td>{{ $item->codGeo }}</td>
                <td>{{ $item->d_Prov }}</td>
                <td>{{ $item->d_Dist }}</td>
                <td>{{ $item->codOOII }}</td>
                <td>{{ $item->d_DreUgel }}</td>
                <td>{{ $item->nLat_IE }}</td>
                <td>{{ $item->nLong_IE }}</td>
                <td>{{ $item->cod_Tur }}</td>
                <td>{{ $item->D_Cod_Tur }}</td>
                <td>{{ $item->estado }}</td>
                <td>{{ $item->d_Estado }}</td>
                <td>{{ $item->tAlum_Hom }}</td>
                <td>{{ $item->tAlum_Muj }}</td>
                <td>{{ $item->tAlumno }}</td>
                <td>{{ $item->tDocente }}</td>
                <td>{{ $item->tSeccion }}</td>
                <td>{{ $item->fechaReg }}</td>
                <td>{{ $item->fecha_Act }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
