<table id="tabla3" class="table table-striped table-bordered tablex" style="font-size:9px;">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <td rowspan="2">Nº</td>
            <td rowspan="2">Código<br>Modular</td>
            <td rowspan="2">Institución Educativa</td>
            <td rowspan="2">Nivel</td>
            <td rowspan="2">Gestión</td>
            <td rowspan="2">Área</td>
            <td rowspan="2">Meta</td>
            <th rowspan="1" colspan="3">Estudiantes</th>
            <th rowspan="2">%Avance</th>
            <th rowspan="1" colspan="4">Estudiantes por Grado</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Hombres</th>
            <th class="text-center">Mujeres</th>
            <th class="text-center">Total</th>

            <th class="text-center">Avanzado Primero</th>
            <th class="text-center">Avanzado Segundo</th>
            <th class="text-center">Avanzado Tercero</th>
            <th class="text-center">Avanzado Cuarto</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="text-center">{{ $item->codmod }}</td>
                    <td class="text-left">{{ $item->iiee }}</td>
                    <td class="text-left">{{ $item->nivel }}</td>
                    <td class="text-left">{{ $item->gestion }}</td>
                    <td class="text-left">{{ $item->area }}</td>
                    <td class="text-center table-warning">{{ $item->meta }}</td>
                    <td>{{ number_format($item->th, 0) }}</td>
                    <td>{{ number_format($item->tm, 0) }}</td>
                    <td>{{ number_format($item->tt, 0) }}</td>
                    <td class="table-warning">{!! avance($item->avance, 1) !!}</td>
                    <td>{{ number_format($item->e1, 0) }}</td>
                    <td>{{ number_format($item->e2, 0) }}</td>
                    <td>{{ number_format($item->e3, 0) }}</td>
                    <td>{{ number_format($item->e4, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th class="text-right" colspan="6">TOTAL</th>
                <th class="text-center">{{ number_format($foot->meta, 0) }}</th>
                <th class="text-center">{{ number_format($foot->th, 0) }}</th>
                <th class="text-center">{{ number_format($foot->tm, 0) }}</th>
                <th class="text-center">{{ number_format($foot->tt, 0) }}</th>
                <th class="text-center">{!! avance($foot->avance, 1) !!}</th>
                <th class="text-center">{{ number_format($foot->e1, 0) }}</th>
                <th class="text-center">{{ number_format($foot->e2, 0) }}</th>
                <th class="text-center">{{ number_format($foot->e3, 0) }}</th>
                <th class="text-center">{{ number_format($foot->e4, 0) }}</th>
            </tr>
        </tfoot>
    @else
        <tbody>
            <tr class="text-center">
                <td class="text-center" colspan="17"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody>
    @endif
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
