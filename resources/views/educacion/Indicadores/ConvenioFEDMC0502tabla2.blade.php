<table id="tabla3" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            {{-- <th rowspan="2" class="text-center">Nº</th> --}}
            <th rowspan="2" class="text-center">Provincia</th>
            <th rowspan="2" class="text-center">Indicador</th>
            <th rowspan="1" colspan="3" class="text-center">Matriculados</th>
            <th rowspan="1" colspan="3" class="text-center">Homologados</th>
            <th rowspan="1" colspan="3" class="text-center">Área Urbana</th>
            <th rowspan="1" colspan="3" class="text-center">Área Rural</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Urbana</th>
            <th class="text-center">Rural</th>
            <th class="text-center">Total</th>

            <th class="text-center">Urbana</th>
            <th class="text-center">Rural</th>
            <th class="text-center">Total</th>

            <th class="text-center">Matriculados</th>
            <th class="text-center">Homologados</th>
            <th class="text-center">% Avance</th>

            <th class="text-center">Matriculados</th>
            <th class="text-center">Homologados</th>
            <th class="text-center">% Avance</th>
        </tr>

    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    {{-- <td>{{ $key + 1 }}</td> --}}
                    <td class="text-left">{{ $item->provincia }}</td>
                    <td>{{ $item->it }} %</td>
                    <td>{{ $item->mu }}</td>
                    <td>{{ $item->mr }}</td>
                    <td class="table-warning">{{ $item->mt }}</td>
                    <td>{{ $item->hu }}</td>
                    <td>{{ $item->hr }}</td>
                    <td class="table-warning">{{ $item->ht }}</td>
                    <td>{{ $item->um }}</td>
                    <td>{{ $item->uh }}</td>
                    <td>{{ $item->uit }} %</td>
                    <td>{{ $item->rm }}</td>
                    <td>{{ $item->rh }}</td>
                    <td>{{ $item->rit }} %</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th colspan="1" class="text-right">TOTAL</th>
                <th>{{ $foot->it }} %</th>
                <th>{{ $foot->mu }}</th>
                <th>{{ $foot->mr }}</th>
                <th>{{ $foot->mt }}</th>
                <th>{{ $foot->hu }}</th>
                <th>{{ $foot->hr }}</th>
                <th>{{ $foot->ht }}</th>
                <th>{{ $foot->um }}</th>
                <th>{{ $foot->uh }}</th>
                <th>{{ $foot->uit }} %</th>
                <th>{{ $foot->rm }}</th>
                <th>{{ $foot->rh }}</th>
                <th>{{ $foot->rit }} %</th>
            </tr>
        </tfoot>
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
