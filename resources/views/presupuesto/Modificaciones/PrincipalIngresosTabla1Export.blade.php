<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:10px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <th>#</th>
            <th>Unidad Ejecutora</th>
            <th>Fecha Aprobacion</th>
            <th>Documento</th>
            <th>Dispositivo Legal</th>
            <th>Justificacion</th>
            <th>Fuente de Financiamiento</th>
            <th>Credito</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($body as $pos => $item)
            <tr>
                <td>{{ $pos + 1 }}</td>
                <td>{{ $item->unidad_ejecutora }}</td>
                <td>{{ $item->fecha_aprobacion }}</td>
                <td>{{ $item->documento }}</td>
                <td>{{ $item->dispositivo_legal }}</td>
                <td>{{ $item->justificacion }}</td>
                <td>{{ $item->fuente_financiamiento }}</td>
                <td>{{ $item->credito }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-right bg-primary text-white">
            <th colspan="7">TOTAL</th>
            <th>{{ $foot['credito'] }}</th>
        </tr>
    </tfoot>
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:85%;">' . round($monto, 1) . '%</span>';
        } elseif ($monto < 75) {
            return '<span class="badge badge-pill badge-warning" style="font-size:85%;background-color:#eb960d;">' . round($monto, 1) . '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:85%;">' . round($monto, 1) . '%</span>';
        }
    }
@endphp
