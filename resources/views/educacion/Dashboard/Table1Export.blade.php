<table id="tabla1" class="table table-bordered mb-0 tablex" style="font-size:11px;">
    <thead>
        <tr>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">UGEL</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Meta</th>
            <th colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Estudiantes</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">%Avance</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">EBR</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">EBE</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">EBA</th>
        </tr>
        <tr>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr>
                    <td style="text-align:left;">{{ $item->ugel }}</td>
                    <td style="text-align:right;">{{ number_format($item->meta, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->th, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tm, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tt, 0) }}</td>
                    <td class="table-warning">{{ number_format($item->avance, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBRth, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBRtm, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBEth, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBEtm, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBAth, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->EBAtm, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">Total</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->meta, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->th, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tt, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->avance, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBRth, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBRtm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBEth, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBEtm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBAth, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->EBAtm, 0) }}</th>
            </tr>
        </tfoot>
    @else
        <tr class="text-center">
            <td colspan="12"><a href="javascript:void(0)">Sin información</a></td>
        </tr>
        </tfoot>
    @endif
</table>

@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' . round($monto, 1) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        }
    }
@endphp
