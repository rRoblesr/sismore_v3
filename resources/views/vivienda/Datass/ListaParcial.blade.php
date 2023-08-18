

<div class="table-responsive">
    <table id="grid" class="table table-striped table-bordered" style="width:8500px">
        <thead class="text-primary">                              
            
            <th style="width:80px">departamento</th>
            <th style="width:80px">provincia</th>
            <th style="width:80px">distrito</th>
            <th style="width:80px">ubigeo_cp</th>
            <th style="width:80px">centro_poblado</th>
            <th style="width:80px">total_viviendas</th>
            <th style="width:80px">viviendas_habitadas</th>
            <th style="width:80px">total_poblacion</th>
            <th style="width:80px">predomina_primera_lengua</th>
            <th style="width:80px">tiene_energia_electrica</th>
            <th style="width:80px">tiene_internet</th>
            <th style="width:80px">tiene_establecimiento_salud</th>
            <th style="width:80px">pronoei</th>
            <th style="width:80px">primaria</th>
            <th style="width:80px">secundaria</th>
            <th style="width:80px">sistema_agua</th>
            <th style="width:80px">sistema_disposicion_excretas</th>
            <th style="width:80px">prestador_codigo</th>
            <th style="width:80px">prestador_de_servicio_agua</th>
            <th style="width:80px">tipo_organizacion_comunal</th>
            <th style="width:80px">cuota_familiar</th>
            <th style="width:80px">servicio_agua_continuo</th>
            <th style="width:80px">sistema_cloracion</th>
            <th style="width:80px">realiza_cloracion_agua</th>
            <th style="width:80px">tipo_sistema_agua</th>
        </thead>
        
    </table>
</div>          

@section('js')

  <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
  <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
  <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

  <script>
      $('#grid').DataTable({
          "ajax": "{{route('Datass.ListaImportada_DataTable',$importacion_id)}}",
          "columns":[             
              {data:'departamento'},{data:'provincia'},{data:'distrito'},{data:'ubigeo_cp'},{data:'centro_poblado'},
              {data:'total_viviendas'},{data:'viviendas_habitadas'},{data:'total_poblacion'},{data:'predomina_primera_lengua'},
              {data:'tiene_energia_electrica'},{data:'tiene_internet'},{data:'tiene_establecimiento_salud'},{data:'pronoei'},
              {data:'primaria'},{data:'secundaria'},{data:'sistema_agua'},{data:'sistema_disposicion_excretas'},{data:'prestador_codigo'},
              {data:'prestador_de_servicio_agua'},{data:'tipo_organizacion_comunal'},{data:'cuota_familiar'},{data:'servicio_agua_continuo'},
              {data:'sistema_cloracion'},{data:'realiza_cloracion_agua'},{data:'tipo_sistema_agua'}        
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

