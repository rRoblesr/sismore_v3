<table id="tabla1" class="table table-sm table-striped table-bordered font-12">
    <thead>
        <tr class="table-success-0 text-white">
            <th class="text-center">N°</th>
            <th class="text-center">Cód. Padrón</th>
            <th class="text-center">Tipo Doc.</th>
            <th class="text-center">Documento</th>
            <th class="text-center">Apellido paterno</th>
            <th class="text-center">Apellido materno</th>
            <th class="text-center">Nombre</th>
            <th class="text-center">Edad</th>
            <th class="text-center">Seguro</th>
            <th class="text-center">Visitado</th>
            <th class="text-center">Encontrado</th>
            <th class="text-center">Distrito</th>
            <th class="text-center">Disa de Atención</th>
            <th class="text-center">Red de Atención</th>
            <th class="text-center">Microred de Atención</th>
            <th class="text-center">Cod. EESS</th>
            <th class="text-center">EESS de Atención</th>
            <th class="text-center">Tipo Doc. de Madre</th>
            <th class="text-center">Documento de Madre</th>
            <th class="text-center">Apellido paterno de Madre</th>
            <th class="text-center">Apellido materno de Madre</th>
            <th class="text-center">Nombre de Madre</th>
            <th class="text-center">Grado de Instruccion de la Madre</th>
            <th class="text-center">Lengua de la Madre</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->padron }}</td>
                <td>{{ $item->tipo_doc != 'Padron' ? $item->tipo_doc : '' }}</td>
                <td>{{ $item->tipo_doc != 'Padron' ? $item->num_doc : '' }}</td>
                <td>{{ $item->apellido_paterno }}</td>
                <td>{{ $item->apellido_materno }}</td>
                <td>{{ $item->nombre }}</td>
                <td>{{ $item->edadx }}</td>
                <td>{{ $item->segurox }}</td>
                <td>{{ $item->visita == 1 ? 'SI' : 'NO' }}</td>
                <td>{{ $item->encontrado == 1 ? 'SI' : 'NO' }}</td>
                <td>{{ $item->distritox }}</td>
                <td>{{ $item->disax }}</td>
                <td>{{ $item->redx }}</td>
                <td>{{ $item->microx }}</td>
                <td>{{ $item->cuix }}</td>
                <td>{{ $item->eessx }}</td>
                <td>{{ $item->tipo_doc_madre }}</td>
                <td>{{ $item->num_doc_madre }}</td>
                <td>{{ $item->apellido_paterno_madre }}</td>
                <td>{{ $item->apellido_materno_madre }}</td>
                <td>{{ $item->nombres_madre }}</td>
                <td>{{ $item->grado_instruccion }}</td>
                <td>{{ $item->lengua_madre }}</td>
            </tr>
        @endforeach
    </tbody>
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' .
                round($monto, 2) .
                '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' .
                round($monto, 2) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' .
                round($monto, 2) .
                '%</span>';
        }
    }
@endphp
