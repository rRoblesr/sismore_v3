<table id="tabla2" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="table-secondary">
            <th rowspan="2" class="text-center">CODIGO MODULAR</th>
            <th rowspan="2" class="text-center">INSTITUCIÓN EDUCATIVA</th>
            <th rowspan="2" class="text-center">NIVEL</th>
            <th rowspan="2" class="text-center">ÁREA</th>
            <th colspan="3" class="text-center">TABLETAS</th>
            <th colspan="3" class="text-center">CARGADORES SOLARES</th>
        </tr>
        <tr class="table-secondary">
            <th class="text-center">ALUMNOS</th>
            <th class="text-center">DOCENTES</th>
            <th class="text-center">TOTAL</th>
            <th class="text-center">ALUMNOS</th>
            <th class="text-center">DOCENTES</th>
            <th class="text-center">TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $item)
            <tr class="text-right">
                <td class="text-center">{{ $item->modular }}</td>
                <td class="text-left">{{ $item->iiee }}</td>
                <td class="text-center">{{ $item->nivel }}</td>
                <td class="text-left">{{ $item->area }}</td>
                <td class="text-center">{{ number_format($item->te, 0) }}</td>
                <td class="text-center">{{ number_format($item->td, 0) }}</td>
                <td class="text-center table-warning">{{ number_format($item->tt, 0) }}</td>
                <td class="text-center">{{ number_format($item->ce, 0) }}</td>
                <td class="text-center">{{ number_format($item->cd, 0) }}</td>
                <td class="text-center table-warning">{{ number_format($item->ct, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="table-secondary text-right">
            <td class="text-left" colspan="4">TOTAL</td>
            <td class="text-center">{{ number_format($foot->te, 0) }}</td>
            <td class="text-center">{{ number_format($foot->td, 0) }}</td>
            <td class="text-center">{{ number_format($foot->tt, 0) }}</td>
            <td class="text-center">{{ number_format($foot->ce, 0) }}</td>
            <td class="text-center">{{ number_format($foot->cd, 0) }}</td>
            <td class="text-center">{{ number_format($foot->ct, 0) }}</td>
        </tr>
    </tfoot>
</table>



@php
    /* function avance($monto)
{
    if ($monto < 51) {
        return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    } elseif ($monto < 100) {
        return '<span class="badge badge-pill badge-warning" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    } else {
        return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    }
} */
@endphp
