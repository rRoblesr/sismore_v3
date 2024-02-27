<table id="tabla2" class="table table-striped table-bordered tablex" style="font-size:9px;">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <td rowspan="2" class="text-center">Nº</td>
            <td rowspan="2" class="text-center">Código Modular</td>
            <td rowspan="2" class="text-center">Institución Educativa</td>
            <td rowspan="2" class="text-center">Nivel</td>
            <td rowspan="2" class="text-center">Gestión</td>
            <td rowspan="2" class="text-center">Área</td>
            <td rowspan="2" class="text-center">Meta</td>
            <th rowspan="1" colspan="3" class="text-center">Estudiantes</th>
            <th rowspan="2" class="text-center">%Avance</th>
            <th rowspan="1" colspan="7" class="text-center">Estudiantes por Edad</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Hombres</th>
            <th class="text-center">Mujeres</th>
            <th class="text-center">Total</th>

            <th class="text-center">0 años</th>
            <th class="text-center">1 año</th>
            <th class="text-center">2 años</th>
            <th class="text-center">3 años</th>
            <th class="text-center">4 años</th>
            <th class="text-center">5 años</th>
            <th class="text-center">más de 5 años</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td class="text-left">{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->codmod }}</td>
                    <td class="text-left">{{ $item->iiee }}</td>
                    <td class="text-left">{{ $item->nivel }}</td>
                    <td class="text-left">{{ $item->gestion }}</td>
                    <td class="text-left">{{ $item->area }}</td>
                    <th class="text-center table-warning">{{ $item->meta }}</th>
                    <td>{{ number_format($item->th, 0) }}</td>
                    <td>{{ number_format($item->tm, 0) }}</td>
                    <th class="table-warning">{{ number_format($item->tt, 0) }}</th>
                    <td>{!! avance($item->avance, 1) !!}</td>
                    <td>{{ number_format($item->e0, 0) }}</td>
                    <td>{{ number_format($item->e1, 0) }}</td>
                    <td>{{ number_format($item->e2, 0) }}</td>
                    <td>{{ number_format($item->e3, 0) }}</td>
                    <td>{{ number_format($item->e4, 0) }}</td>
                    <td>{{ number_format($item->e5, 0) }}</td>
                    <td>{{ number_format($item->e6, 0) }}</td>
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
                <th class="text-center">{{ number_format($foot->e0, 0) }}</th>
                <th class="text-center">{{ number_format($foot->e1, 0) }}</th>
                <th class="text-center">{{ number_format($foot->e2, 0) }}</th>
                <th class="text-center">{{ number_format($foot->e3, 0) }}</th>
                <th class="text-center">{{ number_format($foot->e4, 0) }}</th>
                <th class="text-center">{{ number_format($foot->e5, 0) }}</th>
                <th class="text-center">{{ number_format($foot->e6, 0) }}</th>
            </tr>
        </tfoot>
    @else
        <tbody>
            <tr class="text-center">
                <td class="text-center" colspan="18"><a href="#" class="">Sin información</a></td>
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
