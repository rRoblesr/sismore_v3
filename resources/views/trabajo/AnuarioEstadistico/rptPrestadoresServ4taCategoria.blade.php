@extends('layouts.main',['activePage'=>'importacion','titlePage'=>'PROMEDIO PRESTADORES DE SERVICIO DE 4ta CATEGORIA'])

@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content') 



<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="alert alert-info"> 

            <div class="row">
                <div class="col-md-3 col-xl-3">
                    <div class="card">
                        <select id="sector" name="sector" class="form-control" onchange="cambia_sector();">                                
  
                            <option value="21"> PUBLICO</option>
                            <option value="22"> PRIVADO</option>
                                        
                        </select>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12 col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                            <table id="tabla" class="table table-striped table-bordered" style="width:100%">
                                <thead class="text-primary">                                       
                                    
                                    <th style="width:70px">AÑO</th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>                 
    </div>
</div>




<div class="row">
    <div class="col-md-12 col-xl-12">
        <div class="alert alert-info">                                                          
                <div id="Grafico_ranking_promedio_prestadores_servicio4ta">       
                    {{-- se carga con el scrip lineas abajo --}}
                </div>                     
        </div>                 
    </div>
</div>

@endsection 

           
@section('js')

 <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
 <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
 <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
 <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>

 <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
 <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
 <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
 <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
 <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>


 <script type="text/javascript">

    $(document).ready(function() {
        cargardatatable();
        Grafico_ranking_promedio_prestadores_servicio4ta();
    });

    function Grafico_ranking_promedio_prestadores_servicio4ta() {
            
        $.ajax({  
            // headers: {
            //         'X-CSRF-TOKEN': $('input[name=_token]').val()
            // },                           
            url: "{{ url('/') }}/AnuarioEstadistico/Grafico_ranking_promedio_prestadores_servicio4ta/"+0,
            type: 'get',
        }).done(function (data) {               
            $('#Grafico_ranking_promedio_prestadores_servicio4ta').html(data);
        }).fail(function () {
            alert("Lo sentimos a ocurrido un error");
        });
    }

    function cambia_sector() {
      
        // $('#tabla').DataTable().ajax.reload();
        cargardatatable();
    }
    function cargardatatable() {
            table_principal = $('#tabla').DataTable({
                "ajax": "{{ url('/') }}/AnuarioEstadistico/rptRemunTrabSectorPrivado_DataTable/" + $('#sector').val(),
                "columns": [ {data:'anio'},{data:'enero'} ,{data:'febrero'}  ,{data:'marzo'} ,   
                {data:'abril'},{data:'mayo'} ,{data:'junio'}  ,{data:'julio'},
                {data:'agosto'},{data:'setiembre'} ,{data:'octubre'}  ,{data:'noviembre'} ,
                {data:'diciembre'}    
                ],
                "responsive": false,
                "autoWidth": true,
                // "order": false,
                "destroy": true,
                "language": {
                    "lengthMenu": "Mostrar " +
                        `<select class="custom-select custom-select-sm form-control form-control-sm">
                        <option value = '10'> 10</option>
                        <option value = '25'> 25</option>
                        <option value = '50'> 50</option>
                        <option value = '100'>100</option>
                        <option value = '-1'>Todos</option>
                        </select>` + " registros por página",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(Filtrado de _MAX_ registros totales)",
                    "emptyTable": "No hay datos disponibles en la tabla.",
                    "info": "Del _START_ al _END_ de _TOTAL_ registros ",
                    "infoEmpty": "Mostrando 0 registros de un total de 0. registros",
                    "infoFiltered": "(filtrados de un total de _MAX_ )",
                    "infoPostFix": "",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "searchPlaceholder": "Dato para buscar",
                    "zeroRecords": "No se han encontrado coincidencias.",
                    "paginate": {
                        "next": "siguiente",
                        "previous": "anterior"
                    }
                }
            });
        }
        
    

 
 </script>

 
@endsection
           
