<table id="tabla1" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th class="text-center" rowspan="2">UGEL</th>
            <th class="text-center" colspan="2">INSTITUCIONES EDUCATIVAS</th>
            <th class="text-center" colspan="2">LOCALES ESCOLARES</th>
            <th class="text-center" colspan="2">L.E SANEADOS</th>
            <th class="text-center" colspan="2">L.E NO SANEADOS</th>
            <th class="text-center" colspan="2">L.E EN PROCESO</th>
            <th class="text-center" colspan="2">L.E NO REGISTRADO</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th class="text-center">TOTAL</th>
            <th class="text-center">%</th>
            <th class="text-center">TOTAL</th>
            <th class="text-center">%</th>
            <th class="text-center">TOTAL</th>
            <th class="text-center">%</th>
            <th class="text-center">TOTAL</th>
            <th class="text-center">%</th>
            <th class="text-center">TOTAL</th>
            <th class="text-center">%</th>
            <th class="text-center">TOTAL</th>
            <th class="text-center">%</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td class="text-left">{{ $item->ugel }}</td>
                <td>{{ number_format($item->ie, 0) }}</td>
                <td>{!! avance($item->iep, 0) !!}</td>
                <td>{{ number_format($item->le, 0) }}</td>
                <td>{!! avance($item->lep, 0) !!}</td>
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
            <th>{!! avance(100, 0) !!}</th>
            <th>{{ number_format($foot->le, 0) }}</th>
            <th>{!! avance(100, 0) !!}</th>
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
        if ($monto < 75) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px;">' .
                round($monto, 1) .
                '%</span>';
        } elseif ($monto < 95) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%; width:50px;background-color:#eb960d;">' .
                round($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px;">' .
                round($monto, 1) .
                '%</span>';
        }
    }
@endphp
