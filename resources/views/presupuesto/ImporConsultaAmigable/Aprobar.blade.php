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
                    <h3 class="card-title">DATOS DE IMPORTACION</h3>                           
                </div>
                
                <div class="card-body">
                    <div class="form">                
                        <form action="{{route('pemapacopsa.procesar',$importacion_id)}}" method="post" enctype='multipart/form-data'
                            class="cmxform form-horizontal tasi-form">                            
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
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="grid" class="table table-striped table-bordered">
                            <thead class="text-primary">                              
                                <th>cod_dist</th>
                                <th>distrito</th>
                                <th>cod_sector</th>
                                <th>cod_manzana</th>
                                <th>manzana_nombre</th>
                                <th>lote</th>
                                <th>nro_insc</th>
                                <th>nombres</th>
                                <th>ruc</th>
                                <th>direccion</th>
                                <th>urbanizacion</th>
                                <th>tipo_serv</th>
                                <th>tipo_servicio_nombre</th>
                                <th>est_conex</th>
                                <th>estado_conexion_nombre</th>
                                <th>unid_uso</th>
                                <th>sub_categ</th>
                                <th>sub_categoria_nombre</th>
                                <th>tar</th>
                                <th>num_med</th>
                                <th>lect_sec</th>
                                <th>rep_sec</th>
                            </thead>
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
      $('#grid').DataTable({
          "ajax": "{{route('pemapacopsa.listadoDT',$importacion_id)}}",
          "columns":[             
              {data:'cod_dist'},
              {data:'distrito'},
              {data:'cod_sector'},
              {data:'cod_manzana'},
              {data:'manzana_nombre'},
              {data:'lote'},
              {data:'nro_insc'},
              {data:'nombres'},
              {data:'ruc'},
              {data:'direccion'},
              {data:'urbanizacion'},
              {data:'tipo_serv'},
              {data:'tipo_servicio_nombre'},
              {data:'est_conex'},
              {data:'estado_conexion_nombre'},
              {data:'unid_uso'},
              {data:'sub_categ'},
              {data:'sub_categoria_nombre'},
              {data:'tar'},
              {data:'num_med'},
              {data:'lect_sec'},
              {data:'rep_sec'},
          ],
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
      }

      );
  </script>
@endsection

