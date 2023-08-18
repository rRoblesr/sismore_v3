@extends('layouts.main',['titlePage'=>'RELACIÓN DE INDICADORES DE EDUCACIÓN'])

@section('content')

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-border">
                <div class="card-header bg-transparent pb-0">
                    <h3 class="card-title ">Lista de Indicadores </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="mb-0">
                                @php
                                    $i=1;
                                @endphp

                                @foreach ($clas as $key=> $item)
                                    <li>{{$item->nombre}}</li>
                                    @php
                                        $inds=App\Models\Educacion\Indicador::where('clasificador_id',$item->id)->get();
                                    @endphp
                                    <ul>
                                        @foreach ($inds as $key2=> $item2)
                                        <li>{{$i++}}.-<a href="{{route($item2->url)}}">{{$item2->nombre}}</a></li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
      <!-- col -->
  </div>
    {{--<div class="row">
        <div class="col-lg-12">
            <div class="card card-border">
                <div class="card-header bg-transparent pb-0">
                    <h3 class="card-title ">Lista de Indicadores</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="mb-0">
                                <li>CULMINACIÓN DE LA EDUCACIÓN BÁSICA Y SUPERIOR</li>
                                <ul>
                                    <li>1.-<a href="#">Tasa de conclusión, primaria, grupo de edades 12-13 años (% del total)</a></li>
                                    <li>2.-<a href="#">Tasa de conclusión, secundaria, grupo de edades 17-18 años (% del total)</a></li>
                                    <li>3.-<a href="#">Tasa de conclusión, educación superior, grupo de edades 22-24 años (% del total)</a></li>
                                    <li>FUENTE: Bases de datos de la Encuesta Nacional de Hogares del Instituto Nacional 
                                        de Estadística e Informática - INEI</li>
                                </ul>
                                <li>LOGROS DE APRENDIZAJE</li>
                                <ul>
                                    <li>4.-<a href="{{route('ece.indicador.4')}}">Alumnos que logran los aprendizajes del grado (% de alumnos de 2° grado de primaria participantes en evaluaciones censal)</a></li>
                                    <li>5.-<a href="{{route('ece.indicador.5')}}">Alumnos que logran los aprendizajes del grado (% de alumnos de 2° grado de secundaria participantes en evaluación censal)</a></li>
                                    <li>6.-<a href="{{route('ece.indicador.6')}}">Alumnos que logran los aprendizajes del grado (% de alumnos de 4° grado de primaria participantes en evaluación censal)</a></li>
                                    <li>7.-<a href="{{route('ece.indicador.7')}}">Alumnos de EIB que logran los aprendizajes del 4° grado en lengua materna y en castellano como segunda lengua.</a></li>
                                    <li>FUENTE: Base de datos de la Evaluación Censal De Estudiantes (ECE) del Ministerio 
                                        de Educación-Oficina de Medición de Calidad de los Aprendizajes</li>
                                </ul>
                                <li>ACCESO A LA EDUCACIÓN</li>
                                <ul>
                                    <li>8.-<a href="#">Tasa neta de matrícula, educación inicial (% de población con edades de 3-5 años)</a></li>
                                    <li>9.-<a href="#">Tasa neta de matrícula, educación primaria (% de población con edades de 6-11 años)</a></li>
                                    <li>10.-<a href="#">Tasa neta de matrícula, educación secundaria (% de población con edades de 12-16 años)</a></li>
                                    <li>FUENTE: Base de datos del Sistema de Información de Apoyo a la Gestión de la 
                                        Institución Educativa (SIAGIE).</li>
                                </ul>
                                <li>PROFESORES Y PROMOTORAS EDUCATIVAS</li>
                                <ul>
                                    <li>11.-<a href="#">Profesores titulados, inicial (% del total)</a></li>
                                    <li>12.-<a href="#">Profesores titulados, primaria (% del total)</a></li>
                                    <li>13.-<a href="#">Profesores titulados, secundaria (% del total)</a></li>
                                    <li>FUENTE: Nexus</li>
                                </ul>
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
      <!-- col -->
  </div>--}}
  <!-- End row -->

</div>

@endsection

@section('js')
      <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
      <!-- Validation init js-->
      <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

@endsection