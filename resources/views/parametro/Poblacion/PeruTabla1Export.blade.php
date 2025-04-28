<table id="tabla1" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="2" class="text-center">N°</th>
            <th rowspan="2" class="text-center">Departamento</th>
            <th colspan="3" class="text-center">Población</th>
            <th colspan="1" class="text-center">Niños</th>
            <th colspan="1" class="text-center">Adolescente</th>
            <th colspan="1" class="text-center">Joven</th>
            <th colspan="1" class="text-center">Adulto</th>
            <th colspan="1" class="text-center">Adulto Mayor</th>

        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Total</th>
            <th class="text-center">Hombres</th>
            <th class="text-center">Mujeres</th>
            <th class="text-center"> 0 - 11</th>
            <th class="text-center">12 - 17</th>
            <th class="text-center">18 - 29</th>
            <th class="text-center">30 - 59</th>
            <th class="text-center">60 a más</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->departamento }}</td>
                    <td class="text-center table-warning">{{ number_format($item->conteo, 0) }}</td>
                    <td class="text-center">{{ number_format($item->hconteo, 0) }}</td>
                    <td class="text-center">{{ number_format($item->mconteo, 0) }}</td>
                    <td class="text-center">{{ number_format($item->ev1, 0) }}</td>
                    <td class="text-center">{{ number_format($item->ev2, 0) }}</td>
                    <td class="text-center">{{ number_format($item->ev3, 0) }}</td>
                    <td class="text-center">{{ number_format($item->ev4, 0) }}</td>
                    <td class="text-center">{{ number_format($item->ev5, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th class="text-left" colspan="2">TOTAL</th>
                <th class="text-center">{{ number_format($foot->conteo, 0) }}</th>
                <th class="text-center">{{ number_format($foot->hconteo, 0) }}</th>
                <th class="text-center">{{ number_format($foot->mconteo, 0) }}</th>
                <th class="text-center">{{ number_format($foot->ev1, 0) }}</th>
                <th class="text-center">{{ number_format($foot->ev2, 0) }}</th>
                <th class="text-center">{{ number_format($foot->ev3, 0) }}</th>
                <th class="text-center">{{ number_format($foot->ev4, 0) }}</th>
                <th class="text-center">{{ number_format($foot->ev5, 0) }}</th>
            </tr>
        </tfoot>
    @else
        {{-- <tbody>
            <tr class="text-center">
                <td class="text-center" colspan="11"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody> --}}
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
