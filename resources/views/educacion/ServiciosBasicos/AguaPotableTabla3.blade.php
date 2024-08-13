<table id="{{ $tablax }}" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th rowspan="2" class="text-center">Nº</th>
            <th rowspan="2" class="text-center">Código<br>Local</th>
            <th rowspan="2" class="text-center">Código<br>Modular</th>
            <th rowspan="2" class="text-center">Institución Educatica</th>
            <th rowspan="2" class="text-center">Distrito</th>
            {{-- <th rowspan="2" class="text-center">Modalidad</th> --}}
            <th rowspan="2" class="text-center">Nivel</th>
            <th rowspan="2" class="text-center">Gestión</th>
            <th rowspan="2" class="text-center">Área</th>
            <th colspan="5" class="text-center">Servicios Básicos</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Agua</th>
            <th class="text-center">Desague</th>
            <th class="text-center">Electricidad</th>
            <th class="text-center">Todos</th>
            <th class="text-center">Internet</th>
        </tr>

    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->local }}</td>
                    <td class="text-left">{{ $item->modular }}</td>
                    <td class="text-left">{{ $item->iiee }}</td>
                    <td class="text-center">{{ $item->distrito }}</td>
                    {{-- <td class="text-center">{{ $item->modalidad }}</td> --}}
                    <td class="text-center">{{ $item->nivel }}</td>
                    <td class="text-center">{{ $item->gestion == 3 ? 'Privada' : 'Pública' }}</td>
                    <td class="text-center">{{ $item->area }}</td>
                    <td class="text-center">{{ $item->agua }}</td>
                    <td class="text-center">{{ $item->desague }}</td>
                    <td class="text-center">{{ $item->luz }}</td>
                    <td class="text-center">{!! sino($item->servicios) !!}</td>
                    <td class="text-center">{{ $item->internet }}</td>
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
                <td class="text-center" colspan="13"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody>
    @endif
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;width:40px">' .
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

    function sino($valor)
    {
        if ($valor == 'NO') {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;width:30px">NO</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;width:30px">SI</span>';
        }
    }
@endphp
