<table id="tabla0201" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th class="text-center" rowspan="2">N°</th>
            <th class="text-center" rowspan="2">Centro poblado</th>
            <th class="text-center" colspan="3">Menores con DNI</th>
            <th class="text-center" colspan="3">Menores con Seguro</th>
            <th class="text-center" colspan="3">Menores con EESS</th>
            <th class="text-center" colspan="3">Menores Visitados</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th class="text-center">Num</th>
            <th class="text-center">Den</th>
            <th class="text-center">Indicador</th>
            <th class="text-center">Num</th>
            <th class="text-center">Den</th>
            <th class="text-center">Indicador</th>
            <th class="text-center">Num</th>
            <th class="text-center">Den</th>
            <th class="text-center">Indicador</th>
            <th class="text-center">Num</th>
            <th class="text-center">Den</th>
            <th class="text-center">Indicador</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-left">{{ $item->centro_poblado_nombre }}</td>
                <td>{{ $item->cdni }}</td>
                <td>{{ $item->total }}</td>
                <td>{!! avance($item->ii1) !!}</td>
                <td>{{ $item->cseguro }}</td>
                <td>{{ $item->total }}</td>
                <td>{!! avance($item->ii2) !!}</td>
                <td>{{ $item->ceess }}</td>
                <td>{{ $item->total }}</td>
                <td>{!! avance($item->ii3) !!}</td>
                <td>{{ $item->cvisita }}</td>
                <td>{{ $item->total }}</td>
                <td>{!! avance($item->ii4) !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@php
    function avance($monto)
    {
        if ($monto < 75) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px;">' .
                round($monto, 1) .
                '%</span>';
        } elseif ($monto < 95) {
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
