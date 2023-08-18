
<div class="table-responsive">
 <table id="tabla" class="table table-striped table-bordered" style="width:4200px">
     <thead class="text-primary">                                       
         
         <th style="width:200px">Ruc</th>
         <th style="width:400px">Empresa</th>  
         <th style="width:400px">Titulo</th>  
         <th style="width:400px">Provincia</th> 
         
         <th style="width:400px">Distrito</th>  
         <th style="width:200px">Tipo Documento</th>
         <th style="width:200px">Documento</th>  
         <th style="width:400px">Nombres</th>

         <th style="width:400px">apellidos</th>  
         <th style="width:200px">sexo</th>
         <th style="width:200px">per_Con_Discapacidad</th>  
         <th style="width:400px">email</th>

         <th style="width:400px">telefono1</th>  
         <th style="width:200px">telefono2</th>
         <th style="width:200px">colocado</th>  
         <th style="width:400px">fuente</th>
         <th style="width:400px">observaciones</th>
         
     </thead>
   
 </table>
</div>
             
@section('js')

 <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
 <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
 <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
 <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

 {{-- 'apellidos','sexo','per_Con_Discapacidad','email','telefono1','telefono2',
          'colocado','fuente','observaciones',) --}}
 <script>
     $('#tabla').DataTable({
         "ajax": "{{route('ProEmpleo.ListaImportada_DataTable',$importacion_id)}}",
         "columns":[             
             {data:'ruc'},{data:'empresa'} ,{data:'titulo'}  ,{data:'provincia'} ,   
             {data:'distrito'},{data:'tipDoc'} ,{data:'documento'}  ,{data:'nombres'},
             {data:'apellidos'},{data:'sexo'} ,{data:'per_Con_Discapacidad'}  ,{data:'email'} ,
             {data:'telefono1'},{data:'telefono2'} ,{data:'colocado'}  ,{data:'fuente'}  ,{data:'observaciones'}              
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