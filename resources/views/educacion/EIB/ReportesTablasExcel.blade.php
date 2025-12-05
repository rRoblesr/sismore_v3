@switch($div)
    @case('tabla1')
        <table id="tabla1" class="table table-striped table-bordered font-11">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center">FORMA DE ATENCIÓN</th>
                    <th class="text-center">SERVICIOS EDUCATIVOS</th>
                    <th class="text-center">ESTUDIANTES MATRICULADOS</th>
                    <th class="text-center">PERSONAL DOCENTE</th>
                    <th class="text-center">AUXILIAR DE EDUCACIÓN</th>
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
        <table id="tabla2" class="table table-striped table-bordered font-11">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center">NIVEL EDUCATIVO</th>
                    <th class="text-center">SERVICIOS EDUCATIVOS</th>
                    <th class="text-center">ESTUDIANTES MATRICULADOS</th>
                    <th class="text-center">PERSONAL DOCENTE</th>
                    <th class="text-center">AUXILIAR DE EDUCACIÓN</th>
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
        <table id="tabla3" class="table table-striped table-bordered font-11">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center">LENGUA</th>
                    <th class="text-center">SERVICIOS EDUCATIVOS</th>
                    <th class="text-center">ESTUDIANTES MATRICULADOS</th>
                    <th class="text-center">PERSONAL DOCENTE</th>
                    <th class="text-center">AUXILIAR DE EDUCACIÓN</th>
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
        <table id="tabla4" class="table table-striped table-bordered font-11">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center">N°</th>
                    <th class="text-center">Código Modular</th>
                    <th class="text-center">Código Local</th>
                    <th class="text-center">Institución Educativa</th>
                    <th class="text-center">Nivel Educativo</th>
                    <th class="text-center">Gestión</th>
                    <th class="text-center">Caracteristica</th>
                    <th class="text-center">Area Geografica</th>
                    <th class="text-center">Forma Atención</th>
                    <th class="text-center">Lengua Originaria</th>
                    <th class="text-center">Total Estudiantes</th>
                    <th class="text-center">Estudiantes Hombres</th>
                    <th class="text-center">Estudiantes Mujeres</th>
                    <th class="text-center">Total Docentes</th>
                    <th class="text-center">Nombrados</th>
                    <th class="text-center">Contratados</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $key => $item)
                    <tr class="text-center">
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td class="text-center">{{ $item->modular }}</td>
                        <td class="text-center">{{ $item->local }}</td>
                        <td class="text-left">{{ $item->nombre_ie }}</td>
                        <td class="text-left">{{ $item->nivel }}</td>
                        <td class="text-left">{{ $item->gestion }}</td>
                        <td class="text-left">{{ $item->caracteristica }}</td>
                        <td class="text-left">{{ $item->area }}</td>
                        <td class="text-left">{{ $item->forma_atencion }}</td>
                        <td class="text-left">{{ $item->lengua }}</td>
                        <td class="text-center">{{ $item->matriculados }}</td>
                        <td class="text-center">{{ $item->varon }}</td>
                        <td class="text-center">{{ $item->mujer }}</td>
                        <td class="text-center">{{ $item->docentes }}</td>
                        <td class="text-center">{{ $item->nombrado }}</td>
                        <td class="text-center">{{ $item->contratado }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @break

    @default
@endswitch
