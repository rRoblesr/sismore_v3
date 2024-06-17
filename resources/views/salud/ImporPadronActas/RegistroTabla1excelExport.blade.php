<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:10px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">Nº</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">MUNICIPIO</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">RED</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">MICRORED</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">DISTRITO</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">CODIGO UNICO</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">EE.SS</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">FECHA INICIAL</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">FECHA FINAL</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">FECHA ENVIO</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">NUMERO DE ARCHIVOS</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-right">
                <td style="text-align:right;">{{ $key + 1 }}</td>
                <td style="text-align:right;">{{ $item->municipio }}</td>
                <td style="text-align:right;">{{ $item->red }}</td>
                <td style="text-align:left;">{{ $item->microred }}</td>
                <td style="text-align:left;">{{ $item->distrito }}</td>
                <td style="text-align:left;">{{ $item->cod_unico }}</td>
                <td style="text-align:left;">{{ $item->eess }}</td>
                <td style="text-align:left;">{{ $item->fecha_inicial }}</td>
                <td style="text-align:left;">{{ $item->fecha_final }}</td>
                <td style="text-align:left;">{{ $item->fecha_envio }}</td>
                <td style="text-align:left;">{{ $item->nro_archivos }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
