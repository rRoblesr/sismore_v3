{{-- @extends('layouts.main',['titlePage'=>'']) --}}
@php
    // $ps = ['MODULO EDUCACIÓN', 'MODULO VIVIENDA', 'MODULO SALUD', 'SISMORE ADMINISTRADOR', 'MODULO PRESUPUESTO', 'MODULO TRABAJO'];
    $ps = ['MODULO EDUCACIÓN', '', '', 'SISMORE ADMINISTRADOR', 'MODULO PRESUPUESTO', 'MODULO TRABAJO'];
@endphp

@auth
    @extends('layouts.main', ['titlePage' => $ps[session('sistema_id') - 1]])
    @section('content')
        @if (session('sistema_id') == 1)
            @include('inicioEducacion')
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
    {{-- @endif --}}
@endauth
