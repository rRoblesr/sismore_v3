<table id="tabla3" class="table table-sm table-bordered mb-0" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" style="text-align: center">N°</th>
            <th rowspan="2" style="text-align: center">Código<br>Modular</th>
            <th rowspan="2" style="text-align: center">Institución Educativa</th>
            <th rowspan="2" style="text-align: center">Distrito</th>
            <th rowspan="2" style="text-align: center">Modalidad</th>
            <th rowspan="2" style="text-align: center">Nivel</th>
            <th rowspan="2" style="text-align: center">Gestión</th>
            <th rowspan="2" style="text-align: center">Área</th>
            <th rowspan="2" style="text-align: center">Total</th>
            <th rowspan="1" colspan="2" style="text-align: center">Docentes</th>
            <th rowspan="1" colspan="2" style="text-align: center">Condición Laboral</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th style="text-align: center">Hombres</th>
            <th style="text-align: center">Mujeres</th>
            <th style="text-align: center">Nombrados</th>
            <th style="text-align: center">Contratados</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-center">{{ $item->modular }}</td>
                <td class="text-left">{{ $item->iiee }}</td>
                <td class="text-center">{{ $item->distrito }}</td>
                <td class="text-center">{{ $item->modalidad }}</td>
                <td class="text-center">{{ $item->nivel }}</td>
                <td class="text-center">{{ $item->gestion }}</td>
                <td class="text-center">{{ $item->area }}</td>
                <td>{{ $item->tt }}</td>
                <td>{{ $item->tth }}</td>
                <td>{{ $item->ttm }}</td>
                <td>{{ $item->ttn }}</td>
                <td>{{ $item->ttc }}</td>
            </tr>
        @endforeach
    </tbody>
    @if ($foot != null)
        <tfoot>
            <tr class="table-success-0 text-white text-center">
                <th colspan="8" style="text-align: right">Total</th>
                <th style="text-align: center">{{ number_format($foot->tt, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tth, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->ttm, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->ttn, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->ttc, 0) }}</th>
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
