<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Distrito</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Meta</td>
            <td colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Estudiantes</td>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Indicador</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Prite</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Inicial</th>
            <th colspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Primaria</th>

        </tr>
        <tr class="bg-success-0 text-white text-center">
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
                    <td style="text-align:left;"><a href="javascript:void(0)">{{ $item->distrito }}</td>
                    <th style="text-align:right;">{{ number_format($item->meta, 0) }}</th>
                    <td style="text-align:right;">{{ number_format($item->th, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tm, 0) }}</td>
                    <th style="text-align:right;">{{ number_format($item->tt, 0) }}</th>
                    <td style="text-align:right;">{!! avance($item->avance, 1) !!}</td>
                    <td style="text-align:right;">{{ number_format($item->thi, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tmi, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->thp, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tmp, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ths, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ths, 0) }}</td>
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
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->thi, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tmi, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->thp, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tmp, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ths, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tms, 0) }}</th>
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
