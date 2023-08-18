@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - DATASS'])

@section('content')

    <div class="content">

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                Error al Cargar Archivo <br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($mensaje != '')
            <div class="alert alert-danger">
                <ul>
                    <li>{{ $mensaje }}</li>
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-warning btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                            <button type="button" class="btn btn-success btn-xs"
                                onclick="javascript:window.open('https://docs.google.com/spreadsheets/d/1Za8iooAAKO0zguc9pPI8yE1sfehutRUw/edit?usp=share_link&ouid=108127328164905589197&rtpof=true&sd=true','_blank');"><i
                                    class="fa fa-file-excel"></i>
                                Plantilla</button>
                           {{--  <button type="button" class="btn btn-danger btn-xs"
                                onclick="javascript:window.open('https://1drv.ms/x/s!AgffhPHh-Qgo0AEnoULq3wbXGnu-?e=d81hlQ','_blank');"><i
                                    class="mdi mdi-file-pdf-outline"></i>
                                Manual</button> --}}
                        </div>
                        <h3 class="card-title">Datos de importación</h3>
                    </div>

                    <div class="card-body">
                        <div class="form">

                            <form action="{{ route('Datass.guardar') }}" method="post" enctype='multipart/form-data'
                                class="cmxform form-horizontal tasi-form">
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-md-2 col-form-label">Fuente de datos</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" readonly="readonly" value="DATASS">
                                        </div>
                                        <label class="col-md-2 col-form-label">Comentario</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" placeholder="comentario opcional" id="ccomment" name="comentario"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">

                                        <label class="col-md-2 col-form-label">Fecha Versión</label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" name="fechaActualizacion"
                                                placeholder="Ingrese fecha actualizacion" autofocus required>
                                        </div>
                                        <label class="col-md-2 col-form-label">Archivo</label>
                                        <div class="col-md-4">
                                            <input type="file" name="file" class="form-control" required>
                                        </div>
                                    </div>

                                </div>
                                {{-- <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Fuente de datos</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" readonly="readonly" value="DATASS">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Fecha Versión</label>
                                    <div class="col-md-10">
                                        <input type="date" class="form-control" name="fechaActualizacion"
                                            placeholder="Ingrese fecha actualizacion" autofocus required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Comentario</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" placeholder="comentario opcional" id="ccomment"
                                            name="comentario"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Archivo</label>
                                    <div class="col-md-10">
                                        <input type="file" name="file" class="form-control" required>
                                    </div>
                                </div> --}}

                                <div class="form-group row mb-0 ">
                                    {{-- <div class="offset-lg-2 col-lg-10"> --}}
                                    <div class="col-12 d-flex justify-content-end">
                                        <button class="btn btn-secondary waves-effect" type="button">Cancelar</button>
                                        &nbsp;
                                        <button class="btn btn-success waves-effect waves-light mr-1"
                                            type="submit">Importar</button>
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
