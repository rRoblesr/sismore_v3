{{-- @extends('layouts.main',['titlePage'=>'']) --}}
@php
    // $ps = ['Modulo Educación', 'Modulo Vivienda', 'Modulo Salud', 'Modulo Administrador', 'Modulo Presupuesto', 'Modulo Trabajo'];
    $ps = ['Modulo Educación', '', '', 'Modulo Administrador', '', 'Modulo Trabajo'];
@endphp

@guest
    @extends('layouts.main', ['titlePage' => $ps[session('sistema_publico_id') - 1]])
    @section('content')
        @if (session('sistema_publico_id') == 1)
            @include('inicioEducacionPublico')
        @elseif (session('sistema_publico_id') == 2)
            @include('inicioVivienda')
        @elseif (session('sistema_publico_id') == 3)
            @include('inicioSalud')
        @elseif (session('sistema_publico_id') == 4)
            @include('inicioAdministrador')
        @elseif (session('sistema_publico_id') == 5)
            @include('inicioPresupuesto')
        @elseif (session('sistema_publico_id') == 6)
            @include('inicioTrabajo')
        @else
            <h5>.....</h5>
        @endif
    @endsection

@endguest
