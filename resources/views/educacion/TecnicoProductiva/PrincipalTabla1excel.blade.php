<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:10px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" class="text-center">Nº</th>
            <th rowspan="2" class="text-center">Código<br>Modular</th>
            <th rowspan="2" class="text-center">Institución Educativa</th>
            <th rowspan="2" class="text-center">Distrito</th>
            <th rowspan="2" class="text-center">Gestión</th>
            <th rowspan="2" class="text-center">Área</th>
            <th rowspan="2" class="text-center">Meta</th>
            <th colspan="3" class="text-center">Estudiantes</th>
            <th rowspan="2" class="text-center">Indicador</th>
            <th colspan="3" class="text-center">Docentes</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th class="text-center">Hombres</th>
            <th class="text-center">Mujeres</th>
            <th class="text-center">Total</th>
            <th class="text-center">Contratados</th>
            <th class="text-center">Nombrados</th>
            <th class="text-center">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-right">
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-center">{{ $item->modular }}</td>
                <td class="text-left">{{ $item->iiee }}</td>
                <td class="text-left">{{ $item->distrito }}</td>
                <td class="text-center">{{ $item->gestion }}</td>
                <td class="text-left">{{ $item->area }}</td>
                <td class="text-center table-warning">{{ number_format($item->meta, 0) }}</td>
                <td class="text-center">{{ number_format($item->at, 0) }}</td>
                <td class="text-center">{{ number_format($item->t, 0) }}</td>
                <td class="text-center table-warning">{{ number_format($item->at + $item->t, 0) }}</td>
                <td class="text-center">{!! avance($item->indicador) !!}</td>
                <td class="text-center">{{ number_format($item->c, 0) }}</td>
                <td class="text-center">{{ number_format($item->n, 0) }}</td>
                <td class="text-center table-warning">{{ number_format($item->c + $item->n, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="table-success-0 text-white text-right">
            <th class="text-right" colspan="6">Total</th>
            <th class="text-center">{{ number_format($foot->meta, 0) }}</th>
            <th class="text-center">{{ number_format($foot->at, 0) }}</th>
            <th class="text-center">{{ number_format($foot->t, 0) }}</th>
            <th class="text-center">{{ number_format($foot->at + $foot->t, 0) }}</th>
            <th class="text-center">{!! avance($foot->indicador) !!}</th>
            <th class="text-center">{{ number_format($foot->c, 0) }}</th>
            <th class="text-center">{{ number_format($foot->n, 0) }}</th>
            <th class="text-center">{{ number_format($foot->c + $foot->n, 0) }}</th>
        </tr>
    </tfoot>
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' . round($monto, 2) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        }
    }
@endphp
