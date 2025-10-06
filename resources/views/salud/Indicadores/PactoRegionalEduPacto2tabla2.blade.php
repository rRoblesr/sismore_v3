<table id="tabla2" class="table table-sm table-striped table-bordered font-11 m-0" style="height: 100%">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="1" class="text-center">Distrito</th>
            <th colspan="1" class="text-center">Locales Escolares</th>
            <th rowspan="1" class="text-center">Saneados</th>
            <th colspan="1" class="text-center">Indicador</th>
            <th colspan="1" class="text-center">No Saneado</th>
            <th colspan="1" class="text-center">Proceso de SFL</th>
            <th colspan="1" class="text-center">Sin Información</th>
        </tr>

    </thead>
    @if (count($base) > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td class="text-left">{{ $item->distrito }}</td>
                    <td>{{ $item->conteo }}</td>
                    <td>{{ $item->si }}</td>
                    <td><x-avance-badge :avance="$item->indicador" /></td>
                    <td>{{ $item->no }}</td>
                    <td>{{ $item->pro }}</td>
                    <td>{{ $item->sin }}</td>
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
    function bajas($monto)
    {
        if ($monto < 0) {
            return '<span class="badge badge-pill badge-danger" style="font-size:85%;">' . round($monto, 0) . '</span>';
        } else {
            return number_format($monto);
        }
    }
@endphp
