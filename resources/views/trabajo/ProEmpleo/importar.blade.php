@extends('layouts.main',['titlePage'=>'IMPORTAR DATOS - PROEMPLEO'])

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

                        <form action="{{route('ProEmpleo.guardar')}}" method="post" enctype='multipart/form-data'
                            class="cmxform form-horizontal tasi-form"  >                            
                            @csrf

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">Fuente de datos</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" readonly="readonly" value="DRTPE - PROEMPLEO">
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
                                <label class="col-md-2 col-form-label">Mes</label>
                                
                                <div class="col-md-4">
                                    <select id="mes" name="mes" class="form-control">                                        
                                        <option value=1> Enero </option>
                                        <option value=2> Febrero </option>
                                        <option value=3> Marzo </option>
                                        <option value=4> Abril </option>
                                        <option value=5> Mayo </option>
                                        <option value=6> Junio </option>
                                        <option value=7> Julio </option>
                                        <option value=8> Agosto </option>
                                        <option value=9> Setiembre</option>
                                        <option value=10> Octubre </option>
                                        <option value=11> Noviembre</option>
                                        <option value=12> Diciembre </option>                                       
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <label class="col-md-2 col-form-label">Oferta Hombres</label>
                                <div class="col-md-2">
                                    <input id="oferta_hombres" type="text" value="0" name="demo3_21">       
                                </div>                                                                                  
                            </div> --}}

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label ">Oferta Hombres</label>
                                <div class="col-md-2">                                
                                    <input  id="oferta_hombres" class="form-control"  required="" name="oferta_hombres" value="0" min="0" type="number" >    
                                </div>                                                                                                      
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label ">Oferta Mujeres</label>
                                <div class="col-md-2">                                
                                    <input  id="oferta_mujeres" class="form-control" required="" name="oferta_mujeres" value="0" min="0" type="number" >    
                                </div>                                                                                                      
                            </div>

                            <div class="form-group row">                           
                                <label class="col-md-2 col-form-label">Demanda</label>
                                <div class="col-md-2">
                                    <input  id="demanda" class="form-control" required="" name="demanda" value="0" min="0" type="number" >
                                </div>                                                                                        
                            </div>                           
                            

                            {{-- <div class="form-group row">
                                <label class="col-md-2 col-form-label">Fecha Versión</label>
                                <div class="col-md-4">
                                    <input type="date" class="form-control" name="fechaActualizacion" placeholder="Ingrese fecha actualizacion" autofocus required>
                                </div>
                            </div> --}}

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


    
{{--     
    <!-- Plugins Js -->
    <script src="{{ asset('/') }}public/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/switchery/switchery.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/multiselect/jquery.multi-select.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-quicksearch/jquery.quicksearch.min.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/select2/select2.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-mask-plugin/jquery.mask.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/moment/moment.min.js"></script>
    <!-- Init js-->
    <script src="{{ asset('/') }}public/assets/js/pages/form-advanced.init.js"></script>

    <!-- App js -->
    <script src="{{ asset('/') }}public/assets/js/app.min.js"></script> --}}
      

@endsection