<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Provincia</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">%</td>
            <th rowspan="1" colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Publico</th>
            <th rowspan="1" colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Privado</th>
            <th rowspan="1" colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Urbano</th>
            <th rowspan="1" colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Rural</th>
        </tr>
        <tr>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>

            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>

            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>

            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr>
                    <td style="text-align:left;"><a href="javascript:void(0)">{{ $item->provincia }}</a></td>
                    <td style="text-align:right;">{{ number_format($item->tt, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->avance, 1) }}%</td>
                    <td style="text-align:right;">{{ number_format($item->tpubh, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tpubm, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tpub, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tprih, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tprim, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tpri, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->turh, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->turm, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tur, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->truh, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->trum, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tru, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">TOTAL</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->tt, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($item->avance, 1) }}%</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->tpubh, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->tpubm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->tpub, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->tprih, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->tprim, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->tpri, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->turh, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->turm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->tur, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->truh, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->trum, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->tru, 0) }}</th>
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
