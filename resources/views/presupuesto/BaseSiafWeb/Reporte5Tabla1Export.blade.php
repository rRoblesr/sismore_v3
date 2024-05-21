<table>
    <thead>
        <tr>
            <th rowspan="1" style="background-color:#43beac;color:#ffffff;text-align:center;">FUENTE FINANCIAMIENTO | RUBRO </th>
            <th rowspan="1" style="background-color:#43beac;color:#ffffff;text-align:center;">PIA</th>
            <th rowspan="1" style="background-color:#43beac;color:#ffffff;text-align:center;">PIM</th>
            <th rowspan="1" style="background-color:#43beac;color:#ffffff;text-align:center;">CERTIFICADO</th>
            <th rowspan="1" style="background-color:#43beac;color:#ffffff;text-align:center;">Avance CERT.</th>
            <th rowspan="1" style="background-color:#43beac;color:#ffffff;text-align:center;">DEVENGADO</th>
            <th rowspan="1" style="background-color:#43beac;color:#ffffff;text-align:center;">Avance DEV.</th>
            <th rowspan="1" style="background-color:#43beac;color:#ffffff;text-align:center;">SALDO CERT.</th>
            <th rowspan="1" style="background-color:#43beac;color:#ffffff;text-align:center;">SALDO DEV.</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($head as $pos => $item)
            <tr>
                <td style="background-color:#c2bbbb;color:#0c0101;text-align:left;">{{ $item->cfuente }} {{ $item->fuente }}</td>
                <td style="background-color:#c2bbbb;color:#0c0101;text-align:center;">{{ number_format($item->pia, 0) }}</td>
                <td style="background-color:#c2bbbb;color:#0c0101;text-align:center;">{{ number_format($item->pim, 0) }}</td>
                <td style="background-color:#c2bbbb;color:#0c0101;text-align:center;">{{ number_format($item->cert, 0) }}</td>
                <td style="background-color:#c2bbbb;color:#0c0101;text-align:center;">{!! avance($item->eje1) !!}</td>
                <td style="background-color:#c2bbbb;color:#0c0101;text-align:center;">{{ number_format($item->dev, 0) }}</td>
                <td style="background-color:#c2bbbb;color:#0c0101;text-align:center;">{!! avance($item->eje) !!}</td>
                <td style="background-color:#c2bbbb;color:#0c0101;text-align:center;">{{ number_format($item->saldo1, 0) }}</td>
                <td style="background-color:#c2bbbb;color:#0c0101;text-align:center;">{{ number_format($item->saldo2, 0) }}</td>
            </tr>

            @foreach ($body as $pos => $item2)
                @if ($item2->cfuente == $item->cfuente)
                    <tr>
                        <td style="text-align:left;">&nbsp;&nbsp;&nbsp;{{ $item2->crubro }}{{ $item2->rubro }}</td>
                        <td style="text-align:center;">{{ number_format($item2->pia, 0) }}</td>
                        <td style="text-align:center;">{{ number_format($item2->pim, 0) }}</td>
                        <td style="text-align:center;">{{ number_format($item2->cert, 0) }}</td>
                        <td style="text-align:center;">{!! avance($item2->eje1) !!}</td>
                        <td style="text-align:center;">{{ number_format($item2->dev, 0) }}</td>
                        <td style="text-align:center;">{!! avance($item2->eje) !!}</td>
                        <td style="text-align:center;">{{ number_format($item2->saldo1, 0) }}</td>
                        <td style="text-align:center;">{{ number_format($item2->saldo2, 0) }}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="1" style="background-color:#317eeb;color:#ffffff;">TOTAL</td>
            <td style="background-color:#43beac;color:#ffffff;text-align:center;">{{ number_format($foot->pia, 0) }}</td>
            <td style="background-color:#43beac;color:#ffffff;text-align:center;">{{ number_format($foot->pim, 0) }}</td>
            <td style="background-color:#43beac;color:#ffffff;text-align:center;">{{ number_format($foot->cert, 0) }}</td>
            <td style="background-color:#43beac;color:#ffffff;text-align:center;">{!! avance($foot->eje1) !!}</td>
            <td style="background-color:#43beac;color:#ffffff;text-align:center;">{{ number_format($foot->dev, 0) }}</td>
            <td style="background-color:#43beac;color:#ffffff;text-align:center;">{!! avance($foot->eje) !!}</td>
            <td style="background-color:#43beac;color:#ffffff;text-align:center;">{{ number_format($foot->saldo1, 0) }}</td>
            <td style="background-color:#43beac;color:#ffffff;text-align:center;">{{ number_format($foot->saldo2, 0) }}</td>
        </tr>
    </tfoot>
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:85%;">' .
                round($monto, 1) .
                '%</span>';
        } elseif ($monto < 75) {
            return '<span class="badge badge-pill badge-warning" style="font-size:85%;background-color:#eb960d;">' .
                round($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:85%;">' .
                round($monto, 1) .
                '%</span>';
        }
    }
@endphp
