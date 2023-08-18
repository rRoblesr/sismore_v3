
<div class="table-responsive">
 <table id="tabla" class="table table-striped table-bordered" style="width:1700px">
     <thead class="text-primary">                                       
         
         <th style="width:200px">REGIONES</th>
         <th style="width:100px">ENERO</th>  
         <th style="width:100px">FEBRERO</th>  
         <th style="width:100px">MARZO</th>          
         <th style="width:100px">ABRIL</th>  
         <th style="width:100px">MAYO</th>
         <th style="width:100px">JUNIO</th>  
         <th style="width:100px">JULIO</th>
         <th style="width:100px">AGOSTO</th>  
         <th style="width:100px">SETIEMBRE</th>
         <th style="width:100px">OCTUBRE</th>  
         <th style="width:100px">NOVIEMBRE</th>
         <th style="width:100px">DICIEMBRE</th>  
         
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
         "ajax": "{{route('AnuarioEstadistico.ListaImportada_DataTable',$importacion_id)}}",
         "columns":[             
             {data:'nombre'},{data:'enero'} ,{data:'febrero'}  ,{data:'marzo'} ,   
             {data:'abril'},{data:'mayo'} ,{data:'junio'}  ,{data:'julio'},
             {data:'agosto'},{data:'setiembre'} ,{data:'octubre'}  ,{data:'noviembre'} ,
             {data:'diciembre'}              
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