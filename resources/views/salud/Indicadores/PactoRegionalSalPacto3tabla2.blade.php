<table id="tabla1" class="table table-sm table-striped table-bordered font-12 m-0">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="2" class="text-center">Nº</th>
            <th rowspan="2" class="text-center">Distrito</th>
            <th rowspan="1" colspan="2" class="text-center">Linea Base</th>
            <th rowspan="1" colspan="4" class="text-center">Logro Esperados</th>
            <th rowspan="1" colspan="4" class="text-center">Valores Obtenidos</th>
            <th rowspan="2" class="text-center">Avance<br>{{ $aniob }}</th>
            <th rowspan="2" class="text-center">Condición</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Año</th>
            <th class="text-center">Valor</th>

            <th class="text-center">2023</th>
            <th class="text-center">2024</th>
            <th class="text-center">2025</th>
            <th class="text-center">2026</th>

            <th class="text-center">2023</th>
            <th class="text-center">2024</th>
            <th class="text-center">2025</th>
            <th class="text-center">2026</th>
        </tr>

    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center {{ $item->dis == $ndis ? 'table-warning' : '' }}">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->dis }}</td>
                    <td>{{ $item->anio_base }}</td>
                    <td>{{ $item->valor_base }}</td>
                    <td>{{ $item->v2023 }}%</td>
                    <td>{{ $item->v2024 }}%</td>
                    <td>{{ esperado($item->v2025, 2025) }}</td>
                    <td>{{ esperado($item->v2026, 2026) }}</td>
                    <td>{{ obtenido($item->r2023, 2023) }}</td>
                    <td>{{ obtenido($item->r2024, 2024) }}</td>
                    <td>{{ obtenido($item->r2025, 2025) }}</td>
                    <td>{{ obtenido($item->r2026, 2026) }}</td>
                    <td>{!! avance($item->avance) !!}</td>{{--  --}}
                    <td>
                        @if ($item->cumple == 1)
                            <button type="button"
                                class="btn btn-xs btn-success-0 p-1 font-11 text-nowrap">&nbsp;&nbsp;&nbsp;Cumple&nbsp;&nbsp;&nbsp;</button>
                        @else
                            <button type="button" class="btn btn-xs btn-danger p-1 font-11 text-nowrap">No
                                Cumple</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        {{-- <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th colspan="2" class="text-right">TOTAL</th>
                <th class="text-center">{{ number_format($foot->total, 0) }}</th>
                <th class="text-center">{{ number_format($foot->con, 0) }}</th>
                <th class="text-center">{{ number_format($foot->sin, 0) }}</th>
                <th class="text-center">{!! avance($foot->indicador, 1) !!}</th>
            </tr>
        </tfoot> --}}
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
            return '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px">' .
                round($monto, 0) .
                '%</span>';
        } elseif ($monto < 100) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%; width:50px">' .
                round($monto, 0) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px">' .
                round($monto, 0) .
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
    function esperado($monto, $anio)
    {
        if ($anio <= date('Y')) {
            return number_format($monto, 0).'%';
        } else {
            return '-';
        }
    }

    function obtenido($monto, $anio)
    {
        if ($anio <= date('Y')) {
            return number_format($monto, 0).'%';
        } else {
            return '-';
        }
    }
@endphp
