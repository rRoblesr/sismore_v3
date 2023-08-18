<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-success-1 text-white text-center">
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
            <tr class="text-right {{ $item->corto == 'GOBIERNO REGIONAL DE UCAYALI' ? 'table-warning' : '' }}">
                <td class="text-center">{{ $pos + 1 }}</td>
                <td class="text-left"><a href="javascript:alert('En Creación')">{{ $item->corto }}</a></td>
                <td>{{ number_format($item->pia, 0) }}</td>
                <td>{{ number_format($item->pim, 0) }}</td>
                <td>{{ number_format($item->certificacion, 0) }}</td>
                {{-- <td>{{ number_format($item->compromiso_anual, 0) }}</td> --}}
                <td>{{ number_format($item->devengado, 0) }}</td>
                <td class="text-center">{!! avance($item->eje) !!} </td>
                <td>{{ number_format($item->saldo1, 0) }}</td>
                <td>{{ number_format($item->saldo2, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-right bg-success-1 text-white">
            <th class="text-left" colspan="2">TOTAL</th>
            <th>{{ number_format($foot['pia'], 0) }}</th>
            <th>{{ number_format($foot['pim'], 0) }}</th>
            <th>{{ number_format($foot['certificacion'], 0) }}</th>
            {{-- <th>{{ number_format($foot['compromiso'], 0) }}</th> --}}
            <th>{{ number_format($foot['devengado'], 0) }}</th>
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
            return '<span class="badge badge-pill badge-danger" style="font-size:85%;">' . round($monto, 1) . ' %</span>';
        } elseif ($monto < 75) {
            return '<span class="badge badge-pill badge-warning" style="font-size:85%;background-color:#eb960d;">' . round($monto, 1) . ' %</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:85%;">' . round($monto, 1) . ' %</span>';
        }
    }
@endphp
