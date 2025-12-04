{{-- <td><x-avance-badge :avance="$item->le1p" /></td> --}}
<div>
    <span class="badge badge-pill badge-{{ $color() }}"
        style="font-size: {{ $fontSize }}; width: {{ $width }};">
        {{ number_format($avance, 1) }}%
    </span>
</div>
