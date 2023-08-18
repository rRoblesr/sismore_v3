@extends('layouts.main',['activePage'=>'importacion','titlePage'=>'DISTRIBUCIÓN DE TEXTOS ESCOLARES'])

@section('css')

@endsection 

@section('content')  




<div class="content">
    <input type="hidden" id="hoja" value="1">
    
    <div class="row">
        <div class="col-md-12">           
            <div class="card">
                
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-md-1 col-form-label">Año</label>
                        <div class="col-md-2">
                            <select id="anio" name="anio" class="form-control" onchange="cargar_fechas();">                               
                                @foreach ($anios as $item)
                                    <option value="{{ $item->id }}"> {{ $item->anio }} </option>
                                @endforeach
                            </select>
                        </div>
                       
                        <label class="col-md-1 col-form-label">Fecha</label>
                        <div class="col-md-2">
                            <select id="fechas" name="matricula_fechas" class="form-control"  onchange="cargar_resumen();">
                                @foreach ($fechas as $item)
                                    <option value="{{ $item->tableta_id }}"> {{ $item->fechaActualizacion }} </option>
                                @endforeach
                            </select>
                        </div>                        
                    </div>                    
                           
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                    <br>

                    <br>
                    <div id="datos01" class="form-group row">                        
                            Cargando datos.....                        
                    </div>

                </div>               
                <!-- card-body -->

            </div>
              
        </div> <!-- End col -->
    </div> <!-- End row -->
  
</div>


@endsection 

@section('js')


    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>

    <script type="text/javascript"> 
        
        $(document).ready(function() {
            cargar_fechas();        
        });

        
        function cargar_fechas() {
           
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/TextosEscolares/Fechas/" + $('#anio').val(),
                type: 'post',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                    $("#fechas option").remove();
                    
                    var options = null;
                    
                    $.each(data.fechas_TextosEscolares, function(index, value) {
                        options += "<option value='" + value.tableta_id + "'>" + value.fechaActualizacion + "</option>";                       
                    });
                    
                    $("#fechas").append(options);                  
                    cargar_resumen_porUgel(); 
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                   
                },
            });
            
        }

        function cargar_resumen_porUgel() { 
           
            // alert($('#fechas').val());
            $.ajax({  
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/TextosEscolares/ReporteUgel/"  + $('#fechas').val(),
                type: 'post',
            }).done(function (data) {               
                $('#datos01').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
           
        }      

       
       
    </script>
    
@endsection
