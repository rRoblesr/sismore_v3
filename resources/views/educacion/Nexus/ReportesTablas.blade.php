@switch($div)
    @case('tabla1')
        <table id="tabla1" class="table table-sm table-striped table-bordered font-11">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center" rowspan="2">UGEL</th>
                    <th class="text-center" colspan="6">PLAZAS DOCENTE</th>
                    <th class="text-center" colspan="4">PLAZAS AUXILIARES DE EDUCACIÓN</th>
                    <th class="text-center" colspan="1">PEC</th>
                </tr>
                <tr class="table-success-0 text-white">
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">NOMBRADO</th>
                    <th class="text-center">CONTRATADO</th>
                    <th class="text-center">ENCARGADO</th>
                    <th class="text-center">DESIGNADO</th>
                    <th class="text-center">VACANTE</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">NOMBRADO</th>
                    <th class="text-center">CONTRATADO</th>
                    <th class="text-center">VACANTE</th>
                    <th class="text-center">CONTRATADO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $key => $item)
                    <tr class="text-center">
                        <td class="text-left">{{ $item->ugel }}</td>
                        <td class="table-warning">{{ number_format($item->td, 0) }}</td>
                        <td>{{ number_format($item->tdn, 0) }}</td>
                        <td>{{ number_format($item->tdc, 0) }}</td>
                        <td>{{ number_format($item->tde, 0) }}</td>
                        <td>{{ number_format($item->tdd, 0) }}</td>
                        <td>{{ number_format($item->tdv, 0) }}</td>
                        <td class="table-warning">{{ number_format($item->ta, 0) }}</td>
                        <td>{{ number_format($item->tan, 0) }}</td>
                        <td>{{ number_format($item->tac, 0) }}</td>
                        <td>{{ number_format($item->tav, 0) }}</td>
                        <td>{{ number_format($item->tpc, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center table-success-0 text-white">
                    <th class="text-left">TOTAL</th>
                    <th>{{ number_format($foot->td, 0) }}</th>
                    <th>{{ number_format($foot->tdn, 0) }}</th>
                    <th>{{ number_format($foot->tdc, 0) }}</th>
                    <th>{{ number_format($foot->tde, 0) }}</th>
                    <th>{{ number_format($foot->tdd, 0) }}</th>
                    <th>{{ number_format($foot->tdv, 0) }}</th>
                    <th>{{ number_format($foot->ta, 0) }}</th>
                    <th>{{ number_format($foot->tan, 0) }}</th>
                    <th>{{ number_format($foot->tac, 0) }}</th>
                    <th>{{ number_format($foot->tav, 0) }}</th>
                    <th>{{ number_format($foot->tpc, 0) }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla2')
        <table id="tabla2" class="table table-sm table-striped table-bordered font-11">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center" rowspan="2">LEY</th>
                    <th class="text-center" colspan="6">PLAZAS DOCENTE</th>
                    <th class="text-center" colspan="4">PLAZAS AUXILIARES DE EDUCACIÓN</th>
                    <th class="text-center" colspan="1">PEC</th>
                </tr>
                <tr class="table-success-0 text-white">
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">NOMBRADO</th>
                    <th class="text-center">CONTRATADO</th>
                    <th class="text-center">ENCARGADO</th>
                    <th class="text-center">DESIGNADO</th>
                    <th class="text-center">VACANTE</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">NOMBRADO</th>
                    <th class="text-center">CONTRATADO</th>
                    <th class="text-center">VACANTE</th>
                    <th class="text-center">CONTRATADO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $key => $item)
                    <tr class="text-center">
                        <td class="text-left">{{ $item->ley }}</td>
                        <td class="table-warning">{{ number_format($item->td, 0) }}</td>
                        <td>{{ number_format($item->tdn, 0) }}</td>
                        <td>{{ number_format($item->tdc, 0) }}</td>
                        <td>{{ number_format($item->tde, 0) }}</td>
                        <td>{{ number_format($item->tdd, 0) }}</td>
                        <td>{{ number_format($item->tdv, 0) }}</td>
                        <td class="table-warning">{{ number_format($item->ta, 0) }}</td>
                        <td>{{ number_format($item->tan, 0) }}</td>
                        <td>{{ number_format($item->tac, 0) }}</td>
                        <td>{{ number_format($item->tav, 0) }}</td>
                        <td>{{ number_format($item->tpc, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center table-success-0 text-white">
                    <th class="text-left">TOTAL</th>
                    <th>{{ number_format($foot->td, 0) }}</th>
                    <th>{{ number_format($foot->tdn, 0) }}</th>
                    <th>{{ number_format($foot->tdc, 0) }}</th>
                    <th>{{ number_format($foot->tde, 0) }}</th>
                    <th>{{ number_format($foot->tdd, 0) }}</th>
                    <th>{{ number_format($foot->tdv, 0) }}</th>
                    <th>{{ number_format($foot->ta, 0) }}</th>
                    <th>{{ number_format($foot->tan, 0) }}</th>
                    <th>{{ number_format($foot->tac, 0) }}</th>
                    <th>{{ number_format($foot->tav, 0) }}</th>
                    <th>{{ number_format($foot->tpc, 0) }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla3')
        <table id="tabla3" class="table table-sm table-striped table-bordered font-11">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center" rowspan="2">DISTRITO</th>
                    <th class="text-center" colspan="6">PLAZAS DOCENTE</th>
                    <th class="text-center" colspan="4">PLAZAS AUXILIARES DE EDUCACIÓN</th>
                    <th class="text-center" colspan="1">PEC</th>
                </tr>
                <tr class="table-success-0 text-white">
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">NOMBRADO</th>
                    <th class="text-center">CONTRATADO</th>
                    <th class="text-center">ENCARGADO</th>
                    <th class="text-center">DESIGNADO</th>
                    <th class="text-center">VACANTE</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center">NOMBRADO</th>
                    <th class="text-center">CONTRATADO</th>
                    <th class="text-center">VACANTE</th>
                    <th class="text-center">CONTRATADO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $key => $item)
                    <tr class="text-center">
                        <td class="text-left">{{ $item->distrito }}</td>
                        <td class="table-warning">{{ number_format($item->td, 0) }}</td>
                        <td>{{ number_format($item->tdn, 0) }}</td>
                        <td>{{ number_format($item->tdc, 0) }}</td>
                        <td>{{ number_format($item->tde, 0) }}</td>
                        <td>{{ number_format($item->tdd, 0) }}</td>
                        <td>{{ number_format($item->tdv, 0) }}</td>
                        <td class="table-warning">{{ number_format($item->ta, 0) }}</td>
                        <td>{{ number_format($item->tan, 0) }}</td>
                        <td>{{ number_format($item->tac, 0) }}</td>
                        <td>{{ number_format($item->tav, 0) }}</td>
                        <td>{{ number_format($item->tpc, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center table-success-0 text-white">
                    <th class="text-left">TOTAL</th>
                    <th>{{ number_format($foot->td, 0) }}</th>
                    <th>{{ number_format($foot->tdn, 0) }}</th>
                    <th>{{ number_format($foot->tdc, 0) }}</th>
                    <th>{{ number_format($foot->tde, 0) }}</th>
                    <th>{{ number_format($foot->tdd, 0) }}</th>
                    <th>{{ number_format($foot->tdv, 0) }}</th>
                    <th>{{ number_format($foot->ta, 0) }}</th>
                    <th>{{ number_format($foot->tan, 0) }}</th>
                    <th>{{ number_format($foot->tac, 0) }}</th>
                    <th>{{ number_format($foot->tav, 0) }}</th>
                    <th>{{ number_format($foot->tpc, 0) }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla4')
        <table id="tabla4" class="table table-sm table-striped table-bordered font-11">
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
