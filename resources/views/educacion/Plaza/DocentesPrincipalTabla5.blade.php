{{-- <div class="table-responsive"> --}}
<table id="tabla5" class="table table-striped table-bordered mb-0 tablex" style="font-size:11px;" width="100%">
    <thead>
        <tr class="bg-primary text-white text-center">
            <th>Nº</th>
            <th>DOCUMENTO</th>
            <th>CODIGO PLAZA</th>
            <th>NIVEL</th>
            <th>CODIGO MODULAR</th>
            <th>INSTITUCION EDUCATIVA</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($body as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-left">{{ $item->dni }}</td>
                <td>{{ $item->plaza }}</td>
                <th>{{ $item->nivel }}</th>
                <td>{{ $item->modular }}</td>
                <td class="text-left">{{ $item->iiee }}</td>
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
