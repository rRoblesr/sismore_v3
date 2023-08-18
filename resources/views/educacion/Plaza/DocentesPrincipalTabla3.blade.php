{{-- <div class="table-responsive"> --}}
<table id="tabla1" class="table table-striped table-bordered mb-0 tablex" style="font-size:11px;" width="100%">
    <thead>
        <tr class="bg-primary text-white text-center">
            <th>UGEL</th>
            <th colspan="1">CONTRATADO</th>
            <th colspan="1">DESIG.CONFIAN.</th>
            <th colspan="1">DESIG.EXCEP.</th>
            <th colspan="1">DESIGNADO</th>
            <th colspan="1">DESTACADO</th>
            <th colspan="1">ENCARGADO</th>
            <th colspan="1">NOMBRADO</th>
            <th colspan="1">VACANTE</th>
            <th colspan="1">TOTAL</th>
        </tr>
    </thead>
    <tbody>
        {{-- @foreach ($heads as $head)
            <tr class="text-center">
                <th class="text-left">{{ $head->tipo }}</th>
                <th>{{ number_format($head->dre, 0) }}</th>
                <th>{{ number_format($head->atalaya, 0) }}</th>
                <th>{{ number_format($head->portillo, 0) }}</th>
                <th>{{ number_format($head->abad, 0) }}</th>
                <th>{{ number_format($head->purus, 0) }}</th>
                <th>{{ number_format($head->total, 0) }}</th>
            </tr> --}}
        @foreach ($bodys as $body)
            {{-- @if ($body->tipo == $head->tipo) --}}
            <tr class="text-center">
                <td class="text-left">{{ $body->ugel }}</td>
                <td>{{ valor($body->contratado) }}</td>
                <td>{{ valor($body->desigconfian) }}</td>
                <th>{{ valor($body->desigexcep) }}</th>
                <td>{{ valor($body->designado) }}</td>
                <td>{{ valor($body->destacado) }}</td>
                <td>{{ valor($body->encargado) }}</td>
                <td>{{ valor($body->nombrado) }}</td>
                <td>{{ valor($body->vacante) }}</td>
                <th>{{ valor($body->total) }}</th>
            </tr>
            {{-- @endif --}}
        @endforeach
        {{-- @endforeach --}}
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th width="200" class="text-left">TOTAL</th>
            <th>{{ number_format($foot->contratado, 0) }}</th>
            <th>{{ number_format($foot->desigconfian, 0) }}</th>
            <th>{{ number_format($foot->desigexcep, 0) }}</th>
            <th>{{ number_format($foot->designado, 0) }}</th>
            <th>{{ number_format($foot->destacado, 0) }}</th>
            <th>{{ number_format($foot->encargado, 0) }}</th>
            <th>{{ number_format($foot->nombrado, 0) }}</th>
            <th>{{ number_format($foot->vacante, 0) }}</th>
            <th>{{ number_format($foot->total, 0) }}</th>
        </tr>
    </tfoot>
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
