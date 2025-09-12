<table id="tabla3" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" class="text-center">N°</th>
            <th rowspan="2" class="text-center">Establecimiento de Salud</th>
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
                <td class="text-left"><a href="javascript:void(0)"
                        onclick="abrirmodalcentropoblado('{{ $item->id }}')">{{ $item->codigo }} | {{ $item->nombre }}</a></td>
                <td class="text-center table-warning">{{ number_format($item->pob, 0) }}</td>
                <td class="text-center">{{ number_format($item->pobm, 0) }}</td>
                <td class="text-center">{{ number_format($item->pobf, 0) }}</td>
                <td class="text-center">{{ number_format($item->pob0, 0) }}</td>
                <td class="text-center">{{ number_format($item->pob1, 0) }}</td>
                <td class="text-center">{{ number_format($item->pob2, 0) }}</td>
                <td class="text-center">{{ number_format($item->pob3, 0) }}</td>
                <td class="text-center">{{ number_format($item->pob4, 0) }}</td>
                <td class="text-center">{{ number_format($item->pob5, 0) }}</td>
                <td class="text-center">{{ number_format($item->dni, 0) }}</td>
                <td class="text-center">{{ number_format($item->seguro, 0) }}</td>
                <td class="text-center">{{ number_format($item->programa, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="table-success-0 text-white text-right">
            <th class="text-left" colspan="2">TOTAL</th>
            <th class="text-center">{{ number_format($foot->pob, 0) }}</th>
            <th class="text-center">{{ number_format($foot->pobm, 0) }}</th>
            <th class="text-center">{{ number_format($foot->pobf, 0) }}</th>
            <th class="text-center">{{ number_format($foot->pob0, 0) }}</th>
            <th class="text-center">{{ number_format($foot->pob1, 0) }}</th>
            <th class="text-center">{{ number_format($foot->pob2, 0) }}</th>
            <th class="text-center">{{ number_format($foot->pob3, 0) }}</th>
            <th class="text-center">{{ number_format($foot->pob4, 0) }}</th>
            <th class="text-center">{{ number_format($foot->pob5, 0) }}</th>
            <th class="text-center">{{ number_format($foot->dni, 0) }}</th>
            <th class="text-center">{{ number_format($foot->seguro, 0) }}</th>
            <th class="text-center">{{ number_format($foot->programa, 0) }}</th>
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
