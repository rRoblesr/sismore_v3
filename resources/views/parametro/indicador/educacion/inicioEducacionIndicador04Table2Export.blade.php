<table id="tabla1" class="table table-bordered mb-0 tablex" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">UGEL</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL DOCENTES</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL TITULADOS</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">INDICADOR</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">DOCENTES</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">CONDICIÓN LABORAL</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">TIPO GESTIÓN</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">ÁMBITO GEOGRÁFICO</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">HOMBRES</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">MUJERES</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">NOMBRADOS</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">COMTRATADOS</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">PÚBLICA</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">PRIVADA</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">URBANA</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">RURAL</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr>
                    <td style="text-align:left;">{{ $item->ugel }}</td>
                    <td style="text-align:right;">{{ $item->td }}</td>
                    <td style="text-align:right;">{{ $item->tt }}</td>
                    <td style="text-align:right;">{!! avance($item->avance)!!}</td>
                    <td style="text-align:right;">{{ number_format($item->tth, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ttm, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ttn, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ttc, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->pub, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->pri, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->urb, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->rur, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
         <tfoot>
            <tr class="table-success-0 text-white">
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">Total</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->td, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tt, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{!! avance($item->avance)!!}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tth, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ttm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ttn, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ttc, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->pub, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->pri, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->urb, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->rur, 0) }}</th>
            </tr>
        </tfoot>
    @else
        <tr class="text-center">
            <td colspan="18"><a href="javascript:void(0)">Sin información</a></td>
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
