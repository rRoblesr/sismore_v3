<table id="tabla1" class="table table-sm table-striped table-bordered font-12 m-0">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">DNI</th>
            <th class="text-center">NOMBRE COMPLETO</th>
            <th class="text-center">GENERO</th>
            <th class="text-center">FECHA NACIMIENTO</th>
            <th class="text-center">EDAD</th>
            {{-- <th class="text-center">SEGURO</th> --}}
            {{-- <th class="text-center">EJE VIAL</th> --}}
            <th class="text-center">DIRECCIÓN</th>
            {{-- <th class="text-center">REFERENCIA</th> --}}
            <th class="text-center">PROVINCIA</th>
            <th class="text-center">DISTRITO</th>
            <th class="text-center">UBIGEO CCPP</th>
            <th class="text-center">CENTRO POBLADO</th>
            <th class="text-center">AREA CCPP</th>
            <th class="text-center">ESTABLECIMIENTO SALUD</th>
            <th class="text-center">COD MUDULAR</th>
            <th class="text-center">INSTITUCION EDUCATIVA</th>
            <th class="text-center">NOMBRE COMPLETO MADRE</th>
            <th class="text-center">CELULAR MADRE</th>
            <th class="text-center">MATRICULADO</th>
        </tr>

    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key => $item)
                <tr class="text-center">
                    <td>{{ $key + 1 }}</td>
                    <td class="text-left">{{ $item->dni }}</td>
                    <td>{{ $item->apellido_paterno . ' ' . $item->apellido_materno . ', ' . $item->nombre }}</td>
                    <td>{{ $item->sexo }}</td>
                    <td>{{ $item->fecha_nacimiento }}</td>
                    <td>{{ $item->edad }}</td>
                    <td>{{ $item->direccion }}</td>
                    <td>{{ $item->provincia }}</td>
                    <td>{{ $item->distrito }}</td>
                    <td>{{ $item->centro_poblado }}</td>
                    <td>{{ $item->centro_poblado_nombre }}</td>
                    <td>{{ $item->area_ccpp }}</td>
                    <td>{{ $item->eess }}</td>
                    <td>{{ $item->codigo_ie }}</td>
                    <td>{{ $item->nombre_ie }}</td>
                    <td>{{ $item->apellido_paterno_madre . ' ' . $item->apellido_materno_madre . ', ' . $item->nombres_madre }}</td>
                    <td>{{ $item->celular_madre }}</td>
                    <td>
                        @if ($item->cumple == 1)
                            <button type="button"
                                class="btn btn-xs btn-success-0 p-1 font-11 text-nowrap">SI</button>
                        @else
                            <button type="button" class="btn btn-xs btn-danger p-1 font-11 text-nowrap">NO</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        {{-- <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th colspan="2" class="text-right">TOTAL</th>
                <th class="text-center">{{ number_format($foot->total, 0) }}</th>
                <th class="text-center">{{ number_format($foot->con, 0) }}</th>
                <th class="text-center">{{ number_format($foot->sin, 0) }}</th>
                <th class="text-center">{!! avance($foot->indicador, 1) !!}</th>
            </tr>
        </tfoot> --}}
    @else
        <tbody>
            <tr class="text-center">
                <td class="text-center" colspan="11"><a href="#" class="">Sin información</a></td>
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
