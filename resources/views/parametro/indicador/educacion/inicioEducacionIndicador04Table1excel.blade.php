<table id="tabla1" class="table table-bordered mb-0 tablex" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" style="text-align: center">CÓDIGO MODULAR</th>
            <th rowspan="2" style="text-align: center">Institución Educativa</th>
            <th rowspan="2" style="text-align: center">Área</th>
            <th rowspan="2" style="text-align: center">Total Docentes</th>
            <th rowspan="2" style="text-align: center">Docentes Títulados</th>
            <th rowspan="2" style="text-align: center">Indicador</th>
            <th colspan="3" style="text-align: center">Docentes Nombrados con Título</th>
            <th colspan="3" style="text-align: center">Docentes Contratados con Título</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th>Hombres</th>
            <th>Mujeres</th>
            <th>Total</th>
            <th>Hombres</th>
            <th>Mujeres</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $item)
            <tr class="text-center">
                <td>{{ $item->modular }}</td>
                <td class="text-left">{{ $item->iiee }}</td>
                <td>{{ $item->area }}</td>
                <td>{{ $item->total }}</td>
                <td>{{ number_format($item->d01 + $item->d02 + $item->d03 + $item->d04, 0) }}</td>
                <td>{!! avance($item->total > 0 ? (100 * ($item->d01 + $item->d02 + $item->d03 + $item->d04)) / $item->total : 0, 1) !!}</td>
                <td>{{ number_format($item->d01, 0) }}</td>
                <td>{{ number_format($item->d02, 0) }}</td>
                <td class="table-warning">{{ number_format($item->d01 + $item->d02, 0) }}</td>
                <td>{{ number_format($item->d03, 0) }}</td>
                <td>{{ number_format($item->d04, 0) }}</td>
                <td class="table-warning">{{ number_format($item->d03 + $item->d04, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="table-success-0 text-white">
            <th colspan="3">Total</th>
            <th style="text-align: center">{{ number_format($foot->total, 0) }}</th>
            <th style="text-align: center">{{ number_format($foot->d01 + $foot->d02 + $foot->d03 + $foot->d04, 0) }}
            </th>
            <th style="text-align: center">{!! avance($foot->total > 0 ? (100 * ($foot->d01 + $foot->d02 + $foot->d03 + $foot->d04)) / $foot->total : 0, 1) !!}</th>
            <th style="text-align: center">{{ number_format($foot->d01, 0) }}</th>
            <th style="text-align: center">{{ number_format($foot->d02, 0) }}</th>
            <th style="text-align: center">{{ number_format($foot->d01 + $foot->d02, 0) }}</th>
            <th style="text-align: center">{{ number_format($foot->d03, 0) }}</th>
            <th style="text-align: center">{{ number_format($foot->d04, 0) }}</th>
            <th style="text-align: center">{{ number_format($foot->d03 + $foot->d04, 0) }}</th>
        </tr>
    </tfoot>
</table>


@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' . round($monto, 1) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        }
    }
@endphp
