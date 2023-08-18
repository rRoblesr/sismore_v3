@extends('layouts.main',['activePage'=>'FuenteImportacion','titlePage'=>'IMPORTACION REALIZADA CON EXITO - PADRON WEB'])

@section('css')
   
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css"> --}}

     <!-- Table datatable css -->
     <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content') 
<div class="content">

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Relacion de Instituciones Educativas</h3>
                    {{-- <p class="card-category">IMPORTADAS DEL PADRON WEB</p> --}}
                </div>
                <div class="card-body">
                    @include('educacion.PadronWeb.ListaParcial')         
                    
                </div>
                <!-- card-body -->
            </div> 
            <!-- card -->
        </div>
        <!-- col -->
    </div>
    <!-- row -->
@endsection 