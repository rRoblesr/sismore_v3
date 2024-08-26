<table id="tabla1" class="table table-sm table-striped table-bordered font-10">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="2" class="text-center">N°</th>
            <th rowspan="2" class="text-center">Departamento</th>
            <th colspan="3" class="text-center">Población</th>
            <th colspan="10" class="text-center">Años</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Total</th>
            <th class="text-center">Hombres</th>
            <th class="text-center">Mujeres</th>
            <th class="text-center">2021</th>
            <th class="text-center">2022</th>
            <th class="text-center">2023</th>
            <th class="text-center">2024</th>
            <th class="text-center">2025</th>
            <th class="text-center">2026</th>
            <th class="text-center">2027</th>
            <th class="text-center">2028</th>
            <th class="text-center">2029</th>
            <th class="text-center">2030</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->departamento }}</td>
                    <td class="text-center table-warning">{{ number_format($item->c2024t, 0) }}</td>
                    <td class="text-center">{{ number_format($item->c2024h, 0) }}</td>
                    <td class="text-center">{{ number_format($item->c2024m, 0) }}</td>
                    <td class="text-center {{$anio==2021?'table-warning':''}}">{{ number_format($item->c2021, 0) }}</td>
                    <td class="text-center {{$anio==2022?'table-warning':''}}">{{ number_format($item->c2022, 0) }}</td>
                    <td class="text-center {{$anio==2023?'table-warning':''}}">{{ number_format($item->c2023, 0) }}</td>
                    <td class="text-center {{$anio==2024?'table-warning':''}}">{{ number_format($item->c2024, 0) }}</td>
                    <td class="text-center {{$anio==2025?'table-warning':''}}">{{ number_format($item->c2025, 0) }}</td>
                    <td class="text-center {{$anio==2026?'table-warning':''}}">{{ number_format($item->c2026, 0) }}</td>
                    <td class="text-center {{$anio==2027?'table-warning':''}}">{{ number_format($item->c2027, 0) }}</td>
                    <td class="text-center {{$anio==2028?'table-warning':''}}">{{ number_format($item->c2028, 0) }}</td>
                    <td class="text-center {{$anio==2029?'table-warning':''}}">{{ number_format($item->c2029, 0) }}</td>
                    <td class="text-center {{$anio==2030?'table-warning':''}}">{{ number_format($item->c2030, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th class="text-left" colspan="2">TOTAL</th>
                <th class="text-center">{{ number_format($foot->c2024t, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2024h, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2024m, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2021, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2022, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2023, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2024, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2025, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2026, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2027, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2028, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2029, 0) }}</th>
                <th class="text-center">{{ number_format($foot->c2030, 0) }}</th>
            </tr>
        </tfoot>
    @else
        <tbody>
            <tr class="text-center">
                <td class="text-center" colspan="11"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody>
    @endif
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' .
                round($monto, 1) .
                '%</span>';
        } elseif ($monto < 100) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;">' .
                round($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' .
                round($monto, 1) .
                '%</span>';
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
