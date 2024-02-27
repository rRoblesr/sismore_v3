<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">PROVINCIA</th>
            <th colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Locales Educativos</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Indicador</th>
            <th colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Locales Educativos de EBR</th>
            <th colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Locales Educativos de EBE</th>
            <th colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Locales Educativos de EBA</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Con {{ $tservicio }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Sin {{ $tservicio }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Con {{ $tservicio }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Sin {{ $tservicio }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Con {{ $tservicio }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Sin {{ $tservicio }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Con {{ $tservicio }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Sin {{ $tservicio }}</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr>
                    <td style="text-align:left;"><a href="javascript:void(0)">{{ $item->prov }}</td>
                    <td style="text-align:right;">{{ number_format($item->total, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->con, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->sin, 0) }}</td>
                    <td style="text-align:right;">{!! avance($item->indicador, 1) !!}</td>
                    <td style="text-align:right;">{{ number_format($item->EBRtotal, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBRcon, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBRsin, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBEtotal, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBEcon, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBEsin, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBAtotal, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBAcon, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBAsin, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">TOTAL</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->total, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->con, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->sin, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{!! avance($foot->indicador, 1) !!}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBRtotal, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBRcon, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBRsin, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBEtotal, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBEcon, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBEsin, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBAtotal, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBAcon, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBAsin, 0) }}</th>
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
