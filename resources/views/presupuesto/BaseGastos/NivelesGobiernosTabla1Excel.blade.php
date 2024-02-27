<table id="tabla1" class="">
    <thead>
        <tr class="">
            <th>TIPO DE GOBIERNO</th>
            <th colspan="3">GOBIERNO NACIONAL</th>
            <th colspan="3">GOBIERNOS LOCALES</th>
            <th colspan="3">GOBIERNOS REGIONALES</th>
            <th colspan="3">TOTAL</th>
        </tr>
        <tr class="">
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
        <tr class="">
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
