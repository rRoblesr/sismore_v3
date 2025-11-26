@extends('layouts.main', ['activePage' => 'usuarios', 'titlePage' => 'GESTION DE SISTEMAS'])
{{-- @extends('layouts.app') --}}

@push('css')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .log-message {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            background: transparent;
            border: none;
            color: inherit;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .emergency-message {
            color: #ff0000;
            font-weight: bold;
        }

        .alert-message {
            color: #ff4444;
            font-weight: bold;
        }

        .critical-message {
            color: #ff6666;
        }

        .error-message {
            color: #ff8888;
        }

        .warning-message {
            color: #ffaa00;
        }

        .notice-message {
            color: #00aa00;
        }

        .info-message {
            color: #8888ff;
        }

        .debug-message {
            color: #aaaaaa;
        }

        .unknown-message {
            color: #666666;
        }

        .hour-sections,
        .entries-table {
            display: block;
        }

        .hour-sections.collapsed,
        .entries-table.collapsed {
            display: none;
        }

        .log-entry {
            display: table-row;
        }

        .log-entry.hidden {
            display: none;
        }

        .highlight {
            background-color: yellow;
            color: black;
            font-weight: bold;
        }

        /* Badge colors */
        .badge-emergency {
            background-color: #ff0000;
        }

        .badge-alert {
            background-color: #ff4444;
        }

        .badge-critical {
            background-color: #ff6666;
        }

        .badge-error {
            background-color: #ff8888;
            color: #000;
        }

        .badge-warning {
            background-color: #ffaa00;
            color: #000;
        }

        .badge-notice {
            background-color: #00aa00;
        }

        .badge-info {
            background-color: #8888ff;
            color: #000;
        }

        .badge-debug {
            background-color: #aaaaaa;
            color: #000;
        }

        .badge-unknown {
            background-color: #666666;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-alt"></i> Log: {{ $filename }}
                        </h4>
                        <div>
                            <!-- Buscador -->
                            <div class="input-group input-group-sm mr-2" style="width: 250px;">
                                <input type="text" id="logSearch" class="form-control" placeholder="Buscar en log...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-light" type="button" id="clearSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <a href="{{ route('logs.download', $filename) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Descargar
                            </a>
                            <a href="{{ route('logs.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>

                    <!-- Filtros Rápidos -->
                    <div class="card-header bg-light py-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary active filter-btn" data-level="all">
                                        Todos
                                    </button>
                                    <button class="btn btn-outline-danger filter-btn" data-level="error">
                                        Errores
                                    </button>
                                    <button class="btn btn-outline-warning filter-btn" data-level="warning">
                                        Advertencias
                                    </button>
                                    <button class="btn btn-outline-info filter-btn" data-level="info">
                                        Info
                                    </button>
                                    <button class="btn btn-outline-secondary filter-btn" data-level="debug">
                                        Debug
                                    </button>
                                </div>

                                <div class="btn-group btn-group-sm ml-2">
                                    <button class="btn btn-outline-dark" id="expandAll">
                                        <i class="fas fa-expand"></i> Expandir Todo
                                    </button>
                                    <button class="btn btn-outline-dark" id="collapseAll">
                                        <i class="fas fa-compress"></i> Colapsar Todo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (count($organizedLogs) > 0)
                            <div class="log-content" id="logContent">
                                @foreach ($organizedLogs as $date => $hours)
                                    <div class="date-section mb-4">
                                        <h5
                                            class="date-header bg-dark text-light p-2 rounded d-flex justify-content-between align-items-center cursor-pointer">
                                            <span>
                                                <i class="fas fa-calendar-day"></i>
                                                {{ $date === 'sin_fecha' ? 'Sin Fecha' : \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                                {{-- <small class="badge badge-light ml-2">
                                                    {{ $this->countEntries($hours) }} entradas
                                                </small> --}}
                                                <small class="badge badge-light ml-2">
                                                    {{-- Reemplazar esto: {{ $this->countEntries($hours) }} --}}
                                                    @php
                                                        $count = 0;
                                                        foreach ($hours as $entries) {
                                                            $count += count($entries);
                                                        }
                                                        echo $count;
                                                    @endphp
                                                    entradas
                                                </small>
                                            </span>
                                            <i class="fas fa-chevron-down toggle-icon"></i>
                                        </h5>

                                        <div class="hour-sections ml-3">
                                            @foreach ($hours as $hour => $entries)
                                                <div class="hour-section mb-3">
                                                    <h6
                                                        class="hour-header bg-secondary text-light p-2 rounded d-flex justify-content-between align-items-center cursor-pointer">
                                                        <span>
                                                            <i class="fas fa-clock"></i>
                                                            {{ $hour === 'sin_hora' ? 'Sin Hora' : $hour }}
                                                            <small
                                                                class="badge badge-light ml-2">{{ count($entries) }}</small>
                                                        </span>
                                                        <i class="fas fa-chevron-down toggle-icon"></i>
                                                    </h6>

                                                    <div class="entries-table ml-3">
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-borderless table-hover mb-0">
                                                                <tbody>
                                                                    @foreach ($entries as $entry)
                                                                        <tr class="log-entry"
                                                                            data-level="{{ $entry['level'] }}"
                                                                            data-message="{{ strtolower($entry['message']) }}"
                                                                            data-time="{{ $entry['full_time'] }}">
                                                                            <td class="text-nowrap" style="width: 80px;">
                                                                                <small
                                                                                    class="text-muted">{{ $entry['time'] }}</small>
                                                                            </td>
                                                                            <td class="text-nowrap" style="width: 100px;">
                                                                                {{-- <span
                                                                                    class="badge badge-{{ $this->getLevelBadgeClass($entry['level']) }} level-badge">
                                                                                    {{ strtoupper($entry['level']) }}
                                                                                </span> --}}
                                                                                {{-- <span
                                                                                    class="badge badge-{{ match ($entry['level']) {
                                                                                        'emergency' => 'emergency',
                                                                                        'alert' => 'alert',
                                                                                        'critical' => 'critical',
                                                                                        'error' => 'error',
                                                                                        'warning' => 'warning',
                                                                                        'notice' => 'notice',
                                                                                        'info' => 'info',
                                                                                        'debug' => 'debug',
                                                                                        'unknown' => 'unknown',
                                                                                        default => 'secondary',
                                                                                    } }} level-badge">
                                                                                    {{ strtoupper($entry['level']) }}
                                                                                </span> --}}
                                                                                @php
                                                                                    $levelBadges = [
                                                                                        'emergency' => 'emergency',
                                                                                        'alert' => 'alert',
                                                                                        'critical' => 'critical',
                                                                                        'error' => 'error',
                                                                                        'warning' => 'warning',
                                                                                        'notice' => 'notice',
                                                                                        'info' => 'info',
                                                                                        'debug' => 'debug',
                                                                                        'unknown' => 'unknown',
                                                                                    ];
                                                                                @endphp

                                                                                {{-- ... luego en el loop ... --}}
                                                                                <span
                                                                                    class="badge badge-{{ $levelBadges[$entry['level']] ?? 'secondary' }} level-badge">
                                                                                    {{ strtoupper($entry['level']) }}
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <pre class="log-message mb-0 {{ $entry['level'] }}-message">{{ $entry['message'] }}</pre>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-file-alt fa-3x mb-3"></i>
                                <h5>El archivo de log está vacío</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @push('js')
    <script>
        $(document).ready(function() {
            // Toggle sections
            $('.date-header, .hour-header').click(function() {
                const target = $(this).next();
                const icon = $(this).find('.toggle-icon');

                target.toggleClass('collapsed');
                icon.toggleClass('fa-chevron-down fa-chevron-up');
            });

            // Expandir/Contraer todo
            $('#expandAll').click(function() {
                $('.hour-sections, .entries-table').removeClass('collapsed');
                $('.toggle-icon').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            });

            $('#collapseAll').click(function() {
                $('.hour-sections, .entries-table').addClass('collapsed');
                $('.toggle-icon').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            });

            // Filtros por nivel
            $('.filter-btn').click(function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');

                const level = $(this).data('level');

                $('.log-entry').each(function() {
                    if (level === 'all' || $(this).data('level') === level) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                    }
                });
            });

            // Buscador
            $('#logSearch').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();

                if (searchTerm.length === 0) {
                    $('.log-entry').removeClass('hidden');
                    $('.highlight').removeClass('highlight');
                    return;
                }

                $('.log-entry').each(function() {
                    const message = $(this).data('message');
                    const preElement = $(this).find('.log-message');
                    const originalText = preElement.text();

                    if (message.includes(searchTerm)) {
                        $(this).removeClass('hidden');

                        // Resaltar texto
                        const regex = new RegExp(`(${searchTerm})`, 'gi');
                        const highlighted = originalText.replace(regex,
                            '<span class="highlight">$1</span>');
                        preElement.html(highlighted);
                    } else {
                        $(this).addClass('hidden');
                        preElement.text(originalText); // Restaurar texto original
                    }
                });
            });

            // Limpiar búsqueda
            $('#clearSearch').click(function() {
                $('#logSearch').val('').trigger('input');
            });
        });
    </script>
@endpush --}}

@push('js')
    <script>
        $(document).ready(function() {
            // Inicializar todos los sections como expandidos
            $('.hour-sections, .entries-table').removeClass('collapsed');
            $('.toggle-icon').removeClass('fa-chevron-down').addClass('fa-chevron-up');

            // Toggle sections - CORREGIDO
            $('.date-header').click(function() {
                const target = $(this).next('.hour-sections');
                const icon = $(this).find('.toggle-icon');

                target.toggleClass('collapsed');
                icon.toggleClass('fa-chevron-down fa-chevron-up');
            });

            $('.hour-header').click(function() {
                const target = $(this).next('.entries-table');
                const icon = $(this).find('.toggle-icon');

                target.toggleClass('collapsed');
                icon.toggleClass('fa-chevron-down fa-chevron-up');
            });

            // Expandir/Contraer todo - CORREGIDO
            $('#expandAll').click(function() {
                $('.hour-sections, .entries-table').removeClass('collapsed');
                $('.toggle-icon').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            });

            $('#collapseAll').click(function() {
                $('.hour-sections, .entries-table').addClass('collapsed');
                $('.toggle-icon').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            });

            // Filtros por nivel - CORREGIDO
            $('.filter-btn').click(function() {
                // Remover active de todos los botones
                $('.filter-btn').removeClass(
                        'active btn-primary btn-danger btn-warning btn-info btn-secondary')
                    .addClass(
                        'btn-outline-primary btn-outline-danger btn-outline-warning btn-outline-info btn-outline-secondary'
                        );

                // Agregar active al botón clickeado
                $(this).removeClass(
                        'btn-outline-primary btn-outline-danger btn-outline-warning btn-outline-info btn-outline-secondary'
                        )
                    .addClass('active');

                // Aplicar clases de color según el tipo de botón
                const level = $(this).data('level');
                if (level === 'error') {
                    $(this).addClass('btn-danger');
                } else if (level === 'warning') {
                    $(this).addClass('btn-warning');
                } else if (level === 'info') {
                    $(this).addClass('btn-info');
                } else if (level === 'debug') {
                    $(this).addClass('btn-secondary');
                } else {
                    $(this).addClass('btn-primary');
                }

                // Filtrar entradas
                $('.log-entry').each(function() {
                    if (level === 'all' || $(this).data('level') === level) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                    }
                });
            });

            // Buscador - MEJORADO
            $('#logSearch').on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();

                if (searchTerm.length === 0) {
                    $('.log-entry').removeClass('hidden');
                    $('.log-message').each(function() {
                        const originalText = $(this).data('original-text') || $(this).text();
                        $(this).text(originalText);
                    });
                    return;
                }

                $('.log-entry').each(function() {
                    const preElement = $(this).find('.log-message');
                    let originalText = $(this).data('original-text');

                    // Guardar texto original si no está guardado
                    if (!originalText) {
                        originalText = preElement.text();
                        $(this).data('original-text', originalText);
                    }

                    const message = originalText.toLowerCase();

                    if (message.includes(searchTerm)) {
                        $(this).removeClass('hidden');

                        // Resaltar texto - MEJORADO
                        const regex = new RegExp(
                            `(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                        const highlighted = originalText.replace(regex,
                            '<span class="highlight">$1</span>');
                        preElement.html(highlighted);
                    } else {
                        $(this).addClass('hidden');
                        preElement.text(originalText); // Restaurar texto original
                    }
                });
            });

            // Limpiar búsqueda - MEJORADO
            $('#clearSearch').click(function() {
                $('#logSearch').val('').trigger('input');
            });

            // Inicializar botón "Todos" como activo
            $('.filter-btn[data-level="all"]').addClass('btn-primary').removeClass('btn-outline-primary');
        });
    </script>
@endpush
