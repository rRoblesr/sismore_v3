
<div class="table-responsive">
    <table id="grid" class="table table-striped table-bordered" style="width:9300px">
        <thead class="text-primary">                              
            
          <th style="width:80px">codLocal</th>
          <th style="width:100px">codigosModulares</th>
          <th style="width:200px">nombreInstitucion</th>
          <th style="width:80px">codigoGestion</th>
          <th style="width:250px">descripcionGestion</th>
          <th style="width:80px">codigoOrganoInter</th>
          <th style="width:280px">nombreDre_Ugel</th>
          <th style="width:80px">codigoUbigeo</th>
          <th style="width:100px">Departamento</th>
          <th style="width:100px">Provincia</th>
          <th style="width:100px">Distrito</th>
          <th style="width:200px">centoPoblado</th>
          <th style="width:400px">direccion</th>
          <th style="width:80px">areaGeo</th>
          <th style="width:200px">estadoCenso</th>
          <th style="width:80px">totalAulas</th>
          <th style="width:80px">aulasBuenas</th>
          <th style="width:80px">aulasRegulares</th>
          <th style="width:80px">aulasMalas</th>
          <th style="width:80px">noPuedePrecisarEstadoAulas</th>
          <th style="width:80px">elLocalEs</th>
          <th style="width:100px">propietarioLocal</th>
          <th style="width:80px">cuenta_con_itse</th>
          <th style="width:80px">plan_contingencia</th>
          <th style="width:80px">plan_desastre</th>
          <th style="width:80px">plandesastre_act</th>
          <th style="width:80px">compuEscri_operativos</th>
          <th style="width:80px">compuEscri_inoperativos</th>
          <th style="width:80px">compuPorta_operativos</th>
          <th style="width:80px">compuPorta_inoperativos</th>
          <th style="width:80px">lapto_operativos</th>
          <th style="width:80px">lapto_inoperativos</th>
          <th style="width:80px">tieneInternet</th>
          <th style="width:100px">tipoConexion</th>
          <th style="width:100px">fuenteEnergiaElectrica</th>
          <th style="width:100px">empresaEnergiaElect</th>
          <th style="width:100px">tieneEnergiaElectTodoDia</th>
          <th style="width:100px">fuenteAgua</th>
          <th style="width:100px">empresaAgua</th>
          <th style="width:100px">tieneAguaPotTodoDia</th>
          <th style="width:300px">desagueInfo</th>
      
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
            "ajax": "{{route('Censo.ListaImportada_DataTable',$importacion_id)}}",
            "columns":[             
              {data:'codLocal'},{data:'codigosModulares'},{data:'nombreInstitucion'},{data:'codigoGestion'},
              {data:'descripcionGestion'},{data:'codigoOrganoInter'},{data:'nombreDre_Ugel'},{data:'codigoUbigeo'},
              {data:'Departamento'},{data:'Provincia'},{data:'Distrito'},{data:'centoPoblado'},{data:'direccion'},
              {data:'areaGeo'},{data:'estadoCenso'},{data:'totalAulas'},{data:'aulasBuenas'},{data:'aulasRegulares'},
              {data:'aulasMalas'},{data:'noPuedePrecisarEstadoAulas'},{data:'elLocalEs'},{data:'propietarioLocal'},
              {data:'cuenta_con_itse'},{data:'plan_contingencia'},{data:'plan_desastre'},{data:'plandesastre_act'},
              {data:'compuEscri_operativos'},{data:'compuEscri_inoperativos'},{data:'compuPorta_operativos'},
              {data:'compuPorta_inoperativos'},{data:'lapto_operativos'},{data:'lapto_inoperativos'},{data:'tieneInternet'},
              {data:'tipoConexion'},{data:'fuenteEnergiaElectrica'},{data:'empresaEnergiaElect'},
              {data:'tieneEnergiaElectTodoDia'},{data:'fuenteAgua'},{data:'empresaAgua'},{data:'tieneAguaPotTodoDia'},
              {data:'desagueInfo'},              
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

