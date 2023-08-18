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
                <div class="col-md-9">
                    <p class="titulo_Indicadores  mb-0">{{$title}}  </p>   
                </div>
                <div class="col-md-3 text-right">
                    <p class="texto_dfuente  mb-0">  Fuente: Sistema de Administración y Control de Plazas – NEXUS  </p>  
                    <p class="texto_dfuente  mb-0"> {{$fecha_version}} </p>      
                </div>
               
            </div>

          
            <div class="row">
                <div class="col-md-12">
                    <h5 class="subtitulo_Indicadores mb-1"> DOCENTES DEL NIVEL PRIMARIA - POR UGELES </h5>   
                    
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
                                    <th class="titulo_tabla">UNIDAD DE GESTION EDUCATIVA LOCAL</th>
                                    <th class="titulo_tabla">TITULO PEDAGOCIGO</th>
                                    <th class="titulo_tabla">TOTAL DOCENTES</th>
                                    <th class="titulo_tabla">PORCENTAJE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sumaColA=0;$sumaColB=0;
                                @endphp
                            
                            @foreach ($Lista as $item)
                                    
                                @php
                                    $sumaColA+= $item->pedagogico; 
                                    $sumaColB+= $item->total; 
                                @endphp

                                <tr>                                            
                                    <td class="fila_tabla">{{$item->ugel}}</td>
                                    <td class="columna_derecha fila_tabla">{{ number_format($item->pedagogico,0) }} </td>
                                    <td class="columna_derecha fila_tabla">{{ number_format($item->total,0) }} </td>
                                    <td class="columna_derecha fila_tabla">{{ number_format($item->porcentaje,2) }} </td>
                                </tr>

                                @endforeach

                                <tr> 
                                    <td class="columna_derecha_total fila_tabla"> <b> TOTAL </b></td>
                                    <td class="columna_derecha_total fila_tabla"> {{number_format($sumaColA,0)}} </td>
                                    <td class="columna_derecha_total fila_tabla"> {{number_format($sumaColB,0)}} </td>
                                    <td class="columna_derecha_total fila_tabla"> {{number_format($sumaColA*100/$sumaColB,2)}} </td>
                                </tr>                                              
                                
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
        </div>
    </div>
</div>  

               
@endsection 