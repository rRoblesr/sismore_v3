<table id="tabla0" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <td rowspan="4">UGEL</td>
            <td colspan="3">TOTAL</td>
            <th colspan="12">MATRICULA POR EDAD Y SEXO</th>
        </tr>
        <tr class="bg-primary text-white text-center">
            <td rowspan="3">TOTAL</td>
            <td rowspan="3">HOMBRE</td>
            <td rowspan="3">MUJER</td>
            <td colspan="6">CICLO I</td>
            <td colspan="6">CICLO II</td>
        </tr>
        <tr class="bg-primary text-white text-center">
            <th colspan="2">0</th>
            <th colspan="2">1</th>
            <th colspan="2">2</th>
            <th colspan="2">3</th>
            <th colspan="2">4</th>
            <th colspan="2">5</th>
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
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td class="text-left"><a href="#guiaEBR3_1" onclick="cargarvista3_1({{$item->id}});">{{ $item->ugel }}</a></td>
                <th>{{ number_format($item->tti, 0) }}</th>
                <td>{{ number_format($item->ttih, 0) }}</td>
                <td>{{ number_format($item->ttim, 0) }}</td>
                <td>{{ number_format($item->ICI0H, 0) }}</td>
                <td>{{ number_format($item->ICI0M, 0) }}</td>
                <td>{{ number_format($item->ICI1H, 0) }}</td>
                <td>{{ number_format($item->ICI1M, 0) }}</td>
                <td>{{ number_format($item->ICI2H, 0) }}</td>
                <td>{{ number_format($item->ICI2M, 0) }}</td>
                <td>{{ number_format($item->ICII3H, 0) }}</td>
                <td>{{ number_format($item->ICII3M, 0) }}</td>
                <td>{{ number_format($item->ICII4H, 0) }}</td>
                <td>{{ number_format($item->ICII4M, 0) }}</td>
                <td>{{ number_format($item->ICII5H, 0) }}</td>
                <td>{{ number_format($item->ICII5M, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot->tti, 0) }}</th>
            <th>{{ number_format($foot->ttih, 0) }}</th>
            <th>{{ number_format($foot->ttim, 0) }}</th>
            <th>{{ number_format($foot->ICI0H, 0) }}</th>
            <th>{{ number_format($foot->ICI0M, 0) }}</th>
            <th>{{ number_format($foot->ICI1H, 0) }}</th>
            <th>{{ number_format($foot->ICI1M, 0) }}</th>
            <th>{{ number_format($foot->ICI2H, 0) }}</th>
            <th>{{ number_format($foot->ICI2M, 0) }}</th>
            <th>{{ number_format($foot->ICII3H, 0) }}</th>
            <th>{{ number_format($foot->ICII3M, 0) }}</th>
            <th>{{ number_format($foot->ICII4H, 0) }}</th>
            <th>{{ number_format($foot->ICII4M, 0) }}</th>
            <th>{{ number_format($foot->ICII5H, 0) }}</th>
            <th>{{ number_format($foot->ICII5M, 0) }}</th>
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
