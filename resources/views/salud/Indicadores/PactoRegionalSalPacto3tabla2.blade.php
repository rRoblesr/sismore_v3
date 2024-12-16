<table id="tabla2" class="table table-sm table-striped table-bordered font-12 m-0">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Nº</th>
            <th class="text-center">Red</th>
            <th class="text-center">Microred</th>
            <th class="text-center">Establecimiento de Salud</th>
            <th class="text-center">Total Partos</th>
            <th class="text-center">Examen Auxiliar</th>
            <th class="text-center">Atencion Prenatal</th>
            <th class="text-center">Suplemento Hierro y AF</th>
            <th class="text-center">Paquete Completo</th>
            <th class="text-center">Indicador</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->red }}</td>
                    <td class="text-left">{{ $item->microred }}</td>
                    <td class="text-left">{{ $item->eess_parto }}</td>
                    <td>{{ $item->denominador }}</td>
                    <td>{{ $item->condicion1 }}</td>
                    <td>{{ $item->condicion2 }}</td>
                    <td>{{ $item->condicion3 }}</td>
                    <td>{{ $item->numerador }}</td>
                    <td>{!! avance($item->indicador) !!}</td>
                    {{-- <td>
                        @if ($item->cumple == 1)
                            <button type="button"
                                class="btn btn-xs btn-success-0 p-1 font-11 text-nowrap">&nbsp;&nbsp;&nbsp;Cumple&nbsp;&nbsp;&nbsp;</button>
                        @else
                            <button type="button" class="btn btn-xs btn-danger p-1 font-11 text-nowrap">No Cumple</button>
                        @endif
                    </td> --}}
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
                <td class="text-center" colspan="11"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody>
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
