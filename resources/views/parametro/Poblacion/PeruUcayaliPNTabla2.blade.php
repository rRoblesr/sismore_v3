<table id="tabla2" class="table table-sm table-striped table-bordered font-10">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="2" class="text-center">N°</th>
            <th rowspan="2" class="text-center">Distritos</th>
            <th colspan="3" class="text-center">Población</th>
            <th colspan="6" class="text-center">Edades Simples</th>
            <th colspan="3" class="text-center">Edades especiales</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Total</th>
            <th class="text-center">Hombres</th>
            <th class="text-center">Mujeres</th>
            <th class="text-center">
                < 1A</th>
            <th class="text-center">1A</th>
            <th class="text-center">2A</th>
            <th class="text-center">3A</th>
            <th class="text-center">4A</th>
            <th class="text-center">5A</th>
            <th class="text-center">0 a 28 dias</th>
            <th class="text-center">0 a 5 meses</th>
            <th class="text-center">6 a 11 meses </th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->distrito }}</td>
                    <td class="text-center table-warning">{{ number_format($item->conteo, 0) }}</td>
                    <td class="text-center">{{ number_format($item->hconteo, 0) }}</td>
                    <td class="text-center">{{ number_format($item->mconteo, 0) }}</td>
                    <td class="text-center">{{ number_format($item->edad0, 0) }}</td>
                    <td class="text-center">{{ number_format($item->edad1, 0) }}</td>
                    <td class="text-center">{{ number_format($item->edad2, 0) }}</td>
                    <td class="text-center">{{ number_format($item->edad3, 0) }}</td>
                    <td class="text-center">{{ number_format($item->edad4, 0) }}</td>
                    <td class="text-center">{{ number_format($item->edad5, 0) }}</td>
                    <td class="text-center">{{ number_format($item->edad28, 0) }}</td>
                    <td class="text-center">{{ number_format($item->edad05, 0) }}</td>
                    <td class="text-center">{{ number_format($item->edad611, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th class="text-left" colspan="2">TOTAL</th>
                <th class="text-center">{{ number_format($foot->conteo, 0) }}</th>
                <th class="text-center">{{ number_format($foot->hconteo, 0) }}</th>
                <th class="text-center">{{ number_format($foot->mconteo, 0) }}</th>
                <th class="text-center">{{ number_format($foot->edad0, 0) }}</th>
                <th class="text-center">{{ number_format($foot->edad1, 0) }}</th>
                <th class="text-center">{{ number_format($foot->edad2, 0) }}</th>
                <th class="text-center">{{ number_format($foot->edad3, 0) }}</th>
                <th class="text-center">{{ number_format($foot->edad4, 0) }}</th>
                <th class="text-center">{{ number_format($foot->edad5, 0) }}</th>
                <th class="text-center">{{ number_format($foot->edad28, 0) }}</th>
                <th class="text-center">{{ number_format($foot->edad05, 0) }}</th>
                <th class="text-center">{{ number_format($foot->edad611, 0) }}</th>
            </tr>
        </tfoot>
    @else
        <tbody>
            <tr class="text-center">
                <td class="text-center" colspan="11"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody>
    @endif
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' .
                round($monto, 1) .
                '%</span>';
        } elseif ($monto < 100) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;">' .
                round($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' .
                round($monto, 1) .
                '%</span>';
        }
    }
    function bajas($monto)
    {
        if ($monto < 0) {
            return '<span class="badge badge-pill badge-danger" style="font-size:85%;">' . round($monto, 0) . '</span>';
        } else {
            return number_format($monto);
        }
    }
@endphp
