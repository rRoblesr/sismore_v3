<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-success-1 text-white text-center">
            <th>GENERICA_GASTO</th>
            <th>PIA</th>
            <th>PIM</th>
            <th>CERTIFICADO</th>
            <th>DEVENGADO</th>
            <th>% EJECUCIÓN</th>
            <th>SALDO CERT.</th>
            <th>SALDO DEV.</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($head as $item)
            <tr class="text-right font-weight-bold">
                <td class="text-left">{{ $item->categoria }}</td>
                <td>{{ number_format($item->pia, 0) }}</td>
                <td>{{ number_format($item->pim, 0) }}</td>
                <td>{{ number_format($item->cert, 0) }}</td>
                <td>{{ number_format($item->dev, 0) }}</td>
                <th class="text-center">{!! avance($item->eje) !!}</th>
                <td>{{ number_format($item->saldo1, 0) }}</td>
                <td>{{ number_format($item->saldo2, 0) }}</td>
            </tr>
            @foreach ($body as $item2)
                @if ($item->categoria == $item2->categoria)
                    <tr class="text-right">
                        <td class="text-left">
                            <a href="#anal1"
                                onclick="graficar({{ $item2->id }},'{{ '2.' . $item2->codigo . ' ' . $item2->generica }}')">{{ '2.' . $item2->codigo . ' ' . $item2->generica }}</a>
                        </td>
                        <td>{{ number_format($item2->pia, 0) }}</td>
                        <td>{{ number_format($item2->pim, 0) }}</td>
                        <td>{{ number_format($item2->cert, 0) }}</td>
                        <td>{{ number_format($item2->dev, 0) }}</td>
                        <th class="text-center">{!! avance($item2->eje) !!}</th>
                        <td>{{ number_format($item2->saldo1, 0) }}</td>
                        <td>{{ number_format($item2->saldo2, 0) }}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach

    </tbody>
    <tfoot>
        <tr class="text-center bg-success-1 text-white">
            <th class="text-left" colspan="1">TOTAL</th>
            <th>{{ number_format($foot['pia'], 0) }}</th>
            <th>{{ number_format($foot['pim'], 0) }}</th>
            <th>{{ number_format($foot['cert'], 0) }}</th>
            <th>{{ number_format($foot['dev'], 0) }}</th>
            <th class="text-center">{!! avance($foot['eje']) !!}</th>
            <th>{{ number_format($foot['saldo1'], 0) }}</th>
            <th>{{ number_format($foot['saldo2'], 0) }}</th>
        </tr>
    </tfoot>
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:85%;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 75) {
            return '<span class="badge badge-pill badge-warning" style="font-size:85%;background-color:#eb960d;">' . round($monto, 1) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:85%;">' . round($monto, 1) . '%</span>';
        }
    }
@endphp
