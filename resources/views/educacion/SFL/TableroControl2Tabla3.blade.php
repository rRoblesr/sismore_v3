<table id="tabla3" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th class="text-center">N°</th>
            <th class="text-center">UGEL</th>
            <th class="text-center">Distrito</th>
            <th class="text-center">Centro Poblado</th>
            <th class="text-center">Ambito</th>
            <th class="text-center">Cód.Local</th>
            <th class="text-center">Cód.Modular</th>
            <th class="text-center">Institución Educativa</th>
            <th class="text-center">Nivel</th>
            <th class="text-center">Estado SFL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-left">{{ $item->ugel }}</td>
                <td class="text-left">{{ $item->distrito }}</td>
                <td class="text-left">{{ $item->cpoblado }}</td>
                <td>{{ $item->area }}</td>
                <td>{{ $item->clocal }}</td>
                <td>{{ $item->cmodular }}</td>
                <td class="text-left">{{ $item->nombre }}</td>
                <td class="text-left">{{ $item->nivel }}</td>
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
        } elseif ($monto < 95) {
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
    {
        //<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px;">0.0%</span>
        switch ($estado) {
            case '1':
                return '<span class="badge badge-success" style="font-size:90%;">SANEADO</span>';
                break;
            case '2':
                return '<span class="badge badge-danger" style="font-size:90%;">NO SANEADO</span>';
                break;
            case '3':
                return '<span class="badge badge-secondary" style="font-size:90%;">NO REGISTRADO</span>';
                break;
            case '4':
                return '<span class="badge badge-warning" style="font-size:90%;">EN PROCESO</span>';
                break;

            default:
                return '';
                break;
        }
    }
@endphp
