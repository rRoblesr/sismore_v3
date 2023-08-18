
@section('css')
<link rel="stylesheet" href="{{ asset('/') }}public/assets/css/otros/personalizado.css" type='text/css'>

<script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>
@endsection


<div class="content">
<div class="container-fluid">   
   

    <div class="row">
      
        <div class="col-md-6 ">
            <div class="form-group row">
                <label class="col-md-2 col-form-label">INDICADOR</label>
                
                <div class="col-md-10">
                    <select id="indicador"  name="indicador" class="form-control">                                        
                        <option value=1> C.P. Rurales con sistema de agua </option>
                        <option value=2> C.P. Rurales con Sistema de Cloración </option>
                        <option value=3> Porcentaje Hogares con cobertura de agua por red pública</option>
                        <option value=4> Porcentaje hogares con cobertura de alcantarillado u otras formas de disposición sanitaria de excretas  </option>
                        <option value=5> Porcentaje de hogares en ámbito rural que consume agua segura (clorada) </option>                             
                    </select>
                </div>
            </div>

            


            <div class="col-md-6">
                <div id="Grafico_IndicadorRegional">       
                   
                </div>
            </div>

        </div>
    </div>




</div>
</div>

@section('js')


<script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
<script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>

<script type="text/javascript"> 


    $(document).ready(function() {

        Grafico_IndicadorRegional();
    });

    function Grafico_IndicadorRegional() {
            
            $.ajax({  
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },                           
                url: "{{ url('/') }}/Datass/Grafico_IndicadorRegional/"+1,
                type: 'post',
            }).done(function (data) {               
                $('#Grafico_IndicadorRegional').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }
       

   

</script>


@endsection
