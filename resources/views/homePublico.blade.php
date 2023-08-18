{{-- @extends('layouts.main',['titlePage'=>'']) --}}
@php
    $ps = ['SISMORE Modulo Educación', '', 'Bienvenido al Sistema de Monitoreo Regional', 'Bienvenido al Sistema de Monitoreo Regional', 'Ejecución Presupuestal del Gobierno Regional de Ucayali', 'Bienvenido al Sistema de Monitoreo Regional'];
@endphp


@guest
    @extends('layouts.main', [
        'titlePage' => $ps[session('sistema_id') - 1],
    ])
    @section('content')
        @if (session('sistema_id') == 1)
            @include('inicioEducacionPublico')
        @elseif (session('sistema_id') == 2)
            @include('inicioVivienda')
        @elseif (session('sistema_id') == 3)
            @include('inicioSalud')
        @elseif (session('sistema_id') == 4)
            @include('inicioAdministrador')
        @elseif (session('sistema_id') == 5)
            @include('inicioPresupuesto')
        @elseif (session('sistema_id') == 6)
            @include('inicioTrabajo')
        @else
            <h5>.....</h5>
        @endif
    @endsection

@endguest

