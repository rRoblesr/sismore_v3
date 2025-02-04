<table id="tabla1" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="2" class="text-center">N°</th>
            <th rowspan="2" class="text-center">Tipo</th>
            <th colspan="3" class="text-center">Documento</th>
            <th colspan="6" class="text-center">Nombre del Niño</th>
            <th colspan="4" class="text-center">Fecha Nacimiento</th>
            <th colspan="4" class="text-center">Sexo</th>
            <th colspan="4" class="text-center">Vacunas</th>
            <th colspan="4" class="text-center">Tamizaje</th>
            <th colspan="4" class="text-center">Cred</th>
            <th colspan="4" class="text-center">Visitas</th>
        </tr>
    </thead>
    <tbody>
        <tr class="text-right">
            <td class="text-center">{{ $key + 1 }}</td>
            <td class="text-left">DNI</td>
            <td class="text-center table-warning">93670642</td>
            <td class="text-center">TORRES BRAVO, LIAM VALENTINO</td>
            <td class="text-center">02/01/2024</td>
            <td class="text-center">MASCULINA</td>
            <td class="text-center">SI</td>
            <td class="text-center">SI</td>
            <td class="text-center">NO</td>
            <td class="text-center">SI</td>
        </tr>
    </tbody>

</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' .
                round($monto, 2) .
                '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' .
                round($monto, 2) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' .
                round($monto, 2) .
                '%</span>';
        }
    }
@endphp
