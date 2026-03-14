<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Provincia</td>
            <td rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Meta</td>
            <td colspan="3" style="background-color:#317eeb;color:#ffffff;text-align:center;">Estudiantes</td>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Indicador</th>
            <th colspan="6" style="background-color:#317eeb;color:#ffffff;text-align:center;">Inicial</th>
            <th colspan="9" style="background-color:#317eeb;color:#ffffff;text-align:center;">Primaria</th>
            <th colspan="6" style="background-color:#317eeb;color:#ffffff;text-align:center;">Secundaria</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Hombres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Mujeres</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total</th>
            
            <!-- Inicial -->
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo I H</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo I M</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo I T</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo II H</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo II M</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo II T</th>

            <!-- Primaria -->
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo III H</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo III M</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo III T</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo IV H</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo IV M</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo IV T</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo V H</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo V M</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo V T</th>

            <!-- Secundaria -->
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo VI H</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo VI M</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo VI T</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo VII H</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo VII M</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Ciclo VII T</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $item)
                <tr>
                    <td style="text-align:left;"><a href="javascript:void(0)">{{ $item->provincia }}</td>
                    <th style="text-align:right;">{{ number_format($item->meta, 0) }}</th>
                    <td style="text-align:right;">{{ number_format($item->th, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->tm, 0) }}</td>
                    <th style="text-align:right;">{{ number_format($item->tt, 0) }}</th>
                    <td style="text-align:right;">{!! avance($item->avance, 1) !!}</td>
                    
                    <!-- Inicial -->
                    <td style="text-align:right;">{{ number_format($item->cih, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cim, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ci, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ciih, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ciim, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cii, 0) }}</td>

                    <!-- Primaria -->
                    <td style="text-align:right;">{{ number_format($item->ciiih, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ciiim, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->ciii, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->civh, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->civm, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->civ, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cvh, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cvm, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cv, 0) }}</td>

                    <!-- Secundaria -->
                    <td style="text-align:right;">{{ number_format($item->cvih, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cvim, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cvi, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cviih, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cviim, 0) }}</td>
                    <td style="text-align:right;">{{ number_format($item->cvii, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">TOTAL</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->meta, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->th, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tt, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{!! avance($foot->avance, 1) !!}</th>
                
                <!-- Inicial -->
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cih, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cim, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ci, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ciih, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ciim, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cii, 0) }}</th>

                <!-- Primaria -->
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ciiih, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ciiim, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ciii, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->civh, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->civm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->civ, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cvh, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cvm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cv, 0) }}</th>

                <!-- Secundaria -->
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cvih, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cvim, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cvi, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cviih, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cviim, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cvii, 0) }}</th>
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
