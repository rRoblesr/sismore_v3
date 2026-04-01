---
name: "filtros-ui"
description: "Estandariza formularios de filtros (selects/inputs) con título arriba en Sismore (Blade+Bootstrap). Invocar cuando el usuario pida crear/ajustar filtros o uniformizar UI de filtros."
---

# Filtros UI (Título Arriba)

## Objetivo

Aplicar un formato único para filtros en vistas Blade del proyecto:
- Label/título arriba del control (Año, Mes, Provincia, Tipo, etc.)
- Estilo compacto consistente (font-11 / font-12)
- Grid Bootstrap alineado (row + col-md-*)
- Comportamiento uniforme en cambios (`onchange`) y parámetros

## Cuándo invocar

Invocar cuando el usuario solicite:
- “ponle el título arriba al filtro”
- “uniformiza los filtros”
- “que todos los filtros se vean igual”
- refactor de formularios de filtros en módulos (Educación/Presupuesto/Salud/etc.)

## Patrón de layout (Blade)

Usar este patrón por control:

```blade
<div class="col-md-2">
    <label class="col-form-label font-11 mb-0">Año</label>
    <select class="form-control font-11" id="fano" name="fano" onchange="cargarmes();cargarcuadros();">
        @foreach ($anos as $item)
            <option value="{{ $item->anio }}" {{ $item->anio == $anio ? 'selected' : '' }}>
                {{ $item->anio }}
            </option>
        @endforeach
    </select>
</div>
```

Notas:
- `label` arriba y `mb-0` para no agrandar altura.
- Mantener `font-11` consistente.
- Evitar comentarios nuevos en el código.

## Patrón de contenedor de filtros

```blade
<form class="form-horizontal" id="form-filtro">
    @csrf
    <div class="form-group row">
        <div class="col-md-8">
            <span class="font-11">{{ $actualizado }}</span>
        </div>
        <!-- filtros aquí -->
    </div>
</form>
```

## JS recomendado (consistencia)

- En `descargar()` usar `window.open()` directo con la ruta correcta y valores del formulario.
- En `cargarcuadros()` y `cargarmes()` enviar parámetros siempre desde `$('#id').val()` y evitar valores `null/null/null` en la URL.

Ejemplo:

```js
function descargar() {
  window.open(
    "{{ url('/') }}/Presupuesto/GobsRegs/Exportar/excel/principal01/" +
    $('#fano').val() + "/" + $('#fmes').val() + "/" + $('#ftipo').val()
  );
}
```

## Checklist rápido

- Label arriba (título visible) en todos los filtros.
- `font-11` / `font-12` consistente.
- `id`/`name` consistentes (`fano`, `fmes`, `ftipo`, etc.).
- `onchange` llama las funciones correctas.
- Descarga usa la ruta real registrada en `routes/web.php`.
