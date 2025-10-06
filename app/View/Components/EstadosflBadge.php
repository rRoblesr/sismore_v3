<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EstadosflBadge extends Component
{
    public int $estadoId;
    public string $width;
    public string $fontSize;
    public string $extraClass;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        int $estadoId,
        string $width = 'auto',
        string $fontSize = 'inherit',
        string $extraClass = ''
    ) {
        $this->estadoId = $estadoId;
        $this->width = $width;
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
        return view('components.estadosfl-badge');
    }

    public function texto(): string
    {
        switch ($this->estadoId) {
            case 1:
                return 'SANEADO';
            case 2:
                return 'NO SANEADO';
            case 3:
                return 'NO REGISTRADO';
            case 4:
                return 'EN PROCESO';
            default:
                return 'DESCONOCIDO';
        }
    }

    public function colorClass(): string
    {
        switch ($this->estadoId) {
            case 1:
                return 'badge-success';
            case 2:
                return 'badge-danger';
            case 3:
                return 'badge-secondary';
            case 4:
                return 'badge-warning';
            default:
                return 'badge-light';
        }
    }
}
