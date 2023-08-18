<table id="tabla1rojos" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <th>#</th>
            <th>Codigo Modular</th>
            <th>Institución Educativa</th>
            <th>{{ $nfi }}</th>
            <th>{{ $nff }}</th>
            <th>Retirados / Nuevos Matriculados</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($base as $pos => $item)
            <tr>
                <td class="text-center">{{ $pos + 1 }}</td>
                <td class="text-center">{{ $item->modular }}</td>
                <td>{{ $item->iiee }}</td>
                <td class="text-center">{{ $item->cfi }}</td>
                <td class="text-center">{{ $item->cff }}</td>
                <td class="text-center">{!! bajas($item->ct) !!}</td>
            </tr>
        @endforeach

    </tbody>
    <tfoot>
        <tr class="bg-primary text-white text-center">
            <th colspan="3">Total</th>
            <th>{{ $foot['cfi'] }}</th>
            <th>{{ $foot['cff'] }}</th>
            <th>{!! bajas($foot['ct']) !!}</th>
        </tr>
    </tfoot>

</table>

@php
function avance($monto)
{
    if ($monto < 51) {
        return '<span class="badge badge-pill badge-danger" style="font-size:85%;">' . round($monto, 1) . '%</span>';
    } elseif ($monto < 100) {
        return '<span class="badge badge-pill badge-warning" style="font-size:85%;background-color:#eb960d;">' . round($monto, 1) . '%</span>';
    } else {
        return '<span class="badge badge-pill badge-success" style="font-size:85%;">' . round($monto, 1) . '%</span>';
    }
}
/* function bajas($anoF, $monto, $paint, $mes, $nivel, $ano)
{
    if (!$anoF) {
        if ($paint > 0) {
            if ($monto < 0) {
                return '<a href="javascript:void(0)" onclick="openrojos(' . $mes . ',' . $nivel . ',' . $ano . ')"><span class="badge badge-pill badge-danger" style="font-size:85%;">' . round($monto, 0) . '</span></a>';
            } else {
                return number_format($monto);
            }
        } else {
            return '';
        }
    } else {
        return '';
    }
} */
function bajas($monto)
{
    if ($monto < 0) {
        return '<span class="badge badge-pill badge-danger" style="font-size:85%;">' . round($monto, 0) . '</span>';
    } else {
        return number_format($monto);
    }
}
@endphp
