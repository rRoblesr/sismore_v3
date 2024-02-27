{{-- <div class="table-responsive"> --}}
<table id="tabla5" class="table table-striped table-bordered mb-0 tablex" style="font-size:11px;" width="100%">
    <thead>
        <tr class="bg-primary text-white text-center">
            <th>Nº</th>
            <th>TOTAL</th>
            <th>CON TITULO</th>
            <th>SIN TITULO</th>
            <th>NO CONCLUIDO</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($body as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->tt }}</td>
                <th>{{ $item->t1 }}</th>
                <td>{{ $item->t2 }}</td>
                <td>{{ $item->t3 }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@php
    function valor($v)
    {
        if ($v == 0) {
            return '';
        } else {
            return number_format($v, 0);
        }
    }
@endphp
