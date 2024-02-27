<table id="tabla1" class="table table-striped table-bordered table-sm font-11">
    <thead>
        <tr class="bg-success-0 text-white text-center">
            <th>Fuente Financiamiento</th>
            <th>2014</th>
            <th>2015</th>
            <th>2016</th>
            <th>2017</th>
            <th>2018</th>
            <th>2019</th>
            <th>2020</th>
            <th>2021</th>
            <th>2022</th>
        </tr>

    </thead>
    <tbody>
        @foreach ($body as $item)
            <tr class="text-right">
                <td class="text-left">{{ $item->cod.' '.$item->ff }}</td>
                <td>{{ number_format($item->pim_2014, 0) }}</td>
                <td>{{ number_format($item->pim_2015, 0) }}</td>
                <td>{{ number_format($item->pim_2016, 0) }}</td>
                <td>{{ number_format($item->pim_2017, 0) }}</td>
                <td>{{ number_format($item->pim_2018, 0) }}</td>
                <td>{{ number_format($item->pim_2019, 0) }}</td>
                <td>{{ number_format($item->pim_2020, 0) }}</td>
                <td>{{ number_format($item->pim_2021, 0) }}</td>
                <td>{{ number_format($item->pim_2022, 0) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-success-0 text-white">
            <th class="text-left">TOTAL</th>
            <th>{{ number_format($foot['pim_2014'], 0) }}</th>
            <th>{{ number_format($foot['pim_2015'], 0) }}</th>
            <th>{{ number_format($foot['pim_2016'], 0) }}</th>
            <th>{{ number_format($foot['pim_2017'], 0) }}</th>
            <th>{{ number_format($foot['pim_2018'], 0) }}</th>
            <th>{{ number_format($foot['pim_2019'], 0) }}</th>
            <th>{{ number_format($foot['pim_2020'], 0) }}</th>
            <th>{{ number_format($foot['pim_2021'], 0) }}</th>
            <th>{{ number_format($foot['pim_2022'], 0) }}</th>
        </tr>
    </tfoot>
</table>



@php
/* function avance($monto)
{
    if ($monto < 51) {
        return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    } elseif ($monto < 100) {
        return '<span class="badge badge-pill badge-warning" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    } else {
        return '<span class="badge badge-pill badge-success" style="font-size:90%;">' . round($monto, 1) . '%</span>';
    }
} */
@endphp
