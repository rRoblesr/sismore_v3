<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:10px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Nº</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Código<br>Modular</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Institución Educativa</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Distrito</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Gestión</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Área</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Meta</th>
            <th colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Estudiantes</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Indicador</th>
            <th colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Docentes</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Contratados</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Nombrados</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-right">
                <td style="text-align:right;">{{ $key + 1 }}</td>
                <td style="text-align:right;">{{ $item->modular }}</td>
                <td style="text-align:left;">{{ $item->iiee }}</td>
                <td style="text-align:left;">{{ $item->distrito }}</td>
                <td style="text-align:right;">{{ $item->gestion }}</td>
                <td style="text-align:left;">{{ $item->area }}</td>
                <td style="text-align:center;background-color:#fff4ca;">{{ number_format($item->meta, 0) }}</td>
                <td style="text-align:center;">{{ number_format($item->at, 0) }}</td>
                <td style="text-align:center;">{{ number_format($item->t, 0) }}</td>
                <td style="text-align:center;background-color:#fff4ca;">{{ number_format($item->at + $item->t, 0) }}</td>
                <td style="text-align:center;">{!! avance($item->indicador) !!}</td>
                <td style="text-align:center;">{{ number_format($item->c, 0) }}</td>
                <td style="text-align:center;">{{ number_format($item->n, 0) }}</td>
                <td style="text-align:center;background-color:#fff4ca;">{{ number_format($item->c + $item->n, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="table-success-0 text-white text-right">
            <th style="background-color:#317eeb;color:#ffffff;text-align:right;" colspan="6">Total</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->meta, 0) }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->at, 0) }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->t, 0) }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->at + $foot->t, 0) }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{!! avance($foot->indicador) !!}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->c, 0) }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->n, 0) }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->c + $foot->n, 0) }}</th>
        </tr>
    </tfoot>
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' . round($monto, 2) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
        }
    }
@endphp
