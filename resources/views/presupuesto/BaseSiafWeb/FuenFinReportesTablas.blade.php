@switch($div)
    @case('tabla1')
        <table id="tabla1" class="table table-striped table-bordered font-11">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center">FUENTE DE FINANCIAMIENTO</th>
                    <th class="text-center">PIA</th>
                    <th class="text-center">PIM</th>
                    <th class="text-center">CERTIFICACIÓN</th>
                    <th class="text-center">COMPROMISO</th>
                    <th class="text-center">DEVENGADO</th>
                    <th class="text-center">EJECUCIÓN</th>
                    <th class="text-center">SALDO CERT.</th>
                    <th class="text-center">SALDO DEV.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $item)
                    <tr class="text-center">{{-- columna link que abra un modal --}}
                        <td class="text-left">
                            <a href="#" class="text-decoration-none text-primary" data-bs-toggle="modal"
                                data-bs-target="#modalDetalle"
                                onclick="cargarModalDetalle({{ $item->id }})">{{ $item->nombre }}</a>
                        </td>
                        {{-- <td class="text-left">{{ $item->nombre }}</td> --}}
                        <td class="text-right">{{ number_format($item->pia, 0) }}</td>
                        <td class="text-right">{{ number_format($item->pim, 0) }}</td>
                        <td class="text-right">{{ number_format($item->certificado, 0) }}</td>
                        <td class="text-right">{{ number_format($item->compromiso, 0) }}</td>
                        <td class="text-right">{{ number_format($item->devengado, 0) }}</td>
                        <td><x-avance-badge :avance="$item->avance" fontSize="11px" /></td>
                        <td class="text-right">{{ number_format($item->saldocert, 0) }}</td>
                        <td class="text-right">{{ number_format($item->saldodev, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center table-success-0 text-white">
                    <th class="text-left">TOTAL</th>
                    <th class="text-right">{{ $foot->pia }}</th>
                    <th class="text-right">{{ $foot->pim }}</th>
                    <th class="text-right">{{ $foot->certificado }}</th>
                    <th class="text-right">{{ $foot->compromiso }}</th>
                    <th class="text-right">{{ $foot->devengado }}</th>
                    <th class="text-center"><x-avance-badge :avance="$foot->avance" fontSize="11px" /></th>
                    <th class="text-right">{{ $foot->saldocert }}</th>
                    <th class="text-right">{{ $foot->saldodev }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla0101')
        <div class="card card-border border border-plomo-0">
            <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                <div class="card-widgets">
                    <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla0101')">
                        <i class="fa fa-file-excel"></i> Descargar</button>
                </div>
                <h3 class="card-title text-center">DEVENGADO MENSUALIZADO(S/.)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla0101" class="table table-striped table-bordered font-12">
                        <thead>
                            <tr class="table-success-0 text-white">
                                <th class="text-center">Año</th>
                                <th class="text-center">Ene</th>
                                <th class="text-center">Feb</th>
                                <th class="text-center">Mar</th>
                                <th class="text-center">Abr</th>
                                <th class="text-center">May</th>
                                <th class="text-center">Jun</th>
                                <th class="text-center">Jul</th>
                                <th class="text-center">Ago</th>
                                <th class="text-center">Set</th>
                                <th class="text-center">Oct</th>
                                <th class="text-center">Nov</th>
                                <th class="text-center">Dic</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($base as $item)
                                <tr class="text-center">
                                    <td class="text-primary">{{ $item->anio }}</td>
                                    <td>{{ $item->ene > 0 ? number_format($item->ene, 0) : '' }}</td>
                                    <td>{{ $item->feb > 0 ? number_format($item->feb, 0) : '' }}</td>
                                    <td>{{ $item->mar > 0 ? number_format($item->mar, 0) : '' }}</td>
                                    <td>{{ $item->abr > 0 ? number_format($item->abr, 0) : '' }}</td>
                                    <td>{{ $item->may > 0 ? number_format($item->may, 0) : '' }}</td>
                                    <td>{{ $item->jun > 0 ? number_format($item->jun, 0) : '' }}</td>
                                    <td>{{ $item->jul > 0 ? number_format($item->jul, 0) : '' }}</td>
                                    <td>{{ $item->ago > 0 ? number_format($item->ago, 0) : '' }}</td>
                                    <td>{{ $item->sep > 0 ? number_format($item->sep, 0) : '' }}</td>
                                    <td>{{ $item->oct > 0 ? number_format($item->oct, 0) : '' }}</td>
                                    <td>{{ $item->nov > 0 ? number_format($item->nov, 0) : '' }}</td>
                                    <td>{{ $item->dic > 0 ? number_format($item->dic, 0) : '' }}</td>
                                    <td>{{ number_format($item->total, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @break

    @case('tabla2')
        <table id="tabla2" class="table table-striped table-bordered font-11">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center">RUBRO DE FINANCIAMIENTO</th>
                    <th class="text-center">PIA</th>
                    <th class="text-center">PIM</th>
                    <th class="text-center">CERTIFICACIÓN</th>
                    <th class="text-center">COMPROMISO</th>
                    <th class="text-center">DEVENGADO</th>
                    <th class="text-center">EJECUCIÓN</th>
                    <th class="text-center">SALDO CERT.</th>
                    <th class="text-center">SALDO DEV.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $item)
                    <tr class="text-center">{{-- columna link que abra un modal --}}
                        <td class="text-left">
                            <a href="#" class="text-decoration-none text-primary" data-bs-toggle="modal"
                                data-bs-target="#modalDetalle"
                                onclick="cargarModalDetalle2({{ $item->id }})">{{ $item->nombre }}</a>
                        </td>
                        {{-- <td class="text-left">{{ $item->nombre }}</td> --}}
                        <td class="text-right">{{ number_format($item->pia, 0) }}</td>
                        <td class="text-right">{{ number_format($item->pim, 0) }}</td>
                        <td class="text-right">{{ number_format($item->certificado, 0) }}</td>
                        <td class="text-right">{{ number_format($item->compromiso, 0) }}</td>
                        <td class="text-right">{{ number_format($item->devengado, 0) }}</td>
                        <td><x-avance-badge :avance="$item->avance" fontSize="11px" /></td>
                        <td class="text-right">{{ number_format($item->saldocert, 0) }}</td>
                        <td class="text-right">{{ number_format($item->saldodev, 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center table-success-0 text-white">
                    <th class="text-left">TOTAL</th>
                    <th class="text-right">{{ number_format($foot->pia, 0) }}</th>
                    <th class="text-right">{{ number_format($foot->pim, 0) }}</th>
                    <th class="text-right">{{ number_format($foot->certificado, 0) }}</th>
                    <th class="text-right">{{ number_format($foot->compromiso, 0) }}</th>
                    <th class="text-right">{{ number_format($foot->devengado, 0) }}</th>
                    <th class="text-center"><x-avance-badge :avance="$foot->avance" fontSize="11px" /></th>
                    <th class="text-right">{{ number_format($foot->saldocert, 0) }}</th>
                    <th class="text-right">{{ number_format($foot->saldodev, 0) }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @case('tabla0201')
        <div class="card card-border border border-plomo-0">
            <div class="card-header border-success-0 bg-transparent pb-0 pt-2">
                <div class="card-widgets">
                    <button type="button" class="btn btn-success-0 btn-xs" onclick="descargarExcel('tabla0201')">
                        <i class="fa fa-file-excel"></i> Descargar</button>
                </div>
                <h3 class="card-title text-center">DEVENGADO MENSUALIZADO(S/.)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabla0201" class="table table-striped table-bordered font-12">
                        <thead>
                            <tr class="table-success-0 text-white">
                                <th class="text-center">Año</th>
                                <th class="text-center">Ene</th>
                                <th class="text-center">Feb</th>
                                <th class="text-center">Mar</th>
                                <th class="text-center">Abr</th>
                                <th class="text-center">May</th>
                                <th class="text-center">Jun</th>
                                <th class="text-center">Jul</th>
                                <th class="text-center">Ago</th>
                                <th class="text-center">Set</th>
                                <th class="text-center">Oct</th>
                                <th class="text-center">Nov</th>
                                <th class="text-center">Dic</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($base as $item)
                                <tr class="text-center">
                                    <td class="text-primary">{{ $item->anio }}</td>
                                    <td>{{ $item->ene > 0 ? number_format($item->ene, 0) : '' }}</td>
                                    <td>{{ $item->feb > 0 ? number_format($item->feb, 0) : '' }}</td>
                                    <td>{{ $item->mar > 0 ? number_format($item->mar, 0) : '' }}</td>
                                    <td>{{ $item->abr > 0 ? number_format($item->abr, 0) : '' }}</td>
                                    <td>{{ $item->may > 0 ? number_format($item->may, 0) : '' }}</td>
                                    <td>{{ $item->jun > 0 ? number_format($item->jun, 0) : '' }}</td>
                                    <td>{{ $item->jul > 0 ? number_format($item->jul, 0) : '' }}</td>
                                    <td>{{ $item->ago > 0 ? number_format($item->ago, 0) : '' }}</td>
                                    <td>{{ $item->sep > 0 ? number_format($item->sep, 0) : '' }}</td>
                                    <td>{{ $item->oct > 0 ? number_format($item->oct, 0) : '' }}</td>
                                    <td>{{ $item->nov > 0 ? number_format($item->nov, 0) : '' }}</td>
                                    <td>{{ $item->dic > 0 ? number_format($item->dic, 0) : '' }}</td>
                                    <td>{{ number_format($item->total, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @break

    @case('xtabla1')
        <table id="xtabla1" class="table table-striped table-bordered font-11">
            <thead>
                <tr class="table-success-0 text-white">
                    <th class="text-center">FUENTE DE FINANCIAMIENTO</th>
                    <th class="text-center">PIA</th>
                    <th class="text-center">PIM</th>
                    <th class="text-center">CERTIFICACIÓN</th>
                    <th class="text-center">COMPROMISO</th>
                    <th class="text-center">DEVENGADO</th>
                    <th class="text-center">EJECUCIÓN</th>
                    <th class="text-center">SALDO CERT.</th>
                    <th class="text-center">SALDO DEV.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($base as $item)
                    <tr class="text-center">{{-- columna link que abra un modal --}}
                        <td class="text-left">
                            <a href="#" class="text-decoration-none text-primary" data-bs-toggle="modal"
                                data-bs-target="#modalDetalle"
                                onclick="cargarModalDetalle({{ $item->id }})">{{ $item->nombre }}</a>
                        </td>
                        {{-- <td class="text-left">{{ $item->nombre }}</td> --}}
                        <td class="text-right">{{ $item->pia }}</td>
                        <td class="text-right">{{ $item->pim }}</td>
                        <td class="text-right">{{ $item->certificado }}</td>
                        <td class="text-right">{{ $item->compromiso }}</td>
                        <td class="text-right">{{ $item->devengado }}</td>
                        <td><x-avance-badge :avance="$item->avance" fontSize="11px" /></td>
                        <td class="text-right">{{ $item->saldocert }}</td>
                        <td class="text-right">{{ $item->saldodev }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="text-center table-success-0 text-white">
                    <th class="text-left">TOTAL</th>
                    <th class="text-right">{{ $foot->pia }}</th>
                    <th class="text-right">{{ $foot->pim }}</th>
                    <th class="text-right">{{ $foot->certificado }}</th>
                    <th class="text-right">{{ $foot->compromiso }}</th>
                    <th class="text-right">{{ $foot->devengado }}</th>
                    <th class="text-center"><x-avance-badge :avance="$foot->avance" fontSize="11px" /></th>
                    <th class="text-right">{{ $foot->saldocert }}</th>
                    <th class="text-right">{{ $foot->saldodev }}</th>
                </tr>
            </tfoot>
        </table>
    @break

    @default
@endswitch
