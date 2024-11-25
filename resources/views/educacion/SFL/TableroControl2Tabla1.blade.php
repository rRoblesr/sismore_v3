<table id="tabla1" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th class="text-center" rowspan="2">UGEL</th>
            <th class="text-center" colspan="3">instituciones Educativas</th>
            <th class="text-center" colspan="3">Locales Escolares</th>
            <th class="text-center" colspan="2">L.E Saneado</th>
            <th class="text-center" colspan="2">L.E no Saneado</th>
            <th class="text-center" colspan="2">L.E en Proceso</th>
            <th class="text-center" colspan="2">L.E no Registrado</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th class="text-center">Total</th>
            <th class="text-center">Rural</th>
            <th class="text-center">Urbano</th>
            <th class="text-center">Total</th>
            <th class="text-center">Rural</th>
            <th class="text-center">Urbano</th>
            <th class="text-center">Total</th>
            <th class="text-center">%</th>
            <th class="text-center">Total</th>
            <th class="text-center">%</th>
            <th class="text-center">Total</th>
            <th class="text-center">%</th>
            <th class="text-center">Total</th>
            <th class="text-center">%</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td class="text-left">{{ $item->ugel }}</td>
                <td class="table-warning">{{ number_format($item->ie, 0) }}</td>
                <td>{{ number_format($item->ier, 0) }}</td>
                <td>{{ number_format($item->ieu, 0) }}</td>
                <td class="table-warning">{{ number_format($item->le, 0) }}</td>
                <td>{{ number_format($item->ler, 0) }}</td>
                <td>{{ number_format($item->leu, 0) }}</td>
                <td>{{ number_format($item->e1, 0) }}</td>
                <td>{!! avance($item->e1p, 0) !!}</td>
                <td>{{ number_format($item->e2, 0) }}</td>
                <td>{!! avance($item->e2p, 0) !!}</td>
                <td>{{ number_format($item->e3, 0) }}</td>
                <td>{!! avance($item->e3p, 0) !!}</td>
                <td>{{ number_format($item->e4, 0) }}</td>
                <td>{!! avance($item->e4p, 0) !!}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center table-success-0 text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot->ie, 0) }}</th>
            <th>{{ number_format($foot->ier, 0) }}</th>
            <th>{{ number_format($foot->ieu, 0) }}</th>
            <th>{{ number_format($foot->le, 0) }}</th>
            <th>{{ number_format($foot->ler, 0) }}</th>
            <th>{{ number_format($foot->leu, 0) }}</th>
            <th>{{ number_format($foot->e1, 0) }}</th>
            <th>{!! avance($foot->e1p, 0) !!}</th>
            <th>{{ number_format($foot->e2, 0) }}</th>
            <th>{!! avance($foot->e2p, 0) !!}</th>
            <th>{{ number_format($foot->e3, 0) }}</th>
            <th>{!! avance($foot->e3p, 0) !!}</th>
            <th>{{ number_format($foot->e4, 0) }}</th>
            <th>{!! avance($foot->e4p, 0) !!}</th>
        </tr>
    </tfoot>
</table>

@php
    function avance($monto)
    {
        if ($monto < 50) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px;">' .
                number_format($monto, 1) .
                '%</span>';
        } elseif ($monto < 95) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%; width:50px;background-color:#eb960d;">' .
                number_format($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px;">' .
                number_format($monto, 1) .
                '%</span>';
        }
    }
@endphp
