@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'GESTION DE SISTEMAS'])
@section('css')
    <style>
        .btn-group-sm>.btn {
            padding: 0.25rem 0.5rem;
        }

        .table th {
            border-top: none;
        }
    </style>
@endsection
{{-- @extends('layouts.app') --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-alt"></i> Gestión de Logs del Sistema
                    </h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (count($logFiles) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Nombre del Archivo</th>
                                        <th>Tamaño</th>
                                        <th>Última Modificación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logFiles as $log)
                                        <tr>
                                            <td>
                                                <i class="fas fa-file-text text-info"></i>
                                                {{ $log['name'] }}
                                            </td>
                                            <td><span class="badge badge-secondary">{{ $log['size'] }}</span></td>
                                            <td><small class="text-muted">{{ $log['last_modified'] }}</small></td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('logs.show', $log['name']) }}"
                                                        class="btn btn-info" title="Ver contenido">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('logs.download', $log['name']) }}"
                                                        class="btn btn-success" title="Descargar">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-warning"
                                                        title="Vaciar contenido"
                                                        onclick="confirmClear('{{ $log['name'] }}')">
                                                        <i class="fas fa-broom"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger"
                                                        title="Eliminar archivo"
                                                        onclick="confirmDelete('{{ $log['name'] }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            <h5><i class="fas fa-exclamation-triangle"></i> No se encontraron archivos de log</h5>
                            <p class="mb-0">No hay archivos .log en el directorio de logs.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el archivo <strong id="fileName"></strong>?</p>
                <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Vaciar -->
<div class="modal fade" id="clearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Vaciar Log</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas vaciar el contenido del archivo <strong id="clearFileName"></strong>?</p>
                <p class="text-warning"><small>El archivo se mantendrá pero estará vacío.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="clearForm" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning">Vaciar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        function confirmDelete(filename) {
            document.getElementById('fileName').textContent = filename;
            document.getElementById('deleteForm').action = '{{ url('logs') }}/' + filename;
            $('#deleteModal').modal('show');
        }

        function confirmClear(filename) {
            document.getElementById('clearFileName').textContent = filename;
            document.getElementById('clearForm').action = '{{ url('logs') }}/' + filename + '/clear';
            $('#clearModal').modal('show');
        }
    </script>
@endsection
