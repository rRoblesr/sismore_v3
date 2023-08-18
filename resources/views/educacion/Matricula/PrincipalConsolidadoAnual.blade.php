@extends('layouts.main',['activePage'=>'importacion','titlePage'=>'TOTAL DE ESTUDIANTES Y MATRICULAS CONSOLIDADAS ANUALES'])

@section('css')
    
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection 

@section('content') 

<input type="hidden" id="hoja" value="0">

<div class="content">    
    <div class="row">
        <div class="col-md-12">           
            <div class="card">
                
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-md-1 col-form-label">Año:</label>
                        <div class="col-md-2">
                            <select id="anio" name="anio" class="form-control" onchange="cargar_fechas_matricula();">                               
                                @foreach ($anios as $item)
                                    <option value="{{ $item->id }}"> {{ $item->anio }} </option>
                                @endforeach
                            </select>
                        </div>
                       
                        <label class="col-md-1 col-form-label"></label>

                        <label class="col-md-1 col-form-label">Nivel:</label>
                        <div class="col-md-3">
                            <select id="nivel" name="nivel" class="form-control" onchange="cargar_resumen_porUgel();" >                              
                                <option value="T"> Inicial, primaria y secundaria</option>
                                <option value="I"> Inicial</option>
                                <option value="P"> Primaria</option>    
                                <option value="S"> Secundaria</option>                              
                            </select>
                        </div>

                        <div class="col-md-1">                          
                        </div>

                        <label class="col-md-1 col-form-label">Gestión:</label>
                        <div class="col-md-2">
                            <select id="gestion" name="gestion" class="form-control" onchange="cargar_resumen_porUgel();" >                              
                                    <option value="1"> Públicas y privadas</option>
                                    <option value="2"> Pública</option>
                                    <option value="3"> Privada</option>                              
                            </select>
                        </div>
                        
                    </div>                    
                           
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                    <br>
                    {{-- <div class="col-md-12">                       
                        <div class="portfolioFilter">
                            <a href="#" onClick="cargar_inicio();" class="current waves-effect waves-light">INICIO</a>
                            <a href="#" onClick="cargar_resumen_porUgel();"       class="waves-effect waves-light">UGELES</a>
                            <a href="#" onClick="cargar_matricula_porDistrito();" class="waves-effect waves-light" > DISTRITOS </a>    
                            <a href="#" onClick="cargar_matricula_porInstitucion();" class="waves-effect waves-light" > INSTITUCIONES </a>  
                            <a href="#" onClick="cargar_Grafico();" class="waves-effect waves-light" > GRAFICA </a>                  
                        </div>                        
                    </div> --}}

                    
{{-- 
                    <div class="col-md-6">     
                        <div class="card-header bg-primary py-3 text-white">
                            <h5  class="card-title mb-0 text-white"> Suma de Total de estudiantes matriculados</h5>
                        </div>                 

                        <div class="table-responsive">
                         
                            <table class="table table-bordered mb-0" >
                                <thead>
                                    <tr >
                                        <th colspan="1"   class="titulo_tabla" >UGEL / DISTRITO</th>
                                        @foreach($anioConsolidadoAnual as $indice => $elemento) 
                                            <th colspan="1" class="titulo_tabla"> {{$elemento->anio}} </th>
                                        @endforeach
                                    </tr>                          
                                </thead>

                                <tbody>

                                    @php
                                        $recorre=1;
                                    @endphp
                                   
                                   @foreach($ugelConsolidadoAnual as $indice => $elemento)                                      
                                        <tr>                                            
                                            <td class="fila_tabla"><b>  {{$elemento->ugel}} </b>  </td>
                                                                                       
                                            @for($i=1 ; $i<=$anioConsolidadoAnual->count();$i++)   

                                                <td class="columna_derecha fila_tabla">
                                                    {{number_format($total_matricula_ComsolidadoAnual->where('posUgel', $recorre)->where('posAnio', $i)->first()->cantidadAlumnos,0)}} 
                                                    
                                                    
                                                </td>
                                            @endfor

                                            @php
                                            $recorre+=1;
                                            @endphp                                   

                                        </tr>  
                                    @endforeach     
                                    
                                    <tr >
                                        <th colspan="1"   class="fila_tabla" >TOTAL</th>
                                        @foreach($anioConsolidadoAnual as $indice => $elemento) 
                                            <th colspan="1" class="columna_derecha_total fila_tabla">{{number_format($elemento->cantidadAlumnos,0)}} </th>
                                        @endforeach
                                    </tr>  
                                    
                                </tbody>

                            </table>
                        </div>

                    </div> --}}


                    
                    <div class="content" >
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">      
                                        <div id="contenedor" class="form-group row">       
                                           
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
                    </div>

                </div>               
                <!-- card-body -->

            </div>
              
        </div> <!-- End col -->
    </div> <!-- End row -->  
</div>



{{-- <br><br>

@foreach($anioConsolidadoAnual as $indice => $anio) 
<div class="col-md-6">  
    <div class="table-responsive">
        @php
            $anioConsolidadoAnual_poranios = $anioConsolidadoAnual->where('anio', $anio->anio);

        @endphp     
    
        <table class="table table-bordered mb-0" >
            <thead>
                <tr >
                    <th colspan="1" rowspan="2" class="titulo_tabla" >UGEL / DISTRITO </th>
                    @foreach($anioConsolidadoAnual_poranios as $indice => $elemento) 
                        <th colspan="5" class="titulo_tabla"> {{$elemento->anio}} </th>
                    @endforeach
                </tr>  
                
                <tr >
                    @foreach($anioConsolidadoAnual_poranios as $indice => $elemento) 
                        <th colspan="1"  class="titulo_tabla"> Aprob. </th>
                        <th colspan="1" class="titulo_tabla"> Retir. </th>
                        <th colspan="1" class="titulo_tabla"> Req. Recup.</th>
                        <th colspan="1" class="titulo_tabla"> Desap. </th>
                        <th colspan="1" class="titulo_tabla"> Total </th>
                    @endforeach
                </tr>
            </thead>
          
            <tbody>
                {{$anioConsolidadoAnual_poranios   }} 
                @php
                    $recorre=1;
                    $aniow = $anio->anio;

                    $cont=0;
                @endphp
                {{$aniow}}

                 
                @foreach($ugelConsolidadoAnual as $indice => $elemento)                                      
                    <tr>                                            
                        <td class="fila_tabla"><b>  {{$elemento->ugel}} </b>  </td>
                                 
                        @for($i=1; $i<=$anioConsolidadoAnual_poranios->count();$i++)
                            <td class="columna_derecha fila_tabla">
                                {{number_format($total_matricula_ComsolidadoAnual->where('anio', 2018 )->where('posUgel', $recorre)->where('posAnio', $i)->first()->cantidadAprobados,0)}} 
                            </td>
                            <td class="columna_derecha fila_tabla">
                                {{number_format($total_matricula_ComsolidadoAnual->where('posUgel', $recorre)->where('posAnio', $i)->first()->cantidadRetirados,0)}} 
                            </td>
                            <td class="columna_derecha fila_tabla">
                                {{number_format($total_matricula_ComsolidadoAnual->where('posUgel', $recorre)->where('posAnio', $i)->first()->cantidadRequieren_Recup,0)}} 
                            </td>
                            <td class="columna_derecha fila_tabla">
                                {{number_format($total_matricula_ComsolidadoAnual->where('posUgel', $recorre)->where('posAnio', $i)->first()->cantidadDesaprobados,0)}} 
                            </td>
                            <td class="columna_derecha fila_tabla">
                                {{number_format($total_matricula_ComsolidadoAnual->where('posUgel', $recorre)->where('posAnio', $i)->first()->cantidadAlumnos,0)}} 
                            </td>
                        @endfor

                        @php
                        $recorre+=1;
                        @endphp                                   

                    </tr>  
                @endforeach   
                
               
                
                <tr >
                    <th colspan="1"   class="fila_tabla" >TOTAL</th>
                    @foreach($anioConsolidadoAnual as $indice => $elemento) 
                        <th colspan="1" class="columna_derecha_total fila_tabla">{{number_format($elemento->cantidadAlumnos,0)}} </th>
                    @endforeach
                </tr>  
                
            </tbody>

        </table>
        
    </div>
  
</div>
@endforeach --}}

@endsection 




@section('js')


<script src="{{ asset('/') }}public/assets/libs/isotope/isotope.pkgd.min.js"></script>
<script src="{{ asset('/') }}public/assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="{{ asset('/') }}public/assets/js/pages/gallery.init.js"></script>

<script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>


{{-- https://www.youtube.com/watch?v=HU-hffAZqYw --}}

    <script type="text/javascript"> 
        
        $(document).ready(function() {
            cargar_resumen_porUgel();            
        });

        function cargar_resumen_porUgel() {            
         
            $.ajax({  
                headers: {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/Matricula/ReporteUgelConsolidadoAnual/" + $('#anio').val() +  "/" + $('#gestion').val() + "/" + $('#nivel').val(),
                type: 'post',
            }).done(function (data) {               
                $('#contenedor').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });

        }          
       
    </script>



@endsection
