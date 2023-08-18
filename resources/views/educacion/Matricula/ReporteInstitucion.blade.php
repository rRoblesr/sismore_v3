

@if($tipoDescrip !='EBE')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header bg-primary py-3 text-white">
                        <div class="card-widgets">
                        
                            <a data-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase2">
                                <i class="mdi mdi-minus"></i>
                            </a>
                        
                        </div>
                        <h5 class="card-title mb-0 text-white"> Reporte de Matrícula {{$tipoDescrip}} - Nivel Inicial al {{$fecha_Matricula_texto}}  </h5>
                    </div>
                    <div id="cardCollpase1" class="collapse show">
                        <div class="card-body">
                            
                            <div class="table-responsive">
                                <table id="grid" class="table table-striped table-bordered" style="width:3100px">
                                    <thead > 
                        
                                        <tr >
                                            <th rowspan="4" class="titulo_tabla" style="width:300px">UGEL </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:200px">PROVINCIA </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:200px">DISTRITO </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:300px">CENTRO POBLADO </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:100px">COD-MODULAR </th> 
                                            <th rowspan="4" class="titulo_tabla" style="width:100">ANEXO </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:500px">NOMBRE INSTITUCION </th> 
                                            <th rowspan="4" class="titulo_tabla" style="width:500px">TIPO GESTION </th> 
                                            <th rowspan="4" class="titulo_tabla" style="width:200px">TOTAL ESTUDIANTES MATRICULADOS </th> 
                                        </tr>

                                        <tr >
                                            <th colspan="14" class="titulo_tabla" > ESTUDIANTES POR EDAD </th>
                                        </tr>

                                        <tr >
                                            <th colspan="2" class="titulo_tabla" > 0 AÑOS </th>
                                            <th colspan="2" class="titulo_tabla" > 1 AÑO </th>
                                            <th colspan="2" class="titulo_tabla" > 2 AÑOS </th>
                                            <th colspan="2" class="titulo_tabla" > 3 AÑOS </th>
                                            <th colspan="2" class="titulo_tabla" > 4 AÑOS </th>
                                            <th colspan="2" class="titulo_tabla" > 5 AÑOS </th>
                                            <th colspan="2" class="titulo_tabla" > MAS DE 5 AÑOS </th>
                            
                                        </tr>

                                        <tr >
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                        </tr>

                        
                                    </thead>
                                </table>
                            </div>
                        
                            <div class="form-group row">
                                <div  class="col-md-6">
                                    Fuente: SIAGIE- MINEDU          
                                </div>
                        
                                <div  class="col-md-6" style="text-align: right">
                                    H: Hombre / M: Mujer   
                                </div>
                            </div> 
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header bg-primary py-3 text-white">
                        <div class="card-widgets">
                        
                            <a data-toggle="collapse" href="#cardCollpase2" role="button" aria-expanded="false" aria-controls="cardCollpase2">
                                <i class="mdi mdi-minus"></i>
                            </a>
                        
                        </div>
                        <h5 class="card-title mb-0 text-white"> Reporte de Matrícula {{$tipoDescrip}}- Nivel Primaria al {{$fecha_Matricula_texto}}  </h5>
                    </div>
                    <div id="cardCollpase2" class="collapse show">
                        <div class="card-body">
                            
                            <div class="table-responsive">
                                <table id="grid2" class="table table-striped table-bordered" style="width:3100px">
                                    <thead > 
                        
                                        <tr >
                                            <th rowspan="4" class="titulo_tabla" style="width:300px">UGEL </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:200px">PROVINCIA </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:200px">DISTRITO </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:300px">CENTRO POBLADO </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:100px">COD-MODULAR </th> 
                                            <th rowspan="4" class="titulo_tabla" style="width:100">ANEXO </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:500px">NOMBRE INSTITUCION </th> 
                                            <th rowspan="4" class="titulo_tabla" style="width:500px">TIPO GESTION </th> 
                                            <th rowspan="4" class="titulo_tabla" style="width:200px">TOTAL ESTUDIANTES MATRICULADOS </th> 
                                        </tr>

                                        <tr >
                                            <th colspan="12" class="titulo_tabla" > ESTUDIANTES POR GRADO </th>
                                        </tr>

                                        <tr >
                                            <th colspan="2" class="titulo_tabla" > PRIMERO </th>
                                            <th colspan="2" class="titulo_tabla" > SEGUNDO </th>
                                            <th colspan="2" class="titulo_tabla" > TERCERO </th>
                                            <th colspan="2" class="titulo_tabla" > CUARTO </th>
                                            <th colspan="2" class="titulo_tabla" > QUINTO </th>
                                            <th colspan="2" class="titulo_tabla" > SEXTO </th>                        
                                        </tr>

                                        <tr >
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                    
                                        </tr>

                        
                                    </thead>
                                </table>
                            </div>
                        
                            <div class="form-group row">
                                <div  class="col-md-6">
                                    Fuente: SIAGIE- MINEDU          
                                </div>
                        
                                <div  class="col-md-6" style="text-align: right">
                                    H: Hombre / M: Mujer   
                                </div>
                            </div> 
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header bg-primary py-3 text-white">
                        <div class="card-widgets">
                        
                            <a data-toggle="collapse" href="#cardCollpase3" role="button" aria-expanded="false" aria-controls="cardCollpase2">
                                <i class="mdi mdi-minus"></i>
                            </a>
                        
                        </div>
                        <h5 class="card-title mb-0 text-white"> Reporte de Matrícula {{$tipoDescrip}} - Nivel Secundaria al {{$fecha_Matricula_texto}}  </h5>
                    </div>
                    <div id="cardCollpase3" class="collapse show">
                        <div class="card-body">
                            
                            <div class="table-responsive">
                                <table id="grid3" class="table table-striped table-bordered" style="width:3100px">
                                    <thead > 
                        
                                        <tr >
                                            <th rowspan="4" class="titulo_tabla" style="width:300px">UGEL </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:200px">PROVINCIA </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:200px">DISTRITO </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:300px">CENTRO POBLADO </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:100px">COD-MODULAR </th> 
                                            <th rowspan="4" class="titulo_tabla" style="width:100">ANEXO </th>
                                            <th rowspan="4" class="titulo_tabla" style="width:500px">NOMBRE INSTITUCION </th> 
                                            <th rowspan="4" class="titulo_tabla" style="width:500px">TIPO GESTION </th> 
                                            <th rowspan="4" class="titulo_tabla" style="width:200px">TOTAL ESTUDIANTES MATRICULADOS </th> 
                                        </tr>

                                        <tr >
                                            <th colspan="10" class="titulo_tabla" > ESTUDIANTES POR GRADO </th>
                                        </tr>

                                        <tr >
                                            <th colspan="2" class="titulo_tabla" > PRIMERO </th>
                                            <th colspan="2" class="titulo_tabla" > SEGUNDO </th>
                                            <th colspan="2" class="titulo_tabla" > TERCERO </th>
                                            <th colspan="2" class="titulo_tabla" > CUARTO </th>
                                            <th colspan="2" class="titulo_tabla" > QUINTO </th>
                                                    
                                        </tr>

                                        <tr >
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                            <th  class="titulo_tabla" style="width:50px"> H </th>
                                            <th  class="titulo_tabla" style="width:50px"> M </th>
                                        
                                    
                                        </tr>

                        
                                    </thead>
                                </table>
                            </div>
                        
                            <div class="form-group row">
                                <div  class="col-md-6">
                                    Fuente: SIAGIE- MINEDU          
                                </div>
                        
                                <div  class="col-md-6" style="text-align: right">
                                    H: Hombre / M: Mujer   
                                </div>
                            </div> 
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

@endif

{{-- *********************************** SCRIPTs ************************ --}}

  
<script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>


<script>
    $('#grid').DataTable({
        "ajax": "{{route('Matricula.Institucion_DataTable',array($matricula_id, 'I', $gestion, $tipo ))}}",
        "columns":[             
            {data:'ugel'},{data:'provincia'},{data:'distrito'},{data:'cenPo'},{data:'codModular'},{data:'anexo'},{data:'nombreInstEduc'},{data:'tipoGestion'},
            {data:'total_estudiantes_matriculados'},
            {data:'cero_nivel_hombre'},{data:'cero_nivel_mujer'},
            {data:'primer_nivel_hombre'},{data:'primer_nivel_mujer'},
            {data:'segundo_nivel_hombre'},{data:'segundo_nivel_mujer'},
            {data:'tercero_nivel_hombre'},{data:'tercero_nivel_mujer'},
            {data:'cuarto_nivel_hombre'},{data:'cuarto_nivel_mujer'},
            {data:'quinto_nivel_hombre'},{data:'quinto_nivel_mujer'},      
            {data:'sexto_nivel_hombre'},{data:'sexto_nivel_mujer'},                
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


    $('#grid2').DataTable({
        "ajax": "{{route('Matricula.Institucion_DataTable',array($matricula_id, 'P', $gestion , $tipo))}}",
        "columns":[             
            {data:'ugel'},{data:'provincia'},{data:'distrito'},{data:'cenPo'},{data:'codModular'},{data:'anexo'},{data:'nombreInstEduc'},{data:'tipoGestion'},
            {data:'total_estudiantes_matriculados'},          
            {data:'primer_nivel_hombre'},{data:'primer_nivel_mujer'},
            {data:'segundo_nivel_hombre'},{data:'segundo_nivel_mujer'},
            {data:'tercero_nivel_hombre'},{data:'tercero_nivel_mujer'},
            {data:'cuarto_nivel_hombre'},{data:'cuarto_nivel_mujer'},
            {data:'quinto_nivel_hombre'},{data:'quinto_nivel_mujer'},      
            {data:'sexto_nivel_hombre'},{data:'sexto_nivel_mujer'},                
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

    $('#grid3').DataTable({
        "ajax": "{{route('Matricula.Institucion_DataTable',array($matricula_id, 'S', $gestion, $tipo ))}}",
        "columns":[             
            {data:'ugel'},{data:'provincia'},{data:'distrito'},{data:'cenPo'},{data:'codModular'},{data:'anexo'},{data:'nombreInstEduc'},{data:'tipoGestion'},
            {data:'total_estudiantes_matriculados'},          
            {data:'primer_nivel_hombre'},{data:'primer_nivel_mujer'},
            {data:'segundo_nivel_hombre'},{data:'segundo_nivel_mujer'},
            {data:'tercero_nivel_hombre'},{data:'tercero_nivel_mujer'},
            {data:'cuarto_nivel_hombre'},{data:'cuarto_nivel_mujer'},
            {data:'quinto_nivel_hombre'},{data:'quinto_nivel_mujer'},      
                     
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

