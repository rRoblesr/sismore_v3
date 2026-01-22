<table id="tabla3" class="table table-sm table-striped table-bordered font-12 m-0">
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
                {{-- <tr class="text-center {{ $item->dis == $ndis ? 'table-warning' : '' }}"> --}}
                <tr class="text-center {{ $distritos[$item->id] ?? '' }}">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->dis }}</td>
                    <td>{{ $item->anio_base }}</td>
                    <td>{{ $item->valor_base }}{{ $unidad == 1 ? '%' : '' }}</td>
                    <td><x-formatted-value :anio="2023" :value="$item->le2023" :unidad="$unidad" /></td>
                    <td><x-formatted-value :anio="2024" :value="$item->le2024" :unidad="$unidad" /></td>
                    <td><x-formatted-value :anio="2025" :value="$item->le2025" :unidad="$unidad" /></td>
                    <td><x-formatted-value :anio="2026" :value="$item->le2026" :unidad="$unidad" /></td>
                    <td><x-formatted-value :anio="2023" :value="$item->vo2023" :unidad="$unidad" /></td>
                    <td><x-formatted-value :anio="2024" :value="$item->vo2024" :unidad="$unidad" /></td>
                    <td><x-formatted-value :anio="2025" :value="$item->vo2025" :unidad="$unidad" /></td>
                    <td><x-formatted-value :anio="2026" :value="$item->vo2026" :unidad="$unidad" /></td>
                    <td><x-avance-badge :avance="$item->{'vo' . $aniob}" /></td>
                    <td><x-cumple-boton :cumple="$item->cumple" /> </td>
                </tr>
            @endforeach
        </tbody>
    @else
        <tbody>
            <tr class="text-center">
                <td class="text-center" colspan="11"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody>
    @endif
</table>
