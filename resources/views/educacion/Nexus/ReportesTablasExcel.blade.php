@php ob_clean(); @endphp
@switch($div)
    @case('tabla1')
        <table id="tabla1" border="1" cellpadding="3" cellspacing="0" style="font-size:11px;border-collapse:collapse;">
            <thead>
                <tr>
                    <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">UGEL</th>
                    <th colspan="4" style="background-color:#317eeb;color:#ffffff;text-align:center;">PLAZAS DOCENTE</th>
                    <th colspan="4" style="background-color:#317eeb;color:#ffffff;text-align:center;">PLAZAS AUXILIARES DE EDUCACION</th>
                    <th colspan="5" style="background-color:#317eeb;color:#ffffff;text-align:center;">PLAZAS ADMINISTRATIVAS</th>
                    <th colspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">PEC</th>
                </tr>
                <tr>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">NOMBRADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">VACANTE</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">NOMBRADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">VACANTE</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">NOMBRADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">DESIGNADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">VACANTE</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $key => $item)
                    <tr>
                        <td style="text-align:left;">{{ $item->ugel }}</td>
                        <td style="text-align:right;background-color:#fff2cc;">{{ $item->td }}</td>
                        <td style="text-align:right;">{{ $item->tdn }}</td>
                        <td style="text-align:right;">{{ $item->tdc }}</td>
                        <td style="text-align:right;">{{ $item->tdv }}</td>
                        <td style="text-align:right;background-color:#fff2cc;">{{ $item->ta }}</td>
                        <td style="text-align:right;">{{ $item->tan }}</td>
                        <td style="text-align:right;">{{ $item->tac }}</td>
                        <td style="text-align:right;">{{ $item->tav }}</td>
                        <td style="text-align:right;background-color:#fff2cc;">{{ $item->tad }}</td>
                        <td style="text-align:right;">{{ $item->tadn }}</td>
                        <td style="text-align:right;">{{ $item->tadc }}</td>
                        <td style="text-align:right;">{{ $item->tadd }}</td>
                        <td style="text-align:right;">{{ $item->tadv }}</td>
                        <td style="text-align:right;">{{ $item->tpc }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:left;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->td }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tdn }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tdc }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tdv }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->ta }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tan }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tac }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tav }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tad }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadn }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadc }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadd }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadv }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tpc }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla2')
        <table id="tabla2" border="1" cellpadding="3" cellspacing="0" style="font-size:11px;border-collapse:collapse;">
            <thead>
                <tr>
                    <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">LEY</th>
                    <th colspan="4" style="background-color:#317eeb;color:#ffffff;text-align:center;">PLAZAS DOCENTE</th>
                    <th colspan="4" style="background-color:#317eeb;color:#ffffff;text-align:center;">PLAZAS AUXILIARES DE EDUCACION</th>
                    <th colspan="5" style="background-color:#317eeb;color:#ffffff;text-align:center;">PLAZAS ADMINISTRATIVAS</th>
                    <th colspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">PEC</th>
                </tr>
                <tr>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">NOMBRADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">VACANTE</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">NOMBRADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">VACANTE</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">NOMBRADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">DESIGNADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">VACANTE</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $key => $item)
                    <tr>
                        <td style="text-align:left;">{{ $item->ley }}</td>
                        <td style="text-align:right;background-color:#fff2cc;">{{ $item->td }}</td>
                        <td style="text-align:right;">{{ $item->tdn }}</td>
                        <td style="text-align:right;">{{ $item->tdc }}</td>
                        <td style="text-align:right;">{{ $item->tdv }}</td>
                        <td style="text-align:right;background-color:#fff2cc;">{{ $item->ta }}</td>
                        <td style="text-align:right;">{{ $item->tan }}</td>
                        <td style="text-align:right;">{{ $item->tac }}</td>
                        <td style="text-align:right;">{{ $item->tav }}</td>
                        <td style="text-align:right;background-color:#fff2cc;">{{ $item->tad }}</td>
                        <td style="text-align:right;">{{ $item->tadn }}</td>
                        <td style="text-align:right;">{{ $item->tadc }}</td>
                        <td style="text-align:right;">{{ $item->tadd }}</td>
                        <td style="text-align:right;">{{ $item->tadv }}</td>
                        <td style="text-align:right;">{{ $item->tpc }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:left;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->td }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tdn }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tdc }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tdv }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->ta }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tan }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tac }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tav }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tad }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadn }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadc }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadd }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadv }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tpc }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla3')
        <table id="tabla3" border="1" cellpadding="3" cellspacing="0" style="font-size:11px;border-collapse:collapse;">
            <thead>
                <tr>
                    <th rowspan="2" style="background-color:#317eeb;color:#ffffff;text-align:center;">DISTRITO</th>
                    <th colspan="4" style="background-color:#317eeb;color:#ffffff;text-align:center;">PLAZAS DOCENTE</th>
                    <th colspan="4" style="background-color:#317eeb;color:#ffffff;text-align:center;">PLAZAS AUXILIARES DE EDUCACION</th>
                    <th colspan="5" style="background-color:#317eeb;color:#ffffff;text-align:center;">PLAZAS ADMINISTRATIVAS</th>
                    <th colspan="1" style="background-color:#317eeb;color:#ffffff;text-align:center;">PEC</th>
                </tr>
                <tr>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">NOMBRADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">VACANTE</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">NOMBRADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">VACANTE</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">NOMBRADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">DESIGNADO</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">VACANTE</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">CONTRATADO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $key => $item)
                    <tr>
                        <td style="text-align:left;">{{ $item->distrito }}</td>
                        <td style="text-align:right;background-color:#fff2cc;">{{ $item->td }}</td>
                        <td style="text-align:right;">{{ $item->tdn }}</td>
                        <td style="text-align:right;">{{ $item->tdc }}</td>
                        <td style="text-align:right;">{{ $item->tdv }}</td>
                        <td style="text-align:right;background-color:#fff2cc;">{{ $item->ta }}</td>
                        <td style="text-align:right;">{{ $item->tan }}</td>
                        <td style="text-align:right;">{{ $item->tac }}</td>
                        <td style="text-align:right;">{{ $item->tav }}</td>
                        <td style="text-align:right;background-color:#fff2cc;">{{ $item->tad }}</td>
                        <td style="text-align:right;">{{ $item->tadn }}</td>
                        <td style="text-align:right;">{{ $item->tadc }}</td>
                        <td style="text-align:right;">{{ $item->tadd }}</td>
                        <td style="text-align:right;">{{ $item->tadv }}</td>
                        <td style="text-align:right;">{{ $item->tpc }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:left;">TOTAL</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->td }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tdn }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tdc }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tdv }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->ta }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tan }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tac }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tav }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tad }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadn }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadc }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadd }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tadv }}</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:right;">{{ $foot->tpc }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla4')
        <table id="tabla4" border="1" cellpadding="3" cellspacing="0" style="font-size:11px;border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">N°</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Código Modular</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Institución Educativa</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Nivel Educativo</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Zona</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Distrito</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Total Plazas</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Docente</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Auxiliar de Educación</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Personal Administrativo</th>
                    <th style="background-color:#317eeb;color:#ffffff;text-align:center;">Promotores Educativos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $key => $item)
                    <tr>
                        <td style="text-align:center;">{{ $key + 1 }}</td>
                        <td style="text-align:center;">{{ $item->modular }}</td>
                        <td style="text-align:left;">{{ $item->iiee }}</td>
                        <td style="text-align:left;">{{ $item->nivel }}</td>
                        <td style="text-align:left;">{{ $item->zona }}</td>
                        <td style="text-align:left;">{{ $item->distrito }}</td>
                        <td style="text-align:right;">{{ $item->conteo }}</td>
                        <td style="text-align:right;">{{ $item->docentes }}</td>
                        <td style="text-align:right;">{{ $item->auxiliar }}</td>
                        <td style="text-align:right;">{{ $item->administrativo }}</td>
                        <td style="text-align:right;">{{ $item->promotor }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @break

    @default
@endswitch
