<table id="tabla3" class="table table-sm table-striped table-bordered font-12">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" class="text-center">N°</th>
            <th rowspan="2" class="text-center">Distritos</th>
            <th colspan="3" class="text-center">Población</th>
            <th colspan="6" class="text-center">Edades de Niños y Niñas menores de 6 años</th>
            <th colspan="3" class="text-center">Niños y Niñas tienen</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th class="text-center">Total</th>
            <th class="text-center">Hombres</th>
            <th class="text-center">Mujeres</th>
            <th class="text-center">{{ '<' }}1 A</th>
            <th class="text-center">1 A</th>
            <th class="text-center">2 A</th>
            <th class="text-center">3 A</th>
            <th class="text-center">4 A</th>
            <th class="text-center">5 A</th>
            <th class="text-center">DNI</th>
            <th class="text-center">Seguro</th>
            <th class="text-center">Programa</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-right">
                <td class="text-center">{{ $key + 1 }}</td>
                <td class="text-left">{{ $item->distrito }}</td>
                <td class="text-center table-warning">{{ $item->pob }}</td>
                <td class="text-center">{{ $item->pobm }}</td>
                <td class="text-center">{{ $item->pobf }}</td>
                <td class="text-center">{{ $item->pob0 }}</td>
                <td class="text-center">{{ $item->pob1 }}</td>
                <td class="text-center">{{ $item->pob2 }}</td>
                <td class="text-center">{{ $item->pob3 }}</td>
                <td class="text-center">{{ $item->pob4 }}</td>
                <td class="text-center">{{ $item->pob5 }}</td>
                <td class="text-center">{{ $item->dni }}</td>
                <td class="text-center">{{ $item->seguro }}</td>
                <td class="text-center">{{ $item->programa }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="table-success-0 text-white text-right">
            <th class="text-left" colspan="2">TOTAL</th>
            <th class="text-center">{{ $foot->pob }}</th>
            <th class="text-center">{{ $foot->pobm }}</th>
            <th class="text-center">{{ $foot->pobf }}</th>
            <th class="text-center">{{ $foot->pob0 }}</th>
            <th class="text-center">{{ $foot->pob1 }}</th>
            <th class="text-center">{{ $foot->pob2 }}</th>
            <th class="text-center">{{ $foot->pob3 }}</th>
            <th class="text-center">{{ $foot->pob4 }}</th>
            <th class="text-center">{{ $foot->pob5 }}</th>
            <th class="text-center">{{ $foot->dni }}</th>
            <th class="text-center">{{ $foot->seguro }}</th>
            <th class="text-center">{{ $foot->programa }}</th>
        </tr>
    </tfoot>
</table>


@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' .
                round($monto, 2) .
                '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' .
                round($monto, 2) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' .
                round($monto, 2) .
                '%</span>';
        }
    }
@endphp
