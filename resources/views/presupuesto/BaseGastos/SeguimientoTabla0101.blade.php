<div class="card card-border border border-plomo-0">
    <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
        <div class="card-widgets">
            @if (isset($codes))
                <button type="button" class="btn btn-success-0 btn-xs"
                    onclick="descargarExcelDetalle0201('{{ $codes }}')">
                    <i class="fa fa-file-excel"></i> Descargar</button>
            @elseif (isset($ue))
                <button type="button" class="btn btn-success-0 btn-xs"
                    onclick="descargarExcelDetalle0101({{ $ue }})">
                    <i class="fa fa-file-excel"></i> Descargar</button>
            @endif
        </div>
        <h3 class="card-title text-center">RECAUDADO MENSUALIZADO  (S/.)</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabla0101" class="table table-striped table-bordered tablex" style="font-size:11px;">
                <thead>
                    <tr class="table-success-0 text-white text-center">
                        <th>Año</th>
                        <th>Ene</th>
                        <th>Feb</th>
                        <th>Mar</th>
                        <th>Abr</th>
                        <th>May</th>
                        <th>Jun</th>
                        <th>Jul</th>
                        <th>Ago</th>
                        <th>Sep</th>
                        <th>Oct</th>
                        <th>Nov</th>
                        <th>Dic</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody class="text-right">
                    @foreach ($body as $row)
                        <tr>
                            <td class="text-center text-primary">{{ $row->anio }}</td>
                            <td>{{ number_format($row->ene ?? 0, 0) }}</td>
                            <td>{{ number_format($row->feb ?? 0, 0) }}</td>
                            <td>{{ number_format($row->mar ?? 0, 0) }}</td>
                            <td>{{ number_format($row->abr ?? 0, 0) }}</td>
                            <td>{{ number_format($row->may ?? 0, 0) }}</td>
                            <td>{{ number_format($row->jun ?? 0, 0) }}</td>
                            <td>{{ number_format($row->jul ?? 0, 0) }}</td>
                            <td>{{ number_format($row->ago ?? 0, 0) }}</td>
                            <td>{{ number_format($row->sep ?? 0, 0) }}</td>
                            <td>{{ number_format($row->oct ?? 0, 0) }}</td>
                            <td>{{ number_format($row->nov ?? 0, 0) }}</td>
                            <td>{{ number_format($row->dic ?? 0, 0) }}</td>
                            <td>{{ number_format($row->total ?? 0, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
