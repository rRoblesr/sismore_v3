<?php

namespace App\Exports;

use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\GobiernosRegionalesRepositorio;
use App\Repositories\Presupuesto\ModificacionesRepositorio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ModificacionesGastoExport implements FromView, ShouldAutoSize, WithEvents
{
    public $ano;
    public $mes;
    public $articulo;
    public $tipo;
    public $dispositivo;
    public $ue;

    public function __construct($ano, $mes, $articulo, $tipo, $dispositivo, $ue)
    {
        $this->ano = $ano;
        $this->mes = $mes;
        $this->articulo = $articulo;
        $this->tipo = $tipo;
        $this->dispositivo = $dispositivo;
        $this->ue = $ue;
    }

    public function view(): View
    {
        $body = ModificacionesRepositorio::listar_modificaciones($this->ano, $this->mes, $this->articulo, $this->tipo, $this->dispositivo, $this->ue);
        $foot = ['anulacion' => 0, 'credito' => 0];
        foreach ($body as $key => $value) {
            $foot['anulacion'] += $value->anulacion;
            $foot['credito'] += $value->credito;
        }
        return view("Presupuesto.Modificaciones.PrincipalTabla1Export", compact('body', 'foot'));
    }

    public function  registerEvents(): array
    {
        $head = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'ffffff'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                //'rotation' => 90,
                'startColor' => [
                    'argb' => '317eeb',
                ],
                'endColor' => [
                    'argb' => '317eeb',
                ],

            ],
        ];
        $foot = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'ffffff'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                //'rotation' => 90,
                'startColor' => [
                    'argb' => '317eeb',
                ],
                'endColor' => [
                    'argb' => '317eeb',
                ],

            ],
        ];
        $body = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ];
        $bodya = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $bodyb = [
            'font' => [
                'bold' => false,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'EBE8E4'],
                ],

            ],
        ];
        $opt = [AfterSheet::class => function (AfterSheet $event) use ($head, $foot, $body, $bodya, $bodyb, $border) {
            $event->sheet->getStyle('A1:M1')->applyFromArray($head);
            //$event->sheet->getStyle('A28:I28')->applyFromArray($foot);
            //$event->sheet->getStyle('B2:I27')->applyFromArray($body);
            $event->sheet->getStyle('A2:A27')->applyFromArray($bodya);
            $event->sheet->getStyle('B2:B27')->applyFromArray($bodyb);
            $event->sheet->getStyle('A1:I28')->applyFromArray($border);
        }];

        return $opt;

        /* return [AfterSheet::class => function (AfterSheet $event) use ($head, $foot, $body, $bodya, $border) {
            $event->sheet->getStyle('A1:M2')->applyFromArray($head);
            $event->sheet->getStyle('A12:M12')->applyFromArray($foot);
            $event->sheet->getStyle('B3:M11')->applyFromArray($body);
            $event->sheet->getStyle('A3:A11')->applyFromArray($bodya);
            $event->sheet->getStyle('A1:M12')->applyFromArray($border);
        }]; */
    }
}
