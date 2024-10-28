<table id="tabla2" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" class="text-center">CODIGO MODULAR</th>
            <th rowspan="2" class="text-center">INSTITUCIÓN EDUCATIVA</th>
            <th rowspan="2" class="text-center">NIVEL</th>
            <th rowspan="2" class="text-center">ÁREA</th>
            <th colspan="3" class="text-center">TABLETAS</th>
            <th colspan="3" class="text-center">CARGADORES SOLARES</th>
        </tr>
        <tr class="table-success-0 text-white">
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
                <td class="text-center table-warning">{{ $item->tt }}</td>
                <td class="text-center">{{ number_format($item->ce, 0) }}</td>
                <td class="text-center">{{ number_format($item->cd, 0) }}</td>
                <td class="text-center table-warning">{{ $item->ct }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="table-success-0 text-white text-right">
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
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 2) . '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' . round($monto, 2) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 2) . '%</span>';
        }
    }
@endphp
