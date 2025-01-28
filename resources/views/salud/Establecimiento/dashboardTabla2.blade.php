<table id="tabla2" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" class="text-center">N°</th>
            <th rowspan="2" class="text-center">Distrito</th>
            <th rowspan="2" class="text-center">Total</th>
            <th colspan="2" class="text-center">Puestos de Salud</th>
            <th colspan="2" class="text-center">Centros de Salud</th>
            <th colspan="3" class="text-center">Hospitales/Clínicas Especializados</th>
            <th colspan="3" class="text-center">Hospitales/Institutos Especializados</th>
            <th rowspan="2" class="text-center">Sin Categoria</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th class="text-center">I-1</th>
            <th class="text-center">I-2</th>
            <th class="text-center">I-3</th>
            <th class="text-center">I-4</th>
            <th class="text-center">II-1</th>
            <th class="text-center">II-2</th>
            <th class="text-center">II-E</th>
            <th class="text-center">III-1</th>
            <th class="text-center">III-2</th>
            <th class="text-center">III-E</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-left"> {{ $item->distrito }}</td>
                <td class="table-warning">{{ number_format($item->conteo, 0) }}</td>
                <td>{{ number_format($item->ci1, 0) }}</td>
                <td>{{ number_format($item->ci2, 0) }}</td>
                <td>{{ number_format($item->ci3, 0) }}</td>
                <td>{{ number_format($item->ci4, 0) }}</td>
                <td>{{ number_format($item->cii1, 0) }}</td>
                <td>{{ number_format($item->cii2, 0) }}</td>
                <td>{{ number_format($item->ciie, 0) }}</td>
                <td>{{ number_format($item->ciii1, 0) }}</td>
                <td>{{ number_format($item->ciii2, 0) }}</td>
                <td>{{ number_format($item->ciiie, 0) }}</td>
                <td>{{ number_format($item->csc, 0) }}</td>
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
