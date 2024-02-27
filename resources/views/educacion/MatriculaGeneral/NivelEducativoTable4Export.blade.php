<table id="tabla4" class="table table-striped table-bordered tablex" style="font-size:9px;">
    <thead>
        <tr>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Nº</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Código Modular</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Institución Educativa</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Nivel</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Gestión</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Área</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Provincia</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Distrito</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Centro Poblado</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Meta</td>
            <th rowspan="1" colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Estudiantes</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">%Avance</th>
            <th rowspan="1" colspan="5" style="background-color:#317eeb;color:#ffffff;text-align:center;">Estudiantes por Grado</th>
        </tr>
        <tr>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>

            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Primero</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Segundo</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Tercero</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Cuarto</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Quinto</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr >
                    <td style="text-align:center;">{{ $key + 1 }}</td>
                    <td style="text-align:right;">{{ $item->codmod }}</td>
                    <td style="text-align:left;">{{ $item->iiee }}</td>
                    <td style="text-align:center;">{{ $item->nivel }}</td>
                    <td style="text-align:center;">{{ $item->gestion }}</td>
                    <td style="text-align:center;">{{ $item->area }}</td>
                    <td style="text-align:left;">{{ $item->provincia }}</td>
                    <td style="text-align:left;">{{ $item->distrito }}</td>
                    <td style="text-align:left;">{{ $item->centropoblado }}</td>
                    <td style="text-align:right;">{{ $item->meta }}</td>
                    <td style="text-align:right;">{{ number_format($item->th, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tm, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tt, 0) }}</td>
                    <td style="text-align:right;">{!! avance($item->avance, 1) !!}</td>
                    <td style="text-align:right;">{{ number_format($item->e1, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->e2, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->e3, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->e4, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->e5, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" style="background-color:#317eeb;color:#ffffff;text-align:right;">TOTAL</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->meta, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->th, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->tm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->tt, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{!! avance($foot->avance, 1) !!}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->e1, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->e2, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->e3, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->e4, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ number_format($foot->e5, 0) }}</th>
            </tr>
        </tfoot>
    @else
        <tbody>
            <tr >
                <td  colspan="11"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody>
    @endif
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
    function bajas($monto)
    {
        if ($monto < 0) {
            return '<span class="badge badge-pill badge-danger" style="font-size:85%;">' . round($monto, 0) . '</span>';
        } else {
            return number_format($monto);
        }
    }
@endphp
