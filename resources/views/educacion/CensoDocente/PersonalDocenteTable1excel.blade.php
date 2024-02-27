<table id="tabla1" class="table table-sm table-bordered mb-0" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" style="text-align: center">N°</th>
            <th rowspan="2" style="text-align: center">UGEL</th>
            <th rowspan="2" style="text-align: center">TOTAL</th>
            <th rowspan="1" colspan="3" style="text-align: center">PÚBLICO</th>
            <th rowspan="1" colspan="3" style="text-align: center">PRIVADO</th>
            <th rowspan="1" colspan="3" style="text-align: center">URBANO</th>
            <th rowspan="1" colspan="3" style="text-align: center">RURAL</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th style="text-align: center">Nombrados</th>
            <th style="text-align: center">Contratados</th>
            <th style="text-align: center">Total</th>
            <th style="text-align: center">Nombrados</th>
            <th style="text-align: center">Contratados</th>
            <th style="text-align: center">Total</th>
            <th style="text-align: center">Nombrados</th>
            <th style="text-align: center">Contratados</th>
            <th style="text-align: center">Total</th>
            <th style="text-align: center">Nombrados</th>
            <th style="text-align: center">Contratados</th>
            <th style="text-align: center">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-left">{{ $item->ugel }}</td>
                <td>{{ $item->tt }}</td>
                <td>{{ $item->tpubn }}</td>
                <td>{{ $item->tpubc }}</td>
                <td>{{ $item->tpubn + $item->tpubc }}</td>
                <td>{{ $item->tprin }}</td>
                <td>{{ $item->tpric }}</td>
                <td>{{ $item->tprin + $item->tpric }}</td>
                <td>{{ $item->turbn }}</td>
                <td>{{ $item->turbc }}</td>
                <td>{{ $item->turbn + $item->turbc }}</td>
                <td>{{ $item->trurn }}</td>
                <td>{{ $item->trurc }}</td>
                <td>{{ $item->trurn + $item->trurc }}</td>
            </tr>
        @endforeach
    </tbody>
    @if ($foot != null)
        <tfoot>
            <tr class="table-success-0 text-white text-center">
                <th colspan="2" style="text-align: right">Total</th>
                <th style="text-align: center">{{ number_format($foot->tt, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tpubn, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tpubc, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tpubn + $foot->tpubc, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tprin, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tpric, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tprin + $foot->tpric, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->turbn, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->turbc, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->turbn + $foot->turbc, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->trurn, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->trurc, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->trurn + $foot->trurc, 0) }}</th>
            </tr>
        </tfoot>
    @endif

</table>


@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' . round($monto, 1) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        }
    }
@endphp
