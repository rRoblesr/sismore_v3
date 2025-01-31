<table id="tabla3" class="table table-sm table-striped table-bordered font-11">
    <thead>
        <tr class="table-success-0 text-white">
            <th class="text-center">N°</th>
            <th class="text-center">Código</th>
            <th class="text-center">Establecimiento de Salud</th>
            <th class="text-center">Categoria</th>
            <th class="text-center">Sector</th>
            <th class="text-center">Red</th>
            <th class="text-center">Microrred</th>
            <th class="text-center">Provincia</th>
            <th class="text-center">Distrito</th>
            <th class="text-center">Ubicación</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($base as $key => $item)
            <tr class="text-center">
                <td>{{ $key + 1 }}</td>
                <td class="text-left"> {{ $item->codigo }}</td>
                <td class="text-left"> {{ $item->ipress }}</td>
                <td class="text-left"> {{ $item->categoria }}</td>
                <td class="text-left"> {{ $item->sector }}</td>
                <td class="text-left"> {{ $item->red }}</td>
                <td class="text-left"> {{ $item->microrred }}</td>
                <td class="text-left"> {{ $item->provincia }}</td>
                <td class="text-left"> {{ $item->distrito }}</td>
                
                <td>

                    {{-- 
                    @if ($item->latitud != null || $item->latitud != 0)
                        <button class="btn btn-xs btn-primary"onclick="abrirMapa({{ $item->latitud }},{{ $item->longitud }},'{{ $item->ipress }}')"><i class="fas fa-map-marker-alt"></i></button>
                    @else
                        <button class="btn btn-xs btn-danger"><i class="mdi mdi-cancel"></i></button>
                    @endif 
                    --}}

                    <button class="btn btn-xs btn-primary"
                        onclick="abrirPagina('{{ $item->codigo }}')">
                        <i class="fas fa-map-marker-alt"></i>
                    </button>

                </td>
            </tr>
        @endforeach
    </tbody>
</table>



@php
    function avance($monto)
    {
        if ($monto < 51) {
            return '<span class="badge badge-pill badge-danger" style="font-size:90%;">' .
                round($monto, 2) .
                '%</span>';
        } elseif ($monto < 76) {
            return '<span class="badge badge-pill badge-warning" style="font-size:90%;background-color:#eb960d;">' .
                round($monto, 2) .
                '%</span>';
        } else {
            return '<span class="badge badge-pill badge-success" style="font-size:90%;">' .
                round($monto, 2) .
                '%</span>';
        }
    }
@endphp
