<table id="tabla1" class="table table-striped table-bordered tablex" style="font-size:10px;">
    <thead>
        <tr class="table-success-0 text-white">
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">Nº</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">CÓDIGO LOCAL</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">UGEL</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">PROVINCIA</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">DISTRITO</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">AREA</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">FECHA INSCRIPCION</th>
            <th rowspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">ESTADO DEL SFL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-right">
                <td style="text-align:right;">{{ $key + 1 }}</td>
                <td style="text-align:right;">{{ $item->local }}</td>
                <td style="text-align:left;">{{ $item->ugel }}</td>
                <td style="text-align:left;">{{ $item->provincia }}</td>
                <td style="text-align:left;">{{ $item->distrito }}</td>
                <td style="text-align:left;">{{ $item->area }}</td>
                <td style="text-align:left;">{{ $item->fecha_inscripcion }}</td>
                <td style="text-align:left;">{{ $item->estado }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
