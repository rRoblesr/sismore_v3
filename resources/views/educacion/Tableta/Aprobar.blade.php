@extends('layouts.main',['activePage'=>'importacion','titlePage'=>'APROBAR IMPORTACION'])

@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content') 
<div class="content">
    
    <div class="row">
        <div class="col-md-12">           
            <div class="card">
                
                <div class="card-header">
                    <h3 class="card-title">DATOS DE IMPORTACION </h3>                           
                </div>
                
                <div class="card-body">
                    <div class="form">
                
                    <form action="{{route('Tableta.procesar',$importacion_id)}}" method="post" enctype='multipart/form-data'  class="cmxform form-horizontal tasi-form">                            
                        @csrf
                        @if(Session::has('message'))
                            <p>{{Session::get('message')}}</p>
                        @endif

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Fuente de datos</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" readonly="readonly" value="{{$importacion->formato}} - {{$importacion->nombre}}">                                
                            </div>
                        
                            <label class="col-md-2 col-form-label">Fecha Versión</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" readonly="readonly" value="{{$importacion->fechaActualizacion}}">                              
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Usuario Creación</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" readonly="readonly" value="{{$importacion->usuario}}">                                
                            </div>
                       
                            <label class="col-md-2 col-form-label">Fecha Creación</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" readonly="readonly" value="{{$importacion->created_at}}">                           
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Comentario</label>
                            <div class="col-md-10">
                                <textarea class="form-control"  id="ccomment" readonly="readonly" name="comentario" >{{$importacion->comentario}}</textarea>                                                     
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="offset-lg-2 col-lg-10">
                                <button class="btn btn-success waves-effect waves-light mr-1" type="submit">Guardar</button>
                                <button class="btn btn-secondary waves-effect" id="btnEliminar" type="button" 
                                onClick="{{route('importacion.inicio')}}">Cancelar</button>
                            </div>
                        </div>
                      
                    </form>

                    </div>
                </div>               
                <!-- card-body -->
                    
                    {{-- <div class="card-body">                 
                       
                        <h3 class="card-title">La importacion contiene la siguiente cantidad de filas por archivo:</h3>                    
                    
                        @foreach ($datos_matricula_importada as $item)
                         
                               <h6> {{$item->nivel}} : {{$item->numeroFilas}} filas </h6>                  
                           
                        @endforeach
    
                    </div> --}}
            
                <!-- card-body -->
            </div>
              
        </div> <!-- End col -->
    </div> <!-- End row -->
  
</div>
@endsection 
