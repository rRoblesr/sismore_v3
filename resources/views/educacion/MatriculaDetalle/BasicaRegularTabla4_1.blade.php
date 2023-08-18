<table id="tabla0" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <td rowspan="4">UGEL</td>
            <td colspan="3">TOTAL</td>
            <th colspan="12">MATRICULA POR GRADO Y SEXO</th>
        </tr>
        <tr class="bg-primary text-white text-center">
            <td rowspan="3">TOTAL</td>
            <td rowspan="3">HOMBRE</td>
            <td rowspan="3">MUJER</td>
            <td colspan="4">CICLO III</td>
            <td colspan="4">CICLO IV</td>
            <td colspan="4">CICLO V</td>
        </tr>
        <tr class="bg-primary text-white text-center">
            <th colspan="2">1°</th>
            <th colspan="2">2°</th>
            <th colspan="2">3°</th>
            <th colspan="2">4°</th>
            <th colspan="2">5°</th>
            <th colspan="2">6°</th>
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
        @foreach ($head as $item)
            <tr class="text-center table-warning">
                <td class="text-left"><a href="javascript:void(0)">{{ $item->ugel }}</a></td>
                <th>{{ number_format($item->ttp, 0) }}</th>
                <td>{{ number_format($item->ttph, 0) }}</td>
                <td>{{ number_format($item->ttpm, 0) }}</td>
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
            @foreach ($base as $item2)
                @if ($item->ugel == $item2->ugel)
                    <tr class="text-center">
                        <td class="text-left"><a href="#guiaEBR4_2" onclick="cargarvista4_2({{$item2->id}})">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $item2->distrito }}</a></td>
                        <th>{{ number_format($item2->ttp, 0) }}</th>
                        <td>{{ number_format($item2->ttph, 0) }}</td>
                        <td>{{ number_format($item2->ttpm, 0) }}</td>
                        <td>{{ number_format($item2->ICIII1H, 0) }}</td>
                        <td>{{ number_format($item2->ICIII1M, 0) }}</td>
                        <td>{{ number_format($item2->ICIII2H, 0) }}</td>
                        <td>{{ number_format($item2->ICIII2M, 0) }}</td>
                        <td>{{ number_format($item2->ICIV3H, 0) }}</td>
                        <td>{{ number_format($item2->ICIV3M, 0) }}</td>
                        <td>{{ number_format($item2->ICIV4H, 0) }}</td>
                        <td>{{ number_format($item2->ICIV4M, 0) }}</td>
                        <td>{{ number_format($item2->ICV5H, 0) }}</td>
                        <td>{{ number_format($item2->ICV5M, 0) }}</td>
                        <td>{{ number_format($item2->ICV6H, 0) }}</td>
                        <td>{{ number_format($item2->ICV6M, 0) }}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot->ttp, 0) }}</th>
            <th>{{ number_format($foot->ttph, 0) }}</th>
            <th>{{ number_format($foot->ttpm, 0) }}</th>
            <th>{{ number_format($foot->ICIII1H, 0) }}</th>
            <th>{{ number_format($foot->ICIII1M, 0) }}</th>
            <th>{{ number_format($foot->ICIII2H, 0) }}</th>
            <th>{{ number_format($foot->ICIII2M, 0) }}</th>
            <th>{{ number_format($foot->ICIV3H, 0) }}</th>
            <th>{{ number_format($foot->ICIV3M, 0) }}</th>
            <th>{{ number_format($foot->ICIV4H, 0) }}</th>
            <th>{{ number_format($foot->ICIV4M, 0) }}</th>
            <th>{{ number_format($foot->ICV5H, 0) }}</th>
            <th>{{ number_format($foot->ICV5M, 0) }}</th>
            <th>{{ number_format($foot->ICV6H, 0) }}</th>
            <th>{{ number_format($foot->ICV6M, 0) }}</th>
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
