@extends('layouts.main',['titlePage'=>'INDICADOR'])

@section('content')
    <div class="content">
        <!--div class="row">
            <div class="col-lg-12">
                <div class="card card-fill bg-success">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title text-white">{{$title}}</h3>
                    </div>
                </div>
            </div>
        </div-->
        <div class="row" id="">
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
                                                <th>#</th>
                                                <th>Años</th>
                                                <th>%</th>
                                                <th>Nota</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($inds as $key => $item)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$item->name}}</td>
                                                <td>{{round($item->y,2)}}</td>
                                                <td>{{$item->nota}}</td>
                                            </tr>
                                            @endforeach
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
    </div>
@endsection

@section('js')

    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            graficar({!!$inds!!});
        });

        function graficar(datax){
            Highcharts.chart('con1',{
                chart:{
                    type:'column',
                },
                title:{enabled:false,text:'',},
                xAxis:{
                    type:'category',
                },
                yAxis:{
                    title:{enabled:false,text:'Porcentaje',}
                },
                series:[{
                    name:'AÑOS',
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
    </script>

@endsection
