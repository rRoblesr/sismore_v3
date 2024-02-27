<table id="tabla2" class="table table-sm table-bordered mb-0" style="font-size:11px;">
    <thead>
        <tr class="table-success-0 text-white">
            {{-- <th rowspan="2" style="text-align: center">N°</th> --}}
            <th rowspan="2" style="text-align: center">MODALIDAD/NIVEL EDUCATIVO</th>
            <th rowspan="2" style="text-align: center">TOTAL</th>
            <th rowspan="1" colspan="3" style="text-align: center">PÚBLICO</th>
            <th rowspan="1" colspan="3" style="text-align: center">PRIVADO</th>
            <th rowspan="1" colspan="3" style="text-align: center">URBANO</th>
            <th rowspan="1" colspan="3" style="text-align: center">RURAL</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th style="text-align: center">Nombrados</th>
            <th style="text-align: center">Contratados</th>
            <th style="text-align: center">Total</th>
            <th style="text-align: center">Nombrados</th>
            <th style="text-align: center">Contratados</th>
            <th style="text-align: center">Total</th>
            <th style="text-align: center">Nombrados</th>
            <th style="text-align: center">Contratados</th>
            <th style="text-align: center">Total</th>
            <th style="text-align: center">Nombrados</th>
            <th style="text-align: center">Contratados</th>
            <th style="text-align: center">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($head as $key => $hh)
            <tr class="text-center table-secondary">
                {{-- <td>{{ $key + 1 }}</td> --}}
                <th class="text-left">{{ $hh->modalidad }}</th>
                <th>{{ $hh->tt }}</th>
                <th>{{ $hh->tpubn }}</th>
                <th>{{ $hh->tpubc }}</th>
                <th>{{ $hh->tpubn + $hh->tpubc }}</th>
                <th>{{ $hh->tprin }}</th>
                <th>{{ $hh->tpric }}</th>
                <th>{{ $hh->tprin + $hh->tpric }}</th>
                <th>{{ $hh->turbn }}</th>
                <th>{{ $hh->turbc }}</th>
                <th>{{ $hh->turbn + $hh->turbc }}</th>
                <th>{{ $hh->trurn }}</th>
                <th>{{ $hh->trurc }}</th>
                <th>{{ $hh->trurn + $hh->trurc }}</th>
            </tr>
            @foreach ($base as $key2 => $item)
                @if ($item->modalidad == $hh->modalidad)
                    <tr class="text-center">
                        {{-- <td>{{ $key2 + 1 }}</td> --}}
                        <td class="text-left">{{ $item->nivel }}</td>
                        <td class="table-warning">{{ $item->tt }}</td>
                        <td>{{ $item->tpubn }}</td>
                        <td>{{ $item->tpubc }}</td>
                        <td class="table-warning">{{ $item->tpubn + $item->tpubc }}</td>
                        <td>{{ $item->tprin }}</td>
                        <td>{{ $item->tpric }}</td>
                        <td class="table-warning">{{ $item->tprin + $item->tpric }}</td>
                        <td>{{ $item->turbn }}</td>
                        <td>{{ $item->turbc }}</td>
                        <td class="table-warning">{{ $item->turbn + $item->turbc }}</td>
                        <td>{{ $item->trurn }}</td>
                        <td>{{ $item->trurc }}</td>
                        <td class="table-warning">{{ $item->trurn + $item->trurc }}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
    @if ($foot != null)
        <tfoot>
            <tr class="table-success-0 text-white text-center">
                <th colspan="1" style="text-align: right">Total</th>
                <th style="text-align: center">{{ number_format($foot->tt, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tpubn, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tpubc, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tpubn + $foot->tpubc, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tprin, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tpric, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->tprin + $foot->tpric, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->turbn, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->turbc, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->turbn + $foot->turbc, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->trurn, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->trurc, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->trurn + $foot->trurc, 0) }}</th>
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
