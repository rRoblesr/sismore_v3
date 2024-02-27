<table id="tabla1" class="table table-bordered mb-0 tablex" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">CÓDIGO MODULAR</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">INSTITUCIÓN EDUCATIVA</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">ÁREA</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL DOCENTES</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL TITULADOS</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">INDICADOR</th>
            <th colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">DOCENTES NOMBRADOS CON TITULO</th>
            <th colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">DOCENTES CONTRATADOS CON TITULO</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">HOMBRES</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">MUJERES</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">HOMBRES</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">MUJERES</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr>
                    <td style="text-align:center;">{{ $item->modular }}</td>
                    <td style="text-align:left;">{{ $item->iiee }}</td>
                    <td style="text-align:right;">{{ $item->area }}</td>
                    <td style="text-align:right;">{{ number_format($item->total, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tt, 0) }}</td>
                    <td style="text-align:right;">{!! avance($item->avance)!!}</td>
                    <td style="text-align:right;">{{ number_format($item->d01, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->d02, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ttn, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->d03, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->d04, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ttc, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
         <tfoot>
            <tr class="table-success-0 text-white">
                <th colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:right;">Total</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->total, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tt, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{!! avance($item->avance)!!}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->d01, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->d02, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ttn, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->d03, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->d04, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ttc, 0) }}</th>
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
