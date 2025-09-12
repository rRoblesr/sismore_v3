<table id="tabla3_1" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th class="text-center">N°</th>
            <th class="text-center">Tipo Documento</th>
            <th class="text-center">Documento</th>
            <th class="text-center">Nombre del Niño</th>
            <th class="text-center">Fecha Nacimiento</th>
            <th class="text-center">Distrito</th>
            <th class="text-center">Centro poblado</th>
            <th class="text-center">Seguro</th>
            <th class="text-center">Nombre de la Madre</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-right">
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-left">{{ $item->tipo }}</td>
                <td class="text-left">{{ $item->documento }}</td>
                <td class="text-left">{{ $item->nombre_completo }}</td>
                <td class="text-left">{{ $item->nacimiento }}</td>
                <td class="text-left">{{ $item->distrito }}</td>
                <td class="text-left">{{ $item->cpnombre }}</td>
                <td class="text-left">{{ $item->cseguro }}</td>
                <td class="text-left">{{ $item->nombre_completo_madre }}</td>
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
