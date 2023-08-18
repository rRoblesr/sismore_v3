<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-success-1 text-white text-center">
            <th>SECTOR/PLIEGO</th>
            <th>PIA</th>
            <th>PIM</th>
            <th>%PIM</th>
            <th>RECAUDACION</th>
            <th>% AVANCE</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($head as $item)
            <tr class="text-right font-weight-bold table-primary">
                <td class="text-left">{{ $item->sector }}</td>
                <td>{{ number_format($item->pia, 0) }}</td>
                <td>{{ number_format($item->pim, 0) }}</td>
                <td>{{ number_format(0, 0) }}</td>
                <td>{{ number_format($item->recaudado, 0) }}</td>
                <td class="text-center">{!! avance($item->eje) !!} </td>
            </tr>
            @foreach ($body as $item2)
                @if ($item->sector == $item2->sector)
                    <tr class="text-right">
                        <td class="text-left"><a href="javascript:void(0);">&nbsp;&nbsp;&nbsp;{{ $item2->pliego }}</a>
                        </td>
                        <td>{{ number_format($item2->pia, 0) }}</td>
                        <td>{{ number_format($item2->pim, 0) }}</td>
                        <td>{{ number_format(0, 0) }}</td>
                        <td>{{ number_format($item2->recaudado, 0) }}</td>
                        <td class="text-center">{!! avance($item2->eje) !!} </td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-success-1 text-white">
            <td class="text-left">Total</td>
            <td>{{ number_format($foot->pia, 0) }}</td>
            <td>{{ number_format($foot->pim, 0) }}</td>
            <td>{{ number_format(0, 0) }}</td>
            <td>{{ number_format($foot->recaudado, 0) }}</td>
            <td class="text-center">{!! avance($foot->eje) !!} </td>
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
