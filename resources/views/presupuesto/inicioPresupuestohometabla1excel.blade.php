<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <th>TIPO DE GOBIERNO</th>
            <th colspan="3">GOBIERNO NACIONAL</th>
            <th colspan="3">GOBIERNOS LOCALES</th>
            <th colspan="3">GOBIERNOS REGIONALES</th>
            <th colspan="3">TOTAL</th>
        </tr>
        <tr class="bg-primary text-white text-center">
            <th>AÑO</th>
            <th>PIM</th>
            <th>EJECUTADO</th>
            <th>NO EJECUTADO</th>
            <th>PIM</th>
            <th>EJECUTADO</th>
            <th>NO EJECUTADO</th>
            <th>PIM</th>
            <th>EJECUTADO</th>
            <th>NO EJECUTADO</th>
            <th>PIM</th>
            <th>EJECUTADO</th>
            <th>NO EJECUTADO</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($body as $item)
            <tr class="text-right">
                <th class="text-center">{{ $item->ano }}</th>
                <td>{{ $item->gnp }}</td>
                <td>{{ $item->gnd }}</td>
                <th>{{ $item->gnne }}</th>
                <td>{{ $item->glp }}</td>
                <td>{{ $item->gld }}</td>
                <th>{{ $item->glne }}</th>
                <td>{{ $item->grp }}</td>
                <td>{{ $item->grd }}</td>
                <th>{{ $item->grne }}</th>
                <td>{{ $item->ttp }}</td>
                <td>{{ $item->ttd }}</td>
                <th>{{ $item->ttne }}</th>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ $foot['gnp'] }}</th>
            <th>{{ $foot['gnd'] }}</th>
            <th>{{ $foot['gnne'] }}</th>
            <th>{{ $foot['glp'] }}</th>
            <th>{{ $foot['gld'] }}</th>
            <th>{{ $foot['glne'] }}</th>
            <th>{{ $foot['grp'] }}</th>
            <th>{{ $foot['grd'] }}</th>
            <th>{{ $foot['grne'] }}</th>
            <th>{{ $foot['ttp'] }}</th>
            <th>{{ $foot['ttd'] }}</th>
            <th>{{ $foot['ttne'] }}</th>
        </tr>
    </tfoot>
</table>

{{--
    <tbody>
        @foreach ($body as $item)
            <tr class="text-right">
                <th class="text-center">{{ $item->ano }}</th>
                <td>{{ number_format($item->gnp, 0) }}</td>
                <td>{{ number_format($item->gnd, 2) }}</td>
                <th>{{ number_format($item->gnne, 2) }}</th>
                <td>{{ number_format($item->glp, 0) }}</td>
                <td>{{ number_format($item->gld, 2) }}</td>
                <th>{{ number_format($item->glne, 2) }}</th>
                <td>{{ number_format($item->grp, 0) }}</td>
                <td>{{ number_format($item->grd, 2) }}</td>
                <th>{{ number_format($item->grne, 2) }}</th>
                <td>{{ number_format($item->ttp, 0) }}</td>
                <td>{{ number_format($item->ttd, 2) }}</td>
                <th>{{ number_format($item->ttne, 2) }}</th>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot['gnp'], 0) }}</th>
            <th>{{ number_format($foot['gnd'], 2) }}</th>
            <th>{{ number_format($foot['gnne'], 2) }}</th>
            <th>{{ number_format($foot['glp'], 0) }}</th>
            <th>{{ number_format($foot['gld'], 2) }}</th>
            <th>{{ number_format($foot['glne'], 2) }}</th>
            <th>{{ number_format($foot['grp'], 0) }}</th>
            <th>{{ number_format($foot['grd'], 2) }}</th>
            <th>{{ number_format($foot['grne'], 2) }}</th>
            <th>{{ number_format($foot['ttp'], 0) }}</th>
            <th>{{ number_format($foot['ttd'], 2) }}</th>
            <th>{{ number_format($foot['ttne'], 2) }}</th>
        </tr>
    </tfoot>
    --}}



@php
/* function avance($monto)
{
    if ($monto < 51) {
        return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    } elseif ($monto < 100) {
        return '<span class="badge badge-pill badge-warning" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    } else {
        return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    }
} */
@endphp
