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
                <tr class="text-center {{ $item->distrito == $ndis ? 'table-warning' : '' }}">
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
        @if (isset($foot))
            <tfoot>
                <tr class="text-center bg-success-0 text-white">
                    <th colspan="" class="text-right">{{ $foot->distrito }}</th>
                    <th class="text-center">{{ number_format($foot->conteo, 0) }}</th>
                    <th class="text-center">{{ number_format($foot->si, 0) }}</th>
                    <th class="text-center"><x-avance-badge :avance="$foot->indicador" /></th>
                    <th class="text-center">{{ number_format($foot->no, 0) }}</th>
                    <th class="text-center">{{ number_format($foot->pro, 0) }}</th>
                    <th class="text-center">{{ number_format($foot->sin, 0) }}</th>
                </tr>
            </tfoot>
        @endif
    @else
        <tbody>
            <tr class="text-center">
                <td class="text-center" colspan="7"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody>
    @endif
</table>
