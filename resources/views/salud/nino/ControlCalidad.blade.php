@extends('layouts.main',['activePage'=>'importacion','titlePage'=>'PRUEBA DE CONTROL DE CALIDAD'])

@section('css')
    
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />

@endsection 

@section('content') 

<input type="hidden" id="hoja" value="1">


<div class="content">    
    <div class="row">
        <div class="col-md-12">           
            <div class="card">
                
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-md-1 col-form-label">Año</label>
                        <div class="col-md-2">
                            {{-- <select id="anio" name="anio" class="form-control" onchange="cargar_fechas_matricula();">                               
                                @foreach ($anios as $item)
                                    <option value="{{ $item->id }}"> {{ $item->anio }} </option>
                                @endforeach
                            </select> --}}
                        </div>
                       
                        <label class="col-md-1 col-form-label">Fecha</label>
                        {{-- <div class="col-md-2">
                            <select id="matricula_fechas" name="matricula_fechas" class="form-control"  onchange="cargar_resumen_matricula();">
                                @foreach ($fechas_matriculas as $item)
                                    <option value="{{ $item->matricula_id }}"> {{ $item->fechaActualizacion }} </option>
                                @endforeach
                            </select>
                        </div> --}}
                        
                    </div>                    
                           
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                    <br>
                    <div class="col-md-12">                       
                        <div class="portfolioFilter">
                            <a href="#" onClick="cargar_resumen_porUgel();"       class="current waves-effect waves-light">UGELES</a>
                            <a href="#" onClick="cargar_matricula_porDistrito();" class="waves-effect waves-light" > DISTRITOS </a>    
                            <a href="#" onClick="cargar_matricula_porInstitucion();" class="waves-effect waves-light" > INSTITUCIONES </a>                  
                        </div>                        
                    </div>

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



@endsection
