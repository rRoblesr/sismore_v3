<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CumpleBoton extends Component
{
    public bool $cumple;
    public string $size; // ej: 'btn-xs', 'btn-sm', etc.
    public string $fontSize; // ej: '12px', '0.875rem', etc.
    public string $extraClass;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        bool $cumple,
        string $size = 'btn-xs',
        string $fontSize = 'inherit',
        string $extraClass = ''
    ) {
        $this->cumple = $cumple;
        $this->size = $size;
        $this->fontSize = $fontSize;
        $this->extraClass = $extraClass;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.cumple-boton');
    }

    public function text(): string
    {
        return $this->cumple ? 'Cumple' : 'No Cumple';
    }

    public function colorClass(): string
    {
        return $this->cumple ? 'btn-success-0' : 'btn-danger';
    }
}
