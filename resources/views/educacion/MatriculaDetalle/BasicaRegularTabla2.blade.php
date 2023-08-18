<table id="tabla0" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <td rowspan="2">UGEL</td>
            <td rowspan="2">TOTAL</td>
            <td rowspan="2">%</td>
            <th colspan="2">INICIAL</th>
            <th colspan="3">PRIMARIA</th>
            <th colspan="2">SECUNDARIA</th>
        </tr>
        <tr class="bg-primary text-white text-center">
            <th>CICLO I</th>
            <th>CICLO II</th>
            <th>CICLO III</th>
            <th>CICLO IV</th>
            <th>CICLO V</th>
            <th>CICLO VI</th>
            <th>CICLO VII</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $item)
            <tr class="text-center">
                <td class="text-left"><a href="#" class="">{{ $item->ugel }}</a></td>
                <th>{{ number_format($item->tt, 0) }}</th>
                <td>{{ number_format($item->ptt, 1) }}%</td>
                <td>{{ number_format($item->ICI, 0) }}</td>
                <td>{{ number_format($item->ICII, 0) }}</td>
                <td>{{ number_format($item->ICIII, 0) }}</td>
                <td>{{ number_format($item->ICIV, 0) }}</td>
                <td>{{ number_format($item->ICV, 0) }}</td>
                <td>{{ number_format($item->ICVI, 0) }}</td>
                <td>{{ number_format($item->ICVII, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot->tt, 0) }}</th>
            <td>{{ number_format($foot->ptt, 1) }}%</td>
            <th>{{ number_format($foot->ICI, 0) }}</th>
            <th>{{ number_format($foot->ICII, 0) }}</th>
            <th>{{ number_format($foot->ICIII, 0) }}</th>
            <th>{{ number_format($foot->ICIV, 0) }}</th>
            <th>{{ number_format($foot->ICV, 0) }}</th>
            <th>{{ number_format($foot->ICVI, 0) }}</th>
            <th>{{ number_format($foot->ICVII, 0) }}</th>
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
