<table id="tabla0" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <td>UGEL</td>
            <td>TOTAL</td>
            <th>%</th>        
            <td>HOMBRE</td>
            <td>MUJER</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $item)
            <tr class="text-center">
                <td class="text-left"><a href="#" class="">{{ $item->ugel }}</a></td>
                <th>{{ number_format($item->tt, 0) }}</th>
                <th>{{ number_format($item->ptt, 1) }}%</th>
                <td>{{ number_format($item->tth, 0) }}</td>
                <td>{{ number_format($item->ttm, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot->tt, 0) }}</th>
            <th>{{ number_format($foot->ptt, 1) }}%</th>
            <th>{{ number_format($foot->tth, 0) }}</th>
            <th>{{ number_format($foot->ttm, 0) }}</th>
        </tr>
    </tfoot>
</table>



@php
function avance($monto)
{
    if ($monto < 51) {
        return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    } elseif ($monto < 100) {
        return '<span class="badge badge-pill badge-warning" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    } else {
        return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
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
