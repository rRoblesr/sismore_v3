<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <th>Código</th>
            <th>Categoría Presupuestal</th>
            <th>PIA</th>
            <th>PIM</th>
            <th>CERTIFICADO</th>
            <th>DEVENGADO</th>
            <th>% ejecución</th>
            <th>Saldo CERT.</th>
            <th>Saldo DEV.</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($body as $item)
            <tr class="text-right">
                <td>{{ $item->codigo }}</td>
                <td>{{ $item->categoria }}</td>
                <td>{{ $item->pia }}</td>
                <td>{{ $item->pim }}</td>
                <td>{{ $item->cert }}</td>
                <td>{{ $item->dev }}</td>
                <th>{!! avance($item->eje) !!}</th>
                <td>{{ $item->saldo1 }}</td>
                <td>{{ $item->saldo2 }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th colspan="2">TOTAL</th>
            <th>{{ $foot['pia'] }}</th>
            <th>{{ $foot['pim'] }}</th>
            <th>{{ $foot['cert'] }}</th>
            <th>{{ $foot['dev'] }}</th>
            <th>{!! avance($foot['eje']) !!}</th>
            <th>{{ $foot['saldo1'] }}</th>
            <th>{{ $foot['saldo2'] }}</th>
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
