<table id="tabla1" class="table table-sm table-striped table-bordered font-10" style="height: 100%">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="1" class="text-center">Puesto</th>
            <th rowspan="1" class="text-center">Distrito</th>
            <th colspan="1" class="text-center">Total</th>
            <th rowspan="1" class="text-center">Con {{ $tservicio }}</th>
            <th colspan="1" class="text-center">Sin {{ $tservicio }}</th>
            <th colspan="1" class="text-center">% Indicador</th>
        </tr>

    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->distrito }}</td>
                    <td class="table-warning">{{ number_format($item->total, 0) }}</td>
                    <td>{{ number_format($item->con, 0) }}</td>
                    <td>{{ number_format($item->sin, 0) }}</td>
                    <td>{!! avance($item->indicador, 1) !!}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th colspan="2" class="text-right">TOTAL</th>
                <th class="text-center">{{ number_format($foot->total, 0) }}</th>
                <th class="text-center">{{ number_format($foot->con, 0) }}</th>
                <th class="text-center">{{ number_format($foot->sin, 0) }}</th>
                <th class="text-center">{!! avance($foot->indicador, 1) !!}</th>
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
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;width:40px;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 100) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;width:40px;">' . round($monto, 1) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;width:40px;">' . round($monto, 1) . '%</span>';
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
