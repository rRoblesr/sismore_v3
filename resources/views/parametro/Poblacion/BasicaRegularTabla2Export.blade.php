<table id="tabla2" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Distrito</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Meta</td>
            <td colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Estudiantes</td>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Indicador</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Inicial</th>
            <th colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Primaria</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Secundaria</th>
        </tr>
        <tr>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">ciclo I</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">ciclo II</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">ciclo III</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">ciclo IV</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">ciclo V</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">ciclo VI</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">ciclo VII</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr>
                    <td style="text-align:left;">{{ $item->distrito }}</td>
                    <th style="text-align:right;">{{ number_format($item->meta, 0) }}</th>
                    <td style="text-align:right;">{{ number_format($item->th, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tm, 0) }}</td>
                    <th style="text-align:right;">{{ number_format($item->tt, 0) }}</th>
                    <td style="text-align:right;">{!! avance($item->avance, 1) !!}</td>
                    <td style="text-align:right;">{{ number_format($item->ci, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cii, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ciii, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->civ, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cv, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cvi, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cvii, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">TOTAL</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->meta, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->th, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tt, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{!! avance($foot->avance, 1) !!}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ci, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cii, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ciii, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->civ, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cv, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cvi, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cvii, 0) }}</th>
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
