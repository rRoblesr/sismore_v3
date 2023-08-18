@extends('layouts.main',['activePage'=>'FuenteImportacion','titlePage'=>'IMPORTACION REALIZADA CON EXITO - MATRICULA'])

@section('css')
     <!-- Table datatable css -->
     <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content') 
<div class="content">

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Se importaron la siguiente cantidad de filas por archivo:</h3>                    
                </div>
                <div class="card-body">                 
                   
                   @foreach ($datos_matricula_importada as $item)
                     
                           <h6> {{$item->nivel}} : {{$item->numeroFilas}} filas </h6>                  
                       
                    @endforeach

                </div>
                <!-- card-body -->
            </div> 
            <!-- card -->
        </div>
        <!-- col -->
    </div>
    <!-- row -->
@endsection 

