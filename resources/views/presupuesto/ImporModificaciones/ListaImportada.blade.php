@extends('layouts.main',['activePage'=>'FuenteImportacion','titlePage'=>'IMPORTACION REALIZADA CON EXITO '])

@section('css')
     <!-- Table datatable css -->
     <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content') 

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado subido desde el excel</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="grid" class="table table-striped table-bordered"  style="width:7200px">
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
        <!-- card -->
    </div>
    <!-- col -->
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

