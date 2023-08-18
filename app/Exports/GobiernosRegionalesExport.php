<?php

namespace App\Exports;

use App\Repositories\Presupuesto\BaseGastosRepositorio;
use App\Repositories\Presupuesto\GobiernosRegionalesRepositorio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GobiernosRegionalesExport implements FromView, ShouldAutoSize, WithEvents
{
    public $ano;
    public $mes;
    public $tipo;

    public function __construct($ano, $mes, $tipo)
    {
        $this->ano = $ano;
        $this->mes = $mes;
        $this->tipo = $tipo;
    }

    public function view(): View
    {
        $body = GobiernosRegionalesRepositorio::tipos_gobiernosregionales($this->ano, $this->mes, $this->tipo);
        $foot = ['pia' => 0, 'pim' => 0, 'certificacion' => 0, 'compromiso' => 0, 'devengado' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0];
        foreach ($body as $key => $value) {
            $foot['pia'] += $value->pia;
            $foot['pim'] += $value->pim;
            $foot['certificacion'] += $value->certificacion;
            $foot['compromiso'] += $value->compromiso_anual;
            $foot['devengado'] += $value->devengado;
            $foot['eje'] += $value->eje;
            $foot['saldo1'] += $value->saldo1;
            $foot['saldo2'] += $value->saldo2;
        }
        $foot['eje'] = $foot['pim'] > 0 ? round(100 * $foot['devengado'] / $foot['pim'], 1) : 0;
        return view("Presupuesto.GobiernosRegionales.PrincipalTabla1Export", compact('body', 'foot'));
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
            $event->sheet->getStyle('A28:I28')->applyFromArray($foot);
            $event->sheet->getStyle('B2:I27')->applyFromArray($body);
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
