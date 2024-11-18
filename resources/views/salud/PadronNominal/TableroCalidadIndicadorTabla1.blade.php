<table id="tabla1" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th class="text-center">N°</th>
            <th class="text-center">Distrito</th>
            <th class="text-center">Total Niños</th>
            <th class="text-center">Cumplen</th>
            <th class="text-center">No Cumplen</th>
            <th class="text-center">Indicador</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-left">{{ $item->distrito }}</td>
                <td>{{ number_format($item->total, 0) }}</td>
                <td>{{ number_format($item->cdni, 0) }}</td>
                <td>{{ number_format($item->sdni, 0) }}</td>
                <td>{!! avance($item->ii) !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px;">' .
                round($monto, 1) .
                '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%; width:50px;background-color:#eb960d;">' .
                round($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px;">' .
                round($monto, 1) .
                '%</span>';
        }
    }
@endphp
