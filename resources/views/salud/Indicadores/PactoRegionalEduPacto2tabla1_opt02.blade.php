<table id="tabla1" class="table table-sm table-striped table-bordered font-11 m-0" style="height: 100%">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="1" class="text-center">Nº</th>
            <th rowspan="1" class="text-center">Distrito</th>
            <th colspan="1" class="text-center">Meta</th>
            <th rowspan="1" class="text-center">Denominador</th>
            <th rowspan="1" class="text-center">Numerador</th>
            <th colspan="1" class="text-center">Indicador</th>
            <th colspan="1" class="text-center">Cumple</th>
        </tr>

    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
            {{-- <tr class="text-center {{ $item->distrito == $ndis ? 'table-warning' : '' }}"> --}}
                <tr class="text-center {{ $distritos[$item->id] ?? '' }}">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->distrito }}</td>
                    <td>{{ $item->meta }}%</td>
                    <td>{{ $item->denominador }}</td>
                    <td>{{ $item->numerador }}</td>
                    <td><x-avance-badge :avance="$item->avance ?? 0" /></td>
                    <td><x-pulgar-badge :cumple="$item->cumple" /> </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th colspan="2" class="text-right">{{ $foot->distrito }}</th>
                <th class="text-right"></th>
                <th class="text-center">{{ number_format($foot->denominador, 0) }}</th>
                <th class="text-center">{{ number_format($foot->numerador, 0) }}</th>
                <td><x-avance-badge :avance="$foot->indicador ?? 0" /></td>
                 <th class="text-center"></th>
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
