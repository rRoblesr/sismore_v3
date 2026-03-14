<table id="tabla1" class="table table-striped table-bordered table-hover tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th style="width:40px;">#</th>
            <th>Unidad Ejecutora</th>
            <th>PIA</th>
            <th>PIM</th>
            <th>Recaudado</th>
            <th>% Ejecución</th>
            <th>Devengado</th>
            <th>Girado</th>
            <th>Saldo Recaudado</th>
            <th>Saldo Financiero</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="text-left">
                    <a href="javascript:void(0)" onclick="cargarModalDetalle({{ $item->id }}, '{{ $item->codigo_ue . ' ' . $item->nombre_ejecutora }}')">
                        {{ $item->codigo_ue . ' ' . $item->nombre_ejecutora }}
                    </a>
                </td>
                <td class="text-right">{{ number_format($item->pia, 0) }}</td>
                <td class="text-right">{{ number_format($item->pim, 0) }}</td>
                <td class="text-right">{{ number_format($item->recaudado, 0) }}</td>
                <td class="text-center">{!! avance($item->avance) !!}</td>
                <td class="text-right">{{ number_format($item->devengado, 0) }}</td>
                <td class="text-right">{{ number_format($item->girado, 0) }}</td>
                <td class="text-right">{{ $item->saldo_recaudado < 0 ? 0: number_format($item->saldo_recaudado, 0) }}</td>
                <td class="text-right {{ $item->saldo_financiero < 0 ? 'bg-danger text-white' : '' }}">{{ number_format($item->saldo_financiero, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="bg-success-0 text-white text-right">
            <th class="text-center" colspan="2">TOTAL</th>
            <th class="text-right">{{ number_format($foot['pia'], 0) }}</th>
            <th class="text-right">{{ number_format($foot['pim'], 0) }}</th>
            <th class="text-right">{{ number_format($foot['recaudado'], 0) }}</th>
            <th class="text-center">{!! avance($foot['avance']) !!}</th>
            <th class="text-right">{{ number_format($foot['devengado'], 0) }}</th>  
            <th class="text-right">{{ number_format($foot['girado'], 0) }}</th>
            <th class="text-right">{{ number_format($foot['saldo_recaudado'], 0) }}</th>
            <th class="text-right {{ $foot['saldo_financiero'] < 0 ? 'text-danger' : '' }}">{{ number_format($foot['saldo_financiero'], 0) }}</th>
        </tr>
    </tfoot>
</table>

@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:100%;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 75) {
            return '<span class="badge badge-pill badge-warning" style="font-size:100%;background-color:#eb960d;">' . round($monto, 1) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:100%;">' . round($monto, 1) . '%</span>';
        }
    }
@endphp
