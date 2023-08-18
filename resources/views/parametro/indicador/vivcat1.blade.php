@extends('layouts.main',['titlePage'=>'INDICADOR'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-fill" style="background: #43beac">
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
                            <select id="provincia" name="provincia" class="form-control" onchange="cargardistritos();cargarhistorial();cargardatatable1();">
                                <option value="0">TODOS</option>
                                @foreach ($provincias as $prov)
                                <option value="{{$prov->id}}">{{$prov->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Distrito</label>
                        <div class="col-md-3">
                            <select id="distrito" name="distrito" class="form-control" onchange="cargarhistorial();cargardatatable1();">
                                <option value="0">TODOS</option>
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Fecha</label>
                        <div class="col-md-3">
                            <select id="fecha" name="fecha" class="form-control" onchange="cargarhistorial();cargardatatable1();">
                                @foreach ($ingresos as $item)
                                <option value="{{$item->id}}">{{date('d-m-Y',strtotime($item->fechaActualizacion))}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
                                            <th>Tiene Servicio</th>
                                            @if ($indicador_id==20||$indicador_id==21)
                                            <th>Total Centro Poblado</th>
                                            @else
                                            <th>Total Hogares</th>
                                            @endif
                                            
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
@if ($indicador_id==20)
<div class="row">
    <div class="col-md-6">
        <div class="card card-border card-primary">
            <div class="card-header bg-transparent pb-0">                    
                <h3 class="card-title">TOTAL DE CENTROS POBLADOS RURALES CON SISTEMA DE AGUA EN LA REGION DE UCAYALI POR PROVINCIA</h3>                                    
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th rowspan="2">PROVINCIA</th>
                                        <th rowspan="2">TOTAL CENTRO POBLADO</th>
                                        <th colspan="2"><CENTER>CON SISTEMA DE AGUA</CENTER></th>
                                        <th colspan="2"><CENTER>SIN SISTEMA DE AGUA</CENTER></th>
                                    <tr>
                                        <th>CENTRO POBLADO</th>
                                        <th>PORCENTAJE</th>
                                        <th>CENTRO POBLADO</th>
                                        <th>PORCENTAJE</th>
                                    </tr>
                                </thead>
                                <tbody id='vistafiltro1'>
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
                <div id="con2" style="min-width:400px;height:300px;margin:0 auto;" ></div>
            </div>
        </div>
    </div>        
</div><!-- End row -->      
@elseif ($indicador_id==21)
<div class="row">
    <div class="col-md-6">
        <div class="card card-border card-primary">
            <div class="card-header bg-transparent pb-0">                    
                <h3 class="card-title">TOTAL DE CENTROS POBLADOS RURALES CON SISTEMA DE CLORACIÓN EN LA REGION DE UCAYALI POR PROVINCIA</h3>                                    
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th rowspan="2">PROVINCIA</th>
                                        <th rowspan="2">TOTAL CENTRO POBLADO</th>
                                        <th colspan="2"><CENTER>CON SISTEMA DE CLORACIÓN</CENTER></th>
                                        <th colspan="2"><CENTER>SIN SISTEMA DE CLORACIÓN</CENTER></th>
                                    <tr>
                                        <th>CENTRO POBLADO</th>
                                        <th>PORCENTAJE</th>
                                        <th>CENTRO POBLADO</th>
                                        <th>PORCENTAJE</th>
                                    </tr>
                                </thead>
                                <tbody id='vistafiltro1'>
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
                <div id="con2" style="min-width:400px;height:300px;margin:0 auto;" ></div>
            </div>
        </div>
    </div>        
</div><!-- End row -->     
@elseif ($indicador_id==22)
<div class="row">
    <div class="col-md-6">
        <div class="card card-border card-primary">
            <div class="card-header bg-transparent pb-0">                    
                <h3 class="card-title">TOTAL DE HOGARES CON COBERTURA DE AGUA EN LA REGION DE UCAYALI POR PROVINCIA</h3>                                    
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead >
                                    <tr >
                                        <th rowspan="2">PROVINCIA</th>
                                        <th rowspan="2">TOTAL HOGARES</th>
                                        <th colspan="2"><CENTER>CON COBERTURA DE AGUA</CENTER></th>
                                        <th colspan="2"><CENTER>SIN COBERTURA DE AGUA</CENTER></th>
                                    <tr>
                                        <th>HOGARES</th>
                                        <th>PORCENTAJE</th>
                                        <th>HOGARES</th>
                                        <th>PORCENTAJE</th>
                                    </tr>
                                </thead>
                                <tbody id='vistafiltro1'>
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
                <div id="con2" style="min-width:400px;height:300px;margin:0 auto;" ></div>
            </div>
        </div>
    </div>        
</div><!-- End row -->         
@elseif ($indicador_id==23)
<div class="row">
    <div class="col-md-6">
        <div class="card card-border card-primary">
            <div class="card-header bg-transparent pb-0">                    
                <h3 class="card-title">TOTAL DE HOGARES CON DESAGUE EN LA REGION DE UCAYALI POR PROVINCIA</h3>                                    
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th rowspan="2">PROVINCIA</th>
                                        <th rowspan="2">TOTAL HOGARES</th>
                                        <th colspan="2"><CENTER>CON DESAGUE</CENTER></th>
                                        <th colspan="2"><CENTER>SIN DESAGUE</CENTER></th>
                                    <tr>
                                        <th>HOGARES</th>
                                        <th>PORCENTAJE</th>
                                        <th>HOGARES</th>
                                        <th>PORCENTAJE</th>
                                    </tr>
                                </thead>
                                <tbody id='vistafiltro1'>
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
                <div id="con2" style="min-width:400px;height:300px;margin:0 auto;" ></div>
            </div>
        </div>
    </div>        
</div><!-- End row -->      
@elseif ($indicador_id==26)
<div class="row">
    <div class="col-md-6">
        <div class="card card-border card-primary">
            <div class="card-header bg-transparent pb-0">                    
                <h3 class="card-title">TOTAL DE HOGARES QUE CONSUMEN AGUA SEGURA(CLORADA) EN LA REGION DE UCAYALI POR PROVINCIA</h3>                                    
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead >
                                    <tr>
                                        <th rowspan="2">PROVINCIA</th>
                                        <th rowspan="2">TOTAL HOGARES</th>
                                        <th colspan="2"><CENTER>CON CONSUMEN AGUA SEGURA</CENTER></th>
                                        <th colspan="2"><CENTER>SIN CONSUMEN AGUA SEGURA</CENTER></th>
                                    <tr>
                                        <th>HOGARES</th>
                                        <th>PORCENTAJE</th>
                                        <th>HOGARES</th>
                                        <th>PORCENTAJE</th>
                                    </tr>
                                </thead>
                                <tbody id='vistafiltro1'>
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
                <div id="con2" style="min-width:400px;height:300px;margin:0 auto;" ></div>
            </div>
        </div>
    </div>        
</div><!-- End row -->         
@else
    
@endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-border card-primary">
                <div class="card-header border-primary bg-transparent pb-0">
                    <h3 class="card-title text-primary"></h3>
                </div> 
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="table-responsive">
                                <table id="datatable1" class="table table-striped table-bordered" style="width:100%">
                                    <thead style="background: #43beac; color:white" class="cabecera-dataTable">                                    
                                        <th >PROVINCIA</th>
                                        <th >DISTRITO</th>
                                        <th >CENTRO POBLADO</th>
                                        <th >TIENE SERVICIO</th>
                                    </thead>
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
            cargardatatable1();
            cargarDT();
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
                url: "{{ url('/') }}/INDICADOR/PNSR1/" + $('#provincia').val()+"/"+$('#distrito').val()+"/{{$indicador_id}}/"+$('#fecha').val(),
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
                    /**/
                    vista='';
                    total0=0;
                    total1=0;
                    total2=0;
                    data.filtro1.forEach(element => {
                        vista+='<tr><td>'+element.provincia+'</td>'+
                        '<td>'+separator(element.centro_poblado)+'</td>'+
                        '<td>'+separator(element.servicio_si)+'</td>'+
                        '<td>'+separator(element.porcentaje_si)+'%</td>'+
                        '<td>'+separator(element.servicio_no)+'</td>'+
                        '<td>'+separator(element.porcentaje_no)+'%</td>'+
                        '</tr>';
                        total0+=element.centro_poblado;
                        total1+=element.servicio_si;
                        total2+=element.servicio_no;
                    });
                    vista+='<tr><th>Total</th>'+
                    '<th>'+separator(total0)+'</th>'+
                    '<th>'+separator(total1)+'</th>'+
                    '<th>100%</th>'+
                    '<th>'+separator(total2)+'</th>'+
                    '<th>100%</th></tr>';
                    $('#vistafiltro1').html(vista);
                    grafica1(data.indicador);

                    @if ($indicador_id==20)
                    titulo='Provincias con sistema de agua';
                    @elseif ($indicador_id==21)
                    titulo='Provincias con sistema de cloración';
                    @elseif ($indicador_id==22)
                    titulo='Provincias con cobertura de agua';
                    @elseif ($indicador_id==23)
                    titulo='Provincias con desague';
                    @elseif ($indicador_id==26)
                    titulo='Provincias que consume agua segura';
                    @else
                    titulo='';
                    @endif
                    graficar2(data.gfiltro1,titulo)
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
        function graficar2(datax,titulo){
            Highcharts.chart('con2',{
                chart:{
                    type:'column',
                },
                title:{text:'',},
                xAxis:{
                    type:'category',
                },
                yAxis:{
                    title:{enabled:false,text:'Porcentaje',}
                },
                series:[{
                    name:titulo,//'Distritos con servicio de agua potable',
                    colorByPoint:true,
                    data:datax,
                }],
                plotOptions:{
                    series:{
                        borderWidth:0,
                        dataLabels:{
                            enabled:true,
                            format:'{point.y:.1f}%',
                        },
                    }
                },
                credits:false,
            });
        }           
    </script>

@endsection
