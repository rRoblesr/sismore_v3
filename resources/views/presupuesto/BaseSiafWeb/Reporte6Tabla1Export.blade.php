<table id="tabla1">
    @foreach ($ue as $val)
        <thead>
            <tr>
                <th colspan="8" style="color:#000000;text-align:center;font-weight:bold;">UNIDAD EJECUTORA
                    {{ $val->codigo_ue_ant }}
                    -
                    {{ $val->nombre_ejecutora }}</th>
            </tr>
            <tr>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">GENERICA</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">PIA</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">PIM</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CERTIFICADO</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">DEVENGADO</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">EJECUCIÓN</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Saldo CERT.</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Saldo DEV.</th>
            </tr>

        </thead>
        <tbody>
            @foreach ($head[$val->id] as $item)
                <tr>
                    <td style="font-weight: bold;">{{ $item->categoria }}</td>
                    <td style="font-weight: bold;">{{ $item->pia }}</td>
                    <td style="font-weight: bold;">{{ $item->pim }}</td>
                    <td style="font-weight: bold;">{{ $item->cert }}</td>
                    <td style="font-weight: bold;">{{ $item->dev }}</td>
                    <td style="font-weight: bold;text-align:center">{!! avance($item->eje) !!}</td>
                    <td style="font-weight: bold;">{{ $item->saldo1 }}</td>
                    <td style="font-weight: bold;">{{ $item->saldo2 }}</td>
                </tr>
                @foreach ($body[$val->id] as $item2)
                    @if ($item->categoria == $item2->categoria)
                        <tr>
                            <td>{{ '2.' . $item2->codigo . ' ' . $item2->generica }}</td>
                            <td>{{ $item2->pia }}</td>
                            <td>{{ $item2->pim }}</td>
                            <td>{{ $item2->cert }}</td>
                            <td>{{ $item2->dev }}</td>
                            <td style="text-align:center">{!! avance($item2->eje) !!}</td>
                            <td>{{ $item2->saldo1 }}</td>
                            <td>{{ $item2->saldo2 }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="background-color:#317eeb;color:#ffffff" colspan="1">TOTAL</th>
                <th style="background-color:#317eeb;color:#ffffff">{{ $foot[$val->id]['pia'] }}</th>
                <th style="background-color:#317eeb;color:#ffffff">{{ $foot[$val->id]['pim'] }}</th>
                <th style="background-color:#317eeb;color:#ffffff">{{ $foot[$val->id]['cert'] }}</th>
                <th style="background-color:#317eeb;color:#ffffff">{{ $foot[$val->id]['dev'] }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;font-weight:bold">{!! avance($foot[$val->id]['eje']) !!}</th>
                <th style="background-color:#317eeb;color:#ffffff">{{ $foot[$val->id]['saldo1'] }}</th>
                <th style="background-color:#317eeb;color:#ffffff">{{ $foot[$val->id]['saldo2'] }}</th>
            </tr>
            <tr>
                <th colspan="8"></th>
            </tr>
        </tfoot>
    @endforeach
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
