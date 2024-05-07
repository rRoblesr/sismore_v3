<table id="tabla2vista1" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="2" class="text-center">PROVINCIA</th>
            <th colspan="3" class="text-center">Locales Educativos</th>
            <th rowspan="2" class="text-center">Indicador</th>
            <th colspan="3" class="text-center">Locales Educativos de EBR</th>
            <th colspan="3" class="text-center">Locales Educativos de EBE</th>
            <th colspan="3" class="text-center">Locales Educativos de EBA</th>
        </tr>

        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Total</th>
            <th class="text-center">Con {{ $tservicio }}</th>
            <th class="text-center">Sin {{ $tservicio }}</th>
            <th class="text-center">Total</th>
            <th class="text-center">Con {{ $tservicio }}</th>
            <th class="text-center">Sin {{ $tservicio }}</th>
            <th class="text-center">Total</th>
            <th class="text-center">Con {{ $tservicio }}</th>
            <th class="text-center">Sin {{ $tservicio }}</th>
            <th class="text-center">Total</th>
            <th class="text-center">Con {{ $tservicio }}</th>
            <th class="text-center">Sin {{ $tservicio }}</th>
        </tr>

    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td class="text-left">{{ $item->provincia }}</td>
                    <td class="table-warning">{{ number_format($item->total, 0) }}</td>
                    <td>{{ number_format($item->con, 0) }}</td>
                    <td>{{ number_format($item->sin, 0) }}</td>
                    <td>{!! avance($item->indicador, 1) !!}</td>
                    <td class="table-warning">{{ number_format($item->EBRtotal, 0) }}</td>
                    <td>{{ number_format($item->EBRcon, 0) }}</td>
                    <td>{{ number_format($item->EBRsin, 0) }}</td>
                    <td class="table-warning">{{ number_format($item->EBEtotal, 0) }}</td>
                    <td>{{ number_format($item->EBEcon, 0) }}</td>
                    <td>{{ number_format($item->EBEsin, 0) }}</td>
                    <td class="table-warning">{{ number_format($item->EBAtotal, 0) }}</td>
                    <td>{{ number_format($item->EBAcon, 0) }}</td>
                    <td>{{ number_format($item->EBAsin, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th colspan="1" class="text-right">TOTAL</th>
                <th class="text-center">{{ number_format($foot->total, 0) }}</th>
                <th class="text-center">{{ number_format($foot->con, 0) }}</th>
                <th class="text-center">{{ number_format($foot->sin, 0) }}</th>
                <th class="text-center">{!! avance($foot->indicador, 1) !!}</th>
                <th class="text-center">{{ number_format($foot->EBRtotal, 0) }}</th>
                <th class="text-center">{{ number_format($foot->EBRcon, 0) }}</th>
                <th class="text-center">{{ number_format($foot->EBRsin, 0) }}</th>
                <th class="text-center">{{ number_format($foot->EBEtotal, 0) }}</th>
                <th class="text-center">{{ number_format($foot->EBEcon, 0) }}</th>
                <th class="text-center">{{ number_format($foot->EBEsin, 0) }}</th>
                <th class="text-center">{{ number_format($foot->EBAtotal, 0) }}</th>
                <th class="text-center">{{ number_format($foot->EBAcon, 0) }}</th>
                <th class="text-center">{{ number_format($foot->EBAsin, 0) }}</th>
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
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 100) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
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
