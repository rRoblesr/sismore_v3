{{-- <div class="table-responsive"> --}}
    <table id="tabla1" class="table table-striped table-bordered mb-0 tablex" style="font-size:11px;" width="100%">
        <thead>
            <tr class="bg-primary text-white text-center">
                <th>TIPO TRABAJADOR</th>
                <th colspan="3">ADMINISTRATIVO</th>
                <th colspan="3">DOCENTE</th>
                <th colspan="3">CAS</th>
                <th colspan="3">PEC</th>
                <th colspan="1">TOTAL</th>
            </tr>
            <tr class="bg-primary text-white text-center">
                <th>NIVEL_MODALIDAD</th>
                <th><span title="CONTRATADO">CONTRATADO</span></th>
                <th><span title="NOMBRADO">NOMBRADO</span></th>
                <th>TOTAL</th>
                <th><span title="CONTRATADO">CONTRATADO</span></th>
                <th><span title="NOMBRADO">NOMBRADO</span></th>
                <th>TOTAL</th>
                <th><span title="CONTRATADO">CONTRATADO</span></th>
                <th><span title="NOMBRADO">NOMBRADO</span></th>
                <th>TOTAL</th>
                <th><span title="CONTRATADO">CONTRATADO</span></th>
                <th><span title="NOMBRADO">NOMBRADO</span></th>
                <th>TOTAL</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach ($heads as $head)
                <tr class="text-center">
                    <th class="text-left">{{ $head->ugel }}</th>
                    <th>{{ number_format($head->ACONTRATADO, 0) }}</th>
                    <th>{{ number_format($head->ANOMBRADO, 0) }}</th>
                    <th>{{ number_format($head->ADMINISTRATIVO, 0) }}</th>
                    <th>{{ number_format($head->DCONTRATADO, 0) }}</th>
                    <th>{{ number_format($head->DNOMBRADO, 0) }}</th>
                    <th>{{ number_format($head->DOCENTE, 0) }}</th>
                    <th>{{ number_format($head->CCONTRATADO, 0) }}</th>
                    <th>{{ number_format($head->CNOMBRADO, 0) }}</th>
                    <th>{{ number_format($head->CAS, 0) }}</th>
                    <th>{{ number_format($head->PCONTRATADO, 0) }}</th>
                    <th>{{ number_format($head->PNOMBRADO, 0) }}</th>
                    <th>{{ number_format($head->PEC, 0) }}</th>
                    <th>{{ number_format($head->TOTAL, 0) }}</th>
                </tr> --}}
                @foreach ($bodys as $body)
                    {{-- @if ($body->ugel == $head->ugel) --}}
                        <tr class="text-center">
                            <td class="text-left">{{ $body->nivel }}</td>
                            <td>{{ valor($body->ACONTRATADO) }}</td>
                            <td>{{ valor($body->ANOMBRADO) }}</td>
                            <th>{{ valor($body->ADMINISTRATIVO) }}</th>
                            <td>{{ valor($body->DCONTRATADO) }}</td>
                            <td>{{ valor($body->DNOMBRADO) }}</td>
                            <th>{{ valor($body->DOCENTE) }}</th>
                            <td>{{ valor($body->CCONTRATADO) }}</td>
                            <td>{{ valor($body->CNOMBRADO) }}</td>
                            <th>{{ valor($body->CAS) }}</th>
                            <td>{{ valor($body->PCONTRATADO) }}</td>
                            <td>{{ valor($body->PNOMBRADO) }}</td>
                            <th>{{ valor($body->PEC) }}</th>
                            <th>{{ valor($body->TOTAL) }}</th>
                        </tr>
                    {{-- @endif --}}
                @endforeach
            {{-- @endforeach --}}
        </tbody>
        <tfoot>
            <tr class="text-center bg-primary text-white">
                <th width="200" class="text-left">TOTAL</th>
                <th>{{ number_format($foot->ACONTRATADO, 0) }}</th>
                <th>{{ number_format($foot->ANOMBRADO, 0) }}</th>
                <th>{{ number_format($foot->ADMINISTRATIVO, 0) }}</th>
                <th>{{ number_format($foot->DCONTRATADO, 0) }}</th>
                <th>{{ number_format($foot->DNOMBRADO, 0) }}</th>
                <th>{{ number_format($foot->DOCENTE, 0) }}</th>
                <th>{{ number_format($foot->CCONTRATADO, 0) }}</th>
                <th>{{ number_format($foot->CNOMBRADO, 0) }}</th>
                <th>{{ number_format($foot->CAS, 0) }}</th>
                <th>{{ number_format($foot->PCONTRATADO, 0) }}</th>
                <th>{{ number_format($foot->PNOMBRADO, 0) }}</th>
                <th>{{ number_format($foot->PEC, 0) }}</th>
                <th>{{ number_format($foot->TOTAL, 0) }}</th>
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

    {{-- <tbody>
            @foreach ($heads as $head)
                <tr class="text-center">
                    <th class="text-left">{{ $head->ugel }}</th>
                    <th>{{ number_format($head->ACONTRATADO, 0) }}</th>
                    <th>{{ number_format($head->ANOMBRADO, 0) }}</th>
                    <th>{{ number_format($head->ADMINISTRATIVO, 0) }}</th>
                    <th>{{ number_format($head->DCONTRATADO, 0) }}</th>
                    <th>{{ number_format($head->DNOMBRADO, 0) }}</th>
                    <th>{{ number_format($head->DOCENTE, 0) }}</th>
                    <th>{{ number_format($head->CCONTRATADO, 0) }}</th>
                    <th>{{ number_format($head->CNOMBRADO, 0) }}</th>
                    <th>{{ number_format($head->CAS, 0) }}</th>
                    <th>{{ number_format($head->PCONTRATADO, 0) }}</th>
                    <th>{{ number_format($head->PNOMBRADO, 0) }}</th>
                    <th>{{ number_format($head->PEC, 0) }}</th>
                    <th>{{ number_format($head->TOTAL, 0) }}</th>
                </tr>
                @foreach ($bodys as $body)
                    @if ($body->ugel == $head->ugel)
                        <tr class="text-center">
                            <td class="text-left">{{ $body->nivel }}</td>
                            <td>{{ number_format($body->ACONTRATADO, 0) }}</td>
                            <td>{{ number_format($body->ANOMBRADO, 0) }}</td>
                            <td>{{ number_format($body->ADMINISTRATIVO, 0) }}</td>
                            <td>{{ number_format($body->DCONTRATADO, 0) }}</td>
                            <td>{{ number_format($body->DNOMBRADO, 0) }}</td>
                            <td>{{ number_format($body->DOCENTE, 0) }}</td>
                            <td>{{ number_format($body->CCONTRATADO, 0) }}</td>
                            <td>{{ number_format($body->CNOMBRADO, 0) }}</td>
                            <td>{{ number_format($body->CAS, 0) }}</td>
                            <td>{{ number_format($body->PCONTRATADO, 0) }}</td>
                            <td>{{ number_format($body->PNOMBRADO, 0) }}</td>
                            <td>{{ number_format($body->PEC, 0) }}</td>
                            <td>{{ number_format($body->TOTAL, 0) }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center">
                <th width="200" class="text-left">TOTAL</th>
                <th>{{ number_format($foot->ACONTRATADO, 0) }}</th>
                <th>{{ number_format($foot->ANOMBRADO, 0) }}</th>
                <th>{{ number_format($foot->ADMINISTRATIVO, 0) }}</th>
                <th>{{ number_format($foot->DCONTRATADO, 0) }}</th>
                <th>{{ number_format($foot->DNOMBRADO, 0) }}</th>
                <th>{{ number_format($foot->DOCENTE, 0) }}</th>
                <th>{{ number_format($foot->CCONTRATADO, 0) }}</th>
                <th>{{ number_format($foot->CNOMBRADO, 0) }}</th>
                <th>{{ number_format($foot->CAS, 0) }}</th>
                <th>{{ number_format($foot->PCONTRATADO, 0) }}</th>
                <th>{{ number_format($foot->PNOMBRADO, 0) }}</th>
                <th>{{ number_format($foot->PEC, 0) }}</th>
                <th>{{ number_format($foot->TOTAL, 0) }}</th>
            </tr>
        </tfoot> --}}
{{-- </div> --}}
