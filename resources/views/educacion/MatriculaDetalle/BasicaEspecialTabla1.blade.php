<table id="tabla0" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <td rowspan="3">UGEL</td>
            <td colspan="3">TOTAL</td>
            <th colspan="6">MATRICULA POR EDAD Y SEXO</th>
            <th colspan="12">MATRICULA POR GRADO Y SEXO</th>
        </tr>
        <tr class="bg-primary text-white text-center">
            <td rowspan="2">TOTAL</td>
            <td rowspan="2">HOMBRE</td>
            <td rowspan="2">MUJER</td>
            <td colspan="2">3</td>
            <td colspan="2">4</td>
            <td colspan="2">5</td>
            <td colspan="2">1°</td>
            <td colspan="2">2°</td>
            <td colspan="2">3°</td>
            <td colspan="2">4°</td>
            <td colspan="2">5°</td>
            <td colspan="2">6°</td>
        </tr>
        <tr class="bg-primary text-white text-center">
            <th>H</th>
            <th>M</th>
            <th>H</th>
            <th>M</th>
            <th>H</th>
            <th>M</th>
            <th>H</th>
            <th>M</th>
            <th>H</th>
            <th>M</th>
            <th>H</th>
            <th>M</th>
            <th>H</th>
            <th>M</th>
            <th>H</th>
            <th>M</th>
            <th>H</th>
            <th>M</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $item)
            <tr class="text-center">
                <td class="text-left"><a href="#" class="">{{ $item->ugel }}</a></td>
                <th>{{ number_format($item->tt, 0) }}</th>
                <th>{{ number_format($item->tth, 0) }}</th>
                <th>{{ number_format($item->ttm, 0) }}</th>
                <td>{{ number_format($item->ICII3H, 0) }}</td>
                <td>{{ number_format($item->ICII3M, 0) }}</td>
                <td>{{ number_format($item->ICII4H, 0) }}</td>
                <td>{{ number_format($item->ICII4M, 0) }}</td>
                <td>{{ number_format($item->ICII5H, 0) }}</td>
                <td>{{ number_format($item->ICII5M, 0) }}</td>
                <td>{{ number_format($item->ICIII1H, 0) }}</td>
                <td>{{ number_format($item->ICIII1M, 0) }}</td>
                <td>{{ number_format($item->ICIII2H, 0) }}</td>
                <td>{{ number_format($item->ICIII2M, 0) }}</td>
                <td>{{ number_format($item->ICIV3H, 0) }}</td>
                <td>{{ number_format($item->ICIV3M, 0) }}</td>
                <td>{{ number_format($item->ICIV4H, 0) }}</td>
                <td>{{ number_format($item->ICIV4M, 0) }}</td>
                <td>{{ number_format($item->ICV5H, 0) }}</td>
                <td>{{ number_format($item->ICV5M, 0) }}</td>
                <td>{{ number_format($item->ICV6H, 0) }}</td>
                <td>{{ number_format($item->ICV6M, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot->tt, 0) }}</th>
            <th>{{ number_format($foot->tth, 0) }}</th>
            <th>{{ number_format($foot->ttm, 0) }}</th>
            <td>{{ number_format($foot->ICII3H, 0) }}</td>
            <td>{{ number_format($foot->ICII3M, 0) }}</td>
            <td>{{ number_format($foot->ICII4H, 0) }}</td>
            <td>{{ number_format($foot->ICII4M, 0) }}</td>
            <td>{{ number_format($foot->ICII5H, 0) }}</td>
            <td>{{ number_format($foot->ICII5M, 0) }}</td>
            <td>{{ number_format($foot->ICIII1H, 0) }}</td>
            <td>{{ number_format($foot->ICIII1M, 0) }}</td>
            <td>{{ number_format($foot->ICIII2H, 0) }}</td>
            <td>{{ number_format($foot->ICIII2M, 0) }}</td>
            <td>{{ number_format($foot->ICIV3H, 0) }}</td>
            <td>{{ number_format($foot->ICIV3M, 0) }}</td>
            <td>{{ number_format($foot->ICIV4H, 0) }}</td>
            <td>{{ number_format($foot->ICIV4M, 0) }}</td>
            <td>{{ number_format($foot->ICV5H, 0) }}</td>
            <td>{{ number_format($foot->ICV5M, 0) }}</td>
            <td>{{ number_format($foot->ICV6H, 0) }}</td>
            <td>{{ number_format($foot->ICV6M, 0) }}</td>
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
