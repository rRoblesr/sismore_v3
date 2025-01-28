<table id="tabla2" class="table table-sm table-striped table-bordered font-12 m-0">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">N°</th>
            <th class="text-center">Código</th>
            <th class="text-center">Establecimiento de Salud</th>
            <th class="text-center">Provincia</th>
            <th class="text-center">Distrito</th>
            <th class="text-center">Denominador</th>
            <th class="text-center">Numerador</th>
            <th class="text-center">Indicador</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->codigo }}</td>
                    <td class="text-left">{{ $item->ipress }}</td>
                    <td>{{ $item->provincia }}</td>
                    <td>{{ $item->distrito }}</td>
                    <td>{{ $item->denominador }}</td>
                    <td>{{ $item->numerador }}</td>
                    <td>{!! avance($item->indicador) !!}</td>
                </tr>
            @endforeach
        </tbody>
    @else
        {{-- <tbody>
            <tr class="text-center">
                <td class="text-center" colspan="11"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody> --}}
    @endif
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px">' .
                round($monto, 1) .
                '%</span>';
        } elseif ($monto < 100) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%; width:50px">' .
                round($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px">' .
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
