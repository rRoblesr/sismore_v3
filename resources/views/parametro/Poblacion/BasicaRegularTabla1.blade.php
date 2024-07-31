<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <td rowspan="2" class="text-center">PROVINCIA</td>
            <td rowspan="2" class="text-center">META</td>
            <td colspan="3" class="text-center">ESTUDIANTES</td>
            <th rowspan="2" class="text-center">%AVANCE</th>
            <th colspan="2" class="text-center">INICIAL</th>
            <th colspan="3" class="text-center">PRIMARIA</th>
            <th colspan="2" class="text-center">SECUNDARIA</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">HOMBRES</th>
            <th class="text-center">MUJERES</th>
            <th class="text-center">TOTAL</th>
            <th class="text-center">CICLO I</th>
            <th class="text-center">CICLO II</th>
            <th class="text-center">CICLO III</th>
            <th class="text-center">CICLO IV</th>
            <th class="text-center">CICLO V</th>
            <th class="text-center">CICLO VI</th>
            <th class="text-center">CICLO VII</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr class="text-center">
                    <td class="text-left"><a href="javascript:void(0)"
                            onclick="cargarTablaDistritos(`tabla2`, {{ $item->idprovincia }})"
                            class="">{{ $item->provincia }}</a></td>
                    <th class="table-warning">{{ number_format($item->meta, 0) }}</th>
                    <td>{{ number_format($item->th, 0) }}</td>
                    <td>{{ number_format($item->tm, 0) }}</td>
                    <th class="table-warning">{{ number_format($item->tt, 0) }}</th>
                    <td>{!! avance($item->avance, 1) !!}</td>
                    <td>{{ number_format($item->ci, 0) }}</td>
                    <td>{{ number_format($item->cii, 0) }}</td>
                    <td>{{ number_format($item->ciii, 0) }}</td>
                    <td>{{ number_format($item->civ, 0) }}</td>
                    <td>{{ number_format($item->cv, 0) }}</td>
                    <td>{{ number_format($item->cvi, 0) }}</td>
                    <td>{{ number_format($item->cvii, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th class="text-left">TOTAL</th>
                <th class="text-center">{{ number_format($foot->meta, 0) }}</th>
                <th class="text-center">{{ number_format($foot->th, 0) }}</th>
                <th class="text-center">{{ number_format($foot->tm, 0) }}</th>
                <th class="text-center">{{ number_format($foot->tt, 0) }}</th>
                <th class="text-center">{!! avance($foot->avance, 1) !!}</th>
                <th class="text-center">{{ number_format($foot->ci, 0) }}</th>
                <th class="text-center">{{ number_format($foot->cii, 0) }}</th>
                <th class="text-center">{{ number_format($foot->ciii, 0) }}</th>
                <th class="text-center">{{ number_format($foot->civ, 0) }}</th>
                <th class="text-center">{{ number_format($foot->cv, 0) }}</th>
                <th class="text-center">{{ number_format($foot->cvi, 0) }}</th>
                <th class="text-center">{{ number_format($foot->cvii, 0) }}</th>
            </tr>
        </tfoot>
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
