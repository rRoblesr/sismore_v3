<table id="tabla3" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            {{-- <th rowspan="2" class="text-center">Nº</th> --}}
            <th rowspan="2" class="text-center">Provincia</th>
            <th rowspan="2" class="text-center">Indicador</th>
            <th rowspan="1" colspan="3" class="text-center">Matriculados</th>
            <th rowspan="1" colspan="3" class="text-center">Homologados</th>
            <th rowspan="1" colspan="3" class="text-center">Área Urbana</th>
            <th rowspan="1" colspan="3" class="text-center">Área Rural</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Urbana</th>
            <th class="text-center">Rural</th>
            <th class="text-center">Total</th>

            <th class="text-center">Urbana</th>
            <th class="text-center">Rural</th>
            <th class="text-center">Total</th>

            <th class="text-center">Matriculados</th>
            <th class="text-center">Homologados</th>
            <th class="text-center">% Avance</th>

            <th class="text-center">Matriculados</th>
            <th class="text-center">Homologados</th>
            <th class="text-center">% Avance</th>
        </tr>

    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    {{-- <td>{{ $key + 1 }}</td> --}}
                    <td class="text-left">{{ $item->provincia }}</td>
                    <td>{!! avance($item->it) !!} </td>
                    <td>{{ number_format($item->mu, 0) }}</td>
                    <td>{{ number_format($item->mr, 0) }}</td>
                    <td class="table-warning">{{ number_format($item->mt, 0) }}</td>
                    <td>{{ number_format($item->hu, 0) }}</td>
                    <td>{{ number_format($item->hr, 0) }}</td>
                    <td class="table-warning">{{ number_format($item->ht, 0) }}</td>
                    <td>{{ number_format($item->um, 0) }}</td>
                    <td>{{ number_format($item->uh, 0) }}</td>
                    <td>{!! avance($item->uit) !!} </td>
                    <td>{{ number_format($item->rm, 0) }}</td>
                    <td>{{ number_format($item->rh, 0) }}</td>
                    <td>{!! avance($item->rit) !!} </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th colspan="1" class="text-right">TOTAL</th>
                <th>{!! avance($foot->it) !!} </th>
                <th>{{ number_format($foot->mu, 0) }}</th>
                <th>{{ number_format($foot->mr, 0) }}</th>
                <th>{{ number_format($foot->mt, 0) }}</th>
                <th>{{ number_format($foot->hu, 0) }}</th>
                <th>{{ number_format($foot->hr, 0) }}</th>
                <th>{{ number_format($foot->ht, 0) }}</th>
                <th>{{ number_format($foot->um, 0) }}</th>
                <th>{{ number_format($foot->uh, 0) }}</th>
                <th>{!! avance($foot->uit) !!}</th>
                <th>{{ number_format($foot->rm, 0) }}</th>
                <th>{{ number_format($foot->rh, 0) }}</th>
                <th>{!! avance($foot->rit) !!}</th>
            </tr>
        </tfoot>
    @else
        {{-- <tbody>
            <tr class="text-center">
                <td class="text-center" colspan="11"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody> --}}
    @endif
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px">' .
                round($monto, 1) .
                '%</span>';
        } elseif ($monto < 100) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%; width:50px">' .
                round($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px">' .
                round($monto, 1) .
                '%</span>';
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
