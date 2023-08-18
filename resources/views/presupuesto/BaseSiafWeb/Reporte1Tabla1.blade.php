<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-success-1 text-white text-center">
            <th>#</th>
            <th>CÓDIGO</th>
            <th>UNIDADES EJECUTORAS</th>
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
        @foreach ($body as $pos => $item)
            <tr class="registros text-right" id="reg{{ $item->id }}">
                <td class="text-center">{{ $pos + 1 }}</td>
                <td class="text-center">{{ $item->codigo }}</td>
                <td class="text-left"><a href="#anal1" onclick="graficar({{ $item->id }},`{{ $item->ue }}`)">{{ $item->ue }}</a></td>
                <td>{{ number_format($item->pia, 0) }}</td>
                <td>{{ number_format($item->pim, 0) }}</td>
                <td>{{ number_format($item->cert, 0) }}</td>
                <td>{{ number_format($item->dev, 0) }}</td>
                <th class="text-center">{!! avance($item->eje) !!}</th>
                <td>{{ number_format($item->saldo1, 0) }}</td>
                <td>{{ number_format($item->saldo2, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-success-1 text-white">
            <th class="text-left" colspan="3">TOTAL</th>
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
