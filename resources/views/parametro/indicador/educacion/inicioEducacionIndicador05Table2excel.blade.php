<table id="tabla2" class="table table-bordered mb-0" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" style="text-align: center">UGEL</th>
            <th rowspan="2" style="text-align: center">Total<br>Docentes</th>
            <th rowspan="2" style="text-align: center">Docentes<br>Titulados</th>
            <th rowspan="2" style="text-align: center">Indicador</th>
            <th colspan="2" style="text-align: center">Docentes</th>
            <th colspan="2" style="text-align: center">Condición laboral</th>
            <th colspan="2" style="text-align: center">Tipo Gestión</th>
            <th colspan="2" style="text-align: center">Ámbito Geográfico</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th>Hombres</th>
            <th>Mujeres</th>
            <th>Nombrados</th>
            <th>Contratados</th>
            <th>Publica</th>
            <th>Privada</th>
            <th>Urbana</th>
            <th>Rural</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $item)
            <tr class="text-center">
                <td class="text-left">{{ $item->ugel }}</td>
                <td>{{ number_format($item->td, 0) }}</td>
                <td>{{ number_format($item->tt, 0) }}</td>
                <td>{!! avance($item->td > 0 ? (100 * $item->tt) / $item->td : 0, 1) !!}</td>
                <td>{{ number_format($item->tth, 0) }}</td>
                <td>{{ number_format($item->ttm, 0) }}</td>
                <td>{{ number_format($item->ttn, 0) }}</td>
                <td>{{ number_format($item->ttc, 0) }}</td>
                <td>{{ number_format($item->pub, 0) }}</td>
                <td>{{ number_format($item->pri, 0) }}</td>
                <td>{{ number_format($item->urb, 0) }}</td>
                <td>{{ number_format($item->rur, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    @if ($foot != null)
        <tfoot>
            <tr class="table-success-0 text-white">
                <td class="text-right">Total</td>
                <td class="text-center">{{ number_format($foot->td, 0) }}</td>
                <td class="text-center">{{ number_format($foot->tt, 0) }}</td>
                <td class="text-center">{!! avance($foot->td > 0 ? (100 * $foot->tt) / $foot->td : 0, 1) !!}</td>
                <td class="text-center">{{ number_format($foot->tth, 0) }}</td>
                <td class="text-center">{{ number_format($foot->ttm, 0) }}</td>
                <td class="text-center">{{ number_format($foot->ttn, 0) }}</td>
                <td class="text-center">{{ number_format($foot->ttc, 0) }}</td>
                <td class="text-center">{{ number_format($foot->pub, 0) }}</td>
                <td class="text-center">{{ number_format($foot->pri, 0) }}</td>
                <td class="text-center">{{ number_format($foot->urb, 0) }}</td>
                <td class="text-center">{{ number_format($foot->rur, 0) }}</td>
            </tr>
        </tfoot>
    @endif

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
