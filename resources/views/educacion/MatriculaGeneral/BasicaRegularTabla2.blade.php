<table id="tabla2" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <td rowspan="2" class="text-center">DISTRITO</td>
            <td rowspan="2" class="text-center">META</td>
            <td colspan="3" class="text-center">ESTUDIANTES</td>
            <th rowspan="2" class="text-center">%AVANCE</th>
            <th colspan="2" class="text-center">INICIAL</th>
            <th colspan="3" class="text-center">PRIMARIA</th>
            <th colspan="2" class="text-center">SECUNDARIA</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th class="text-center">HOMBRES</th>
            <th class="text-center">MUJERES</th>
            <th class="text-center">TOTAL</th>

            <!-- Inicial -->
            {{-- <th class="text-center">CICLO I H</th>
            <th class="text-center">CICLO I M</th> --}}
            <th class="text-center">CICLO I</th>
            {{-- <th class="text-center">CICLO II H</th>
            <th class="text-center">CICLO II M</th> --}}
            <th class="text-center">CICLO II</th>

            <!-- Primaria -->
            {{-- <th class="text-center">CICLO III H</th>
            <th class="text-center">CICLO III M</th> --}}
            <th class="text-center">CICLO III</th>
            {{-- <th class="text-center">CICLO IV H</th>
            <th class="text-center">CICLO IV M</th> --}}
            <th class="text-center">CICLO IV</th>
            {{-- <th class="text-center">CICLO V H</th>
            <th class="text-center">CICLO V M</th> --}}
            <th class="text-center">CICLO V</th>

            <!-- Secundaria -->
            {{-- <th class="text-center">CICLO VI H</th>
            <th class="text-center">CICLO VI M</th> --}}
            <th class="text-center">CICLO VI</th>
            {{-- <th class="text-center">CICLO VII H</th>
            <th class="text-center">CICLO VII M</th> --}}
            <th class="text-center">CICLO VII</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr class="text-center">
                    <td class="text-left"><a href="javascript:void(0)"
                            onclick="cargarTablaCentroPoblado('tabla3', {{ $item->iddistrito }})"
                            class="">{{ $item->distrito }}</a>
                    </td>
                    <th class="table-warning">{{ number_format($item->meta, 0) }}</th>
                    <td>{{ number_format($item->th, 0) }}</td>
                    <td>{{ number_format($item->tm, 0) }}</td>
                    <th class="table-warning">{{ number_format($item->tt, 0) }}</th>
                    <td>{!! avance($item->avance, 1) !!}</td>

                    <!-- Inicial -->
                    {{-- <td>{{ number_format($item->cih, 0) }}</td>
                    <td>{{ number_format($item->cim, 0) }}</td> --}}
                    <td>{{ number_format($item->ci, 0) }}</td>
                    {{-- <td>{{ number_format($item->ciih, 0) }}</td>
                    <td>{{ number_format($item->ciim, 0) }}</td> --}}
                    <td>{{ number_format($item->cii, 0) }}</td>

                    <!-- Primaria -->
                    {{-- <td>{{ number_format($item->ciiih, 0) }}</td>
                    <td>{{ number_format($item->ciiim, 0) }}</td> --}}
                    <td>{{ number_format($item->ciii, 0) }}</td>
                    {{-- <td>{{ number_format($item->civh, 0) }}</td>
                    <td>{{ number_format($item->civm, 0) }}</td> --}}
                    <td>{{ number_format($item->civ, 0) }}</td>
                    {{-- <td>{{ number_format($item->cvh, 0) }}</td>
                    <td>{{ number_format($item->cvm, 0) }}</td> --}}
                    <td>{{ number_format($item->cv, 0) }}</td>

                    <!-- Secundaria -->
                    {{-- <td>{{ number_format($item->cvih, 0) }}</td>
                    <td>{{ number_format($item->cvim, 0) }}</td> --}}
                    <td>{{ number_format($item->cvi, 0) }}</td>
                    {{-- <td>{{ number_format($item->cviih, 0) }}</td>
                    <td>{{ number_format($item->cviim, 0) }}</td> --}}
                    <td>{{ number_format($item->cvii, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center bg-success-0 text-white">
                <th class="text-left">TOTAL</th>
                <th class="text-center">{{ number_format($foot->meta, 0) }}</th>
                <th class="text-center">{{ number_format($foot->th, 0) }}</th>
                <th class="text-center">{{ number_format($foot->tm, 0) }}</th>
                <th class="text-center">{{ number_format($foot->tt, 0) }}</th>
                <th class="text-center">{!! avance($foot->avance, 1) !!}</th>
                
                <!-- Inicial -->
                {{-- <th class="text-center">{{ number_format($foot->cih, 0) }}</th>
                <th class="text-center">{{ number_format($foot->cim, 0) }}</th> --}}
                <th class="text-center">{{ number_format($foot->ci, 0) }}</th>
                {{-- <th class="text-center">{{ number_format($foot->ciih, 0) }}</th>
                <th class="text-center">{{ number_format($foot->ciim, 0) }}</th> --}}
                <th class="text-center">{{ number_format($foot->cii, 0) }}</th>

                <!-- Primaria -->
                {{-- <th class="text-center">{{ number_format($foot->ciiih, 0) }}</th>
                <th class="text-center">{{ number_format($foot->ciiim, 0) }}</th> --}}
                <th class="text-center">{{ number_format($foot->ciii, 0) }}</th>
                {{-- <th class="text-center">{{ number_format($foot->civh, 0) }}</th>
                <th class="text-center">{{ number_format($foot->civm, 0) }}</th> --}}
                <th class="text-center">{{ number_format($foot->civ, 0) }}</th>
                {{-- <th class="text-center">{{ number_format($foot->cvh, 0) }}</th>
                <th class="text-center">{{ number_format($foot->cvm, 0) }}</th> --}}
                <th class="text-center">{{ number_format($foot->cv, 0) }}</th>

                <!-- Secundaria -->
                {{-- <th class="text-center">{{ number_format($foot->cvih, 0) }}</th>
                <th class="text-center">{{ number_format($foot->cvim, 0) }}</th> --}}
                <th class="text-center">{{ number_format($foot->cvi, 0) }}</th>
                {{-- <th class="text-center">{{ number_format($foot->cviih, 0) }}</th>
                <th class="text-center">{{ number_format($foot->cviim, 0) }}</th> --}}
                <th class="text-center">{{ number_format($foot->cvii, 0) }}</th>
            </tr>
        </tfoot>
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
