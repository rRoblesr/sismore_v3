@extends('layouts.main', ['titlePage' => 'CONTROL DE CALIDAD - PADRON NOMINAL'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
@endsection
@section('content')
    <div class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="card card-border">
                    <div class="card-header border-success-0 bg-transparent pb-0">
                        <div class="card-widgets">
                            <button type="button" class="btn btn-danger btn-xs" onclick="location.reload()"><i
                                    class="fa fa-redo"></i> Actualizar</button>
                        </div>
                        <h3 class="card-title">Lista de niños del padrón nominal con observaciones</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="datatable"
                                        class="table table-sm-5 table-striped table-bordered dt-responsive nowrap font-12"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead class="text-white  bg-success-0">
                                            <tr>
                                                <th>N°</th>
                                                <th>Tipo</th>
                                                <th>Descripción</th>
                                                <th>Cantidad</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- card-body -->
                </div>
            </div> <!-- End col -->
        </div> <!-- End row -->
    </div>
@endsection

@section('js')
    <script>
        var table_principal = '';
        $(document).ready(function() {
            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                searching: false,
                lengthChange: false,
                language: table_language,
                ajax: "{{ route('salud.padron.calidad.listadogeneral') }}",
                type: "POST",
            });
        });

        function verListadoTipo(tipo) {
            var url = "{{ route('salud.padron.calidad.listadotipo', ':tipo') }}";
            url = url.replace(':tipo', tipo);
            // Ahora puedes usar la URL generada en tu JavaScript
            console.log('URL para el listado del tipo:', url);
            // También puedes redirigir a esa URL si lo deseas
            window.location.href = url;
        }
    </script>
    <script src="{{ asset('/') }}public/assets/libs/jquery-validation/jquery.validate.min.js"></script>
    <!-- Validation init js-->
    <script src="{{ asset('/') }}public/assets/js/pages/form-validation.init.js"></script>

    <script src="{{ asset('/') }}public/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}public/assets/libs/datatables/responsive.bootstrap4.min.js"></script>
@endsection
