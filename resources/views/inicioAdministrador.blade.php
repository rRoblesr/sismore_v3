@section('css')


    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/highcharts-more.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts/solid-gauge.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/exporting.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/export-data.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/highcharts-modules/accessibility.js"></script>



    {{-- <script src="https://code.highcharts.com/modules/solid-gauge.js"></script> --}}

@endsection

<div>

    <div id="container-speed" class="chart-container"></div>

</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            @php
                $color=array('info','purple','success','primary','pink','dark','warning','secondary',);
            @endphp
            @foreach ($sistemas as $pos => $sis)
                <div class="col-md-6 col-xl-3">
                    <div class="card-box">
                        <div class="media">
                            <div class="avatar-md bg-{{$color[$pos]}} rounded-circle mr-2">
                                <i class="{{$sis->icono}} avatar-title font-26 text-white"></i>
                                {{-- <i class="ion-logo-usd avatar-title font-26 text-white"></i> --}}
                            </div>
                            <div class="media-body align-self-center">
                                <div class="text-right">
                                    <h4 class="font-20 my-0 font-weight-bold"><span data-plugin="counterup">{{$sis->nrousuario}}</span>
                                    </h4>
                                    <p class="mb-0 mt-1 text-truncate">Accesos de Usuario</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6 class="text-uppercase">Sistema {{$sis->nombre}} <!--span class="float-right">60%</span--></h6>
                            <div class="progress progress-sm m-0">
                                <div class="progress-bar bg-{{$color[$pos]}}" role="progressbar" aria-valuenow="60"
                                    aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    <span class="sr-only">60% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card-box-->
                </div>
            @endforeach
        </div>
        <!-- end row -->
        
    </div>
</div>
