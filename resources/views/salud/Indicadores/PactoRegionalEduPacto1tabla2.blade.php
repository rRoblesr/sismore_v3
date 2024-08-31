<table id="tabla2" class="table table-sm table-striped table-bordered font-11 m-0">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="2" class="text-center">Nº</th>
            <th rowspan="2" class="text-center">Nivel Educativo</th>
            <th rowspan="1" colspan="3" class="text-center">Matriculados</th>
            <th rowspan="1" colspan="3" class="text-center">3 Años</th>
            <th rowspan="1" colspan="3" class="text-center">4 Años</th>
            <th rowspan="1" colspan="3" class="text-center">5 Años</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Total</th>
            <th class="text-center">Hombre</th>
            <th class="text-center">Mujer</th>
            <th class="text-center">Total</th>
            <th class="text-center">Hombre</th>
            <th class="text-center">Mujer</th>
            <th class="text-center">Total</th>
            <th class="text-center">Hombre</th>
            <th class="text-center">Mujer</th>
            <th class="text-center">Total</th>
            <th class="text-center">Hombre</th>
            <th class="text-center">Mujer</th>

        </tr>


    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->nivelmodalidad }}</td>
                    <td class="table-warning">{{ number_format($item->conteo,0) }}</td>
                    <td>{{ number_format($item->hconteo,0) }}</td>
                    <td>{{ number_format($item->mconteo,0) }}</td>
                    <td class="table-warning">{{ number_format($item->conteo3,0) }}</td>
                    <td>{{ number_format($item->hconteo3,0) }}</td>
                    <td>{{ number_format($item->mconteo3,0) }}</td>
                    <td class="table-warning">{{ number_format($item->conteo4,0) }}</td>
                    <td>{{ number_format($item->hconteo4,0) }}</td>
                    <td>{{ number_format($item->mconteo4,0) }}</td>
                    <td class="table-warning">{{ number_format($item->conteo5,0) }}</td>
                    <td>{{ number_format($item->hconteo5,0) }}</td>
                    <td>{{ number_format($item->mconteo5,0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th colspan="2" class="text-right">TOTAL</th>
                <th class="text-center">{{ number_format($foot->conteo, 0) }}</th>
                <th class="text-center">{{ number_format($foot->hconteo, 0) }}</th>
                <th class="text-center">{{ number_format($foot->mconteo, 0) }}</th>
                <th class="text-center">{{ number_format($foot->conteo3, 0) }}</th>
                <th class="text-center">{{ number_format($foot->hconteo3, 0) }}</th>
                <th class="text-center">{{ number_format($foot->mconteo3, 0) }}</th>
                <th class="text-center">{{ number_format($foot->conteo4, 0) }}</th>
                <th class="text-center">{{ number_format($foot->hconteo4, 0) }}</th>
                <th class="text-center">{{ number_format($foot->mconteo4, 0) }}</th>
                <th class="text-center">{{ number_format($foot->conteo5, 0) }}</th>
                <th class="text-center">{{ number_format($foot->hconteo5, 0) }}</th>
                <th class="text-center">{{ number_format($foot->mconteo5, 0) }}</th>
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
            return '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px">' .
                round($monto, 1) .
                '%</span>';
        } elseif ($monto < 100) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%; width:50px">' .
                round($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px">' .
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
