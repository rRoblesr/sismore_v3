<table id="tabla1" class="table table-sm table-striped table-bordered font-12">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="2" class="text-center">N°</th>
            <th rowspan="2" class="text-center">Distrito</th>
            <th colspan="3" class="text-center">Población</th>
            <th colspan="6" class="text-center">Edades Simples</th>
            <th colspan="3" class="text-center">Edades Especiales</th>
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
            <th class="text-center">0 A 28 días</th>
            <th class="text-center">0 a 5 meses</th>
            <th class="text-center">6 a 11 meses</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->distrito }}</td>
                    <td class="text-center table-warning">{{ number_format($item->total, 0) }}</td>
                    <td class="text-center">{{ number_format($item->th, 0) }}</td>
                    <td class="text-center">{{ number_format($item->tm, 0) }}</td>
                    <td class="text-center">{{ number_format($item->c0a, 0) }}</td>
                    <td class="text-center">{{ number_format($item->c1a, 0) }}</td>
                    <td class="text-center">{{ number_format($item->c2a, 0) }}</td>
                    <td class="text-center">{{ number_format($item->c3a, 0) }}</td>
                    <td class="text-center">{{ number_format($item->c4a, 0) }}</td>
                    <td class="text-center">{{ number_format($item->c5a, 0) }}</td>
                    <td class="text-center">{{ number_format($item->ee1, 0) }}</td>
                    <td class="text-center">{{ number_format($item->ee2, 0) }}</td>
                    <td class="text-center">{{ number_format($item->ee3, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th class="text-left" colspan="2">TOTAL</th>
                <th class="text-center">{{ number_format($foot->total, 0) }}</th>
                <th class="text-center">{{ number_format($foot->th, 0) }}</th>
                <th class="text-center">{{ number_format($foot->tm, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c0a, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c1a, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2a, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c3a, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c4a, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c5a, 0) }}</th>
                <th class="text-center">{{ number_format($foot->ee1, 0) }}</th>
                <th class="text-center">{{ number_format($foot->ee2, 0) }}</th>
                <th class="text-center">{{ number_format($foot->ee3, 0) }}</th>
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
