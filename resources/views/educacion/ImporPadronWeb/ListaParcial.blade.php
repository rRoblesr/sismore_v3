
<div class="table-responsive">
    <table id="PadronWeb" class="table table-striped table-bordered" style="width:7200px">
        <thead class="text-primary">                                       
            
            <th style="width:100px">CodModular</th>
            <th style="width:80px">Anexo</th>
            <th style="width:100px">CodLocal</th>
            <th style="width:300px">Nombre</th>
            <th style="width:60px">CodNivel</th>
            <th style="width:240px">Nivel</th>
            <th style="width:80px">Forma</th>
            <th style="width:80px">cod_Car</th>
            <th style="width:160px">d_Cod_Car</th>
            <th style="width:80px">TipsSexo</th>
            <th style="width:80px">d_TipsSexo</th>
            <th style="width:80px">gestion</th>
            <th style="width:200px">d_Gestion</th>
            <th style="width:80px">ges_Dep</th>
            <th style="width:200px">d_Ges_Dep</th>                                            
            <th style="width:360px">director</th>
            <th style="width:80px">telefono</th>
            <th style="width:100px">email</th>
            <th style="width:80px">pagWeb</th>
            <th style="width:400px">dir_Cen</th>
            <th style="width:380px">referencia</th>
            <th style="width:180px">localidad</th>
            <th style="width:80px">codcp_Inei</th>
            <th style="width:80px">codccpp</th>
            <th style="width:280px">cen_Pob</th>
            <th style="width:80px">area_Censo</th>
            <th style="width:80px">d_areaCenso</th>
            <th style="width:80px">codGeo</th>
            <th style="width:180px">d_Dpto</th>
            <th style="width:180px">d_Prov</th>
            <th style="width:180px">d_Dist</th>
            <th style="width:120px">d_Region</th>
            <th style="width:80px">codOOII</th>
            <th style="width:220px">d_DreUgel</th>
            <th style="width:80px">tipoProg</th>
            <th style="width:140px">d_TipoProg</th>
            <th style="width:80px">cod_Tur</th>
            <th style="width:180px">D_Cod_Tur</th>
            <th style="width:80px">estado</th>
            <th style="width:80px">d_Estado</th>
            <th style="width:180px">fecha_Act</th>                                          
            
        </thead>
      
    </table>
</div>
                
@section('js')
  
    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

    <script>
        $('#PadronWeb').DataTable({
            "ajax": "{{route('PadronWeb.ListaImportada_DataTable',$importacion_id)}}",
            "columns":[             
                {data:'cod_Mod'},{data:'anexo'},{data:'cod_Local'},{data:'cen_Edu'},{data:'niv_Mod'},
                {data:'d_Niv_Mod'},{data:'d_Forma'},{data:'cod_Car'},{data:'d_Cod_Car'},{data:'TipsSexo'},
                {data:'d_TipsSexo'},{data:'gestion'},{data:'d_Gestion'},{data:'ges_Dep'},{data:'d_Ges_Dep'},
                {data:'director'},{data:'telefono'},{data:'email'},{data:'pagWeb'},{data:'dir_Cen'},
                {data:'referencia'},{data:'localidad'},{data:'codcp_Inei'},{data:'codccpp'},{data:'cen_Pob'},
                {data:'area_Censo'},{data:'d_areaCenso'},{data:'codGeo'},{data:'d_Dpto'},{data:'d_Prov'},
                {data:'d_Dist'},{data:'d_Region'},{data:'codOOII'},{data:'d_DreUgel'},{data:'tipoProg'},
                {data:'d_TipoProg'},{data:'cod_Tur'},{data:'D_Cod_Tur'},{data:'estado'},{data:'d_Estado'},
                {data:'fecha_Act'},                
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