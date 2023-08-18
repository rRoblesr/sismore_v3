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
                    <table id="grid" class="table table-striped table-bordered">
                        <thead class="text-primary">                              
                            <tr>
                                <th>codigo modular</th>
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

                        </tbody>
                        
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
          "ajax": "{{route('ece.importar.listadoDT',$importacion_id)}}",
          "columns":[             
              {data:'codigo_modular'},
              {data:'programados'},
              {data:'materia'},
              {data:'evaluados'},
              {data:'previo'},
              {data:'inicio'},
              {data:'proceso'},
              {data:'satisfactorio'},
              {data:'mediapromedio'}, 
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

