{{-- @extends('layouts.main',['titlePage'=>'']) --}}
@php
    $ps = ['SISMORE EDUCACIÓN', '', 'Bienvenido al Sistema de Monitoreo Regional', 'SISMORE ADMINISTRADOR', 'Ejecución Presupuestal del Gobierno Regional de Ucayali', 'Bienvenido al Sistema de Monitoreo Regional'];
@endphp

@auth
    {{-- @if (!isset(Auth::user()->id)) --}}
        @extends(isset(Auth::user()->layouts) ? (Auth::user()->layouts == 'VERTICAL' ? 'layouts.main' : 'layouts.mainh') : 'layouts.main', [
            'titlePage' => $ps[session('sistema_id') - 1],
            //'titlePage' => session('sistema_id') == 5 ? 'Ejecución Presupuestal del Gobierno Regional de Ucayali' : 'Bienvenido al Sistema de Monitoreo Regional',
        ])
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
