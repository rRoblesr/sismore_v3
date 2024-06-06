
@section('css')
    <link rel="stylesheet" href="{{ asset('/') }}public/assets/css/otros/personalizado.css" type='text/css'>

    {{-- <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script> --}}


    {{-- <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script> --}}

@endsection


<div class="content">

    <div class="row">
        <div class="col-md-6 ">
            <h4>MODULO VIVIENDA</h4>
        </div>

        <div class="col-md-6 ">
            <p style="text-align: right; color:rgb(147, 141, 141)"> Ultima Actualización: {{$fechaVersion}}
                <br>Fuente: Ministerio de Vivienda, Construcción y Saneamiento  - DATASS </p>
        </div>
    </div>




        <div class="row">
            <div class="col-md-6 ">


                {{-- <iframe width="100%" height="490" src="https://datastudio.google.com/embed/reporting/6c73c567-559b-4dd6-8608-64a0b502c85c/page/XXx8C"
                frameborder="0" style="border:0" allowfullscreen></iframe> --}}

                {{-- @include('mapa.basico') --}}


                <div id="mapa_basico">
                    {{-- se carga con el scrip lineas abajo --}}
                </div>


                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <div class="alert alert-info">

                                <div id="Grafico_IndicadorProvincial">
                                    {{-- se carga con el scrip lineas abajo --}}
                                </div>
                        </div>
                    </div>
                </div>





            </div>
            <div class="col-md-6 ">

                <div class="form-group row">
                    {{-- <label class="col-md-2 col-form-label">INDICADOR</label> --}}

                    <div class="col-md-12">
                        <select id="indicador" name="indicador" class="form-control" onchange="cargar_datos();">
                            <option value=1> 1. Centros Poblados Rurales con Sistema de Agua </option>
                            <option value=2> 2. Centros Poblados Rurales con Sistema de Cloración </option>
                            <option value=3> 3. Porcentaje de Hogares con Cobertura de Agua por Red Pública</option>
                            <option value=4> 4. Porcentaje de Hogares con Cobertura de Alcantarillado u Otras Formas de Disposición Sanitaria de Excretas  </option>
                            <option value=5> 5. Porcentaje de Hogares en Ambito Rural que Consume Agua Segura (Clorada) </option>
                        </select>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <div class="alert alert-info">
                                <div id="Grafico_IndicadorRegional_Periodos">
                                    {{-- se carga con el scrip lineas abajo --}}
                                </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <div class="alert alert-info">
                                <div id="Grafico_IndicadorProvincial">

                                </div>
                        </div>
                    </div>
                </div> --}}

                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <div class="alert alert-info">
                                <div id="Grafico_IndicadorProvincial_masDistrital">
                                    {{-- se carga con el scrip lineas abajo --}}
                                </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>



</div>

@section('js')



    {{-- este script tiene modificaciones para la paleta de colores --}}
    <script src="{{ asset('/') }}public/assets/libs/highchartsV2/highcharts.js"></script>

    {{-- <script src="https://code.highcharts.com/highcharts.js"></script> --}}
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>



    <script type="text/javascript">


        $(document).ready(function() {
            cargar_datos() ;
        });


        function cargar_datos(){
            Grafico_IndicadorRegional_Periodos();
            Grafico_IndicadorProvincial();
            Grafico_IndicadorProvincial_masDistrital();
            mapa_basico();
        }

        function Grafico_IndicadorRegional_Periodos() {

            $.ajax({
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/Datass/Grafico_IndicadorRegional_Periodos/" + $('#indicador').val() ,
                type: 'post',
            }).done(function (data) {
                $('#Grafico_IndicadorRegional_Periodos').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function Grafico_IndicadorRegional() {

            $.ajax({
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/Datass/Grafico_IndicadorRegional/" + $('#indicador').val() + "/" + {{$importacion_id}},
                type: 'post',
            }).done(function (data) {
                $('#Grafico_IndicadorRegional').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function Grafico_IndicadorProvincial() {

            $.ajax({
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/Datass/Grafico_IndicadorProvincial/" + $('#indicador').val() + "/" + {{$importacion_id}},
                type: 'post',
            }).done(function (data) {
                $('#Grafico_IndicadorProvincial').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function Grafico_IndicadorProvincial_masDistrital() {

            $.ajax({
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/Datass/Grafico_IndicadorProvincial_masDistrital/" + $('#indicador').val() + "/" + {{$importacion_id}},
                type: 'post',
            }).done(function (data) {
                $('#Grafico_IndicadorProvincial_masDistrital').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }

        function mapa_basico() {

            $.ajax({
                headers: {
                     'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/Datass/mapa_basico/" + $('#indicador').val() ,
                type: 'post',
            }).done(function (data) {
                $('#mapa_basico').html(data);
            }).fail(function () {
                alert("Lo sentimos a ocurrido un error");
            });
        }




    </script>


@endsection
