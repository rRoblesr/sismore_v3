@extends('layouts.main', ['titlePage' => ''])
@section('css')
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css"
        rel="stylesheet"type="text/css" />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <a href="javascript:history.back()">
                <img src="{{ asset('/') }}public/img/paginavacio.png.webp" alt="" style="width: 100%" height="100%">
            </a>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection
