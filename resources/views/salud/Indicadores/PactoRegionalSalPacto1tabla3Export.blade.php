<table id="tabla2" class="table table-sm table-striped table-bordered font-11 m-0">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th>N°</th>
            <th>Tipo Doc</th>
            <th>Documento</th>
            <th>Departamento</th>
            <th>Provincia</th>
            <th>Distrito</th>
            <th>Centro Poblado</th>
            <th>CUI EESS</th>
            <th>Establecimiento de Salud</th>
            <th>Estado</th>
        </tr>
    </thead>
    @if (count($base) > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->tipo_doc }}</td>
                    <td>{{ $item->num_doc }}</td>
                    <td>{{ $item->departamento }}</td>
                    <td>{{ $item->provincia }}</td>
                    <td>{{ $item->distrito }}</td>
                    <td>{{ $item->centro_poblado }}</td>
                    <td>{{ $item->cui_atencion > 0 ? str_pad($item->cui_atencion, 8, '0', STR_PAD_LEFT) : '' }}</td>
                    <td>{{ $item->cui_atencion > 0 ? $item->nombre_establecimiento : '' }}</td>
                    <td>{!! avance($item->num) !!}</td>
                </tr>
            @endforeach
        </tbody>
    @else
        <tbody>
            <tr class="text-center">
                <td class="text-center" colspan="8"><a href="#" class="">Sin información</a></td>
            </tr>
        </tbody>
    @endif
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%; width:50px">' .
                round($monto, 1) .
                '%</span>';
        } elseif ($monto < 100) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%; width:50px">' .
                round($monto, 1) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%; width:50px">' .
                round($monto, 1) .
                '%</span>';
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
