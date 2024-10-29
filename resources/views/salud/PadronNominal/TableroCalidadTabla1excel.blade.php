<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2">N°</th>
            <th colspan="5">Registro con Observaciones</th>
            <th colspan="5">Total</th>
            <th colspan="5">Edades de Niños y Niñas menores a 6 años</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th>{{ '<' }} 1 A</th>
            <th>1 A</th>
            <th>2 A</th>
            <th>3 A</th>
            <th>4 A</th>
            <th>5 A</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-left">{{ $item->criterio }}</td>
                <td>{{ number_format($item->t1, 0) }}</td>
                <td>{{ number_format($item->t2, 0) }}</td>
                <td class="table-warning text-center">{!! avance($item->t3) !!}</td>
                <td>{{ number_format($item->t4, 0) }}</td>
                <td>{{ number_format($item->t5, 0) }}</td>
                <td>{{ number_format($item->c1, 0) }}</td>
                <td>{{ number_format($item->c2, 0) }}</td>
                <td class="table-warning text-center">{!! avance($item->c3) !!}</td>
                <td>{{ number_format($item->c4, 0) }}</td>
                <td>{{ number_format($item->c5, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="table-success-0 text-white text-center">
            <td class="text-left">TOTAL</td>
            <td>{{ number_format($foot->t1, 0) }}</td>
            <td>{{ number_format($foot->t2, 0) }}</td>
            <td class="text-center">{!! avance($item->t3) !!}</td>
            <td>{{ number_format($foot->t4, 0) }}</td>
            <td>{{ number_format($foot->t5, 0) }}</td>
            <td>{{ number_format($foot->c1, 0) }}</td>
            <td>{{ number_format($foot->c2, 0) }}</td>
            <td class="text-center">{!! avance($item->c3) !!}</td>
            <td>{{ number_format($foot->c4, 0) }}</td>
            <td>{{ number_format($foot->c5, 0) }}</td>
        </tr>
    </tfoot>
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' .
                round($monto, 2) .
                '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' .
                round($monto, 2) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' .
                round($monto, 2) .
                '%</span>';
        }
    }
@endphp
