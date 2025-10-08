<table id="tabla4" class="table table-sm table-striped table-bordered font-11 m-0">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="1" class="text-center">Nº</th>
            <th rowspan="1" class="text-center">Còdigo Local</th>
            <th rowspan="1" class="text-center">Còdigo Modular</th>
            <th rowspan="1" class="text-center">Instituciòn Educativa</th>
            <th rowspan="1" class="text-center">Modalidad</th>
            <th rowspan="1" class="text-center">Nivel</th>
            <th rowspan="1" class="text-center">UGEL</th>
            <th rowspan="1" class="text-center">Distrito</th>
            <th rowspan="1" class="text-center">Centro Poblado</th>
            <th rowspan="1" class="text-center">Àrea</th>
            <th rowspan="1" class="text-center">EstadoSFL</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->clocal }}</td>
                    <td>{{ $item->cmodular }}</td>
                    <td class="text-left">{{ $item->iiee }}</td>
                    <td class="text-left">{{ $item->modalidad }}</td>
                    <td>{{ $item->nivel }}</td>
                    <td>{{ $item->ugel }}</td>
                    <td class="text-left">{{ $item->distrito }}</td>
                    <td class="text-left">{{ $item->centropoblado }}</td>
                    <td>{{ $item->area }}</td>
                    <td><x-estadosfl-badge :estado-id="$item->estadox" /></td>
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
