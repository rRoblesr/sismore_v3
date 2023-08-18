@extends('layouts.main',['titlePage'=>'IMPORTAR DATOS - ANUARIO ESTADISTICO'])

@section('content')

<div class="content">

    @if(count($errors)>0)
        <div class="alert alert-danger">
            Error al Cargar Archivo <br><br>
            <ul>
                @foreach($errors -> all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>           
    @endif

    @if($mensaje!='')
    <div class="alert alert-danger">
        <ul>
            <li>{{$mensaje}}</li>            
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Datos de importación</h3>
                </div>
             
                <div class="card-body">
                    <div class="form">

                        <form action="{{route('AnuarioEstadistico.guardar')}}" method="post" enctype='multipart/form-data'
                            class="cmxform form-horizontal tasi-form"  >                            
                            @csrf

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Fuente de datos</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly="readonly" value="MTPE - ANUARIO ESTADISTICO">
                                </div>
                            </div>  

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Año</label>
                                
                                <div class="col-md-4">
                                    <select id="anio" name="anio" class="form-control" >                                
                                        @foreach ($anios as $item)
                                            <option value="{{ $item->id }}"> {{ $item->anio }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                          
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Reporte</label>
                                
                                <div class="col-md-4">
                                    <select id="formato_reporte" name="formato_reporte" class="form-control" required>        
                                        <option value="">[Seleccione]</option>                                
                                        <option value=19>Promedio de Remuneración Trabajadores del Sector Privado </option>
                                        <option value=20>Trabajadores del Sector Privado </option>       
                                        <option value=21>Prestadores de Servicio 4ta Categoria - Sector Público </option> 
                                        <option value=22>Prestadores de Servicio 4ta Categoria - Sector Privado </option>    
                                        <option value=23>Empresas - Sector Privado </option>                                                                                   
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Comentario</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" placeholder="comentario opcional" id="ccomment" name="comentario" ></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Archivo</label>
                                <div class="col-md-4">
                                    <input type="file" name="file" class="form-control" required > 
                                </div>
                            </div>                           
                          
                            <div class="form-group row mb-0">
                                <div class="offset-lg-2 col-lg-4">
                                    <button class="btn btn-success waves-effect waves-light mr-1" type="submit">Importar</button>
                                    <button class="btn btn-secondary waves-effect" type="button">Cancelar</button>
                                </div>
                            </div>
                      </form>
                  </div>
                  <!-- .form -->
              </div>
              <!-- card-body -->
          </div>
          <!-- card -->
      </div>
      <!-- col -->
  </div>
  <!-- End row -->

</div>

@endsection

@section('js')
      <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
      <!-- Validation init js-->
      <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

@endsection