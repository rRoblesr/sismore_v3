@extends('layouts.main', ['titlePage' => 'IMPORTAR DATOS - PADRON WEB DE INSTITUCIONES EDUCATIVAS'])
@section('css')
    <!-- Table datatable css -->
    <link href="{{ asset('/') }}public/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"> --}}
@endsection
@section('content')
    <div class="container mt-4">
        <h2>Importar Personal Nexus</h2>

        <!-- Formulario de subida -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Subir archivo Excel</h5>
            </div>
            <div class="card-body">
                <form id="formImportar" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Fecha de actualización</label>
                        <input type="date" name="fechaActualizacion" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Archivo Excel (.xlsx, .xls)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="btnSubir">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"
                            id="spinner"></span>
                        Subir y procesar
                    </button>
                </form>
                <div id="mensajeError" class="alert alert-danger mt-3 d-none"></div>
            </div>
        </div>

        <!-- Panel de seguimiento -->
        <div class="card" id="panelSeguimiento" style="display: none;">
            <div class="card-header">
                <h5>Estado de la importación</h5>
            </div>
            <div class="card-body">
                <p><strong>ID de importación:</strong> <span id="idImportacion">-</span></p>
                <p><strong>Estado:</strong> <span id="estadoImportacion">Pendiente</span></p>
                <div class="progress mt-3">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 33%;" id="barraProgreso">
                        Procesando...</div>
                </div>
                <div id="mensajeFinal" class="mt-3"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('formImportar').addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const btn = document.getElementById('btnSubir');
                const spinner = document.getElementById('spinner');
                const errorDiv = document.getElementById('mensajeError');

                btn.disabled = true;
                spinner.classList.remove('d-none');
                errorDiv.classList.add('d-none');

                try {
                    const res = await axios.post('{{ route('impornexus.guardar') }}', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });

                    // Mostrar panel de seguimiento
                    document.getElementById('panelSeguimiento').style.display = 'block';
                    document.getElementById('idImportacion').textContent = res.data.importacion_id;
                    document.getElementById('estadoImportacion').textContent = 'Procesando...';
                    document.getElementById('barraProgreso').className = 'progress-bar bg-info';
                    document.getElementById('barraProgreso').style.width = '33%';
                    document.getElementById('barraProgreso').textContent = 'Procesando...';

                    // Iniciar seguimiento
                    iniciarSeguimiento(res.data.importacion_id);

                } catch (error) {
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                    errorDiv.textContent = error.response?.data?.error || 'Error al subir el archivo.';
                    errorDiv.classList.remove('d-none');
                }
            });

            async function iniciarSeguimiento(importacionId) {
                const interval = setInterval(async () => {
                    try {
                        const res = await axios.get(`/educación/Importar/estado/${importacionId}`);
                        const estado = res.data.estado;

                        if (estado === 'PR') {
                            clearInterval(interval);
                            document.getElementById('estadoImportacion').textContent = '✅ Completado';
                            document.getElementById('barraProgreso').className = 'progress-bar bg-success';
                            document.getElementById('barraProgreso').style.width = '100%';
                            document.getElementById('barraProgreso').textContent = 'Listo';
                            document.getElementById('mensajeFinal').innerHTML =
                                `<div class="alert alert-success">¡Importación exitosa! Total de registros: ${res.data.total_registros}</div>`;
                        } else if (estado === 'EL') {
                            clearInterval(interval);
                            document.getElementById('estadoImportacion').textContent = '❌ Error';
                            document.getElementById('barraProgreso').className = 'progress-bar bg-danger';
                            document.getElementById('barraProgreso').style.width = '100%';
                            document.getElementById('barraProgreso').textContent = 'Fallido';
                            document.getElementById('mensajeFinal').innerHTML =
                                `<div class="alert alert-danger">La importación falló. Por favor, revise el archivo e intente nuevamente.</div>`;
                        }
                        // Si es 'PE', sigue esperando
                    } catch (err) {
                        console.error('Error al verificar estado:', err);
                    }
                }, 2000); // Verifica cada 2 segundos
            }
        </script>
    @endpush
@endsection
