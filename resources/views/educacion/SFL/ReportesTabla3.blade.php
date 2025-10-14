<table id="tabla3" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th class="text-center">N°</th>
            <th class="text-center">UGEL</th>
            <th class="text-center">Distrito</th>
            <th class="text-center">Centro Poblado</th>
            <th class="text-center">Ambito</th>
            <th class="text-center">Cód.Local</th>
            <th class="text-center">Cód.Modular</th>
            <th class="text-center">Institución Educativa</th>
            <th class="text-center">Nivel</th>
            <th class="text-center">Estado SFL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-left">{{ $item->ugel }}</td>
                <td class="text-left">{{ $item->distrito }}</td>
                <td class="text-left">{{ $item->centropoblado }}</td>
                <td>{{ $item->ambito }}</td>
                <td>{{ $item->clocal }}</td>
                <td>{{ $item->cmodular }}</td>
                <td class="text-left">{{ $item->iiee }}</td>
                <td class="text-left">{{ $item->nivel }}</td>
                <td><x-estadosfl-badge :estado-id="$item->estado"/></td>
            </tr>
        @endforeach
    </tbody>
</table>