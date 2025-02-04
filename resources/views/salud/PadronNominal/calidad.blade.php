@extends('layouts.main', ['titlePage' => 'CONTROL DE CALIDAD - PADRON NOMINAL'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
@endsection
@section('content')
    <div class="row d-none">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header bg-success-0">
                    <div class="card-widgets">
                        {{-- <button type="button" class="btn btn-orange-0 btn-xs"
                                onclick="location.href=`{{ route('matriculageneral.niveleducativo.eba.principal') }}`"
                                title=''><i class="fas fa-align-justify"></i> Nivel Educativo</button> --}}
                        {{-- <button type="button" class="btn btn-orange-0 btn-xs" onclick="location.reload()"
                                title='ACTUALIZAR'><i class=" fas fa-history"></i> Actualizar</button> --}}
                    </div>
                    <h3 class="card-title text-white font-12">Control de Calidad</h3>
                </div>
                <div class="card-body pb-0">
                    <div class="form-group row align-items-center vh-5">
                        <div class="col-lg-6 col-md-4 col-sm-4">
                            <h5 class="page-title font-12">PADRON NOMINAL - DIRESA,</h5>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">


                            <div class="custom-select-container">
                                <label for="provincia">RED</label>
                                <select class="form-control" name="vred" id="vred"
                                    onchange="cargar_lista();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">


                            <div class="custom-select-container">
                                <label for="provincia">MICRORED</label>
                                <select class="form-control" name="vmicrored" id="vmicrored"
                                    onchange="cargar_lista();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2">


                            <div class="custom-select-container">
                                <label for="provincia">EE.SS</label>
                                <select class="form-control" name="veess" id="veess"
                                    onchange="cargar_lista();">
                                    <option value="0">TODOS</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endsection

@section('js')
    <script>
        var table_principal = '';
        $(document).ready(function() {
            cargar_lista();
        });

        // function verListadoTipo(tipo) {
        //     var url = "{{ route('salud.padron.calidad.listadotipo', ':tipo') }}";
        //     url = url.replace(':tipo', tipo);
        //     // Ahora puedes usar la URL generada en tu JavaScript
        //     console.log('URL para el listado del tipo:', url);
        //     // También puedes redirigir a esa URL si lo deseas
        //     window.location.href = url;
        // }

        function cargar_lista() {
            table_principal = $('#datatable').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: true,
                searching: false,
                lengthChange: false,
                language: table_language,
                ajax: "{{ route('salud.padronnominal.calidad.listado') }}",
                data: {
                    red: $('vred').val(),
                    microred: $('vmicrored').val(),
                    eess: $('veess').val()
                },
                type: "POST",
            });
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
