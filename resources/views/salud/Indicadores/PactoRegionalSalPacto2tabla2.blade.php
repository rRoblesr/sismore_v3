<table id="tabla2" class="table table-sm table-striped table-bordered font-11 m-0" style="height: 100%">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="1" class="text-center">Nº</th>
            <th rowspan="1" class="text-center">RED</th>
            <th rowspan="1" class="text-center">Denominador</th>
            <th rowspan="1" class="text-center">Numerador</th>
            <th colspan="1" class="text-center">Indicador</th>
            {{-- <th colspan="1" class="text-center">Condición</th> --}}
        </tr>

    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">
                        <a href="javascript:void(0)" onclick="cargarmicrored({{ $item->idred }})">{{ $item->red }}
                        </a>
                    </td>
                    <td>{{ $item->den }}</td>
                    <td>{{ $item->num }}</td>
                    <td>{!! avance($item->ind) !!}</td>
                    {{-- <td>
                        @if ($item->cumple == 1)
                            <button type="button"
                                class="btn btn-xs btn-success-0 p-1 font-11 text-nowrap">&nbsp;&nbsp;&nbsp;Cumple&nbsp;&nbsp;&nbsp;</button>
                        @else
                            <button type="button" class="btn btn-xs btn-danger p-1 font-11 text-nowrap">No Cumple</button>
                        @endif
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th colspan="2" class="text-right">TOTAL</th>
                <th class="text-center">{{ number_format($foot->den, 0) }}</th>
                <th class="text-center">{{ number_format($foot->num, 0) }}</th>
                <td>{!! avance($foot->ind) !!}</td>
                {{-- <td>
                    @if ($foot->cumple == 1)
                        <button type="button"
                            class="btn btn-xs btn-success-0 p-1 font-11 text-nowrap">&nbsp;&nbsp;&nbsp;Cumple&nbsp;&nbsp;&nbsp;</button>
                    @else
                        <button type="button" class="btn btn-xs btn-danger p-1 font-11 text-nowrap">No Cumple</button>
                    @endif
                </td> --}}
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
        } elseif ($monto < 95) {
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
