@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-alt"></i> Contenido del Log: {{ $filename }}
                        </h4>
                        <div>
                            <a href="{{ route('logs.download', $filename) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Descargar
                            </a>
                            <a href="{{ route('logs.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="log-content bg-dark text-light p-3 rounded"
                            style="max-height: 70vh; overflow-y: auto; font-family: 'Courier New', monospace; font-size: 0.9rem;">
                            @if (count($lines) > 0)
                                @foreach ($lines as $line)
                                    @if (!empty(trim($line)))
                                        <div class="log-line mb-1">
                                            @if (strpos($line, 'ERROR') !== false)
                                                <span class="text-danger">{{ $line }}</span>
                                            @elseif(strpos($line, 'WARNING') !== false)
                                                <span class="text-warning">{{ $line }}</span>
                                            @elseif(strpos($line, 'INFO') !== false)
                                                <span class="text-info">{{ $line }}</span>
                                            @else
                                                <span class="text-light">{{ $line }}</span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                                    <p>El archivo de log está vacío</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
