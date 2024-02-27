<table id="tabla1" class="table table-bordered mb-0 tablex" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th style="text-align: center">UGEL</th>
            <th style="text-align: center">META</th>
            <th style="text-align: center">ENE</th>
            <th style="text-align: center">FEB</th>
            <th style="text-align: center">MAR</th>
            <th style="text-align: center">ABR</th>
            <th style="text-align: center">MAY</th>
            <th style="text-align: center">JUN</th>
            <th style="text-align: center">JUL</th>
            <th style="text-align: center">AGO</th>
            <th style="text-align: center">SET</th>
            <th style="text-align: center">OCT</th>
            <th style="text-align: center">NOV</th>
            <th style="text-align: center">DIC</th>
            <th style="text-align: center">TOTAL</th>
            <th style="text-align: center">AVANCE</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr class="text-center">
                    <td class="text-left"><a href="javascript:void(0)"
                            onclick="cargarTablaNivel('tabla2',{{ $item->idugel }})">{{ $item->ugel }}</a></td>
                    <th class="table-warning">{{ number_format($item->meta, 0) }}</th>
                    <td>{{ number_format($item->ene, 0) }}</td>
                    <td>{{ number_format($item->feb, 0) }}</td>
                    <td>{{ number_format($item->mar, 0) }}</td>
                    <td>{{ number_format($item->abr, 0) }}</td>
                    <td>{{ number_format($item->may, 0) }}</td>
                    <td>{{ number_format($item->jun, 0) }}</td>
                    <td>{{ number_format($item->jul, 0) }}</td>
                    <td>{{ number_format($item->ago, 0) }}</td>
                    <td>{{ number_format($item->sep, 0) }}</td>
                    <td>{{ number_format($item->oct, 0) }}</td>
                    <td>{{ number_format($item->nov, 0) }}</td>
                    <td>{{ number_format($item->dic, 0) }}</td>
                    <th class="table-warning">{{ number_format($item->total, 0) }}</th>
                    <td>{!! avance($item->avance) !!}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-success-0 text-white">
                <th>Total</th>
                <th style="text-align: center">{{ number_format($foot->meta, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->ene, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->feb, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->mar, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->abr, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->may, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->jun, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->jul, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->ago, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->set, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->oct, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->nov, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->dic, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->total, 0) }}</th>
                <th style="text-align: center">{!! avance($foot->avance) !!}</th>
            </tr>
        </tfoot>
    @else
        <tr class="text-center">
            <td colspan="18"><a href="javascript:void(0)">Sin información</a></td>
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
