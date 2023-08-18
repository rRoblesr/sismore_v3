<table id="tablaEBR3_2" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <td rowspan="4"></td>
            <td rowspan="4">INSTITUCION EDUCATIVA</td>
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
        @foreach ($base as $key => $item2)
            <tr class="text-center">
                <th>{{ $key+1 }}</th>
                <td class="text-left"><a href="#" onclick="">{{ $item2->iiee }}</a></td>
                <th>{{ number_format($item2->tti, 0) }}</th>
                <td>{{ number_format($item2->ttih, 0) }}</td>
                <td>{{ number_format($item2->ttim, 0) }}</td>
                <td>{{ number_format($item2->ICI0H, 0) }}</td>
                <td>{{ number_format($item2->ICI0M, 0) }}</td>
                <td>{{ number_format($item2->ICI1H, 0) }}</td>
                <td>{{ number_format($item2->ICI1M, 0) }}</td>
                <td>{{ number_format($item2->ICI2H, 0) }}</td>
                <td>{{ number_format($item2->ICI2M, 0) }}</td>
                <td>{{ number_format($item2->ICII3H, 0) }}</td>
                <td>{{ number_format($item2->ICII3M, 0) }}</td>
                <td>{{ number_format($item2->ICII4H, 0) }}</td>
                <td>{{ number_format($item2->ICII4M, 0) }}</td>
                <td>{{ number_format($item2->ICII5H, 0) }}</td>
                <td>{{ number_format($item2->ICII5M, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th class="text-left"></th>
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
