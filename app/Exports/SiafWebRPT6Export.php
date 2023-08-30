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

class SiafWebRPT6Export implements FromView, ShouldAutoSize, WithEvents
{
    public $ano;
    public $articulo;
    public $ue;
    public $basesiafweb_id;

    public function __construct($ano, $articulo, $ue, $basesiafweb_id)
    {
        $this->ano = $ano;
        $this->articulo = $articulo;
        $this->ue = $ue;
        $this->basesiafweb_id = $basesiafweb_id;
    }

    public function view(): View
    {
        foreach ($this->ue as $key => $ue) {
            $data = BaseSiafWebRepositorio::listar_generica_anio_acticulo_ue_categoria($this->ano, $this->articulo, $ue->id);
            $body[$ue->id] = $data['body'];
            $head[$ue->id] = $data['head'];
            $foot[$ue->id] = ['pia' => 0, 'pim' => 0, 'cert' => 0, 'dev' => 0, 'eje' => 0, 'saldo1' => 0, 'saldo2' => 0];
            foreach ($body[$ue->id] as $key => $value) {
                $foot[$ue->id]['pia'] += $value->pia;
                $foot[$ue->id]['pim'] += $value->pim;
                $foot[$ue->id]['cert'] += $value->cert;
                $foot[$ue->id]['dev'] += $value->dev;
                $foot[$ue->id]['saldo1'] += $value->saldo1;
                $foot[$ue->id]['saldo2'] += $value->saldo2;
            }
            $foot[$ue->id]['eje'] = $foot[$ue->id]['pim'] > 0 ? number_format(100 * $foot[$ue->id]['dev'] / $foot[$ue->id]['pim'], 1) : 0;
        }

        $ue = $this->ue;

        return view("Presupuesto.BaseSiafWeb.Reporte6Tabla1Export", compact('head', 'body', 'foot', 'ue'));
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
            //$event->sheet->getStyle('A1:H1')->applyFromArray($head);
            //$event->sheet->getStyle('A28:I28')->applyFromArray($foot);
            //$event->sheet->getStyle('B2:H27')->applyFromArray($body);
            //$event->sheet->getStyle('A2:A27')->applyFromArray($bodyb);
            //$event->sheet->getStyle('B2:B27')->applyFromArray($bodyb);
            //$event->sheet->getStyle('A1:I28')->applyFromArray($border);
        }];

        return $opt;

        //return [AfterSheet::class => function (AfterSheet $event) use ($head, $foot, $body, $bodya, $border) {
        //    $event->sheet->getStyle('A1:M2')->applyFromArray($head);
        //    $event->sheet->getStyle('A12:M12')->applyFromArray($foot);
        //    $event->sheet->getStyle('B3:M11')->applyFromArray($body);
        //    $event->sheet->getStyle('A3:A11')->applyFromArray($bodya);
        //    $event->sheet->getStyle('A1:M12')->applyFromArray($border);
        //}];
    }
}
