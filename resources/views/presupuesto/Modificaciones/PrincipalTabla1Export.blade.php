<table id="tabla1" class="table table-striped table-bordered tablex">
    <thead>
        <tr>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">#</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Unidad Ejecutora</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Fecha Aprobacion</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Documento</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Justificacion</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">SecFun</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CatPres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">ProdProy</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">ActAccObra</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Rb</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Especifica Detalle</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Anulacion</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Credito</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($body as $pos => $item)
            <tr>
                <td>{{ $pos + 1 }}</td>
                <td>{{ $item->unidad_ejecutora }}</td>
                <td>{{ $item->fecha_aprobacion }}</td>
                <td>{{ $item->documento }}</td>
                <td>{{ $item->justificacion }}</td>
                <td>{{ $item->secfun }}</td>
                <td>{{ $item->catpres }}</td>
                <td>{{ $item->prod_proy }}</td>
                <td>{{ $item->act_acc_obra }}</td>
                <td>{{ $item->rb }}</td>
                <td>{{ $item->clasificador . ' ' . $item->especifica_detalle }}</td>
                <td>{{ $item->anulacion }}</td>
                <td>{{ $item->credito }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;" colspan="11">TOTAL</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ $foot['anulacion'] }}</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ $foot['credito'] }}</th>
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
