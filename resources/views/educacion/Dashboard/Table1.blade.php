<table id="tabla1" class="table table-bordered mb-0 tablex" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" style="text-align: center">UGEL</th>
            <th rowspan="2" style="text-align: center">Meta</th>
            <th colspan="3" style="text-align: center">Estudiantes</th>
            <th rowspan="2" style="text-align: center">%Avance</th>
            <th colspan="2" style="text-align: center">EBR</th>
            <th colspan="2" style="text-align: center">EBE</th>
            <th colspan="2" style="text-align: center">EBA</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th style="text-align: center">Hombres</th>
            <th style="text-align: center">Mujeres</th>
            <th style="text-align: center">Total</th>
            <th style="text-align: center">Hombres</th>
            <th style="text-align: center">Mujeres</th>
            <th style="text-align: center">Hombres</th>
            <th style="text-align: center">Mujeres</th>
            <th style="text-align: center">Hombres</th>
            <th style="text-align: center">Mujeres</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr class="text-center">
                    <td class="text-left">{{ $item->ugel }}</td>
                    <td>{{ number_format($item->meta, 0) }}</td>
                    <td>{{ number_format($item->th, 0) }}</td>
                    <td>{{ number_format($item->tm, 0) }}</td>
                    <td>{{ number_format($item->tt, 0) }}</td>
                    <td class="table-warning">{{ number_format($item->avance, 0) }}</td>
                    <td>{{ number_format($item->EBRth, 0) }}</td>
                    <td>{{ number_format($item->EBRtm, 0) }}</td>
                    <td>{{ number_format($item->EBEth, 0) }}</td>
                    <td>{{ number_format($item->EBEtm, 0) }}</td>
                    <td>{{ number_format($item->EBAth, 0) }}</td>
                    <td>{{ number_format($item->EBAtm, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-success-0 text-white">
                <th>Total</th>
                <th style="text-align: center">{{ number_format($foot->meta, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->th, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tm, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tt, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->avance, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->EBRth, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->EBRtm, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->EBEth, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->EBEtm, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->EBAth, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->EBAtm, 0) }}</th>
            </tr>
        </tfoot>
    @else
        <tr class="text-center">
            <td colspan="18"><a href="javascript:void(0)">Sin información</a></td>
        </tr>
        </tfoot>
    @endif
</table>

@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' . round($monto, 1) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        }
    }
@endphp
