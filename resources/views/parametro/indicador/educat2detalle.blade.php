@extends('layouts.main',['titlePage'=>'UGEL'])

@section('content')
    <div class="content">
        <form id="form_indicadores" action="#">
            @csrf
            <input type="hidden" name="grado" value="{{ $grado }}">
            <input type="hidden" name="aniox" value="">
            <input type="hidden" name="tipo" value="{{ $tipo }}">
            <input type="hidden" name="materia" value="{{ $materia }}">

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-border">
                        <div class="card-header border-primary bg-transparent pb-0">
                            <h3 class="card-title">{{ $title }}
                                <div class="float-right">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">año</label>
                                        <div class="col-md-8">
                                            <select id="anio" name="anio" class="form-control" onchange="satisfactorios();">
                                                @foreach ($anios as $item)
                                                    <option value="{{ $item->anio }}">{{ $item->anio }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </h3>

                        </div>
                        <div class="card-body">
                            <div class="row" id="vistaindicadores">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form><!--End form-->
        <div class="row">
            @foreach ($anios as $key => $anio)
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                    <div class="card-header border-primary bg-transparent pb-0">
                        <h3 class="card-title text-primary">Estudiantes por nivel de logro de aprendizaje según UGEL {{$anio->anio}}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>UGEL</th>
                                            <th class="text-center">EVALUADOS</th>
                                            @if ($anio->previo!=0)
                                            {{--<th class="text-secondary text-center">CANTIDAD</th>--}}
                                            
                                            <th class="text-center">PREVIO</th>
                                            @endif
                                            {{--<th class="text-danger text-center">CANTIDAD</th>--}}
                                            <th class="text-center">INICIO</th>
                                            {{--<th class="text-warning text-center">CANTIDAD</th>--}}
                                            <th class="text-center">PROCESO</th>
                                            {{--<th class="text-success text-center">CANTIDAD</th>--}}
                                            <th class="text-success text-center">SATISFACTORIO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $v1=0;$v2=0;$v3=0;$v4=0;
                                        @endphp
                                        @foreach ($anio->indicador as $ind)
                                        <tr>
                                            <td>{{$ind->ugel}}</td>
                                            <td class="text-center">{{$ind->evaluados}}</td>
                                            @if ($anio->previo!=0)
                                            <td class="text-center">{{$ind->previo}}</td>
                                            {{--<td class="text-secondary text-center">{{round($ind->previo * 100 / $ind->evaluados, 2)}}%</td>--}}
                                            @endif
                                            <td class="text-center">{{$ind->inicio}}</td>
                                            {{--<td class="text-danger text-center">{{round($ind->inicio * 100 / $ind->evaluados, 2)}}%</td>--}}
                                            <td class=" text-center">{{$ind->proceso}}</td>
                                            {{--<td class="text-warning text-center">{{round($ind->proceso * 100 / $ind->evaluados, 2)}}%</td>--}}
                                            <td class="text-success text-center">{{$ind->satisfactorio}}</td>
                                            {{--<td class="text-success text-center">{{round($ind->satisfactorio * 100 / $ind->evaluados, 2)}}%</td>--}}
                                        </tr> 
                                        @php
                                            $v1+=$ind->previo;$v2+=$ind->inicio;$v3+=$ind->proceso;$v4+=$ind->satisfactorio;
                                        @endphp   
                                        @endforeach
                                        <tr class="table-success">
                                            <td>Total</td>
                                            <td class="text-center">{{$v1+$v2+$v3+$v4}}</td>
                                            @if ($anio->previo!=0)
                                            <td class="text-center">{{$v1}}</td>
                                            @endif
                                            <td class="text-center">{{$v2}}</td>
                                            <td class=" text-center">{{$v3}}</td>
                                            <td class="text-success text-center">{{$v4}}</td>
                                        </tr>    
                                    </tbody>
                                </table>
                            </div>
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
        satisfactorios();
    }); 
    function satisfactorios() {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
            url: "{{ route('ind.ajax.satisfactorio') }}",
            type: 'post',
            data: $("#form_indicadores").serialize(),
            beforeSend: function() {
                $("#vistaindicadores").html('<br><h3>Cargando datos...</h3>');
            },
            success: function(data) {
                $("#vistaindicadores").html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
            },
            });
    }        
    @foreach ($anios as $pos1 => $anio)
        Highcharts.chart('con{{$pos1}}',{
            chart:{type:'column',},
            title:{text:'',},
            xAxis:{
                categories:[
                @foreach ($anio->indicador as $item)
                {!!'"'.$item->ugel.'",'!!}
                @endforeach
                ]
            },
            yAxis:{
                allowDecimals:false,
                min:0,
                title:{enabled:false,text:'Porcentaje',}
            },
            series:[
                @if ($anio->previo!=0){
                name:'Previo',
                color:'#7C7D7D',
                data:[
                @foreach ($anio->indicador as $item)
                {{ round(($item->previo  * 100) / $item->evaluados, 2) . ',' }}
                @endforeach
                ]
            },@endif{
                name:'Inicio',
                color:'#F25656',
                data:[
                @foreach ($anio->indicador as $item)
                {{ round(( $item->inicio * 100) / $item->evaluados, 2) . ',' }}
                @endforeach
                ],
            },{
                name:'Proceso',
                color:'#F2CA4C',
                data:[
                @foreach ($anio->indicador as $item)
                {{ round(($item->proceso * 100) / $item->evaluados, 2) . ',' }}
                @endforeach
                ]
            },{
                name:'Satisfactorio',
                color:'#22BAA0',
                data:[
                @foreach ($anio->indicador as $item)
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
