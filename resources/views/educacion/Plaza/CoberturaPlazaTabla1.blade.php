<table id="tabla0" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <th>Ugel</th>
            <th>Meta</th>
            <th>ene</th>
            <th>feb</th>
            <th>mar</th>
            <th>abr</th>
            <th>may</th>
            <th>jun</th>
            <th>jul</th>
            <th>ago</th>
            <th>set</th>
            <th>oct</th>
            <th>nov</th>
            <th>dic</th>
            <th>Total</th>
            <th>%Avance</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $item)
            <tr class="text-center">
                <td class="text-left"><a href="#" class="">{{ $item->ugel }}</a></td>
                <th class="">{{ number_format($item->tregA) }}</th>
                <td>{!! bajas2($anoF == 1, $item->ene, $anoF - 1) !!}</td>
                <td>{!! bajas2($anoF == 2, $item->feb, $anoF - 2) !!}</td>
                <td>{!! bajas2($anoF == 3, $item->mar, $anoF - 3) !!}</td>
                <td>{!! bajas2($anoF == 4, $item->abr, $anoF - 4) !!}</td>
                <td>{!! bajas2($anoF == 5, $item->may, $anoF - 5) !!}</td>
                <td>{!! bajas2($anoF == 6, $item->jun, $anoF - 6) !!}</td>
                <td>{!! bajas2($anoF == 7, $item->jul, $anoF - 7) !!}</td>
                <td>{!! bajas2($anoF == 8, $item->ago, $anoF - 8) !!}</td>
                <td>{!! bajas2($anoF == 9, $item->set, $anoF - 9) !!}</td>
                <td>{!! bajas2($anoF == 10, $item->oct, $anoF - 10) !!}</td>
                <td>{!! bajas2($anoF == 11, $item->nov, $anoF - 11) !!}</td>
                <td>{!! bajas2($anoF == 12, $item->dic, $anoF - 12) !!}</td>
                <th>{!! bajas($item->treg) !!}</th>
                <td>{!! avance($item->avance * 100) !!}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th class="text-left">Total</th>
            <th class="">{{ number_format($foot['meta'], 0) }}</th>
            <th>{!! bajas2($anoF == 1, $foot['ene'], $anoF - 1) !!}</th>
            <th>{!! bajas2($anoF == 2, $foot['feb'], $anoF - 2) !!}</th>
            <th>{!! bajas2($anoF == 3, $foot['mar'], $anoF - 3) !!}</th>
            <th>{!! bajas2($anoF == 4, $foot['abr'], $anoF - 4) !!}</th>
            <th>{!! bajas2($anoF == 5, $foot['may'], $anoF - 5) !!}</th>
            <th>{!! bajas2($anoF == 6, $foot['jun'], $anoF - 6) !!}</th>
            <th>{!! bajas2($anoF == 7, $foot['jul'], $anoF - 7) !!}</th>
            <th>{!! bajas2($anoF == 8, $foot['ago'], $anoF - 8) !!}</th>
            <th>{!! bajas2($anoF == 9, $foot['set'], $anoF - 9) !!}</th>
            <th>{!! bajas2($anoF == 10, $foot['oct'], $anoF - 10) !!}</th>
            <th>{!! bajas2($anoF == 11, $foot['nov'], $anoF - 11) !!}</th>
            <th>{!! bajas2($anoF == 12, $foot['dic'], $anoF - 12) !!}</th>
            <th>{!! bajas($foot['total']) !!}</th>
            <th>{!! avance($foot['avance'] * 100) !!}</th>
        </tr>
    </tfoot>
</table>
@php
function bajas2($anoF, $monto, $paint)
{
    if (!$anoF) {
        if ($paint > 0) {
            if ($monto < 0) {
                return '<span class="badge badge-pill badge-danger" style="font-size:85%;">' . round($monto, 0) . '</span>';
            } else {
                return number_format($monto);
            }
        } else {
            return '';
        }
    } else {
        return '';
    }
}
function avance($monto)
{
    if ($monto < 51) {
        return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    } elseif ($monto < 100) {
        return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' . round($monto, 1) . '%</span>';
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
