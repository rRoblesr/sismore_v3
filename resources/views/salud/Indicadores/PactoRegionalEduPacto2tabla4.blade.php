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
                    <td>{{ $item->local }}</td>
                    <td>{{ $item->modular }}</td>
                    <td class="text-left">{{ $item->iiee }}</td>
                    <td>{{ $item->modalidad }}</td>
                    <td>{{ $item->nivel }}</td>
                    <td>{{ $item->ugel }}</td>
                    <td>{{ $item->distrito }}</td>
                    <td>{{ $item->centropoblado }}</td>
                    <td>{{ $item->area }}</td>
                    {{-- <td>{{ $item->estado }}</td> --}}
                    <td>
                        {{-- @if ($item->estado == 'SANEADO')
                            <button type="button"
                                class="btn btn-xs btn-success-0 font-10">&nbsp;&nbsp;&nbsp;{{$item->estado}}&nbsp;&nbsp;&nbsp;</button>
                        @else
                            <button type="button" class="btn btn-xs btn-danger font-10">{{$item->estado}}</button>
                        @endif --}}

                        @switch($item->estadox)
                            @case(1)
                                <button type="button" class="btn btn-xs btn-success-0 font-8">{{ $item->estado }}</button>
                            @break

                            @case(2)
                                <button type="button" class="btn btn-xs btn-danger font-8">{{ $item->estado }}</button>
                            @break

                            @case(3)
                                <button type="button" class="btn btn-xs btn-secondary font-8">{{ $item->estado }}</button>
                            @break

                            @case(4)
                                <button type="button" class="btn btn-xs btn-warning font-8">{{ $item->estado }}</button>
                            @break

                            @default
                        @endswitch
                    </td>
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
