@extends('layouts.main',['titlePage'=>'INDICADOR'])

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
               <div class="row">
            <div class="col-xl-6">
                <div class="card card-border card-default">
                    <div class="card-header border-default bg-transparent pb-0">
                        <h3 class="card-title text-default">{{$title}}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Titulado</th>
                                                <th>Cantidad</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                     
                    <div class="card-body">
                        <div id="con1" style="min-width:400px;height:300px;margin:0 auto;" ></div>
                    </div>
                </div>
            </div>
        </div><!-- End row -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card card-border card-default">
                    <div class="card-header border-default bg-transparent pb-0">
                        <h3 class="card-title text-default">PROFESORES TITULADOS, POR UGEL</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Ugel</th>
                                                <th>Cantidad</th>
                                                <th>%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                              $ff=0;  
                                            @endphp
                                            @foreach ($datas['ugel'] as $item)
                                            <tr>
                                                <td>{{$item->name}}</td>
                                                <td>{{$item->si}}</td>
                                                <td>{{round($item->y,2)}}</td>
                                                @php
                                                  $ff+=$item->si  
                                                @endphp
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <th> Total</th>
                                                <th>{{$ff}}</th>
                                                <th>100</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-border card-primary">
                     
                    <div class="card-body">
                        <div id="con2" style="min-width:400px;height:300px;margin:0 auto;" ></div>
                    </div>
                </div>
            </div>
        </div><!-- End row -->
        
    </div>

@endsection

@section('js')

    <!-- flot chart -->
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    {{--<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>--}}
    <script type="text/javascript">
        $(document).ready(function() {
            graficaPie({!!$datas['titulados']!!});
            graficarBar({!!$datas['ugel']!!});
        });
        function abrirdetalle(){
        }        
        function graficarBar(datax){
            Highcharts.chart('con2',{
                chart:{
                    type:'column',
                },
                title:{text:'',},
                xAxis:{
                    type:'category',
                },
                yAxis:{
                    title:{enabled:false,text:'Porcentaje',}
                },
                series:[{
                    name:'UGEL',
                    colorByPoint:true,
                    data:datax,
                }],
                plotOptions:{
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
        }
        function graficaPie(datos){
            Highcharts.chart('con1',{
                chart:{
                    type:'pie',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                },
                title:{
                    text:''
                },
                tooltip:{
                    pointFormat:'<b>{series.name}:</b>{point.y}',
                },
                plotOptions:{
                    pie:{
                        allowPointSelect:true,
                        cursor:'pointer',
                        dataLabels:{
                            enabled:true,
                            format:'<b>{point.name}: {point.percentage:.2f}%</b>'
                        },
                    }
                },
                series:[{
                    name:'Cantidad',
                    colorByPoint:true,
                    data:datos,
                }],
                credits:false,

            });
        } 

    </script>

@endsection
