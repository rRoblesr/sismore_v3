<table id="tabla1" class="table table-sm table-bordered mb-0 font-10">
    <thead>
        <tr class="table-success-0 text-white">
            <th style="text-align: center" rowspan="2">N°</th>
            <th style="text-align: center" rowspan="2">UGEL</th>
            <th style="text-align: center" rowspan="2">DISTRITO</th>
            <th style="text-align: center" rowspan="2">GESTIÓN</th>
            <th style="text-align: center" rowspan="2">ÁREA</th>
            <th style="text-align: center" rowspan="2">CÓDIGO MODULAR</th>
            <th style="text-align: center" rowspan="2">INSTITUCIÓN EDUCATIVA</th>
            <th style="text-align: center" colspan="3">ESTUDIANTES EVALUADOS</th>
            <th style="text-align: center" colspan="4">NIVELES DE LOGRO</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th style="text-align: center">TOTAL</th>
            <th style="text-align: center">HOMBRE</th>
            <th style="text-align: center">MUJER</th>
            <th style="text-align: center">SATISFACTORIO</th>
            <th style="text-align: center">EN PROCESO</th>
            <th style="text-align: center">EN INICIO</th>
            <th style="text-align: center">PREVIO AL INICIO</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->ugel }}</td>
                    <td>{{ $item->distrito }}</td>
                    <td>{{ $item->gestion }}</td>
                    <td>{{ $item->area }}</td>
                    <td>{{ $item->modular }}</td>
                    <td>{{ $item->iiee }}</td>
                    <td>{{ number_format($item->alumnos, 0) }}</td>
                    <td>{{ number_format($item->alumnos_hombres, 0) }}</td>
                    <td>{{ number_format($item->alumnos_mujeres, 0) }}</td>
                    <td>{{ number_format($item->s, 1) }}%</td>
                    <td>{{ number_format($item->p, 1) }}%</td>
                    <td>{{ number_format($item->i, 1) }}%</td>
                    <td>{{ number_format($item->a, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-success-0 text-white">
                <th colspan="7" style="text-align: right">Total</th>
                <th style="text-align: center">{{ number_format($foot->alumnos, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->alumnos_hombres, 0) }}</th>
                <th style="text-align: center">{{ number_format($foot->alumnos_mujeres, 0) }}</th>
                <th style="text-align: center"></th>
                <th style="text-align: center"></th>
                <th style="text-align: center"></th>
                <th style="text-align: center"></th>
                {{-- <th style="text-align: center">{{ number_format($foot->s, 0) }}</th> --}}
                {{-- <th style="text-align: center">{{ number_format($foot->p, 0) }}</th> --}}
                {{-- <th style="text-align: center">{{ number_format($foot->i, 0) }}</th> --}}
                {{-- <th style="text-align: center">{{ number_format($foot->a, 0) }}</th> --}}
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
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' .
                round($monto, 1) .
                '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' .
                round($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' .
                round($monto, 1) .
                '%</span>';
        }
    }
@endphp
