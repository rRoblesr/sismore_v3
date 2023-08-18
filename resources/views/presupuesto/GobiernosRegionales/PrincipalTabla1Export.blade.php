<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <th>Puesto</th>
            <th>Gobiernos Regionales</th>
            <th>PIA</th>
            <th>PIM</th>
            <th>CERTIFICADO</th>
            {{-- <th>COMPROMISO</th> --}}
            <th>DEVENGADO</th>
            <th>% Ejecución</th>
            <th>Saldo CERT.</th>
            <th>Saldo DEV.</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($body as $pos => $item)
            <tr>
                <td>{{ $pos + 1 }}</td>{{--  class="text-center" --}}
                <td>{{ $item->corto }}</td>
                <td>{{ $item->pia }}</td>
                <td>{{ $item->pim }}</td>
                <td>{{ $item->certificacion }}</td>
                {{-- <td>{{ $item->compromiso_anual }}</td> --}}
                <td>{{ $item->devengado }}</td>
                <td>{!! avance($item->eje) !!} </td>
                <td>{{ $item->saldo1 }}</td>
                <td>{{ $item->saldo2 }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="bg-primary text-white">
            <th colspan="2">TOTAL</th>
            <th>{{ $foot['pia'] }}</th>
            <th>{{ $foot['pim'] }}</th>
            <th>{{ $foot['certificacion'] }}</th>
            {{-- <th>{{ $foot['compromiso'] }}</th> --}}
            <th>{{ $foot['devengado'] }}</th>
            <th>{!! avance($foot['eje']) !!} %</th>
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
