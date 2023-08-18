<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-success-1 text-white text-center">
            <th></th>
            {{-- <th>CÓDIGO</th> --}}
            <th>UNIDADES ORGANICAS - SEDE CENTRAL</th>
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
        @foreach ($head as $pos => $item)
            <tr id="unidadorganica{{ $item->id }}" class="text-right">
                {{-- <tr class="accordion-toggle collapsed text-right font-weight-bold" id="accordion{{ $pos + 1 }}"
                data-mdb-toggle="collapse" data-mdb-parent="#accordion{{ $pos + 1 }}"
                href="#collapseOne{{ $pos + 1 }}" aria-controls="collapseOne{{ $pos + 1 }}"> --}}
                {{-- <td class="text-left">{{ $pos + 1 }}</td> --}}
                <td class="text-center">{{$pos+1}}</td>
                <td class="text-left">{{ $item->uo }}</td>
                <td>{{ number_format($item->pia, 0) }}</td>
                <td>{{ number_format($item->pim, 0) }}</td>
                <td>{{ number_format($item->cert, 0) }}</td>
                <td>{{ number_format($item->dev, 0) }}</td>
                <th class="text-center">{!! avance($item->eje) !!}</th>
                <td>{{ number_format($item->saldo1, 0) }}</td>
                <td>{{ number_format($item->saldo2, 0) }}</td>
            </tr>

            {{-- @foreach ($subhead as $pos2 => $item2)
                @if ($item2->uo == $item->uo)
                    <tr id="funcion{{ $item2->id }}" class="hide-table-padding text-right">
                        <td class="text-left"></td>
                        <td class="text-left">{{ $item2->funcion }}</td>
                        <td>{{ number_format($item2->pia, 0) }}</td>
                        <td>{{ number_format($item2->pim, 0) }}</td>
                        <td>{{ number_format($item2->cert, 0) }}</td>
                        <td>{{ number_format($item2->dev, 0) }}</td>
                        <th class="text-center">{!! avance($item2->eje) !!}</th>
                        <td>{{ number_format($item2->saldo1, 0) }}</td>
                        <td>{{ number_format($item2->saldo2, 0) }}</td>
                    </tr>

                    @foreach ($body as $pos3 => $item3)
                        @if ($item3->uo == $item->uo && $item3->funcion == $item2->funcion)
                            <tr id="especificadetalle{{ $item3->id }}" class="hide-table-padding text-right">
                                <td class="text-left"></td>
                                <td class="text-left">
                                    <a href="#anal1" onclick="#">{{ $item3->codigo }}
                                        {{ $item3->especificadetalle }}</a>
                                </td>
                                <td>{{ number_format($item3->pia, 0) }}</td>
                                <td>{{ number_format($item3->pim, 0) }}</td>
                                <td>{{ number_format($item3->cert, 0) }}</td>
                                <td>{{ number_format($item3->dev, 0) }}</td>
                                <th class="text-center">{!! avance($item3->eje) !!}</th>
                                <td>{{ number_format($item3->saldo1, 0) }}</td>
                                <td>{{ number_format($item3->saldo2, 0) }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach --}}
        @endforeach

    </tbody>
    <tfoot>
        <tr class="text-center bg-success-1 text-white">
            <th class="text-left" colspan="2">TOTAL</th>
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
