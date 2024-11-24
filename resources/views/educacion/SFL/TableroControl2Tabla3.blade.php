<table id="tabla3" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th class="text-center">N°</th>
            <th class="text-center">UGEL</th>
            <th class="text-center">DISTRITO</th>
            <th class="text-center">CENTRO POBLADO</th>
            <th class="text-center">AMBITO</th>
            <th class="text-center">CÓDIGO LOCAL</th>
            <th class="text-center">CÓDIGO MODULAR</th>
            <th class="text-center">INSTITUCIÓN EDUCATIVA</th>
            <th class="text-center">NIVEL</th>
            <th class="text-center">ESTADO SFL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-left">{{ $item->ugel }}</td>
                <td class="text-left">{{ $item->distrito }}</td>
                <td>{{ $item->cpoblado }}</td>
                <td>{{ $item->area }}</td>
                <td>{{ $item->clocal }}</td>
                <td>{{ $item->cmodular }}</td>
                <td class="text-left">{{ $item->nombre }}</td>
                <td>{{ $item->nivel }}</td>
                <td>{!! estado($item->estado) !!}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        {{-- <tr class="text-center table-success-0 text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot->ie, 0) }}</th>
            <th>{{ number_format($foot->ier, 0) }}</th>
            <th>{{ number_format($foot->ieu, 0) }}</th>
            <th>{{ number_format($foot->le, 0) }}</th>
            <th>{{ number_format($foot->ler, 0) }}</th>
            <th>{{ number_format($foot->leu, 0) }}</th>
            <th>{{ number_format($foot->e1, 0) }}</th>
            <th>{!! avance($foot->e1p, 0) !!}</th>
            <th>{{ number_format($foot->e2, 0) }}</th>
            <th>{!! avance($foot->e2p, 0) !!}</th>
            <th>{{ number_format($foot->e3, 0) }}</th>
            <th>{!! avance($foot->e3p, 0) !!}</th>
            <th>{{ number_format($foot->e4, 0) }}</th>
            <th>{!! avance($foot->e4p, 0) !!}</th>
        </tr> --}}
    </tfoot>
</table>

@php
    function avance($monto)
    {
        if ($monto < 50) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px;">' .
                number_format($monto, 1) .
                '%</span>';
        } elseif ($monto < 75) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%; width:50px;background-color:#eb960d;">' .
                number_format($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px;">' .
                number_format($monto, 1) .
                '%</span>';
        }
    }

    function estado($estado)
    {//<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px;">0.0%</span>
        switch ($estado) {
            case '1':
                return '<span class="badge badge-success" style="font-size:90%; width:100px;">SANEADO</span>';
                break;
            case '2':
                return '<span class="badge badge-danger" style="font-size:90%; width:100px;">NO SANEADO</span>';
                break;
            case '3':
                return '<span class="badge badge-secondary" style="font-size:90%; width:100px;">NO REGISTRADO</span>';
                break;
            case '4':
                return '<span class="badge badge-warning" style="font-size:90%; width:100px;">EN PROCESO</span>';
                break;

            default:
                return '';
                break;
        }
    }
@endphp
