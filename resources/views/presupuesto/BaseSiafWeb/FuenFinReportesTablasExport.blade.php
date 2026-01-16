@switch($div)
    @case('tabla1')
        @php
            $grouped = $base->groupBy('unidadejecutora')->sortKeys();
        @endphp
        <table id="tabla1" class="table table-striped table-bordered font-11">
            <thead>
                <tr>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">UNIDAD EJECUTORA</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">PIA</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">PIM</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">CERTIFICADO</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">DEVENGADO</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">% Ejecución</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Saldo PIM-CERT</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Saldo PIM-DEV</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grouped as $ue => $items)
                    @php
                        $ue_pia = $items->sum('pia');
                        $ue_pim = $items->sum('pim');
                        $ue_cert = $items->sum('certificado');
                        $ue_dev = $items->sum('devengado');
                        $ue_avance = $ue_pim > 0 ? round(($ue_dev / $ue_pim) * 100, 1) : 0;
                        $ue_saldo_cert = $ue_pim - $ue_cert;
                        $ue_saldo_dev = $ue_pim - $ue_dev;
                    @endphp
                    <tr style="font-weight: bold;">
                        <td style="font-weight: bold;">{{ $ue }}</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_pia }}</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_pim }}</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_cert }}</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_dev }}</td>
                        <td style="text-align:center;font-weight: bold;">{{ $ue_avance }}%</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_saldo_cert }}</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_saldo_dev }}</td>
                    </tr>
                    @foreach ($items as $item)
                        <tr>
                            <td style="padding-left: 20px;">{{ $item->nombre }}</td>
                            <td style="text-align:right">{{ $item->pia }}</td>
                            <td style="text-align:right">{{ $item->pim }}</td>
                            <td style="text-align:right">{{ $item->certificado }}</td>
                            <td style="text-align:right">{{ $item->devengado }}</td>
                            <td style="text-align:center">{{ $item->avance }}%</td>
                            <td style="text-align:right">{{ $item->saldocert }}</td>
                            <td style="text-align:right">{{ $item->saldodev }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">TOTAL</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">{{ number_format($foot->pia, 0) }}
                    </th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">{{ number_format($foot->pim, 0) }}
                    </th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">
                        {{ number_format($foot->certificado, 0) }}</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">
                        {{ number_format($foot->devengado, 0) }}</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">{{ $foot->avance }}%</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">
                        {{ number_format($foot->saldocert, 2) }}</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">
                        {{ number_format($foot->saldodev, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla0101')
        <table id="tabla0101">
            <thead>
                <tr>
                    <th colspan="14" style="background-color:#43beac;color:#ffffff;text-align:center;">
                        DEVENGADO MENSUALIZADO(S/.)
                    </th>
                </tr>
                <tr class="table-success-0 text-white">
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Año</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Ene</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Feb</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Mar</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Abr</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">May</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Jun</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Jul</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Ago</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Set</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Oct</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Nov</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Dic</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $item)
                    <tr>
                        <td>{{ $item->anio }}</td>
                        <td>{{ $item->ene > 0 ? $item->ene : '' }}</td>
                        <td>{{ $item->feb > 0 ? $item->feb : '' }}</td>
                        <td>{{ $item->mar > 0 ? $item->mar : '' }}</td>
                        <td>{{ $item->abr > 0 ? $item->abr : '' }}</td>
                        <td>{{ $item->may > 0 ? $item->may : '' }}</td>
                        <td>{{ $item->jun > 0 ? $item->jun : '' }}</td>
                        <td>{{ $item->jul > 0 ? $item->jul : '' }}</td>
                        <td>{{ $item->ago > 0 ? $item->ago : '' }}</td>
                        <td>{{ $item->sep > 0 ? $item->sep : '' }}</td>
                        <td>{{ $item->oct > 0 ? $item->oct : '' }}</td>
                        <td>{{ $item->nov > 0 ? $item->nov : '' }}</td>
                        <td>{{ $item->dic > 0 ? $item->dic : '' }}</td>
                        <td>{{ $item->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @break

    @case('tabla2')
        @php
            $grouped = $base->groupBy('unidadejecutora')->sortKeys();
        @endphp
        <table id="tabla2" class="table table-striped table-bordered font-11">
            <thead>
                <tr>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">UNIDAD EJECUTORA</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">PIA</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">PIM</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">CERTIFICADO</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">DEVENGADO</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">% Ejecución</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Saldo PIM-CERT</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Saldo PIM-DEV</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grouped as $ue => $items)
                    @php
                        $ue_pia = $items->sum('pia');
                        $ue_pim = $items->sum('pim');
                        $ue_cert = $items->sum('certificado');
                        $ue_dev = $items->sum('devengado');
                        $ue_avance = $ue_pim > 0 ? round(($ue_dev / $ue_pim) * 100, 1) : 0;
                        $ue_saldo_cert = $ue_pim - $ue_cert;
                        $ue_saldo_dev = $ue_pim - $ue_dev;
                    @endphp
                    <tr style="font-weight: bold;">
                        <td style="font-weight: bold;">{{ $ue }}</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_pia }}</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_pim }}</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_cert }}</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_dev }}</td>
                        <td style="text-align:center;font-weight: bold;">{{ $ue_avance }}%</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_saldo_cert }}</td>
                        <td style="text-align:right;font-weight: bold;">{{ $ue_saldo_dev }}</td>
                    </tr>
                    @foreach ($items as $item)
                        <tr>
                            <td style="padding-left: 20px;">{{ $item->nombre }}</td>
                            <td style="text-align:right">{{ $item->pia }}</td>
                            <td style="text-align:right">{{ $item->pim }}</td>
                            <td style="text-align:right">{{ $item->certificado }}</td>
                            <td style="text-align:right">{{ $item->devengado }}</td>
                            <td style="text-align:center">{{ $item->avance }}%</td>
                            <td style="text-align:right">{{ $item->saldocert }}</td>
                            <td style="text-align:right">{{ $item->saldodev }}</td> 
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">TOTAL</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">{{ $foot->pia }}
                    </th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">{{ $foot->pim }}
                    </th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">{{ $foot->certificado }}
                    </th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">
                        {{ $foot->devengado }}
                    </th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">{{ $foot->avance }}%</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">
                        {{ $foot->saldocert }}
                    </th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:right;">
                        {{ $foot->saldodev }}
                    </th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla0201')
        <table id="tabla0201">
            <thead>
                <tr>
                    <th colspan="14" style="background-color:#43beac;color:#ffffff;text-align:center;">
                        DEVENGADO MENSUALIZADO(S/.)
                    </th>
                </tr>
                <tr>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Año</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Ene</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Feb</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Mar</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Abr</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">May</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Jun</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Jul</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Ago</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Set</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Oct</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Nov</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Dic</th>
                    <th style="background-color:#43beac;color:#ffffff;text-align:center;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $item)
                    <tr>
                        <td>{{ $item->anio }}</td>
                        <td>{{ $item->ene > 0 ? $item->ene : '' }}</td>
                        <td>{{ $item->feb > 0 ? $item->feb : '' }}</td>
                        <td>{{ $item->mar > 0 ? $item->mar : '' }}</td>
                        <td>{{ $item->abr > 0 ? $item->abr : '' }}</td>
                        <td>{{ $item->may > 0 ? $item->may : '' }}</td>
                        <td>{{ $item->jun > 0 ? $item->jun : '' }}</td>
                        <td>{{ $item->jul > 0 ? $item->jul : '' }}</td>
                        <td>{{ $item->ago > 0 ? $item->ago : '' }}</td>
                        <td>{{ $item->sep > 0 ? $item->sep : '' }}</td>
                        <td>{{ $item->oct > 0 ? $item->oct : '' }}</td>
                        <td>{{ $item->nov > 0 ? $item->nov : '' }}</td>
                        <td>{{ $item->dic > 0 ? $item->dic : '' }}</td>
                        <td>{{ $item->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @break

    @default
@endswitch
