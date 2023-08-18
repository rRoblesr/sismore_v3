@extends('layouts.main',['activePage'=>'importacion','titlePage'=>''])


@section('css')

    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>

@endsection 

@section('content') 

<div class="content">
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <p class="titulo_Indicadores  mb-0">{{$title}}  </p>   
                </div>
                <div class="col-md-6 text-right">
                    <p class="texto_dfuente  mb-0">  Fuente: Sistema de Administración y Control de Plazas – NEXUS  </p>  
                    <p class="texto_dfuente  mb-0"> {{$fecha_version}} </p>      
                </div>
               
            </div>

            {{-- <div class="progress progress-sm m-0">
                <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
            </div> --}}

            <br>
            <div class="row">
                <div class="col-md-12">
                    <h5 class="subtitulo_Indicadores mb-1"> DOCENTES POR UGELES </h5>   
                    
                </div>     

                
                <div class="col-md-12">
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                </div>   

            </div>
        
            <div class="row">

                <div class="col-md-6">                 
                    
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>                                                 
                                    <th class="titulo_tabla">UGEL</th>
                                    <th class="titulo_tabla">DOCENTES BILINGUES</th>
                                    <th class="titulo_tabla">TOTAL DOCENTES</th>
                                    <th class="titulo_tabla">PORCENTAJE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sumaColA=0;$sumaColB=0;
                                @endphp
                            
                                @foreach ($dataCabecera as $item)                                       
                                        @php
                                            $sumaColA+= $item->Bilingue; 
                                            $sumaColB+= $item->total; 
                                        @endphp
                                @endforeach

                                <tr> 
                                    <td class="fila_tabla"> <b> TOTAL </b></td>
                                    <td class="columna_derecha_total fila_tabla"> {{number_format($sumaColA,0)}} </td>
                                    <td class="columna_derecha_total fila_tabla"> {{number_format($sumaColB,0)}} </td>
                                    <td class="columna_derecha_total fila_tabla"> {{number_format($sumaColA*100/$sumaColB,2)}} </td>
                                </tr>

                                @foreach ($dataCabecera as $itemCab)

                                <tr>                                            
                                            <td class="fila_tabla"><b>{{$itemCab->ugel}}</b></td>
                                            <td class="columna_derecha fila_tabla">{{ number_format($itemCab->Bilingue,0) }} </td>
                                            <td class="columna_derecha fila_tabla">{{ number_format($itemCab->total,0) }} </td>
                                            <td class="columna_derecha fila_tabla">{{ number_format($itemCab->porcentaje,2) }} </td>
                                </tr>

                                    
                                @endforeach


                                                                              
                                
                            </tbody>
                        </table>
                    </div>

                </div>               


                <div class="col-md-6">
                    <div id="{{$contenedor}}">       
                        @include('graficos.Circular')
                    </div>
                </div>
            </div>


       
             {{-- <div class="content">
                <div class="card">
                    <div class="card-body"> --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="progress progress-sm m-0">
                                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                                </div>
                            </div>   
                            <div class="col-md-12">      
                                <div id="barra1">       
                                    {{-- se carga con el scrip lineas abajo --}}
                                </div> 
                            </div> 
                        </div> 
                    {{-- </div> 
                </div> 
            </div>  --}}


            <br>
            <div class="row">
                <div class="col-md-6">
                    <h5 class="subtitulo_Indicadores mb-0"> DOCENTES POR UGELES Y NIVELES EDUCATIVOS</h5>   
                </div>               
            </div>
            {{-- detallessssssssssssssssssssssssssssssssssssssssssssss --}}
            {{-- fila 1 --}}
            <div class="row">

                <div class="col-md-6">
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>                                                 
                                    <th class="titulo_tabla">NIVEL EDUCATIVO</th>
                                    <th class="titulo_tabla">DOCENTES BILINGUES</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                               
                                @foreach ($dataCabecera as $itemCab)
                                    @if ($itemCab->ugel_id==10)     
                                        <tr>                                            
                                                    <td class="fila_tabla"><b>{{$itemCab->ugel}}</b></td>
                                                    <td class="columna_derecha_total fila_tabla">{{ number_format($itemCab->Bilingue,0) }} </td>

                                        </tr>

                                        @foreach ($lista as $item)

                                            {{-- @if ($itemCab->ugel==$item->ugel)                                        --}}
                                            @if ($item->ugel_id==10)     
                                                <tr>                                            
                                                    <td class="fila_tabla">{{$item->nivel_educativo}}</td>
                                                    <td class="columna_derecha fila_tabla">{{ number_format($item->Bilingue,0) }} </td>

                                                </tr>

                                            @endif

                                        @endforeach
                                    @endif
                                @endforeach                                       
                                
                            </tbody>
                        </table>
                    </div>                   

                </div>
            
                <div class="col-md-6">
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>                                                 
                                    <th class="titulo_tabla">NIVEL EDUCATIVO</th>
                                    <th class="titulo_tabla">DOCENTES BILINGUES</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                               
                                @foreach ($dataCabecera as $itemCab)
                                    @if ($itemCab->ugel_id==11)     
                                        <tr>                                            
                                                    <td class="fila_tabla"><b>{{$itemCab->ugel}}</b></td>
                                                    <td class="columna_derecha_total fila_tabla">{{ number_format($itemCab->Bilingue,0) }} </td>

                                        </tr>

                                        @foreach ($lista as $item)

                                            {{-- @if ($itemCab->ugel==$item->ugel)                                        --}}
                                            @if ($item->ugel_id==11)     
                                                <tr>                                            
                                                    <td class="fila_tabla">{{$item->nivel_educativo}}</td>
                                                    <td class="columna_derecha fila_tabla">{{ number_format($item->Bilingue,0) }} </td>

                                                </tr>

                                            @endif

                                        @endforeach
                                    @endif
                                @endforeach                                       
                                
                            </tbody>
                        </table>
                    </div>                   

                </div>

            </div>

            {{-- fila 2 --}}
            <br>
            <div class="row">

                <div class="col-md-6">
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>                                                 
                                    <th class="titulo_tabla">NIVEL EDUCATIVO</th>
                                    <th class="titulo_tabla">DOCENTES BILINGUES</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                               
                                @foreach ($dataCabecera as $itemCab)
                                    @if ($itemCab->ugel_id==12)     
                                        <tr>                                            
                                                    <td class="fila_tabla"><b>{{$itemCab->ugel}}</b></td>
                                                    <td class="columna_derecha_total fila_tabla">{{ number_format($itemCab->Bilingue,0) }} </td>

                                        </tr>

                                        @foreach ($lista as $item)

                                            {{-- @if ($itemCab->ugel==$item->ugel)                                        --}}
                                            @if ($item->ugel_id==12)     
                                                <tr>                                            
                                                    <td class="fila_tabla">{{$item->nivel_educativo}}</td>
                                                    <td class="columna_derecha fila_tabla">{{ number_format($item->Bilingue,0) }} </td>

                                                </tr>

                                            @endif

                                        @endforeach
                                    @endif
                                @endforeach                                       
                                
                            </tbody>
                        </table>
                    </div>                   

                </div>
            
                <div class="col-md-6">
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>                                                 
                                    <th class="titulo_tabla">NIVEL EDUCATIVO</th>
                                    <th class="titulo_tabla">DOCENTES BILINGUES</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                               
                                @foreach ($dataCabecera as $itemCab)
                                    @if ($itemCab->ugel_id==13)     
                                        <tr>                                            
                                                    <td class="fila_tabla"><b>{{$itemCab->ugel}}</b></td>
                                                    <td class="columna_derecha_total fila_tabla">{{ number_format($itemCab->Bilingue,0) }} </td>

                                        </tr>

                                        @foreach ($lista as $item)

                                            {{-- @if ($itemCab->ugel==$item->ugel)                                        --}}
                                            @if ($item->ugel_id==13)     
                                                <tr>                                            
                                                    <td class="fila_tabla">{{$item->nivel_educativo}}</td>
                                                    <td class="columna_derecha fila_tabla">{{ number_format($item->Bilingue,0) }} </td>

                                                </tr>

                                            @endif

                                        @endforeach
                                    @endif
                                @endforeach                                       
                                
                            </tbody>
                        </table>
                    </div>                   

                </div>

            </div>


        </div>
    </div>
</div>






@endsection 



@section('js')

    <script type="text/javascript"> 
        
        $(document).ready(function() {
         
            cargar_GraficoBarra();        
        });

       
        function cargar_GraficoBarra() {            
            $.ajax({  
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/CuadroAsigPersonal/ReporteBilingues/GraficoBarrasPrincipal/"+ {{$importacion_id}},
                type: 'post',
            }).done(function (data) {               
                $('#barra1').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }
       
    </script>
    
@endsection


