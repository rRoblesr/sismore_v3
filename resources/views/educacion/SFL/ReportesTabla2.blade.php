<table id="tabla2" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th class="text-center" rowspan="2">Distrito</th>
            <th class="text-center" colspan="3">Instituciones Educativas</th>
            <th class="text-center" colspan="3">Locales Escolares</th>
            <th class="text-center" colspan="2">L.E Saneado</th>
            <th class="text-center" colspan="2">L.E no Saneado</th>
            <th class="text-center" colspan="2">L.E en Proceso</th>
            <th class="text-center" colspan="2">L.E no Registrado</th>
        </tr>
        <tr class="table-success-0 text-white">
            <th class="text-center">Total</th>
            <th class="text-center">Rural</th>
            <th class="text-center">Urbano</th>
            <th class="text-center">Total</th>
            <th class="text-center">Rural</th>
            <th class="text-center">Urbano</th>
            <th class="text-center">Total</th>
            <th class="text-center">%</th>
            <th class="text-center">Total</th>
            <th class="text-center">%</th>
            <th class="text-center">Total</th>
            <th class="text-center">%</th>
            <th class="text-center">Total</th>
            <th class="text-center">%</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td class="text-left">{{ $item->distrito }}</td>
                <td class="table-warning">{{ number_format($item->se, 0) }}</td>
                <td>{{ number_format($item->ser, 0) }}</td>
                <td>{{ number_format($item->seu, 0) }}</td>
                <td class="table-warning">{{ number_format($item->le, 0) }}</td>
                <td>{{ number_format($item->ler, 0) }}</td>
                <td>{{ number_format($item->leu, 0) }}</td>
                <td>{{ number_format($item->le1, 0) }}</td>
                <td><x-avance-badge :avance="$item->le1p" /></td>
                <td>{{ number_format($item->le2, 0) }}</td>
                <td><x-avance-badge :avance="$item->le2p" /></td>
                <td>{{ number_format($item->le3, 0) }}</td>
                <td><x-avance-badge :avance="$item->le3p" /></td>
                <td>{{ number_format($item->le4, 0) }}</td>
                <td><x-avance-badge :avance="$item->le4p" /></td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center table-success-0 text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot->se, 0) }}</th>
            <th>{{ number_format($foot->ser, 0) }}</th>
            <th>{{ number_format($foot->seu, 0) }}</th>
            <th>{{ number_format($foot->le, 0) }}</th>
            <th>{{ number_format($foot->ler, 0) }}</th>
            <th>{{ number_format($foot->leu, 0) }}</th>
            <th>{{ number_format($foot->le1, 0) }}</th>
            <td><x-avance-badge :avance="$foot->le1p" /></td>
            <th>{{ number_format($foot->le2, 0) }}</th>
            <td><x-avance-badge :avance="$foot->le2p" /></td>
            <th>{{ number_format($foot->le3, 0) }}</th>
            <td><x-avance-badge :avance="$foot->le3p" /></td>
            <th>{{ number_format($foot->le4, 0) }}</th>
            <td><x-avance-badge :avance="$foot->le4p" /></td>
        </tr>
    </tfoot>
</table>