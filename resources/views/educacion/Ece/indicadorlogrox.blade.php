@extends('layouts.main',['titlePage'=>$title])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-border">
                <div class="card-header border-primary bg-transparent pb-0">
                    <div class="card-title text-primary">Filtro</div>
                </div>
                <div class="card-body">
                    <form action="" method="post" name="form_filtro" id="form_filtro">
                        @csrf
                        <input type="hidden" name="grado" value="{{$grado}}">
                        <input type="hidden" name="tipo" value="{{$tipo}}">
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-label">AÑO</label>
                                <select id="anio" name="anio" class="form-control" onchange="vistaindicador()">
                                    @foreach ($anios as $item)
                                        <option value="{{$item->anio}}">{{$item->anio}}</option>
                                    @endforeach
                                </select>
                                <span class="held-block"></span>
                            </div>
                            <div class="col-sm-3">
                                <label class="form-label">PROVINCIA</label>
                                <select id="provincia" name="provincia" class="form-control" onchange="cargardistritos();vistaindicador();">
                                    <option value="0">TODOS</option>
                                    @foreach ($provincias as $prov)
                                    <option value="{{$prov->id}}">{!!$prov->nombre!!}</option>
                                    @endforeach
                                </select>
                                <span class="held-block"></span>
                            </div>
                            <div class="col-sm-3">
                                <label class="form-label">DISTRITO</label>
                                <select id="distrito" name="distrito" class="form-control input-sm" onchange="vistaindicador();">
                                    <option value="0">TODOS</option></select>
                                <span class="held-block"></span>
                            </div> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-border">
                <div class="card-header border-primary bg-transparent pb-0">
                    <div class="card-title text-primary">Indicador</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" id="vistatabla">
                                {{--!! $tabla!!--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End row -->
</div>

@endsection

@section('js')
      <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
      <!-- Validation init js-->
      <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

    <script>
        $(document).ready(function(){
            vistaindicador();
        });
        function vistaindicador() {
            if(true){
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
                    url: "{{route($ruta)}}",
                    type: 'post',
                    data: $("#form_filtro").serialize(),
                    beforeSend: function() {
                        $("#vistatabla").html('<br><h3>Cargando datos...</h3>');
                    },
                    success: function(data) {
                        console.log(data);
                        $("#vistatabla").html(data);
                    },
                    error:function(jqXHR,textStatus,errorThrown){
                        console.log(jqXHR);
                    },
                });
            }
        }

        function cargardistritos() {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
                url:"{{url('/')}}/ECE/IndicadorDistritos/"+$('#provincia').val(),
                type: 'post',
                dataType:'JSON',
                success: function(data) {
                    console.log(data);
                    $("#distrito option").remove();
                    var options = '<option value="">TODOS</option>';
                    $.each(data.distritos, function(index, value) {
                        options += "<option value='" + value.id + "'>" + value.nombre +"</option>"
                    });
                    $("#distrito").append(options);
                },
                error:function(jqXHR,textStatus,errorThrown){
                    console.log(jqXHR);
                },
            });                
        }
    </script>

@endsection