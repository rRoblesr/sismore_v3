<table id="tabla1" class="table table-sm table-striped table-bordered font-11 m-0" style="height: 100%">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="1" class="text-center">Nº</th>
            <th rowspan="1" class="text-center">Distrito</th>
            <th colspan="1" class="text-center">Meta</th>
            <th rowspan="1" class="text-center">Numerador</th>
            <th rowspan="1" class="text-center">Denominador</th>
            <th rowspan="1" class="text-center">Indicador</th>
            <th colspan="1" class="text-center">Cumple</th>
        </tr>

    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center {{ $item->distrito == $ndis ? 'table-warning' : '' }}">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->distrito }}</td>
                    <td class="table-warning">{{ $item->meta }}%</td>
                    <td>{{ number_format($item->numerador, 0) }}</td>
                    <td>{{ number_format($item->denominador, 0) }}</td>
                    {{-- <td>{{ number_format($item->denominador - $item->numerador, 0) }}</td> --}}
                    <td>{!! avance($item->indicador) !!}</td>
                    <td>
                        @if ($item->cumple == 1)
                            <i class="mdi mdi-thumb-up" style="font-size:13px;color:#43beac" title="CUMPLE"></i>
                        @else
                            <i class="mdi mdi-thumb-down" style="font-size:13px;color: red" title="NO CUMPLE"></i>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        {{-- <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th colspan="2" class="text-right">TOTAL</th>
                <th class="text-center">{{ number_format($foot->total, 0) }}</th>
                <th class="text-center">{{ number_format($foot->con, 0) }}</th>
                <th class="text-center">{{ number_format($foot->sin, 0) }}</th>
                <th class="text-center">{!! avance($foot->indicador, 1) !!}</th>
            </tr>
        </tfoot> --}}
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
        $roundedMonto = round($monto, 1); // Redondear una sola vez
        $badgeClass = '';
        if ($monto < 51) {
            $badgeClass = 'badge-danger';
        } elseif ($monto < 95) {
            $badgeClass = 'badge-orange-x';
        } else {
            $badgeClass = 'badge-success';
        }
        return '<span class="badge badge-pill ' .
            $badgeClass .
            '" style="font-size:90%; width:50px;">' .
            $roundedMonto .
            '%</span>';
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
