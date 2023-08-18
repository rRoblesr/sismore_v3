@extends('layouts.main',['titlePage'=>'INDICADORES'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-fill bg-primary">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title text-white">{{ $title }}</h3>
                    </div>
                </div>
            </div>
        </div>
        @if ($sinaprobar->count() > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-border">
                        <div class="card-header border-danger bg-transparent pb-0">
                            <div class="card-title">Importaciones sin aprobar</div>
                        </div>
                        <div class="card-body">
                            @foreach ($sinaprobar as $item)
                                <div class="alert alert-danger">
                                    {{ $item->comentario }}, de la fecha {{ $item->created_at }}
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            @foreach ($materias as $key => $omateria)
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-primary text-center">AÑO</th>
                                        @if ($omateria->previo!=0)
                                        <th class="text-secondary text-center">CANTIDAD</th>
                                        <th class="text-secondary text-center">PREVIO</th>    
                                        @endif
                                        <th class="text-danger text-center">CANTIDAD</th>
                                        <th class="text-danger text-center">INICIO</th>
                                        {{--
                                        <th class="text-warning text-center">CANTIDAD</th>
                                        <th class="text-warning text-center">PROCESO</th>
                                        <th class="text-success text-center">CANTIDAD</th>
                                        <th class="text-success text-center">SATISFACTORIO</th>--}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($omateria->indicador as $ind)
                                    <tr>
                                        <td class="text-primary text-center">{{$ind->anio}}</td>
                                        @if ($omateria->previo!=0)
                                        <td class="text-secondary text-center">{{$ind->previo}}</td>
                                        <td class="text-secondary text-center">{{round($ind->previo * 100 / $ind->evaluados, 2)}}%</td>
                                        @endif
                                        <td class="text-danger text-center">{{$ind->inicio}}</td>
                                        <td class="text-danger text-center">{{round($ind->inicio * 100 / $ind->evaluados, 2)}}%</td>
                                        {{--
                                        <td class="text-warning text-center">{{$ind->proceso}}</td>
                                        <td class="text-warning text-center">{{round($ind->proceso * 100 / $ind->evaluados, 2)}}%</td>
                                        <td class="text-success text-center">{{$ind->satisfactorio}}</td>
                                        <td class="text-success text-center">{{round($ind->satisfactorio * 100 / $ind->evaluados, 2)}}%</td>--}}
                                    </tr>    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>                        
                    </div>
                </div>

            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-body">
                        <div id="con{{$key}}" style="min-width:400px;height:300px;margin:0 auto;" ></div>
                    </div>
                </div>
            </div>    
            @endforeach
        </div><!-- End row -->      
        <div class="row">
            @foreach ($anios as $key => $oanio)
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent pb-0">
                        <h3 class="card-title text-primary">{{$title}} según UGEL {{$oanio->anio}}
                        </h3>
                    </div>
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-primary text-center">UGEL</th>
                                        @if ($oanio->previo!=0)
                                        <th class="text-secondary text-center">CANTIDAD</th>
                                        <th class="text-secondary text-center">PREVIO</th>    
                                        @endif
                                        <th class="text-danger text-center">CANTIDAD</th>
                                        <th class="text-danger text-center">INICIO</th>
                                        {{--
                                        <th class="text-warning text-center">CANTIDAD</th>
                                        <th class="text-warning text-center">PROCESO</th>
                                        <th class="text-success text-center">CANTIDAD</th>
                                        <th class="text-success text-center">SATISFACTORIO</th>--}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($oanio->indicador as $ind)
                                    <tr>
                                        <td class="text-primary text-center">{{$ind->ugel}}</td>
                                        @if ($oanio->previo!=0)
                                        <td class="text-secondary text-center">{{$ind->previo}}</td>
                                        <td class="text-secondary text-center">{{round($ind->previo * 100 / $ind->evaluados, 2)}}%</td>
                                        @endif
                                        <td class="text-danger text-center">{{$ind->inicio}}</td>
                                        <td class="text-danger text-center">{{round($ind->inicio * 100 / $ind->evaluados, 2)}}%</td>
                                        {{--
                                        <td class="text-warning text-center">{{$ind->proceso}}</td>
                                        <td class="text-warning text-center">{{round($ind->proceso * 100 / $ind->evaluados, 2)}}%</td>
                                        <td class="text-success text-center">{{$ind->satisfactorio}}</td>
                                        <td class="text-success text-center">{{round($ind->satisfactorio * 100 / $ind->evaluados, 2)}}%</td>--}}
                                    </tr>    
                                    @endforeach
                                </tbody>
                            </table>
                        </div>                        
                    </div>
                </div>

            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-body">
                        <div id="conugel{{$key}}" style="min-width:400px;height:300px;margin:0 auto;" ></div>
                    </div>
                </div>
            </div>    
            @endforeach
        </div><!-- End row -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent pb-0">
                        <h3 class="card-title text-primary">Resumen general por gestion y area</h3>
                    </div> 
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-1 col-form-label">Gestion</label>
                            <div class="col-md-3">
                                <select id="gestion" name="gestion" class="form-control" onchange="cargardatatable1();">
                                    <option value="0">TODOS</option>
                                    @foreach ($gestions as $ges)
                                    <option value="{{$ges->id}}">{{$ges->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-md-1 col-form-label">Area</label>
                            <div class="col-md-3">
                                <select id="area" name="area" class="form-control" onchange="cargardatatable1();">
                                    <option value="0">TODOS</option>
                                    @foreach ($areas as $area)
                                    <option value="{{$area->id}}">{{$area->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-md-1 col-form-label">Año</label>
                            <div class="col-md-3">
                                <select id="anio1" name="anio1" class="form-control" onchange="cargardatatable1();">
                                    @foreach ($anios as $item)
                                        <option value="{{ $item->anio }}">{{ $item->anio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="table-responsive">
                                    <table id="datatable1" class="table table-striped table-bordered" style="width:100%">
                                        <thead class="cabecera-dataTable">                                    
                                            <th class="text-primary">IIEE</th>
                                            @if ($tipo!=1)
                                            <th class="text-secondary text-center">CANTIDAD</th>
                                            <th class="text-secondary text-center">PREVIO</th>    
                                            @endif
                                            <th class="text-danger text-center">CANTIDAD</th>
                                            <th class="text-danger text-center">INICIO</th>
                                            {{--
                                            <th class="text-warning text-center">CANTIDAD</th>
                                            <th class="text-warning text-center">PROCESO</th>
                                            <th class="text-success text-center">CANTIDAD</th>
                                            <th class="text-success text-center">SATISFACTORIO</th>--}}
                                        </thead>
                                    </table>
                                </div>   
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>    
        </div><!-- End row -->
    </div>

  
@endsection

@section('js')
<script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>

<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        cargardatatable1();
    });
    function cargardatatable1(){
        $('#datatable1').DataTable({
            "ajax": "{{url('/')}}/INDICADOR/ReporteGestionAreaDT/" + $('#anio1').val()+ "/{{$grado}}/{{$tipo}}/{{$materia}}/" + $('#gestion').val()+ "/" + $('#area').val(),
            "columns":[
                {data:'nombre'},
                @if ($tipo!=1)
                {data:'previo'},
                {data:'p1'},
                @endif
                {data:'inicio'}, 
                {data:'p2'},
                {{--{data:'proceso'},  
                {data:'p3'},
                {data:'satisfactorio'},
                {data:'p4'},  --}}  
            ],
            "responsive":false,
            "autoWidth":true,
            "order":false,
            "destroy":true,
            "language": {
                    "lengthMenu": "Mostrar "+
                    `<select class="custom-select custom-select-sm form-control form-control-sm">
                        <option value = '10'> 10</option>
                        <option value = '25'> 25</option>
                        <option value = '50'> 50</option>
                        <option value = '100'>100</option>
                        <option value = '-1'>Todos</option>
                        </select>` + " registros por página",          
                    "info": "Mostrando la página _PAGE_ de _PAGES_" ,
                    "infoEmpty": "No records available",
                    "infoFiltered": "(Filtrado de _MAX_ registros totales)",  
                    "emptyTable":			"No hay datos disponibles en la tabla.",
                    "info":		   			"Del _START_ al _END_ de _TOTAL_ registros ",
                    "infoEmpty":			"Mostrando 0 registros de un total de 0. registros",
                    "infoFiltered":			"(filtrados de un total de _MAX_ )",
                    "infoPostFix":			"",           
                    "loadingRecords":		"Cargando...",
                    "processing":			"Procesando...",
                    "search":				"Buscar:",
                    "searchPlaceholder":	"Dato para buscar",
                    "zeroRecords":			"No se han encontrado coincidencias.",            
                    "paginate":{
                        "next":"siguiente",
                        "previous":"anterior"
                        }
            }
        }); 
    }
    @foreach ($materias as $pos1 => $omateria)
            Highcharts.chart('con{{$pos1}}',{
                chart:{
                    type:'column',
                },
                title:{text:'',},
                xAxis:{
                    categories:[
                        @foreach ($omateria->indicador as $item)
                        {!!'"'.$item->anio.'",'!!}
                        @endforeach
                    ]
                },
                yAxis:{
                    allowDecimals:false,
                    min:0,
                    title:{enabled:false,text:'Porcentaje',}
                },
                series:[@if ($omateria->previo!=0){
                    name:'Previo',     
                    color:'#7C7D7D',
                    data:[
                        @foreach ($omateria->indicador as $item)
                        {{ round(($item->previo  * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ]
                },@endif{
                    name:'Inicio',
                    color:'#F25656',
                    data:[
                        @foreach ($omateria->indicador as $item)
                        {{ round(( $item->inicio * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ],
                },
                {{--{
                    name:'Proceso',
                    color:'#F2CA4C',
                    data:[
                        @foreach ($omateria->indicador as $item)
                        {{ round(($item->proceso * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ]
                },{
                    name:'Satisfactorio',
                    color:'#22BAA0',
                    data:[
                        @foreach ($omateria->indicador as $item)
                        {{ round(($item->satisfactorio * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ]
                }--}}
                ],
                plotOptions:{
                    columns:{stacking:'normal'},
                    series:{
                        borderWidth:0,
                        dataLabels:{
                            enabled:true,
                            format:'{point.y:.1f}%',
                        },
                    }
                },
                credits:false,
            });
    @endforeach         


    @foreach ($anios as $pos1 => $oanio)
            Highcharts.chart('conugel{{$pos1}}',{
                chart:{
                    type:'column',
                },
                title:{text:'',},
                xAxis:{
                    categories:[
                        @foreach ($oanio->indicador as $item)
                        {!!'"'.$item->ugel.'",'!!}
                        @endforeach
                    ]
                },
                yAxis:{
                    allowDecimals:false,
                    min:0,
                    title:{enabled:false,text:'Porcentaje',}
                },
                series:[@if ($oanio->previo!=0){
                    name:'Previo',     
                    color:'#7C7D7D',
                    data:[
                        @foreach ($oanio->indicador as $item)
                        {{ round(($item->previo  * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ]
                },@endif{
                    name:'Inicio',
                    color:'#F25656',
                    data:[
                        @foreach ($oanio->indicador as $item)
                        {{ round(( $item->inicio * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ],
                },
                {{--{
                    name:'Proceso',
                    color:'#F2CA4C',
                    data:[
                        @foreach ($oanio->indicador as $item)
                        {{ round(($item->proceso * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ]
                },{
                    name:'Satisfactorio',
                    color:'#22BAA0',
                    data:[
                        @foreach ($oanio->indicador as $item)
                        {{ round(($item->satisfactorio * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ]
                }--}}
                ],
                plotOptions:{
                    columns:{stacking:'normal'},
                    series:{
                        borderWidth:0,
                        dataLabels:{
                            enabled:true,
                            format:'{point.y:.1f}%',
                        },
                    }
                },
                credits:false,
            });
    @endforeach    
</script>

@endsection
