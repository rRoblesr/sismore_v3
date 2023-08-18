{{-- <div class="table-responsive"> --}}
<table id="tabla1" class="table table-striped table-bordered mb-0 tablex" style="font-size:11px;" width="100%">
    <thead>
        <tr class="bg-primary text-white text-center">
            <th>TIPO TRABAJADOR</th>
            <th colspan="1">DRE UCAYALI</th>
            <th colspan="1">UGEL ATALAYA</th>
            <th colspan="1">UGEL CORONEL PORTILLO</th>
            <th colspan="1">UGEL PADRE ABAD</th>
            <th colspan="1">UGEL PURUS</th>
            <th colspan="1">TOTAL</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($heads as $head)
            <tr class="text-center table-warning">
                <th class="text-left">{{ $head->tipo }}</th>
                <th>{{ number_format($head->dre, 0) }}</th>
                <th>{{ number_format($head->atalaya, 0) }}</th>
                <th>{{ number_format($head->portillo, 0) }}</th>
                <th>{{ number_format($head->abad, 0) }}</th>
                <th>{{ number_format($head->purus, 0) }}</th>
                <th>{{ number_format($head->total, 0) }}</th>
            </tr>
            @foreach ($bodys as $body)
                @if ($body->tipo == $head->tipo)
                    <tr class="text-center">
                        <td class="text-left">&nbsp;&nbsp;&nbsp;&nbsp;{{ $body->subtipo=='ESPECIALISTAS ADMINISTRATIVOS E INSTITUCIONALES DE LAS UGEL'?'ESPECIALISTAS ADMINISTRATIVOS':$body->subtipo }}</td>
                        <td>{{ valor($body->dre) }}</td>
                        <td>{{ valor($body->atalaya) }}</td>
                        <th>{{ valor($body->portillo) }}</th>
                        <td>{{ valor($body->abad) }}</td>
                        <td>{{ valor($body->purus) }}</td>
                        <th>{{ valor($body->total) }}</th>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr class="text-center bg-primary text-white">
            <th width="200" class="text-left">TOTAL</th>
            <th>{{ number_format($foot->dre, 0) }}</th>
            <th>{{ number_format($foot->atalaya, 0) }}</th>
            <th>{{ number_format($foot->portillo, 0) }}</th>
            <th>{{ number_format($foot->abad, 0) }}</th>
            <th>{{ number_format($foot->purus, 0) }}</th>
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
