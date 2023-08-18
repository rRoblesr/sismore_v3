@extends('layouts.main',['titlePage'=>'INDICADORES'])
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
            @foreach ($materias as $key => $materia)
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent pb-0">
                        <h3 class="card-title text-primary">Estudiantes del {{$gt[0]->grado}} grado de {{$gt[0]->nivel}} que logran el nivel satisfactorio en  {{$materia->descripcion}}
                            <div class="float-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-xs waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{route('ind.det.edu',['indicador_id'=>$indicador_id,'grado'=>$grado,'tipo'=>$tipo,'materia'=>$materia->id])}}" class="dropdown-item">Ugel</a></li>
                                        <li><a href="{{route('ind.res.edu',['indicador_id'=>$indicador_id,'grado'=>$grado,'tipo'=>$tipo,'materia'=>$materia->id])}}" class="dropdown-item">Institución</a></li>
                                    </ul>
                                </div>
                            </div>
                        </h3>
                    </div>
                    <div class="card-body "{{-- style="height:280px;" --}}>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class=" text-center">AÑO</th>
                                        <th class=" text-center">EVALUADOS</th>
                                        @if ($materia->previo!=0)
                                        {{--<th class="text-secondary text-center">CANTIDAD</th>--}}
                                        <th class="text-center">PREVIO</th>
                                        @endif
                                        <th class="text-center">INICIO</th>
                                        {{--<th class="text-danger text-center">CANTIDAD</th>--}}
                                        <th class="text-center">PROCESO</th>
                                        {{--<th class="text-warning text-center">CANTIDAD</th>--}}
                                        <th class="text-success text-center">SATISFACTORIO</th>
                                        {{--<th class="text-success text-center">CANTIDAD</th>--}}
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($materia->indicador as $ind)
                                    <tr>
                                        <td class="text-center">{{$ind->anio}}</td>
                                        <td class="text-center">{{$ind->evaluados}}</td>
                                        @if ($materia->previo!=0)
                                        <td class="text-center">{{$ind->previo}}</td>
                                        {{--<td class="text-secondary text-center">{{round($ind->previo * 100 / $ind->evaluados, 2)}}%</td>--}}
                                        @endif
                                        <td class="text-center">{{$ind->inicio}}</td>
                                        {{--<td class="text-danger text-center">{{round($ind->inicio * 100 / $ind->evaluados, 2)}}%</td>--}}
                                        <td class="text-center">{{$ind->proceso}}</td>
                                        {{--<td class="text-warning text-center">{{round($ind->proceso * 100 / $ind->evaluados, 2)}}%</td>--}}
                                        <td class="text-success text-center">{{$ind->satisfactorio}}</td>
                                        {{--<td class="text-success text-center">{{round($ind->satisfactorio * 100 / $ind->evaluados, 2)}}%</td>--}}
                                        
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
    </div>
@endsection

@section('js')
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script>
        $(document).ready(function() {
       
        });
        function abrirdetalle(pos){
            $('#modal_detalle_'+pos).modal('show');
        }
    @foreach ($materias as $pos1 => $materia)
            Highcharts.chart('con{{$pos1}}',{
                chart:{
                    type:'column',
                },
                title:{text:'',},
                xAxis:{
                    categories:[
                        @foreach ($materia->indicador as $item)
                        {!!'"'.$item->anio.'",'!!}
                        @endforeach
                    ]
                },
                yAxis:{
                    allowDecimals:false,
                    min:0,
                    title:{enabled:false,text:'Porcentaje',}
                },
                series:[
                    @if ($materia->previo!=0){
                    name:'Previo',     
                    color:'#7C7D7D',
                    data:[
                        @foreach ($materia->indicador as $item)
                        {{ round(($item->previo  * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ]
                },@endif{
                    name:'Inicio',
                    color:'#F25656',
                    data:[
                        @foreach ($materia->indicador as $item)
                        {{ round(( $item->inicio * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ],
                },{
                    name:'Proceso',
                    color:'#F2CA4C',
                    data:[
                        @foreach ($materia->indicador as $item)
                        {{ round(($item->proceso * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ]
                },{
                    name:'Satisfactorio',
                    color:'#22BAA0',
                    data:[
                        @foreach ($materia->indicador as $item)
                        {{ round(($item->satisfactorio * 100) / $item->evaluados, 2) . ',' }}
                        @endforeach
                    ]
                }],
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
