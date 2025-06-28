
<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <td rowspan="2" class="text-center">Provincia</td>
            <td rowspan="2" class="text-center">Meta</td>
            <td colspan="3" class="text-center">Estudiantes</td>
            <th rowspan="2" class="text-center">%Avance</th>
            <th colspan="2" class="text-center">Prite</th>
            <th colspan="2" class="text-center">Inicial</th>
            <th colspan="2" class="text-center">Primaria</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">Hombres</th>
            <th class="text-center">Mujeres</th>
            <th class="text-center">Total</th>
            <th class="text-center">Hombres</th>
            <th class="text-center">Mujeres</th>
            <th class="text-center">Hombres</th>
            <th class="text-center">Mujeres</th>
            <th class="text-center">Hombres</th>
            <th class="text-center">Mujeres</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr class="text-center">
                    <td class="text-left"><a href="javascript:void(0)"
                            onclick="cargarTablaDistritos('tabla2', {{ $item->idprovincia }})">{{ $item->provincia }}</a>
                    </td>
                    <th class="table-warning">{{ number_format($item->meta, 0) }}</th>
                    <td>{{ number_format($item->th, 0) }}</td>
                    <td>{{ number_format($item->tm, 0) }}</td>
                    <th class="table-warning">{{ number_format($item->tt, 0) }}</th>
                    <td>{!! avance($item->avance, 1) !!}</td>
                    <td>{{ number_format($item->thi, 0) }}</td>
                    <td>{{ number_format($item->tmi, 0) }}</td>
                    <td>{{ number_format($item->thp, 0) }}</td>
                    <td>{{ number_format($item->tmp, 0) }}</td>
                    <td>{{ number_format($item->ths, 0) }}</td>
                    <td>{{ number_format($item->tms, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th class="text-left">TOTAL</th>
                <th>{{ number_format($foot->meta, 0) }}</th>
                <th>{{ number_format($foot->th, 0) }}</th>
                <th>{{ number_format($foot->tm, 0) }}</th>
                <th>{{ number_format($foot->tt, 0) }}</th>
                <th>{!! avance($foot->avance, 1) !!}</th>
                <th>{{ number_format($foot->thi, 0) }}</th>
                <th>{{ number_format($foot->tmi, 0) }}</th>
                <th>{{ number_format($foot->thp, 0) }}</th>
                <th>{{ number_format($foot->tmp, 0) }}</th>
                <th>{{ number_format($foot->ths, 0) }}</th>
                <th>{{ number_format($foot->tms, 0) }}</th>
            </tr>
        </tfoot>
    @else
        <tboy>
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
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 100) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
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
