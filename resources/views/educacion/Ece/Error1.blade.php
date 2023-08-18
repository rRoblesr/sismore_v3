@extends('layouts.main',['titlePage'=>'IMPORTAR DATOS - EXCEL DE INDICADORES'])

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

    <div class="alert alert-danger alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{$errores['msn']}}.
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="float-right">
                <div class="btn-toolbar" role="toolbar">
                    <a href="{{route('ece.importar')}}" class="btn btn-success waves-effect waves-light"><span>CONTINUAR </span><i class="fas fa-forward"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-sm-12">
            <div class="card card-border">
                <div class="card-header">
                    <h3 class="card-title">Estas Instituciones con CODIGO MODULAR no estan registrados</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>CODIGO MODULAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($noagregados as $key =>$item)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$item}}</td>
                                        </tr> 
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    
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