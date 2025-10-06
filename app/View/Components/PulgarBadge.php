<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PulgarBadge extends Component
{
    public $cumple;
    public $fontSize;
    public $color;
    public $width;
    public $title;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $cumple,
        string $fontSize = '13px',
        ?string $color = null,
        string $width = 'auto',
        ?string $title = null
    ) {
        $this->cumple = $cumple;
        $this->fontSize = $fontSize;
        $this->color = $color;
        $this->width = $width;
        $this->title = $title ?? ($cumple ? 'CUMPLE' : 'NO CUMPLE');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.pulgar-badge');
    }

    public function icon(): string
    {
        return $this->cumple ? 'mdi-thumb-up' : 'mdi-thumb-down';
    }

    public function defaultColor(): string
    {
        return $this->cumple ? '#43beac' : 'red';
    }

    public function finalColor(): string
    {
        return $this->color ?? $this->defaultColor();
    }
}
