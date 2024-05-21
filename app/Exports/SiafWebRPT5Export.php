<?php

namespace App\Exports;

use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\BaseSiafWebRepositorio;
use App\Repositories\Presupuesto\GobiernosRegionalesRepositorio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SiafWebRPT5Export implements FromView, ShouldAutoSize, WithEvents
{
    public $ano;
    public $articulo;
    public $ue;

    public function __construct($ano, $articulo, $ue)
    {
        $this->ano = $ano;
        $this->articulo = $articulo;
        $this->ue = $ue;
    }

    public function view(): View
    {
        $data = BaseSiafWebRepositorio::listar_fuentefinanciamiento_anio_acticulo_ue_categoria($this->ano, $this->articulo, $this->ue);
        $body = $data['body'];
        $head = $data['head'];
        $foot = clone $data['body'][0];
        $foot->pia = 0;
        $foot->pim = 0;
        $foot->cert = 0;
        $foot->dev = 0;
        $foot->saldo1 = 0;
        $foot->saldo2 = 0;
        $foot->eje = 0;
        foreach ($body as $key => $value) {
            $foot->pia += $value->pia;
            $foot->pia += $value->pim;
            $foot->pia += $value->cert;
            $foot->pia += $value->dev;
            $foot->pia  += $value->saldo1;
            $foot->pia  += $value->saldo2;
        }
        $foot->eje = $foot->pim > 0 ? number_format(100 * $foot->dev / $foot->pim, 1) : 0;
        return view("Presupuesto.BaseSiafWeb.Reporte5Tabla1Export", compact('body', 'head', 'foot'));
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
            $event->sheet->getStyle('A1:I1')->applyFromArray($head);
            //$event->sheet->getStyle('A28:I28')->applyFromArray($foot);
            $event->sheet->getStyle('B2:I27')->applyFromArray($body);
            $event->sheet->getStyle('A2:A27')->applyFromArray($bodya);
            $event->sheet->getStyle('B2:B27')->applyFromArray($bodyb);
            //$event->sheet->getStyle('A1:I28')->applyFromArray($border);
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
