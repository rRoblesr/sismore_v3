@props(['item', 'year', 'unidad', 'prefix' => 'vo'])

@php
    $property = $prefix . $year;
    $value = $item->{$property} ?? null;
@endphp

@if ($year <= now()->year)
    @if ($value !== null)
        {{ $value }}{{ $unidad == 1 ? '%' : '' }}
    @else
        -
    @endif
@endif
