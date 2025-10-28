@extends('layouts.main',['titlePage'=>'INDICADORES DE LA DIRECCION REGIONAL DE TRABAJO SEGÚN EL PLAN DE DESARROLLO REGIONAL CONCERTADO'])

@section('content')


{{-- 
<div class="row">
    <div class="col-md-12 col-xl-12">
       <div class="card-box">
        <h5 class="mb-0 mt-1 text-truncate">INDICADORES DE LA DIRECCION REGIONAL DE TRABAJO SEGÚN EL PLAN DE DESARROLLO REGIONAL CONCERTADO</h5>
      
       </div>
    </div>
</div> --}}



<div class="row">
    
    <div class="col-md-12 col-xl-12">
       <div class="card-box">

        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card-box">
                    <h5 class="mb-0 mt-0 text-truncate">PPR 0103 - FORTALECIMIENTO DE LAS CONDICIONES LABORALES AL 2021</h5>
                    <br>
                </div>
            </div>

            
        </div>

            <div class="row">
           
                <div class="col-md-6 col-xl-6">
                    <div class="alert alert-info"> 
                      
                        <div class="card card-border card-primary"> 
                            <div class="card-body">
                                <h3 class="card-title text-secundary ">Porcentaje de la PEA ocupada asalariada del sector privado con contrato</h3>
                                <br>
                                <div class="table-responsive">
                                    <table style="width: 100%;" border="1px solid #000" >
                                        <thead>
                                            <tr>
                                                <th  class="titulo_tabla">Indicador</th>               
                                                <th class="titulo_tabla" > Absoluto</th>
                                                <th class="titulo_tabla" > %</th>                        
                                            </tr>                    
                                        </thead>

                                        <tbody>
                                        
                                                <tr>                                            
                                                    <td class="titulo_tabla">Con Contrato</td>                
                                                    <td class="titulo_tabla">22,810</td>
                                                    <td class="titulo_tabla">22,4</td>                           
                                                </tr> 
                                                
                                                <tr>                                            
                                                    <td class="titulo_tabla">Sin Contrato</td>                
                                                    <td class="titulo_tabla">79,006</td>
                                                    <td class="titulo_tabla">77,6</td>                           
                                                </tr> 
                                        
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            
            </div>


           

            <div class="row">
                <div class="col-md-6 col-xl-6">
                    <div class="alert alert-info"> 

                        <div class="card card-border card-primary"> 
                            <div class="card-body">

                                <h3 class="card-title text-secundary ">Porcentaje de la PEA ocupada asalariada del sector privado con algún sistema de pensiones</h3>
                                <br>

                                <div class="table-responsive">
                                    <table style="width: 100%;" border="1px solid #000" >
                                        <thead>
                                            <tr>
                                                <th class="titulo_tabla"> Indicador</th>               
                                                <th class="titulo_tabla"> Absoluto</th>
                                                <th class="titulo_tabla"> %</th>                        
                                            </tr>                    
                                        </thead>
                                        <tbody>
                                        
                                                <tr>
                                                    <td class="titulo_tabla">Afiliado</td>                
                                                    <td class="titulo_tabla">32,132</td>
                                                    <td class="titulo_tabla">31,6</td>                           
                                                </tr> 
                                                
                                                <tr>                                            
                                                    <td class="titulo_tabla">No Afiliado</td>                
                                                    <td class="titulo_tabla">69,684</td>
                                                    <td class="titulo_tabla">68,4</td>                           
                                                </tr>

                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6 col-xl-6">
                    <div class="alert alert-info"> 


                        <div class="card card-border card-primary"> 
                            <div class="card-body">

                                <h3 class="card-title text-secundary ">Porcentaje de la PEA ocupada asalariada del sector privado con afiliación a algún seguro de salud</h3>
                                <br>

                                <div class="table-responsive">
                                    <table style="width: 100%;" border="1px solid #000" >
                                        <thead>
                                            <tr>
                                                <th  class="titulo_tabla">Indicador</th>               
                                                <th class="titulo_tabla" > Absoluto</th>
                                                <th class="titulo_tabla" > %</th>                        
                                            </tr>                    
                                        </thead>

                                        <tbody>
                                        
                                                <tr>                                            
                                                    <td class="titulo_tabla">Afiliado</td>                
                                                    <td class="titulo_tabla">70,277</td>
                                                    <td class="titulo_tabla">69,0</td>                           
                                                </tr> 
                                                
                                                <tr>                                            
                                                    <td class="titulo_tabla">No Afiliado</td>                
                                                    <td class="titulo_tabla">31,539</td>
                                                    <td class="titulo_tabla">31,0</td>                           
                                                </tr> 
                                        
                                        </tbody>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div  class="col-md-12">
                Fuente: INEI - Encuesta Nacional de Hogares sobre Condiciones de Vida y Pobreza        
                </div>

            </div> 

        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="card-box">
            <h5 class="mb-0 mt-0 text-truncate"> OET 1 - MEJORAR LA CALIDAD DE VIDA DE LA POBLACION VULNERABLE DE LA REGION</h5>
            <br>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xl-12">
       <div class="card-box">     
            @include('trabajo.Indicadores.PeaParcial')
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12 col-xl-12">
       <div class="card-box">     
            @include('trabajo.Indicadores.PeaIPMParcial')
        </div>
    </div>

</div>
@endsection




@section('js')

    <script src="{{ asset('/') }}assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}assets/libs/highcharts-modules/accessibility.js"></script>

    <script type="text/javascript"> 
        
        $(document).ready(function() {
            cargar_datos() ;        
        });


        function cargar_datos(){
            Grafico_PEA();
            Grafico_PEA_IPM();
        }

        function Grafico_PEA() {
            
            $.ajax({  
                // headers: {
                //      'X-CSRF-TOKEN': $('input[name=_token]').val()
                // },                           
                url: "{{ url('/') }}/IndicadorTrabajo/Grafico_PEA/" + 0,
                type: 'get',
            }).done(function (data) {               
                $('#Grafico_PEA').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function Grafico_PEA_IPM() {
            
            $.ajax({  
                // headers: {
                //      'X-CSRF-TOKEN': $('input[name=_token]').val()
                // },                           
                url: "{{ url('/') }}/IndicadorTrabajo/Grafico_PEA_IPM/" + 0,
                type: 'get',
            }).done(function (data) {               
                $('#Grafico_PEA_IPM').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }


       
       
    </script>
    
@endsection