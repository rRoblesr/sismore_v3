@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => ''])
@section('css')
    {{-- <style>
        .card-custom {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        .card-custom:hover {
            transform: scale(1.03);
        }

        .card-header-custom {
            /*background-color: #28a745;*/
            color: white;
            padding: 10px;
            font-weight: bold;
        }

        .card-footer-custom {
            background-color: #f8f9fa;
            padding: 10px;
            border-top: 1px solid #dee2e6;
        }
    </style> --}}

    <style>
        .card-custom {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-custom:hover {
            transform: scale(1.03);
        }

        .card-header-custom {
            /*background-color: #008000;*/
            color: white;
            padding: 10px;
            font-weight: bold;
        }

        .card-footer-custom {
            background-color: #f8f9fa;
            padding: 10px;
            border-top: 1px solid #dee2e6;
            margin-top: auto;
        }

        .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            /*justify-content: space-between;*/
        }

        .titulo-indicador {
            min-height: 60px;
            text-transform: uppercase;
            font-variant: small-caps;
        }
    </style>
@endsection

@section('content')
    <div class="form-group row align-items-center vh-5">
        <div class="col-lg-8 col-md-8 col-sm-8">
            <h4 class="page-title font-16">CONVENIO DE GESTION</h4>
        </div>
        {{-- <div class="col-lg-2 col-md-2 col-sm-2">
            <select id="provincia" name="provincia" class="form-control font-11" onchange="cargarDistritos(),cargarCards();">
                <option value="0">PROVINCIA</option>

            </select>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            <select id="distrito" name="distrito" class="form-control font-11" onchange="cargarCards();">
                <option value="0">DISTRITO</option>

            </select>
        </div> --}}
    </div>

    <div class="row">
        @foreach ($indicadores as $indicador)
            <div class="col-md-6 col-xl-3 my-2">
                <div class="card card-custom text-center">
                    <div class="card-header card-header-custom bg-success-0 py-2">GESTIÓN N° {{ $indicador['codigo'] }}</div>
                    <div class="card-body py-0 px-1">
                        <div id="graeducacion{{ $indicador['codigo'] }}"></div>
                        <p class="mt-2 font-12">Actualizado: 22/01/2025</p>
                        {{-- @if ($indicador['numerador'] / $indicador['denominador'] >= 1)
                            <span class="badge badge-success"> <i class="mdi mdi-thumb-up"></i> CUMPLE</span>
                        @else
                            <span class="badge badge-danger"> <i class="mdi mdi-thumb-down"></i> NO CUMPLE</span>
                        @endif --}}
                        <div class="row mt-2">
                            <div class="col-6">
                                <strong>Numerador</strong>
                                <p>{{ $indicador['numerador'] }}</p>
                            </div>
                            <div class="col-6">
                                <strong>Denominador</strong>
                                <p>{{ $indicador['denominador'] }}</p>
                            </div>
                        </div>
                        <p class="mt-2 font-12 titulo-indicador">{{ $indicador['titulo'] }}</p>
                    </div>
                    <div class="card-footer card-footer-custom">
                        <a href="{{ $indicador['enlace'] }}" class="btn btn-warning btn-sm text-dark" target="_blank">Ver
                            detalle</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- <div class="row">
        @foreach ($indicadores as $indicador)
            <div class="col-md-6 col-xl-3">
                <div class="card card-custom text-center">
                    <div class="card-header card-header-custom bg-success-0">SI {{ $indicador['codigo'] }}</div>
                    <div class="card-body">
                        <div id="graeducacion{{ $indicador['codigo'] }}"></div>
                        <p class="mt-2 font-12">Actualizado: 02/04/2024</p>
                        @if ($indicador['numerador'] / $indicador['denominador'] >= 1)
                            <span class="badge badge-success"> <i class="mdi mdi-thumb-up"></i> CUMPLE</span>
                        @else
                            <span class="badge badge-danger"> <i class="mdi mdi-thumb-down"></i> NO CUMPLE</span>
                        @endif
                        <div class="row mt-2">
                            <div class="col-6">
                                <strong>Numerador</strong>
                                <p>{{ $indicador['numerador'] }}</p>
                            </div>
                            <div class="col-6">
                                <strong>Denominador</strong>
                                <p>{{ $indicador['denominador'] }}</p>
                            </div>
                        </div>
                        <p class="mt-2 font-12">{{ $indicador['titulo'] }}</p>
                    </div>
                    <div class="card-footer card-footer-custom">
                        <a href="{{ $indicador['enlace'] }}" class="btn btn-warning btn-sm text-dark" target="_blank">Ver
                            detalle</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}

    {{-- <div class="row">
        @foreach (range(1, 5) as $i)
            <div class="col-md-6 col-xl-3">
                <div class="card card-custom text-center">
                    <div class="card-header card-header-custom bg-success">SI 01-0{{ $i }}</div>
                    <div class="card-body">
                        <div id="graeducacion0{{ $i }}"></div>
                        <p class="mt-2 font-12">Actualizado: 02/04/2024</p>
                        @if ($i % 2 == 0)
                            <span class="badge badge-danger"> <i class="mdi mdi-thumb-down"></i> NO CUMPLE</span>
                        @else
                            <span class="badge badge-success"> <i class="mdi mdi-thumb-up"></i> CUMPLE</span>
                        @endif
                        <div class="row mt-2">
                            <div class="col-6">
                                <strong>Numerador</strong>
                                <p>100</p>
                            </div>
                            <div class="col-6">
                                <strong>Denominador</strong>
                                <p>100</p>
                            </div>
                        </div>
                        <p class="mt-2 font-12">Número de gobiernos locales que registraron oportunamente las actas de
                            homologación en el sistema de padrón nominal.</p>
                    </div>
                    <div class="card-footer card-footer-custom">
                        <a href="#" class="btn btn-warning btn-sm text-dark">Ver detalle</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div> --}}

    <div class="row d-none">
        <div class="col-md-6 col-xl-3">
            <div class="card text-center border border-success-0">
                <div class="pricing-header bg-success-0 p-0 rounded-top">
                    <div class="card-widgets">
                        <span onclick=""><i class="mdi mdi-rotate-180 mdi-alert-circle"
                                style="color:#FFF;font-size: 20px;"></i>&nbsp;&nbsp;</span>
                    </div>
                    <h5 class="text-white font-14 font-weight-normal mt-1 mb-1"><i class="mdi mdi-shield-plus"
                            style="font-size: 20px"></i>
                        SI 01-01 </h5>
                </div>
                <div class="pb-4 pl-4 pr-4">
                    <ul class="list-unstyled mt-0">
                        <li class="mt-0 pt-0">
                        <li class="m-0 pt-0">
                            <figure class="p-0 m-0">
                                <div id="graeducacion01"></div>
                                {{-- graDITSALUD01 --}}
                            </figure>
                        </li>
                        </li>
                        <li class="mt-0 pt-0 font-12">Actualizado: 02/04/2024</li>
                        <li class="mt-0 pt-0">
                            @if (true)
                                <span class="badge badge-success m-2" style="font-size: 90%; width:100px">
                                    <i class="mdi mdi-thumb-up"></i> CUMPLE</span>
                            @else
                                <span class="badge badge-danger m-2" style="font-size: 90%; width:100px">
                                    <i class="mdi mdi-thumb-down"></i> NO CUMPLE</span>
                            @endif
                        </li>
                        <li class="mt-0 pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0)" class="text-green-0 font-weight-bold" data-toggle="popover"
                                        title="Numerador"
                                        data-content="Locales Educativos conectados a red de agra potable, según departamentos"><i
                                            class="mdi mdi-arrow-down-bold" data-placement="top"></i>
                                        Numerador</a>
                                    <div class="font-weight-bold">100</div>
                                </div>

                                <div class="col-6">
                                    <a href="javascript:void(0)" class="text-green-0 font-weight-bold" data-toggle="popover"
                                        title="Denominador"
                                        data-content="Locales Educativos conectados a red de agra potable, según departamentos">
                                        <i class="mdi mdi-arrow-up-bold" data-placement="top"></i>
                                        Denominador</a>
                                    <div class="font-weight-bold">100</div>
                                </div>
                            </div>
                        </li>

                        <li class="mt-1 pt-1">
                            <p class="font-12" style="height: 5rem;">Número de gobiernos
                                locales que registraron oportunamente las actas de
                                homologación en el sistema de padron nominal</p>
                        </li>

                    </ul>
                    <div class="mt-1 pt-1">
                        <a href="#" class="btn btn-warning btn-sm text-dark  width-md waves-effect waves-light">Ver
                            detalle</a>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card text-center border border-success-0">
                <div class="pricing-header bg-success-0 p-0 rounded-top">
                    <div class="pricing-header bg-success-0 p-0 rounded-top">
                        <div class="card-widgets">
                            <span onclick=""><i class="mdi mdi-rotate-180 mdi-alert-circle"
                                    style="color:#FFF;font-size: 20px;"></i>&nbsp;&nbsp;</span>
                        </div>
                        <h5 class="text-white font-14 font-weight-normal mt-1 mb-1"><i class="mdi mdi-shield-plus"
                                style="font-size: 20px"></i>
                            SI 01-02 </h5>
                    </div>
                </div>
                <div class="pb-4 pl-4 pr-4">
                    <ul class="list-unstyled mt-0">
                        <li class="mt-0 pt-0">
                            {{-- <i class="mdi mdi-finance font-44 text-green-0"></i></li>
                                    <li class="mt-0 pt-0 font-16">Avance</li>
                                    <li class="mt-0 pt-0 font-40 font-weight-bold">98.8 % --}}
                        <li class="m-0 pt-0">
                            <figure class="p-0 m-0">
                                <div id="graeducacion02"></div>
                                {{-- graDITSALUD01 --}}
                            </figure>
                        </li>
                        </li>
                        <li class="mt-0 pt-0 font-12">Actualizado: 02/04/2024</li>

                        <li class="mt-0 pt-0">
                            @if (false)
                                <span class="badge badge-success m-2" style="font-size: 90%; width:100px">
                                    <i class="mdi mdi-thumb-up"></i> CUMPLE</span>
                            @else
                                <span class="badge badge-danger m-2" style="font-size: 90%; width:100px">
                                    <i class="mdi mdi-thumb-down"></i> NO CUMPLE</span>
                            @endif
                        </li>

                        <li class="mt-0 pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0)" class="text-green-0 font-weight-bold"
                                        data-toggle="popover" title="Numerador"
                                        data-content="Locales Educativos conectados a red de agra potable, según departamentos"><i
                                            class="mdi mdi-arrow-down-bold" data-placement="top"></i>
                                        Numerador</a>
                                    <div class="font-weight-bold">100</div>
                                </div>

                                <div class="col-6">
                                    <a href="javascript:void(0)" class="text-green-0 font-weight-bold"
                                        data-toggle="popover" title="Denominador"
                                        data-content="Locales Educativos conectados a red de agra potable, según departamentos">
                                        <i class="mdi mdi-arrow-up-bold" data-placement="top"></i>
                                        Denominador</a>
                                    <div class="font-weight-bold">100</div>
                                </div>
                            </div>
                        </li>
                        <li class="mt-1 pt-1">
                            <p class="font-12" style="height: 5rem;">Número de gobiernos
                                locales que registraron oportunamente las actas de
                                homologación en el sistema de padron nominal</p>
                        </li>

                    </ul>
                    <div class="mt-1 pt-1">
                        <a href="#" class="btn btn-warning btn-sm text-dark  width-md waves-effect waves-light">Ver
                            detalle</a>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card text-center border border-success-0">
                <div class="pricing-header bg-success-0 p-0 rounded-top">
                    <div class="card-widgets">
                        <span onclick=""><i class="mdi mdi-rotate-180 mdi-alert-circle"
                                style="color:#FFF;font-size: 20px;"></i>&nbsp;&nbsp;</span>
                    </div>
                    <h5 class="text-white font-14 font-weight-normal mt-1 mb-1"><i class="mdi mdi-shield-plus"
                            style="font-size: 20px"></i>
                        SI 01-03 </h5>
                </div>
                <div class="pb-4 pl-4 pr-4">
                    <ul class="list-unstyled mt-0">
                        <li class="mt-0 pt-0">
                            {{-- <i class="mdi mdi-finance font-44 text-green-0"></i></li>
                                    <li class="mt-0 pt-0 font-16">Avance</li>
                                    <li class="mt-0 pt-0 font-40 font-weight-bold">98.8 % --}}
                        <li class="m-0 pt-0">
                            <figure class="p-0 m-0">
                                <div id="graeducacion03"></div>
                                {{-- graDITSALUD01 --}}
                            </figure>
                        </li>
                        </li>
                        <li class="mt-0 pt-0 font-12">Actualizado: 02/04/2024
                        </li>
                        <li class="mt-0 pt-0">
                            @if (true)
                                <span class="badge badge-success m-2" style="font-size: 90%; width:100px">
                                    <i class="mdi mdi-thumb-up"></i> CUMPLE</span>
                            @else
                                <span class="badge badge-danger m-2" style="font-size: 90%; width:100px">
                                    <i class="mdi mdi-thumb-down"></i> NO CUMPLE</span>
                            @endif
                        </li>
                        <li class="mt-0 pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0)" class="text-green-0 font-weight-bold"
                                        data-toggle="popover" title="Numerador"
                                        data-content="Locales Educativos conectados a red de agra potable, según departamentos"><i
                                            class="mdi mdi-arrow-down-bold" data-placement="top"></i>
                                        Numerador</a>
                                    <div class="font-weight-bold">100</div>
                                </div>

                                <div class="col-6">
                                    <a href="javascript:void(0)" class="text-green-0 font-weight-bold"
                                        data-toggle="popover" title="Denominador"
                                        data-content="Locales Educativos conectados a red de agra potable, según departamentos">
                                        <i class="mdi mdi-arrow-up-bold" data-placement="top"></i>
                                        Denominador</a>
                                    <div class="font-weight-bold">100</div>
                                </div>
                            </div>
                        </li>
                        <li class="mt-1 pt-1">
                            <p class="font-12" style="height: 5rem;">Número de gobiernos
                                locales que registraron oportunamente las actas de
                                homologación en el sistema de padron nominal</p>
                        </li>
                    </ul>
                    <div class="mt-1 pt-1">
                        <a href="#" class="btn btn-warning btn-sm text-dark  width-md waves-effect waves-light">Ver
                            detalle</a>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card text-center border border-success-0">
                <div class="pricing-header bg-success-0 p-0 rounded-top">
                    <div class="card-widgets">
                        <span onclick=""><i class="mdi mdi-rotate-180 mdi-alert-circle"
                                style="color:#FFF;font-size: 20px;"></i>&nbsp;&nbsp;</span>
                    </div>
                    <h5 class="text-white font-14 font-weight-normal mt-1 mb-1"><i class="mdi mdi-shield-plus"
                            style="font-size: 20px"></i>
                        SI 01-04 </h5>
                </div>
                <div class="pb-4 pl-4 pr-4">
                    <ul class="list-unstyled mt-0">
                        <li class="mt-0 pt-0">
                            {{-- <i class="mdi mdi-finance font-44 text-green-0"></i></li>
                                    <li class="mt-0 pt-0 font-16">Avance</li>
                                    <li class="mt-0 pt-0 font-40 font-weight-bold">98.8 % --}}
                        <li class="m-0 pt-0">
                            <figure class="p-0 m-0">
                                <div id="graeducacion04"></div>
                                {{-- graDITSALUD01 --}}
                            </figure>
                        </li>
                        </li>
                        <li class="mt-0 pt-0 font-12">Actualizado: 02/04/2024
                        </li>
                        <li class="mt-0 pt-0">
                            @if (false)
                                <span class="badge badge-success m-2" style="font-size: 90%; width:100px">
                                    <i class="mdi mdi-thumb-up"></i> CUMPLE</span>
                            @else
                                <span class="badge badge-danger m-2" style="font-size: 90%; width:100px">
                                    <i class="mdi mdi-thumb-down"></i> NO CUMPLE</span>
                            @endif
                        </li>
                        <li class="mt-0 pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0)" class="text-green-0 font-weight-bold"
                                        data-toggle="popover" title="Numerador"
                                        data-content="Locales Educativos conectados a red de agra potable, según departamentos"><i
                                            class="mdi mdi-arrow-down-bold" data-placement="top"></i>
                                        Numerador</a>
                                    <div class="font-weight-bold">100</div>
                                </div>

                                <div class="col-6">
                                    <a href="javascript:void(0)" class="text-green-0 font-weight-bold"
                                        data-toggle="popover" title="Denominador"
                                        data-content="Locales Educativos conectados a red de agra potable, según departamentos">
                                        <i class="mdi mdi-arrow-up-bold" data-placement="top"></i>
                                        Denominador</a>
                                    <div class="font-weight-bold">100</div>
                                </div>
                            </div>
                        </li>
                        <li class="mt-1 pt-1">
                            <p class="font-12" style="height: 5rem;">Número de gobiernos
                                locales que registraron oportunamente las actas de
                                homologación en el sistema de padron nominal</p>
                        </li>

                    </ul>
                    <div class="mt-1 pt-1">
                        <a href="#" class="btn btn-warning btn-sm text-dark  width-md waves-effect waves-light">Ver
                            detalle</a>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card text-center border border-success-0">
                <div class="pricing-header bg-success-0 p-0 rounded-top">
                    <div class="card-widgets">
                        <span onclick=""><i class="mdi mdi-rotate-180 mdi-alert-circle"
                                style="color:#FFF;font-size: 20px;"></i>&nbsp;&nbsp;</span>
                    </div>
                    <h5 class="text-white font-14 font-weight-normal mt-1 mb-1"><i class="mdi mdi-shield-plus"
                            style="font-size: 20px"></i>
                        SI 01-05 </h5>
                </div>
                <div class="pb-4 pl-4 pr-4">
                    <ul class="list-unstyled mt-0">
                        <li class="mt-0 pt-0">
                            {{-- <i class="mdi mdi-finance font-44 text-green-0"></i></li>
                                    <li class="mt-0 pt-0 font-16">Avance</li>
                                    <li class="mt-0 pt-0 font-40 font-weight-bold">98.8 % --}}
                        <li class="m-0 pt-0">
                            <figure class="p-0 m-0">
                                <div id="graeducacion05"></div>
                                {{-- graDITSALUD01 --}}
                            </figure>
                        </li>
                        </li>
                        <li class="mt-0 pt-0 font-12">Actualizado: 02/04/2024
                        </li>
                        <li class="mt-0 pt-0">
                            @if (true)
                                <span class="badge badge-success m-2" style="font-size: 90%; width:100px">
                                    <i class="mdi mdi-thumb-up"></i> CUMPLE</span>
                            @else
                                <span class="badge badge-danger m-2" style="font-size: 90%; width:100px">
                                    <i class="mdi mdi-thumb-down"></i> NO CUMPLE</span>
                            @endif
                        </li>
                        <li class="mt-0 pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0)" class="text-green-0 font-weight-bold"
                                        data-toggle="popover" title="Numerador"
                                        data-content="Locales Educativos conectados a red de agra potable, según departamentos"><i
                                            class="mdi mdi-arrow-down-bold" data-placement="top"></i>
                                        Numerador</a>
                                    <div class="font-weight-bold">100</div>
                                </div>

                                <div class="col-6">
                                    <a href="javascript:void(0)" class="text-green-0 font-weight-bold"
                                        data-toggle="popover" title="Denominador"
                                        data-content="Locales Educativos conectados a red de agra potable, según departamentos">
                                        <i class="mdi mdi-arrow-up-bold" data-placement="top"></i>
                                        Denominador</a>
                                    <div class="font-weight-bold">100</div>
                                </div>
                            </div>
                        </li>
                        <li class="mt-1 pt-1">
                            <p class="font-12" style="height: 5rem;">Número de gobiernos
                                locales que registraron oportunamente las actas de
                                homologación en el sistema de padron nominal</p>
                        </li>

                    </ul>
                    <div class="mt-1 pt-1">
                        <a href="#" class="btn btn-warning btn-sm text-dark  width-md waves-effect waves-light">Ver
                            detalle</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        var paleta_colores = ['#5eb9aa', '#F9FFFE', '#f5bd22', '#058DC7', '#50B432', '#9D561B', '#DDDF00', '#24CBE5',
            '#64E572', '#9F9655', '#FFF263', '#6AF9C4'
        ];
        $(document).ready(function() {
            $('[data-toggle="popover"]').popover({
                html: true,
                template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div><div class="popover-footer"><a href="#" class="btn btn-info btn-sm">Close</a></div></div>'
            });

            Highcharts.setOptions({
                lang: {
                    thousandsSep: ","
                }
            });

            @foreach ($indicadores as $indicador)
                @if ($indicador['denominador'] > 1)
                GaugeSeries('graeducacion{{ $indicador['codigo'] }}',
                {{ round((100 * $indicador['numerador']) / $indicador['denominador'], 1) }});
                @else
                GaugeSeries('graeducacion{{ $indicador['codigo'] }}', {{ $indicador['numerador'] }});
                @endif                
            @endforeach
            // GaugeSeries('graeducacion01', 71);
            // GaugeSeries('graeducacion02', 82);
            // GaugeSeries('graeducacion03', 92);
            // GaugeSeries('graeducacion04', 99);
            // GaugeSeries('graeducacion05', 62);
            // cargarCards();


            // $('[data-original-title]').css('background-color','#256398');
            // $('#v1').tooltip({
            //     boundary: 'window',
            //     template: '<div class="tooltip bs-tooltip-top" role="tooltip"><div class="arrow"></div><div class="tooltip-inner">aaaaaaaaaaaaaaaaaaaaaaa</div></div>'
            // })
        });
        function GaugeSeries(div, data) {
    Highcharts.chart(div, {
        chart: {
            height: 165,
            margin: [0, 0, 0, 0],
            spacing: [0, 0, 0, 0],
            type: 'solidgauge'
        },
        yAxis: {
            labels: {
                enabled: false // Oculta los labels del eje Y
            },
            tickLength: 0,
            lineColor: 'transparent',
            minorTickLength: 0,
            minorGridLineWidth: 0,
            gridLineWidth: 0,
            min: 0,
            max: 200, // Ahora permite valores mayores a 100
            dataClasses: [
                { from: 0, to: 50, color: '#ef5350' },   // Rojo
                { from: 51, to: 100, color: '#f5bd22' }, // Amarillo
                { from: 101, to: 200, color: '#5eb9aa' } // Verde
            ],
        },
        pane: {
            background: {
                innerRadius: '80%',
                outerRadius: '100%'
            }
        },
        credits: { enabled: false },
        exporting: { enabled: false },
        title: { text: '' },
        plotOptions: {
            series: {
                dataLabels: {
                    formatter: function () {
                        return '<div style="text-align:center; margin-top: -20px">' +
                            '<div style="font-size:2.5em;">' + Highcharts.numberFormat(this.y, 0, '.', ',') + '%</div>' +
                            '<div style="font-size:12px; opacity:0.4; text-align: center;">Avance</div>' +
                            '</div>';
                    },
                    useHTML: true,
                    borderWidth: 0
                }
            }
        },
        series: [{
            name: 'Avance',
            innerRadius: '80%',
            data: [{
                y: data,
                colorIndex: '50'
            }],
            radius: '100%',
        }],
        tooltip: {
            valueSuffix: '%',
            formatter: function () {
                return Highcharts.numberFormat(this.y, 0, '.', ',') + '%';
            }
        }
    });
}

        function GaugeSerieasd(div, data) {
            Highcharts.chart(div, {
                chart: {
                    height: 165,
                    margin: [0, 0, 0, 0],
                    spacing: [0, 0, 0, 0],
                    type: 'solidgauge'
                },
                yAxis: {
                    labels: {
                        enabled: false // Oculta los labels del eje Y
                    },
                    tickLength: 0,
                    lineColor: 'transparent',
                    minorTickLength: 0,
                    minorGridLineWidth: 0,
                    gridLineWidth: 0,

                    min: 0,
                    max: 200, // Ahora permite valores mayores a 100
                    dataClasses: [{
                            from: 0,
                            to: 50,
                            color: '#ef5350'
                        }, // Rojo
                        {
                            from: 51,
                            to: 100,
                            color: '#f5bd22'
                        }, // Amarillo
                        {
                            from: 101,
                            to: 200,
                            color: '#5eb9aa'
                        } // Verde
                    ],
                },
                pane: {
                    background: {
                        innerRadius: '80%',
                        outerRadius: '100%'
                    }
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                title: {
                    text: ''
                },
                plotOptions: {
                    series: {
                        dataLabels: {
                            format: '<div style="text-align:center; margin-top: -20px">' +
                                '<div style="font-size:2.5em;">{y}%</div>' +
                                '<div style="font-size:12px; opacity:0.4; text-align: center;">Avance</div>' +
                                '</div>',
                            useHTML: true,
                            borderWidth: 0
                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    innerRadius: '80%',
                    data: [{
                        y: data,
                        colorIndex: '50'
                    }],
                    radius: '100%',
                }],
                tooltip: {
                    valueSuffix: '%'
                }
            });
        }


        function GaugeSeriesxx(div, data) {
            //colors: ['#5eb9aa', '#f5bd22', '#ef5350'],
            Highcharts.chart(div, {
                chart: {
                    height: 165,
                    margin: [0, 0, 0, 0],
                    spacing: [0, 0, 0, 0],
                    type: 'solidgauge'
                },
                yAxis: {
                    labels: {
                        style: {
                            display: 'none'
                        }
                    },
                    tickLength: 0,
                    lineColor: 'transparent',
                    minorTickLength: 0,
                    minorGridLineWidth: 0,
                    gridLineWidth: 0,

                    min: 0,
                    max: 100,
                    dataClasses: [{
                        from: 0,
                        to: 50,
                        color: '#ef5350'
                    }, {
                        from: 51,
                        to: 99,
                        color: '#f5bd22'
                    }, {
                        from: 100,
                        to: 200,
                        color: '#5eb9aa'
                    }],
                },
                pane: {
                    background: {
                        innerRadius: '80%',
                        outerRadius: '100%'
                    }
                },
                accessibility: {
                    // typeDescription: 'The gauge chart with 1 data point.'
                },
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: false,
                },
                title: {
                    text: ''
                },

                plotOptions: {
                    series: {
                        // className: 'highcharts-live-kpi',
                        dataLabels: {
                            format: '<div style="text-align:center; margin-top: -20px">' +
                                '<div style="font-size:2.5em;">{y}%</div>' +
                                '<div style="font-size:12px; opacity:0.4; text-align: center;">Avance</div>' +
                                '</div>',
                            useHTML: true,
                            borderWidth: 0,

                        }
                    }
                },
                series: [{
                    name: 'Avance',
                    // data:[80],
                    innerRadius: '80%',
                    data: [{
                        y: data,
                        colorIndex: '50'
                    }],
                    radius: '100%',
                }],
                xAxis: {
                    accessibility: {
                        // description: 'Days'
                    }
                },
                lang: {
                    accessibility: {
                        // chartContainerLabel: 'CPU usage. Highcharts interactive chart.'
                    }
                },
                tooltip: {
                    valueSuffix: '%'
                }

            });

        }
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <!-- optional -->
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
@endsection
