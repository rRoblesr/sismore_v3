<table id="tabla2" class="table table-bordered mb-0 tablex" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th>MODALIDAD/NIVEL</th>
            <th>META</th>
            <th>ENE</th>
            <th>FEB</th>
            <th>MAR</th>
            <th>ABR</th>
            <th>MAY</th>
            <th>JUN</th>
            <th>JUL</th>
            <th>AGO</th>
            <th>SET</th>
            <th>OCT</th>
            <th>NOV</th>
            <th>DIC</th>
            <th>TOTAL</th>
            <th>AVANCE</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($head as $hh)
                <tr class="text-center table-active">
                    <th class="text-left">{{ $hh->tipo }}</th>
                    <th>{{ number_format($hh->meta, 0) }}</th>
                    <th>{{ number_format($hh->ene, 0) }}</th>
                    <th>{{ number_format($hh->feb, 0) }}</th>
                    <th>{{ number_format($hh->mar, 0) }}</th>
                    <th>{{ number_format($hh->abr, 0) }}</th>
                    <th>{{ number_format($hh->may, 0) }}</th>
                    <th>{{ number_format($hh->jun, 0) }}</th>
                    <th>{{ number_format($hh->jul, 0) }}</th>
                    <th>{{ number_format($hh->ago, 0) }}</th>
                    <th>{{ number_format($hh->set, 0) }}</th>
                    <th>{{ number_format($hh->oct, 0) }}</th>
                    <th>{{ number_format($hh->nov, 0) }}</th>
                    <th>{{ number_format($hh->dic, 0) }}</th>
                    <th>{{ number_format($hh->total, 0) }}</th>
                    <th>{!! avance($hh->avance) !!}</th>
                </tr>
                @foreach ($base as $bb)
                    @if ($bb->tipo == $hh->tipo)
                        <tr class="text-center">
                            <td class="text-left">{{ $bb->nivel }}</td>
                            <th class="table-warning">{{ number_format($bb->meta, 0) }}</th>
                            <td>{{ number_format($bb->ene, 0) }}</td>
                            <td>{{ number_format($bb->feb, 0) }}</td>
                            <td>{{ number_format($bb->mar, 0) }}</td>
                            <td>{{ number_format($bb->abr, 0) }}</td>
                            <td>{{ number_format($bb->may, 0) }}</td>
                            <td>{{ number_format($bb->jun, 0) }}</td>
                            <td>{{ number_format($bb->jul, 0) }}</td>
                            <td>{{ number_format($bb->ago, 0) }}</td>
                            <td>{{ number_format($bb->sep, 0) }}</td>
                            <td>{{ number_format($bb->oct, 0) }}</td>
                            <td>{{ number_format($bb->nov, 0) }}</td>
                            <td>{{ number_format($bb->dic, 0) }}</td>
                            <th class="table-warning">{{ number_format($bb->total, 0) }}</th>
                            <td>{!! avance($bb->avance) !!}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach


        </tbody>
        <tfoot>
            <tr class="table-success-0 text-white">
                <th>Total</th>
                <th style="text-align: center">{{ number_format($foot->meta, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->ene, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->feb, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->mar, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->abr, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->may, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->jun, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->jul, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->ago, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->set, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->oct, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->nov, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->dic, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->total, 0) }}</th>
                <th style="text-align: center">{!! avance($foot->avance) !!}</th>
            </tr>
        </tfoot>
    @else
        <tr class="text-center">
            <td colspan="21"><a href="javascript:void(0)">Sin información</a></td>
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
