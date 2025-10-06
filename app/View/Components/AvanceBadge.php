<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AvanceBadge extends Component
{
    public float $avance;
    public string $fontSize;
    public string $width;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        ?float $avance = 0.0, // Valor entre 0 y 100 acepta null
        string $fontSize = '90%',
        string $width = '50px'
    ) {
        $this->avance = $avance ?? 0.0;
        $this->fontSize = $fontSize;
        $this->width = $width;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.avance-badge');
    }

    public function color(): string
    {
        if ($this->avance > 95) {
            return 'success';
        } elseif ($this->avance > 50) {
            return 'warning';
        } else {
            return 'danger';
        }
    }
}
