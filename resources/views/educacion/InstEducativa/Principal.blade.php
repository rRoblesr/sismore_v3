@extends('layouts.main',['activePage'=>'InstEducativa','titlePage'=>'INSTITUCIONES EDUCATIVAS - UCAYALI'])

@section('css')
@endsection



@section('content')
    <div class="row">

        <div class="col-md-6 col-xl-6">
            <div class="alert alert-info">
                <div id="GraficoBarras_Instituciones_Distrito">
                    {{-- se carga con el scrip lineas abajo --}}
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-6">
            <div class="card-box">
                <div class="media">
                    <div class="avatar-md bg-success rounded-circle mr-2">
                        <i class=" ion-md-home avatar-title font-26 text-white"></i>
                    </div>
                    <div class="media-body align-self-center">
                        <div class="text-right">
                            <h4 class="font-20 my-0 font-weight-bold"><span data-plugin="counterup">
                                    {{ number_format($privadas + $publicas, 0) }} </span></h4>
                            <p class="mb-0 mt-1 text-truncate">Activas </p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <h6 class="text-uppercase">Instituciones Educativas <span class="float-right"> </span></h6>
                </div>

                <div class="mt-4">
                    <h6 class="text-uppercase">Públicas <span class="float-right"> {{ number_format($publicas, 0) }}
                        </span></h6>
                    <div class="progress progress-sm m-0">
                    </div>

                    <h6 class="text-uppercase">Privadas <span class="float-right">{{ number_format($privadas, 0) }}</span>
                    </h6>
                    <div class="progress progress-sm m-0">
                    </div>


                    <br><br><br><br><br><br><br><br><br>
                </div>
            </div>
            <!-- end card-box-->
        </div>
    </div>


    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">

                        <div id="datos01" class="form-group row">
                            Cargando datos.....
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-right">
        <p class="texto_dfuente  mb-0"> Fuente: Padron Web - Escale - MINEDU </p>
        <p class="texto_dfuente  mb-0"> Ultima actualización: {{ $fecha_version }} </p>
    </div>
@endsection



@section('js')
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {

            cargar_porDistrito();
            cargar_GraficoBarras_Instituciones_Distrito();

        });

        function cargar_GraficoBarras_Instituciones_Distrito() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/InstEducativa/GraficoBarras_Instituciones_Distrito",
                type: 'post',
            }).done(function(data) {
                $('#GraficoBarras_Instituciones_Distrito').html(data);
            }).fail(function() {
                alert("Lo sentimos a ocurrido un error");
            });
        }


        function cargar_porDistrito() {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                url: "{{ url('/') }}/InstEducativa/ReporteDistrito",
                type: 'post',
            }).done(function(data) {
                $('#datos01').html(data);
            }).fail(function() {
                alert("Lo sentimos a ocurrido un error");
            });
        }
    </script>
@endsection
