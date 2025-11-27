@switch($div)
    @case('tabla1')
        <table id="tabla1" class="table table-striped table-bordered font-12">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center" rowspan="2">FORMA DE ATENCIÓN</th>
                    <th class="text-center" colspan="3">SERVICIOS EDUCATIVOS</th>
                    <th class="text-center" colspan="3">ESTUDIANTES MATRICULADOS</th>
                    <th class="text-center" colspan="3">PERSONAL DOCENTE</th>
                    <th class="text-center" colspan="3">AUXILIAR DE EDUCACIÓN</th>
                    <th class="text-center" colspan="1">PEC</th>
                </tr>
                <tr class="table-success-0 text-white">
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">RURAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $item)
                    <tr class="text-center">
                        <td class="text-left">{{ $item->forma_atencion }}</td>
                        <!-- SERVICIOS EDUCATIVOS -->
                        <td class="table-warning">{{ number_format($item->ts, 0) }}</td>
                        <td>{{ number_format($item->tsr, 0) }}</td>
                        <td>{{ number_format($item->tsu, 0) }}</td>
                        <!-- ESTUDIANTES MATRICULADOS -->
                        <td class="table-warning">{{ number_format($item->tm, 0) }}</td>
                        <td>{{ number_format($item->tmr, 0) }}</td>
                        <td>{{ number_format($item->tmu, 0) }}</td>
                        <!-- PERSONAL DOCENTE -->
                        <td class="table-warning">{{ number_format($item->td, 0) }}</td>
                        <td>{{ number_format($item->tdr, 0) }}</td>
                        <td>{{ number_format($item->tdu, 0) }}</td>
                        <!-- AUXILIAR DE EDUCACIÓN -->
                        <td class="table-warning">{{ number_format($item->ta, 0) }}</td>
                        <td>{{ number_format($item->tar, 0) }}</td>
                        <td>{{ number_format($item->tau, 0) }}</td>
                        <!-- PEC -->
                        <td>{{ number_format($item->tpr, 0) }}</td> <!-- Solo rural, según tu ejemplo -->
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center table-success-0 text-white">
                    <th class="text-left">TOTAL</th>
                    <!-- SERVICIOS EDUCATIVOS -->
                    <th>{{ number_format($foot->ts, 0) }}</th>
                    <th>{{ number_format($foot->tsr, 0) }}</th>
                    <th>{{ number_format($foot->tsu, 0) }}</th>
                    <!-- ESTUDIANTES MATRICULADOS -->
                    <th>{{ number_format($foot->tm, 0) }}</th>
                    <th>{{ number_format($foot->tmr, 0) }}</th>
                    <th>{{ number_format($foot->tmu, 0) }}</th>
                    <!-- PERSONAL DOCENTE -->
                    <th>{{ number_format($foot->td, 0) }}</th>
                    <th>{{ number_format($foot->tdr, 0) }}</th>
                    <th>{{ number_format($foot->tdu, 0) }}</th>
                    <!-- AUXILIAR DE EDUCACIÓN -->
                    <th>{{ number_format($foot->ta, 0) }}</th>
                    <th>{{ number_format($foot->tar, 0) }}</th>
                    <th>{{ number_format($foot->tau, 0) }}</th>
                    <!-- PEC -->
                    <th>{{ number_format($foot->tpr, 0) }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla2')
        <table id="tabla2" class="table table-striped table-bordered font-12">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center" rowspan="2">NIVEL EDUCATIVO</th>
                    <th class="text-center" colspan="3">SERVICIOS EDUCATIVOS</th>
                    <th class="text-center" colspan="3">ESTUDIANTES MATRICULADOS</th>
                    <th class="text-center" colspan="3">PERSONAL DOCENTE</th>
                    <th class="text-center" colspan="3">AUXILIAR DE EDUCACIÓN</th>
                    <th class="text-center" colspan="1">PEC</th>
                </tr>
                <tr class="table-success-0 text-white">
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">RURAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $item)
                    <tr class="text-center">
                        <td class="text-left">{{ $item->nivel_modalidad }}</td>
                        <!-- SERVICIOS EDUCATIVOS -->
                        <td class="table-warning">{{ number_format($item->ts, 0) }}</td>
                        <td>{{ number_format($item->tsr, 0) }}</td>
                        <td>{{ number_format($item->tsu, 0) }}</td>
                        <!-- ESTUDIANTES MATRICULADOS -->
                        <td class="table-warning">{{ number_format($item->tm, 0) }}</td>
                        <td>{{ number_format($item->tmr, 0) }}</td>
                        <td>{{ number_format($item->tmu, 0) }}</td>
                        <!-- PERSONAL DOCENTE -->
                        <td class="table-warning">{{ number_format($item->td, 0) }}</td>
                        <td>{{ number_format($item->tdr, 0) }}</td>
                        <td>{{ number_format($item->tdu, 0) }}</td>
                        <!-- AUXILIAR DE EDUCACIÓN -->
                        <td class="table-warning">{{ number_format($item->ta, 0) }}</td>
                        <td>{{ number_format($item->tar, 0) }}</td>
                        <td>{{ number_format($item->tau, 0) }}</td>
                        <!-- PEC -->
                        <td>{{ number_format($item->tpr, 0) }}</td> <!-- Solo rural, según tu ejemplo -->
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center table-success-0 text-white">
                    <th class="text-left">TOTAL</th>
                    <!-- SERVICIOS EDUCATIVOS -->
                    <th>{{ number_format($foot->ts, 0) }}</th>
                    <th>{{ number_format($foot->tsr, 0) }}</th>
                    <th>{{ number_format($foot->tsu, 0) }}</th>
                    <!-- ESTUDIANTES MATRICULADOS -->
                    <th>{{ number_format($foot->tm, 0) }}</th>
                    <th>{{ number_format($foot->tmr, 0) }}</th>
                    <th>{{ number_format($foot->tmu, 0) }}</th>
                    <!-- PERSONAL DOCENTE -->
                    <th>{{ number_format($foot->td, 0) }}</th>
                    <th>{{ number_format($foot->tdr, 0) }}</th>
                    <th>{{ number_format($foot->tdu, 0) }}</th>
                    <!-- AUXILIAR DE EDUCACIÓN -->
                    <th>{{ number_format($foot->ta, 0) }}</th>
                    <th>{{ number_format($foot->tar, 0) }}</th>
                    <th>{{ number_format($foot->tau, 0) }}</th>
                    <!-- PEC -->
                    <th>{{ number_format($foot->tpr, 0) }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla3')
        <table id="tabla3" class="table table-striped table-bordered font-12">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center" rowspan="2">LENGUA</th>
                    <th class="text-center" colspan="3">SERVICIOS EDUCATIVOS</th>
                    <th class="text-center" colspan="3">ESTUDIANTES MATRICULADOS</th>
                    <th class="text-center" colspan="3">PERSONAL DOCENTE</th>
                    <th class="text-center" colspan="3">AUXILIAR DE EDUCACIÓN</th>
                    <th class="text-center" colspan="1">PEC</th>
                </tr>
                <tr class="table-success-0 text-white">
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">RURAL</th>
                    <th class="text-center">URBANO</th>
                    <th class="text-center">RURAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $item)
                    <tr class="text-center">
                        <td class="text-left">{{ $item->lengua }}</td>
                        <!-- SERVICIOS EDUCATIVOS -->
                        <td class="table-warning">{{ number_format($item->ts, 0) }}</td>
                        <td>{{ number_format($item->tsr, 0) }}</td>
                        <td>{{ number_format($item->tsu, 0) }}</td>
                        <!-- ESTUDIANTES MATRICULADOS -->
                        <td class="table-warning">{{ number_format($item->tm, 0) }}</td>
                        <td>{{ number_format($item->tmr, 0) }}</td>
                        <td>{{ number_format($item->tmu, 0) }}</td>
                        <!-- PERSONAL DOCENTE -->
                        <td class="table-warning">{{ number_format($item->td, 0) }}</td>
                        <td>{{ number_format($item->tdr, 0) }}</td>
                        <td>{{ number_format($item->tdu, 0) }}</td>
                        <!-- AUXILIAR DE EDUCACIÓN -->
                        <td class="table-warning">{{ number_format($item->ta, 0) }}</td>
                        <td>{{ number_format($item->tar, 0) }}</td>
                        <td>{{ number_format($item->tau, 0) }}</td>
                        <!-- PEC -->
                        <td>{{ number_format($item->tpr, 0) }}</td> <!-- Solo rural, según tu ejemplo -->
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center table-success-0 text-white">
                    <th class="text-left">TOTAL</th>
                    <!-- SERVICIOS EDUCATIVOS -->
                    <th>{{ number_format($foot->ts, 0) }}</th>
                    <th>{{ number_format($foot->tsr, 0) }}</th>
                    <th>{{ number_format($foot->tsu, 0) }}</th>
                    <!-- ESTUDIANTES MATRICULADOS -->
                    <th>{{ number_format($foot->tm, 0) }}</th>
                    <th>{{ number_format($foot->tmr, 0) }}</th>
                    <th>{{ number_format($foot->tmu, 0) }}</th>
                    <!-- PERSONAL DOCENTE -->
                    <th>{{ number_format($foot->td, 0) }}</th>
                    <th>{{ number_format($foot->tdr, 0) }}</th>
                    <th>{{ number_format($foot->tdu, 0) }}</th>
                    <!-- AUXILIAR DE EDUCACIÓN -->
                    <th>{{ number_format($foot->ta, 0) }}</th>
                    <th>{{ number_format($foot->tar, 0) }}</th>
                    <th>{{ number_format($foot->tau, 0) }}</th>
                    <!-- PEC -->
                    <th>{{ number_format($foot->tpr, 0) }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla4')
        <table id="tabla4" class="table table-striped table-bordered font-12">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center">N°</th>
                    <th class="text-center">Código Modular</th>
                    <th class="text-center">Institución Educativa</th>
                    <th class="text-center">Tipo de Institución</th>
                    <th class="text-center">Nivel Educativo</th>
                    <th class="text-center">Gestión</th>
                    <th class="text-center">Zona</th>
                    <th class="text-center">Distrito</th>
                    <th class="text-center">Total Plazas</th>
                    <th class="text-center">Docente</th>
                    <th class="text-center">Auxiliar de Educación</th>
                    <th class="text-center">Promotores Educativos</th>
                    <th class="text-center">Personal Administrativo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $key => $item)
                    <tr class="text-center">
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $item->modular }}</td>
                        <td class="text-left">{{ $item->iiee }}</td>
                        <td class="text-left">{{ $item->tipo }}</td>
                        <td class="text-left">{{ $item->nivel }}</td>
                        <td class="text-left">{{ $item->gestion }}</td>
                        <td class="text-left">{{ $item->zona }}</td>
                        <td class="text-left">{{ $item->distrito }}</td>
                        <td class="text-center">{{ $item->conteo }}</td>
                        <td class="text-center">{{ $item->docentes }}</td>
                        <td class="text-center">{{ $item->auxiliar }}</td>
                        <td class="text-center">{{ $item->promotor }}</td>
                        <td class="text-center">{{ $item->administrativo }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-success-0 text-white">
                    <!-- Mismo número de <th> que columnas -->
                    <th class="text-right" colspan="8">TOTAL</th>
                    <th class="text-center"></th> <!-- col 9 -->
                    <th class="text-center"></th> <!-- col 10 -->
                    <th class="text-center"></th> <!-- col 11 -->
                    <th class="text-center"></th> <!-- col 14 -->
                    <th class="text-center"></th> <!-- col 15 -->
                </tr>
            </tfoot>
        </table>
    @break

    @default
@endswitch
