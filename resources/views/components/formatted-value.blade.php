@props(['anio', 'value', 'unidad'])

@if ($anio <= now()->year)
    {{ $value !== null ? $value . ($unidad == 1 ? '%' : '') : '-' }}
@endif
