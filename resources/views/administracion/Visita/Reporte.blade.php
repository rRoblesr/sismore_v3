@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'REPORTE DE VISITAS'])

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endsection

@section('content')
    <div class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-border">
                            <div class="card-header border-success-0 bg-transparent pb-2 pl-0">
                                <h4 class="card-title">Historial de Visitas Públicas</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id="tabla" class="table table-striped table-bordered font-12">
                                        <thead class="cabecera-dataTable table-success-0 text-white">
                                            <tr>
                                                <th class="text-center">Nº</th>
                                                <th class="text-center">IP</th>
                                                <th class="text-center">Sistema</th>
                                                <th class="text-center">URL Visitada</th>
                                                <th class="text-center">Navegador</th>
                                                <th class="text-center">Fecha y Hora</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tabla').DataTable({
                responsive: true,
                autoWidth: false,
                ordered: false,
                destroy: true,
                language: table_language,
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ route('visitas.reporte.listar') }}",
                    type: "GET"
                },
                columnDefs: [
                    { targets: 0, className: 'text-center' },
                    { targets: 1, className: 'text-center' },
                    { targets: 2, className: 'text-center' },
                    { targets: 5, className: 'text-center' }
                ]
            });
        });
    </script>
@endsection
