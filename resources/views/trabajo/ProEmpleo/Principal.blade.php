@extends('layouts.main',['titlePage'=>''])

@section('content') 
<div class="content">
    
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">                             
                            <div class="row">
                                <div  class="col-md-9">                                   
                                </div>

                                <label class="col-md-1 col-form-label">Año:</label>
                                                        
                                <div class="col-md-2">
                                    <select id="anio" name="anio" class="form-control" onchange="cargar_datos();">                                
                                        @foreach ($anios as $item)
                                            <option value="{{ $item->id }}"> {{ $item->anio }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
           
                    </div>
                </div>
            </div> <!-- End row -->
        </div>
    </div> <!-- End row -->

    <div class="row">
        <div class="col-md-12">
                {{-- @include('trabajo.ProEmpleo.VariablesMercadoParcial')       --}}
                <div id="VariablesMercado">       
                    {{-- se carga con el scrip lineas abajo --}}
                </div>  
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="alert alert-info">                                                          
                <div id="Grafico_Colocados_Hombres_Vs_Mujeres">       
                    {{-- se carga con el scrip lineas abajo --}}
                </div>                     
            </div>                 
        </div>   
    </div> <!-- End row -->

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="alert alert-info">                                                          
                <div id="Grafico_oferta_demanda_colocados">       
                    {{-- se carga con el scrip lineas abajo --}}
                </div>                     
            </div>                 
        </div>
    </div><!-- End row -->

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="alert alert-info">                                                          
                <div id="Grafico_Colocados_per_Con_Discapacidad">       
                    {{-- se carga con el scrip lineas abajo --}}
                </div>                     
            </div>                 
        </div>
    </div><!-- End row -->

</div> {{-- fin content --}}

{{-- <h1>
    EMPRESAS QUE MAS CONTRATAN.... RANKING
</h1> --}}

@endsection

@section('js')

    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>

    <script type="text/javascript"> 
        
        $(document).ready(function() {
            cargar_datos() ;        
        });


        function cargar_datos(){
            Grafico_Colocados_Hombres_Vs_Mujeres(); 
            Grafico_Colocados_per_Con_Discapacidad();  
            Grafico_oferta_demanda_colocados() ;    
            VariablesMercado();
        }

        function VariablesMercado() {
            
            $.ajax({  
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/ProEmpleo/VariablesMercado/" + $('#anio').val(),
                type: 'post',
            }).done(function (data) {               
                $('#VariablesMercado').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function Grafico_oferta_demanda_colocados() {
            
            $.ajax({  
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/ProEmpleo/Grafico_oferta_demanda_colocados/" + $('#anio').val(),
                type: 'post',
            }).done(function (data) {               
                $('#Grafico_oferta_demanda_colocados').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function Grafico_Colocados_Hombres_Vs_Mujeres() {
            
            $.ajax({  
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/ProEmpleo/Grafico_Colocados_Hombres_Vs_Mujeres/" + $('#anio').val(),
                type: 'post',
            }).done(function (data) {               
                $('#Grafico_Colocados_Hombres_Vs_Mujeres').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function Grafico_Colocados_per_Con_Discapacidad() {
            
            $.ajax({  
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/ProEmpleo/Grafico_Colocados_per_Con_Discapacidad/" + $('#anio').val(),
                type: 'post',
            }).done(function (data) {               
                $('#Grafico_Colocados_per_Con_Discapacidad').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }
       
       
    </script>
    
@endsection

