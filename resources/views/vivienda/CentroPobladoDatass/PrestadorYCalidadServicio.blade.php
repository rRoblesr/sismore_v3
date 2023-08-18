@extends('layouts.main',['activePage'=>'importacion','titlePage'=>''])

@section('css')
<link rel="stylesheet" href="{{ asset('/') }}public/assets/css/otros/personalizado.css" type='text/css'>



@endsection


@section('content')

<div class="content">

    
    
    <div class="row">
        <div class="col-md-6 ">
            <h4>REGION UCAYALI</h4>
        </div>
        
        <div class="col-md-6 ">
            <p style="text-align: right; color:rgb(147, 141, 141)"> Ultima Actualización: {{$fechaVersion}}
                <br>Fuente: Ministerio de Vivienda, Construcción y Saneamiento  - DATASS </p> 
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 ">
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="alert alert-info">                                                          
                            <div id="Grafico_tipo_organizacion_comunal">       
                                {{-- se carga con el scrip lineas abajo --}}
                            </div>                     
                    </div>                 
                </div>
            </div>      
    
        </div>
    
        <div class="col-md-6 ">
               
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="alert alert-info">                                                          
                            <div id="Grafico_Asociados_organizacion_comunal">       
                                {{-- se carga con el scrip lineas abajo --}}
                            </div>                     
                    </div>                 
                </div>
            </div>
        </div>
        
    
    </div>


    <div class="row">
        <div class="col-md-6 ">
               
            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="alert alert-danger">                                                          
                            <div id="Graficorealiza_cloracion_agua">       
                                {{-- se carga con el scrip lineas abajo --}}
                            </div>                     
                    </div>                 
                </div>
            </div>
  
  
        </div>
  
        <div class="col-md-6 ">
               
         <div class="row">
             <div class="col-md-12 col-xl-12">
                 <div class="alert alert-danger">                                                          
                         <div id="Grafico_PorRegion_CP_Periodos">       
                             {{-- se carga con el scrip lineas abajo --}}
                         </div>                     
                 </div>                 
             </div>
         </div>
  
  
     </div>
  
  </div>

  
 <div class="row">
       <div class="col-md-6 ">
              
           <div class="row">
               <div class="col-md-12 col-xl-12">
                   <div class="alert alert-info">                                                          
                           <div id="Graficocuota_familiar">       
                               {{-- se carga con el scrip lineas abajo --}}
                           </div>                     
                   </div>                 
               </div>
           </div>


       </div>

       <div class="col-md-6 ">
              
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="alert alert-info">                                                          
                        <div id="Graficoservicio_agua_continuo">       
                            {{-- se carga con el scrip lineas abajo --}}
                        </div>                     
                </div>                 
            </div>
        </div>


     </div>

</div>

 





@endsection

@section('js')



{{-- este script tiene modificaciones para la paleta de colores --}}
<script src="{{ asset('/') }}public/assets/libs/highchartsV2/highcharts.js"></script>

{{-- <script src="https://code.highcharts.com/highcharts.js"></script> --}}
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>



<script type="text/javascript"> 


    $(document).ready(function() {
        cargar_datos() ;        
    });


    function cargar_datos(){
           Grafico_cuota_familiar();
           Grafico_servicio_agua_continuo();
           Grafico_realiza_cloracion_agua();
           Grafico_PorRegion_CP_Periodos();
           Grafico_tipo_organizacion_comunal();
           Grafico_Asociados_organizacion_comunal();
    }   
    
    function Grafico_cuota_familiar() {
            
        $.ajax({  
            headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
            },                           
            url: "{{ url('/') }}/CentroPobladoDatass/Grafico_PorRegion_segunColumna/cuota_familiar" ,
            type: 'post',
        }).done(function (data) {               
            $('#Graficocuota_familiar').html(data);
        }).fail(function () {
            alert("Lo sentimos a ocurrido un error");
        });
    }

    function Grafico_servicio_agua_continuo() {
        
        $.ajax({  
            headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
            },                           
            url: "{{ url('/') }}/CentroPobladoDatass/Grafico_PorRegion_segunColumna/servicio_agua_continuo" ,
            type: 'post',
        }).done(function (data) {               
            $('#Graficoservicio_agua_continuo').html(data);
        }).fail(function () {
            alert("Lo sentimos a ocurrido un error");
        });
    }
 

    function Grafico_realiza_cloracion_agua() {
        
        $.ajax({  
            headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
            },                           
            url: "{{ url('/') }}/CentroPobladoDatass/Grafico_PorRegion_segunColumna/realiza_cloracion_agua" ,
            type: 'post',
        }).done(function (data) {               
            $('#Graficorealiza_cloracion_agua').html(data);
        }).fail(function () {
            alert("Lo sentimos a ocurrido un error");
        });
    }
 

    function Grafico_PorRegion_CP_Periodos() {
        
        $.ajax({  
            headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
            },                           
            url: "{{ url('/') }}/CentroPobladoDatass/Grafico_PorRegion_CP_Periodos" ,
            type: 'post',
        }).done(function (data) {               
            $('#Grafico_PorRegion_CP_Periodos').html(data);
        }).fail(function () {
            alert("Lo sentimos a ocurrido un error");
        });
    }

    function Grafico_tipo_organizacion_comunal() {
        
        $.ajax({  
            headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
            },                           
            url: "{{ url('/') }}/CentroPobladoDatass/Grafico_tipo_organizacion_comunal/" + {{$importacion_id}} ,
            type: 'post',
        }).done(function (data) {               
            $('#Grafico_tipo_organizacion_comunal').html(data);
        }).fail(function () {
            alert("Lo sentimos a ocurrido un error");
        });
    }
 
    function Grafico_Asociados_organizacion_comunal() {
        
        $.ajax({  
            headers: {
                 'X-CSRF-TOKEN': $('input[name=_token]').val()
            },                           
            url: "{{ url('/') }}/CentroPobladoDatass/Grafico_Asociados_organizacion_comunal/" + {{$importacion_id}} ,
            type: 'post',
        }).done(function (data) {               
            $('#Grafico_Asociados_organizacion_comunal').html(data);
        }).fail(function () {
            alert("Lo sentimos a ocurrido un error");
        });
    }
    

 



</script>


@endsection
