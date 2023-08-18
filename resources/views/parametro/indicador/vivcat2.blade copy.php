@extends('layouts.main',['titlePage'=>'INDICADOR'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-fill bg-primary">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title text-white">{{$title}}</h3>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!--div class="card-header">
                    <h3 class="card-title">Default Buttons</h3>
                </div-->
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-1 col-form-label">Provincia</label>
                        <div class="col-md-3">
                            <select id="provincia" name="provincia" class="form-control" onchange="cargardistritos();cargarhistorial();">
                                <option value="0">TODOS</option>
                                @foreach ($provincias as $prov)
                                <option value="{{$prov->id}}">{{$prov->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Distrito</label>
                        <div class="col-md-3">
                            <select id="distrito" name="distrito" class="form-control" onchange="cargarhistorial();">
                                <option value="0">TODOS</option>
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Fecha</label>
                        <div class="col-md-3">
                            <select id="fecha" name="fecha" class="form-control" onchange="cargarhistorial();">
                                @foreach ($ingresos as $item)
                                <option value="{{$item->id}}">{{date('d-m-Y',strtotime($item->fechaActualizacion))}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                    </div>
                    <!--div class="form-group row">
                        <label class="col-md-1 col-form-label">Conexion</label>
                        <div class="col-md-5">
                            <select id="econexion" name="econexion" class="form-control" onchange="cargarhistorial();">
                                <option value="0">TODOS</option>
                                @foreach ($econexion as $prov)
                                <option value="{{$prov->id}}">{{$prov->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        
                    </div-->
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-border card-primary">
                <div class="card-header bg-transparent pb-0">
                    <h3 class="card-title">{{$title}}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Tipo Servicio</th>
                                            <th>Hogares</th>
                                            <th>Porcentaje</th>
                                        </tr>
                                    </thead>
                                    <tbody id='vistax1'>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-border card-primary">
                <div class="card-body">
                    <div id="con1" style="min-width:400px;height:300px;margin:0 auto;" ></div>
                </div>
            </div>
        </div>
        
    </div><!-- End row -->  
    <div class="row">
        <div class="col-md-6">
            <div class="card card-border card-primary">
                <div class="card-header bg-transparent pb-0">
                    @if ($indicador_id==24)
                    <h3 class="card-title">TOTAL DE HOGARES CON COBERTURA DE AGUA POR RED PÚBLICA, POR CATEGORIA EN LA PROVINCIA CORONEL PORTILLO</h3>
                    @else
                    <h3 class="card-title">TOTAL DE HOGARES CON COBERTURA DE ALCANTARILLADO U OTRAS FORMAS DE DISPOSICIÓN SANITARIA DE EXCRETAS, POR CATEGORIA EN LA PROVINCIA CORONEL PORTILLO</h3>
                    @endif                    
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>CATEGORIAS</th>
                                            <th>CON SERVICIO</th>
                                            <th>UNIDADES DE USO</th>
                                        </tr>
                                    </thead>
                                    <tbody id='vistaca'>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-border card-primary">
                <div class="card-header bg-transparent pb-0">
                    @if ($indicador_id==24)
                    <h3 class="card-title">TOTAL DE HOGARES SIN COBERTURA DE AGUA POR RED PÚBLICA, POR CATEGORIA EN LA PROVINCIA CORONEL PORTILLO</h3>
                    @else
                    <h3 class="card-title">TOTAL DE HOGARES SIN COBERTURA DE ALCANTARILLADO U OTRAS FORMAS DE DISPOSICIÓN SANITARIA DE EXCRETAS, POR CATEGORIA EN LA PROVINCIA CORONEL PORTILLO</h3>
                    @endif                 
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>CATEGORIAS</th>
                                            <th>SIN SERVICIO</th>
                                            <th>UNIDADES DE USO</th>
                                        </tr>
                                    </thead>
                                    <tbody id='vistasa'>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
        
    </div><!-- End row -->  
    
@endsection

@section('js')

    <!-- flot chart -->
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>

    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            cargarhistorial();
            //cargardatatable1();
            //cargarDT();
        });
        function separator(numb) {
            var str = numb.toString().split(".");
            str[0] = str[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return str.join(".");
        }
        function cargarDT() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/INDICADOR/ReporteCPVivDT/" + $('#provincia').val()+"/"+$('#distrito').val()+"/"+
                $('#fecha').val()+'/{{$indicador_id}}/',
                type: 'post',
                dataType: 'JSON',
                success: function(data) {
                    console.log(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }        
        function cargardistritos() {
            $('#distrito').val('0');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/INDICADOR/Distritos/" + $('#provincia').val(),
                type: 'post',
                dataType: 'JSON',
                success: function(data) {
                    $("#distrito option").remove();
                    var options = '<option value="0">TODOS</option>';
                    $.each(data.distritos, function(index, value) {
                        options += "<option value='" + value.id + "'>" + value.nombre + "</option>"
                    });
                    $("#distrito").append(options);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }
        function cargarhistorial() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/INDICADOR/PNSR2/" + $('#provincia').val()+"/"+$('#distrito').val()+"/{{$indicador_id}}/"+$('#econexion').val()+"/"+$('#fecha').val(),
                type: 'post',
                dataType: 'JSON',
                success: function(data) {
                    vista='';
                    total=0;
                    data.indicador.forEach(element => {total+=element.y});
                    data.indicador.forEach(element => {
                        por=element.y*100/total;
                        vista+='<tr><td>'+element.name+'</td><td>'+separator(element.y)+'</td><td>'+por.toFixed(2)+'%</td></tr>';
                    });
                    vista+='<tr><th>Total</th><th>'+separator(total)+'</th><th>100%</th></tr>';
                    $('#vistax1').html(vista);
                    vista='';
                    total1=0;
                    total2=0;
                    data.categoriaconagua.forEach(element => {
                        vista+='<tr><td>'+element.categoria+'</td><td align=center>'+separator(element.con_agua)+'</td><td align=center>'+separator(element.unid_uso)+'</td></tr>';
                        total1+=element.con_agua;
                        total2+=element.unid_uso;
                    });
                    vista+='<tr><th>Total</th><th><center>'+separator(total1)+'</center></th><th><center>'+separator(total2)+'</center></th></tr>';
                    $('#vistaca').html(vista);
                    vista='';
                    total1=0;
                    total2=0;
                    data.categoriasinagua.forEach(element => {
                        vista+='<tr><td>'+element.categoria+'</td><td align=center>'+separator(element.sin_agua)+'</td><td align=center>'+separator(element.unid_uso)+'</td></tr>';
                        total1+=element.sin_agua;
                        total2+=element.unid_uso;
                    });
                    vista+='<tr><th>Total</th><th><center>'+separator(total1)+'</center></th><th><center>'+separator(total2)+'</center></th></tr>';
                    $('#vistasa').html(vista);
                    grafica1(data.indicador);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                },
            });
        }        
        function grafica1(datos){
            Highcharts.chart('con1',{
                chart:{
                    type:'pie',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                },
                title:{
                    text:''
                },
                tooltip:{
                    pointFormat:'<b>{series.name}:</b>{point.y}',
                },
                plotOptions:{
                    pie:{
                        allowPointSelect:true,
                        cursor:'pointer',
                        dataLabels:{
                            enabled:true,
                            format:'<b>{point.name}: {point.percentage:.2f}%</b>'
                        },
                    }
                },
                series:[{
                    name:'Cantidad',
                    colorByPoint:true,
                    data:datos,
                }],
                credits:false,

            });
        } 
        function cargardatatable1(){
            $('#datatable1').DataTable({
                "ajax": "{{ url('/') }}/INDICADOR/ReporteCPVivDT/" + $('#provincia').val()+"/"+$('#distrito').val()+"/"+
                $('#fecha').val()+'/{{$indicador_id}}/',
                "columns":[
                    {data:'provincia'},
                    {data:'distrito'},
                    {data:'cp'},
                    {data:'servicio'},
                ],
                "responsive":false,
                "autoWidth":true,
                "order":false,
                "destroy":true,
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
            }); 
        }        
    </script>

@endsection
