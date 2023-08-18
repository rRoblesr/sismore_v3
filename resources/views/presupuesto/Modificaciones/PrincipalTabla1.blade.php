<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:10px;">
    <thead>
        <tr class="bg-primary text-white text-center">
            <th>Unidad Ejecutora</th>
            <th>Fecha Aprobacion</th>
            <th>Documento</th>
            <th>Justificacion</th>
            <th>SecFun</th>
            <th>CatPres</th>
            <th>ProdProy</th>
            <th>ActAccObra</th>
            <th>Rb</th>
            <th>Especifica Detalle</th>
            <th>Anulacion</th>
            <th>Credito</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($body as $pos => $item)
            <tr class="text-left">
                <td class="text-center">{{ $item->unidad_ejecutora }}</td>
                <td>{{ $item->fecha_aprobacion }}</td>
                <td>{{ $item->documento }}</td>
                <td>{{ $item->justificacion }}</td>
                <td>{{ $item->secfun }}</td>
                <td>{{ $item->catpres }}</td>
                <td>{{ $item->prod_proy }}</td>
                <td>{{ $item->act_acc_obra }}</td>
                <td>{{ $item->rb }}</td>
                <td>{{ $item->clasificador .' '. $item->especifica_detalle }}</td>
                <td class="text-right">{{ number_format($item->anulacion, 0) }}</td>
                <td class="text-right">{{ number_format($item->credito, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-right bg-primary text-white">
            <th class="text-left" colspan="11">TOTAL</th>
            <th>{{ number_format($foot['anulacion'], 0) }}</th>
            <th>{{ number_format($foot['credito'], 0) }}</th>
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
