<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:11px;">
    <thead>
        <tr>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Nº</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Código<br>Local</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Código<br>Modular</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Institución Educatica</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Distrito</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Modalidad</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Nivel</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Gestión</th>
            <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">Área</th>
            <th colspan="5" style="background-color:#317eeb;color:#ffffff;text-align:center;">Servicios Básicos</th>
        </tr>
        <tr class="bg-success-0 text-white text-center">
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">>Agua</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Desague</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Electricidad</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Todos</th>
            <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Internet</th>
        </tr>
    </thead>
    @if ($base->count() > 0)
        <tbody>
            @foreach ($base as $key=>$item)
                <tr>
                    <td style="text-align:center;">{{ $key + 1 }}</td>
                    <td style="text-align:center;">{{ $item->local }}</td>
                    <td style="text-align:center;">{{ $item->modular }}</td>
                    <td style="text-align:left;">{{ $item->iiee }}</td>
                    <td style="text-align:center;">{{ $item->distrito }}</td>
                    <td style="text-align:center;">{{ $item->modalidad }}</td>
                    <td style="text-align:center;">{{ $item->nivel }}</td>
                    <td style="text-align:center;">{{ $item->gestion==3?"Privada":"Pública" }}</td>
                    <td style="text-align:center;">{{ $item->area }}</td>
                    <td style="text-align:center;">{{ $item->agua }}</td>
                    <td style="text-align:center;">{{ $item->desague }}</td>
                    <td style="text-align:center;">{{ $item->luz }}</td>
                    <td style="text-align:center;">{{ $item->servicios }}</td>
                    <td style="text-align:center;">{{ $item->internet }}</td>
                </tr>
            @endforeach
        </tbody>
        {{-- <tfoot>
            <tr>
                <th style="background-color:#317eeb;color:#ffffff;text-align:right;">TOTAL</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->meta, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->th, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tm, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->tt, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{!! avance($foot->avance, 1) !!}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ci, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cii, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->ciii, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->civ, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cv, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cvi, 0) }}</th>
                <th style="background-color:#317eeb;color:#ffffff;text-align:center;">{{ number_format($foot->cvii, 0) }}</th>
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
