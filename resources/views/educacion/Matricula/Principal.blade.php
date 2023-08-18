@extends('layouts.main',['activePage'=>'importacion','titlePage'=>'MATRICULAS'])

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
                       
                        <label class="col-md-1 col-form-label">Fecha:</label>
                        <div class="col-md-2">
                            <select id="matricula_fechas" name="matricula_fechas" class="form-control"  onchange="cambia_fecha();">
                                @foreach ($fechas_matriculas as $item)
                                    <option value="{{ $item->matricula_id }}"> {{ $item->fechaActualizacion }} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                        </div>

                        <label class="col-md-1 col-form-label">Gestión:</label>
                        <div class="col-md-2">
                            <select id="gestion" name="gestion" class="form-control" onchange="cambia_gestion();" >                              
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
                    <div class="col-md-12">                       
                        <div class="portfolioFilter">
                            <a href="#" onClick="cargar_inicio();" class="current waves-effect waves-light">INICIO</a>
                            <a href="#" onClick="cargar_resumen_porUgel();"       class="waves-effect waves-light">UGELES</a>
                            <a href="#" onClick="cargar_matricula_porDistrito();" class="waves-effect waves-light" > DISTRITOS </a>    
                            <a href="#" onClick="cargar_matricula_porInstitucion();" class="waves-effect waves-light" > INSTITUCIONES </a>  
                            <a href="#" onClick="cargar_Grafico();" class="waves-effect waves-light" > GRAFICA </a>                  
                        </div>                        
                    </div>

                    {{-- <br>
                    <div id="datos01" class="form-group row">                        
                            Cargando datos.....                        
                    </div>
                    --}}

                    
                    <div class="content" >
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">      
                                        <div id="barra1" class="form-group row">       
                                           
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
            //cargar_inicio();
            cargar_fechas_matricula(); 
        });

        function cargar_inicio() {            
            $('#hoja').val(0);
            $.ajax({  
                headers: {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/Matricula/inicio/" + $('#matricula_fechas').val() + "/" + $('#gestion').val()+ "/" + {{$tipo}},
                type: 'post',
            }).done(function (data) {               
                $('#barra1').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }
        
        function cargar_fechas_matricula() {
           
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/Matricula/Fechas/" + $('#anio').val(),
                type: 'post',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    $("#matricula_fechas option").remove();
                    
                    var options = null;
                    
                    $.each(data.fechas_matriculas, function(index, value) {
                        options += "<option value='" + value.matricula_id + "'>" + value.fechaActualizacion + "</option>";                       
                    });
                    
                    $("#matricula_fechas").append(options);                  
                    cambia_fecha(); 
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                   
                },
            });
            
            //cargar_Grafico();
        }

        function cambia_fecha() {   

            if($('#hoja').val()==0)  
                cargar_inicio();
            else  
            {
                if($('#hoja').val()==1)
                    cargar_resumen_porUgel();                      
                else
                {
                    if($('#hoja').val()==2)
                        cargar_matricula_porDistrito();                       
                    else
                    {
                        if($('#hoja').val()==3)
                            cargar_matricula_porInstitucion();  
                        else
                            cargar_Grafico();
                    }
                        
                }
                    
            }       
                               
        }

        function cargar_resumen_porUgel() {            
            $('#hoja').val(1);
            $.ajax({  
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/Matricula/ReporteUgel/" + $('#anio').val() + "/" + $('#matricula_fechas').val()+ "/" + $('#gestion').val()+ "/" + {{$tipo}},
                type: 'post',
            }).done(function (data) {               
                $('#barra1').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function cargar_matricula_porDistrito() {
            $('#hoja').val(2);
            $.ajax({  
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/Matricula/ReporteDistrito/" + $('#anio').val() + "/" + $('#matricula_fechas').val()+ "/" + $('#gestion').val()+ "/" + {{$tipo}},
                type: 'post',
            }).done(function (data) {               
                $('#barra1').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function cargar_matricula_porInstitucion() {
            $('#hoja').val(3);
            $.ajax({  
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/Matricula/ReporteInstitucion/" + $('#anio').val() + "/" + $('#matricula_fechas').val()+ "/" + $('#gestion').val()+ "/" + {{$tipo}},
                type: 'post',
            }).done(function (data) {               
                $('#barra1').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function cargar_Grafico() {
            $('#hoja').val(4);
            $.ajax({  
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/Matricula/GraficoBarrasPrincipal/"+ $('#anio').val()+ "/" + $('#gestion').val()+ "/" + {{$tipo}},
                type: 'post',
            }).done(function (data) {  
                // $('#datos01').html();             
                $('#barra1').html(data);                
             
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function cambia_gestion() {            
           
            cambia_fecha();
           
        }
  
       
    </script>



@endsection
