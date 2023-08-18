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
            <td colspan="4">CICLO VI</td>
            <td colspan="6">CICLO VII</td>
        </tr>
        <tr class="bg-primary text-white text-center">
            <th colspan="2">1°</th>
            <th colspan="2">2°</th>
            <th colspan="2">3°</th>
            <th colspan="2">4°</th>
            <th colspan="2">5°</th>
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
        </tr>
    </thead>
    <tbody>
        @foreach ($head as $item)
            <tr class="text-center table-warning">
                <td class="text-left"><a href="javascript:void(0)" class="">{{ $item->ugel }}</a></td>
                <th>{{ number_format($item->tts, 0) }}</th>
                <td>{{ number_format($item->ttsh, 0) }}</td>
                <td>{{ number_format($item->ttsm, 0) }}</td>
                <td>{{ number_format($item->ICVI1H, 0) }}</td>
                <td>{{ number_format($item->ICVI1M, 0) }}</td>
                <td>{{ number_format($item->ICVI2H, 0) }}</td>
                <td>{{ number_format($item->ICVI2M, 0) }}</td>
                <td>{{ number_format($item->ICVII3H, 0) }}</td>
                <td>{{ number_format($item->ICVII3M, 0) }}</td>
                <td>{{ number_format($item->ICVII4H, 0) }}</td>
                <td>{{ number_format($item->ICVII4M, 0) }}</td>
                <td>{{ number_format($item->ICVII5H, 0) }}</td>
                <td>{{ number_format($item->ICVII5M, 0) }}</td>
            </tr>
            @foreach ($base as $item2)
                @if ($item->ugel == $item2->ugel)
                    <tr class="text-center">
                        <td class="text-left"><a href="#guiaEBR5_2" onclick="cargarvista5_2({{$item2->id}})">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $item2->distrito }}</a></td>
                        <th>{{ number_format($item2->tts, 0) }}</th>
                        <td>{{ number_format($item2->ttsh, 0) }}</td>
                        <td>{{ number_format($item2->ttsm, 0) }}</td>
                        <td>{{ number_format($item2->ICVI1H, 0) }}</td>
                        <td>{{ number_format($item2->ICVI1M, 0) }}</td>
                        <td>{{ number_format($item2->ICVI2H, 0) }}</td>
                        <td>{{ number_format($item2->ICVI2M, 0) }}</td>
                        <td>{{ number_format($item2->ICVII3H, 0) }}</td>
                        <td>{{ number_format($item2->ICVII3M, 0) }}</td>
                        <td>{{ number_format($item2->ICVII4H, 0) }}</td>
                        <td>{{ number_format($item2->ICVII4M, 0) }}</td>
                        <td>{{ number_format($item2->ICVII5H, 0) }}</td>
                        <td>{{ number_format($item2->ICVII5M, 0) }}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot->tts, 0) }}</th>
            <th>{{ number_format($foot->ttsh, 0) }}</th>
            <th>{{ number_format($foot->ttsm, 0) }}</th>
            <th>{{ number_format($foot->ICVI1H, 0) }}</th>
            <th>{{ number_format($foot->ICVI1M, 0) }}</th>
            <th>{{ number_format($foot->ICVI2H, 0) }}</th>
            <th>{{ number_format($foot->ICVI2M, 0) }}</th>
            <th>{{ number_format($foot->ICVII3H, 0) }}</th>
            <th>{{ number_format($foot->ICVII3M, 0) }}</th>
            <th>{{ number_format($foot->ICVII4H, 0) }}</th>
            <th>{{ number_format($foot->ICVII4M, 0) }}</th>
            <th>{{ number_format($foot->ICVII5H, 0) }}</th>
            <th>{{ number_format($foot->ICVII5M, 0) }}</th>
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
