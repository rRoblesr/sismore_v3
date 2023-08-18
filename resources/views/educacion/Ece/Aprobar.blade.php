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
                
                    <form action="{{route('ece.importar.aprobar.guardar',compact('importacion'))}}" method="get" 
                        class="cmxform form-horizontal tasi-form">                            
                        @csrf
                        @if(Session::has('message'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ Session::get('message') }}.
                        </div>
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
                            <label class="col-md-2 col-form-label">Comentario </label>
                            <div class="col-md-10">
                                <textarea class="form-control"  id="ccomment" readonly="readonly" name="comentario" >{{$importacion->comentario}}</textarea>                                                     
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Año</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" readonly="readonly" value="{{$ece->anio}}">
                            </div>

                            <label class="col-md-2 col-form-label">Alumno EIB</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" readonly="readonly" value="{{$ece->tipo==0?'NO':'SI'}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Nivel</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" readonly="readonly" value="{{$ece->nivel}}">
                            </div>
                        
                            <label class="col-md-2 col-form-label">Grado</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" readonly="readonly" value="{{$ece->grado}}">
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="offset-lg-2 col-lg-10">
                                @if ($importacion->estado=='PE')
                                <button class="btn btn-success waves-effect waves-light mr-1" type="submit">Guardar</button>    
                                @endif
                                <button class="btn btn-secondary waves-effect" id="btnEliminar" type="button" 
                                onClick="{{route('importacion.inicio')}}">Cancelar</button>
                            </div>
                        </div>
                      
                    </form>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0;font-size:11px; width: 100%;">
                            <thead class="text-primary">    
                                <tr>
                                    <th style="">codigo modular</th>
                                    <th>programados</th>
                                    <th>materia</th>
                                    <th>evaluados</th>
                                    <th>previo</th>
                                    <th>inicio</th>
                                    <th>proceso</th>
                                    <th>satisfactorio</th>
                                    <th>media promedio</th>  
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resultados as $item)
                                <tr>
                                    <td>{{$item->codigo_modular}}</td>
                                    <td>{{$item->programados}}</td>
                                    <td>{{$item->materia}}</td>
                                    <td>{{$item->evaluados}}</td>
                                    <td>{{$item->previo}}</td>
                                    <td>{{$item->inicio}}</td>
                                    <td>{{$item->proceso}}</td>
                                    <td>{{$item->satisfactorio}}</td>
                                    <td>{{round($item->mediapromedio,2)}}</td>
                                </tr>
                                
                                @endforeach
                            </tbody>
                            
                        </table>
                    </div>
                    
                </div>
                <!-- card-body -->
                
            </div>
              
        </div> <!-- End col -->
    </div> <!-- End row -->
  
</div>
@endsection 

@section('js')
    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
    <script>
        $('#datatable').DataTable({
            // responsive:true,
            autoWidth:true,
            "language": {
            "lengthMenu": "Mostrar "+
            `<select class="custom-select custom-select-sm form-control form-control-sm">
                <option value = '10'> 10</option>
                <option value = '25'> 25</option>
                <option value = '50'> 50</option>
                <option value = '100'>100</option>
                <option value = '-1'>Todos</option>
                </select>` + " registros por página",          
            "info": "Mostrando la página _PAGE_ de _PAGES_" ,
            "infoEmpty": "No records available",
            "infoFiltered": "(Filtrado de _MAX_ registros totales)",  
            "emptyTable":			"No hay datos disponibles en la tabla.",
            "info":		   			"Del _START_ al _END_ de _TOTAL_ registros ",
            "infoEmpty":			"Mostrando 0 registros de un total de 0. registros",
            "infoFiltered":			"(filtrados de un total de _MAX_ )",
            "infoPostFix":			"",           
            "loadingRecords":		"Cargando...",
            "processing":			"Procesando...",
            "search":				"Buscar:",
            "searchPlaceholder":	"Dato para buscar",
            "zeroRecords":			"No se han encontrado coincidencias.",
            
            "paginate":{
                "next":"siguiente",
                "previous":"anterior"
                }
            }
        }); 
    </script>



@endsection