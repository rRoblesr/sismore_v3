<table id="tabla1" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" class="text-center">N°</th>
            <th rowspan="2" class="text-center">Registro con Observaciones</th>
            <th rowspan="2" class="text-center">Total</th>
            <th colspan="6" class="text-center">Edades de Niños y Niñas menores a 6 años</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th class="text-center">{{ '<' }} 1 A</th>
            <th class="text-center">1 A</th>
            <th class="text-center">2 A</th>
            <th class="text-center">3 A</th>
            <th class="text-center">4 A</th>
            <th class="text-center">5 A</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-left">
                    <a
                        href="{{ route('salud.padronnominal.tablerocalidad.criterio', ['importacion' => $impMaxAnio, 'criterio' => $key + 1]) }}">
                        {{ $item->criterio }}
                    </a>
                </td>
                <td class="table-warning">{{ number_format($item->total, 0) }}</td>
                <td>{{ number_format($item->pob0, 0) }}</td>
                <td>{{ number_format($item->pob1, 0) }}</td>
                <td>{{ number_format($item->pob2, 0) }}</td>
                <td>{{ number_format($item->pob3, 0) }}</td>
                <td>{{ number_format($item->pob4, 0) }}</td>
                <td>{{ number_format($item->pob5, 0) }}</td>
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
