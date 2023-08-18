<table id="tabla0" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <td rowspan="2">UGEL</td>
            <td rowspan="2">TOTAL</td>
            <td rowspan="2">%</td>
            <th colspan="2">TOTAL</th>
            <th colspan="2">INICIAL</th>
            <th colspan="2">PRIMARIA</th>
            <th colspan="2">SECUNDARIA</th>
        </tr>
        <tr class="bg-primary text-white text-center">
            <th>HOMBRES</th>
            <th>MUJERES</th>
            <th>HOMBRES</th>
            <th>MUJERES</th>
            <th>HOMBRES</th>
            <th>MUJERES</th>
            <th>HOMBRES</th>
            <th>MUJERES</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $item)
            <tr class="text-center">
                <td class="text-left"><a href="#" class="">{{ $item->ugel }}</a></td>
                <th>{{ number_format($item->tt, 0) }}</th>
                <td>{{ number_format($item->ptt, 1) }}%</td>
                <td>{{ number_format($item->th, 0) }}</td>
                <td>{{ number_format($item->tm, 0) }}</td>
                <td>{{ number_format($item->ih, 0) }}</td>
                <td>{{ number_format($item->im, 0) }}</td>
                <td>{{ number_format($item->ph, 0) }}</td>
                <td>{{ number_format($item->pm, 0) }}</td>
                <td>{{ number_format($item->sh, 0) }}</td>
                <td>{{ number_format($item->sm, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot->tt, 0) }}</th>
            <td>{{ number_format($foot->ptt, 1) }}%</td>
            <th>{{ number_format($foot->th, 0) }}</th>
            <th>{{ number_format($foot->tm, 0) }}</th>
            <th>{{ number_format($foot->ih, 0) }}</th>
            <th>{{ number_format($foot->im, 0) }}</th>
            <th>{{ number_format($foot->ph, 0) }}</th>
            <th>{{ number_format($foot->pm, 0) }}</th>
            <th>{{ number_format($foot->sh, 0) }}</th>
            <th>{{ number_format($foot->sm, 0) }}</th>
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
