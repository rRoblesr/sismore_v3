<table id="tabla1" class="table table-striped table-bordered table-sm font-11">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th>TIPO DE GOBIERNO</th>
            <th colspan="3">GOBIERNO NACIONAL</th>
            <th colspan="3">GOBIERNOS LOCALES</th>
            <th colspan="3">GOBIERNOS REGIONALES</th>
            <th colspan="3">TOTAL</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
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
        <tr class="text-center bg-success-0 text-white">
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
@endphp
