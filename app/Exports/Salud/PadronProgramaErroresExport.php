<?php

namespace App\Exports\Salud;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PadronProgramaErroresExport implements FromCollection, WithHeadings, WithEvents
{
    protected $importacion_id;

    public function __construct($importacion_id)
    {
        $this->importacion_id = $importacion_id;
    }

    public function collection()
    {
        // return DB::table('sal_impor_padron_programa as p')
        //     ->leftJoin('sal_padron_programa_b as b', 'b.impor_padron_programa_id', '=', 'p.id')
        //     ->whereNull('b.impor_padron_programa_id')
        //     ->where('p.importacion_id', $this->importacion_id)
        //     ->select(
        //         'p.programa',
        //         'p.servicio',
        //         'p.anio',
        //         'p.mes',
        //         'p.tipo_doc_m',
        //         'p.num_doc_m',
        //         'p.ape_pat_m',
        //         'p.ape_mat_m',
        //         'p.nombre_m',
        //         'p.sexo_m',
        //         'p.fec_nac_m',
        //         'p.telefono',
        //         'p.direccion',
        //         'p.referencia',
        //         'p.ubigeo_distrito',
        //         'p.ubigeo_ccpp',
        //         'p.latitud',
        //         'p.longitud',
        //         'p.num_doc_a',
        //         'p.ape_pat_a',
        //         'p.ape_mat_a',
        //         'p.nombre_a'
        //     )
        //     ->get();

        $data = DB::table('sal_impor_padron_programa as p')
            ->leftJoin('sal_padron_programa_b as b', 'b.impor_padron_programa_id', '=', 'p.id')
            ->whereNull('b.impor_padron_programa_id')
            ->where('p.importacion_id', $this->importacion_id)
            ->select(
                'p.servicio',
                'p.anio',
                'p.mes',
                'p.tipo_doc_m',
                'p.num_doc_m',
                'p.ape_pat_m',
                'p.ape_mat_m',
                'p.nombre_m',
                'p.sexo_m',
                'p.fec_nac_m',
                'p.telefono',
                'p.direccion',
                'p.referencia',
                'p.ubigeo_distrito',
                'p.ubigeo_ccpp',
                'p.latitud',
                'p.longitud',
                'p.num_doc_a',
                'p.ape_pat_a',
                'p.ape_mat_a',
                'p.nombre_a'
            )
            ->get();

        // Transforma la columna de fecha si es numérica (asumiendo formato Excel)
        $data->transform(function ($item) {
            if (is_numeric($item->fec_nac_m)) {
                // Convierte el número de Excel a un objeto DateTime
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item->fec_nac_m);
                // Formatea la fecha como "día/mes/año" (ajusta el formato según necesites)
                $item->fec_nac_m = $date->format('d/m/Y');
            }
            return $item;
        });

        return $data;
    }

    public function headings(): array
    {
        // return [
        //     'programa',
        //     'servicio',
        //     'anio',
        //     'mes',
        //     'tipo_doc_m',
        //     'num_doc_m',
        //     'ape_pat_m',
        //     'ape_mat_m',
        //     'nombre_m',
        //     'sexo_m',
        //     'fec_nac_m',
        //     'telefono',
        //     'direccion',
        //     'referencia',
        //     'ubigeo_distrito',
        //     'ubigeo_ccpp',
        //     'latitud',
        //     'longitud',
        //     'num_doc_a',
        //     'ape_pat_a',
        //     'ape_mat_a',
        //     'nombre_a'
        // ];
        return [
            'SERVICIO',
            'ANIO',
            'MES',
            'TIPO_DOC_MENOR',
            'NUM_DOC_MENOR',
            'APE_PAT_MENOR',
            'APE_MAT_MENOR',
            'NOMBRE_MENOR',
            'SEXO_MENOR',
            'FEC_NAC_MENOR',
            'TELEFONO',
            'DIRECCION',
            'REFERENCIA',
            'UBIGEO_DISTRITO',
            'UBIGEO_CCPP',
            'LATITUD',
            'LONGITUD',
            'NUM_DOC_APODERADO',
            'APE_PAT_APODERADO',
            'APE_MAT_APODERADO',
            'NOMBRE_APODERADO'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Obtén la última fila con datos
                $highestRow = $event->sheet->getDelegate()->getHighestRow();

                // Aplica negrita a la cabecera (fila 1, de A a V)
                $event->sheet->getDelegate()->getStyle('A1:V1')->getFont()->setBold(true);

                // Define el rango para las columnas 'servicio', 'anio', 'mes'
                $range1 = "C1:F{$highestRow}";
                // Define el rango para las columnas 'sexo_m' y 'fec_nac_m'
                $range2 = "I1:J{$highestRow}";

                $range3 = "N1:O{$highestRow}";

                $styleArray = [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['argb' => 'FFFFFF00'], // Amarillo (Asegúrate de usar formato ARGB: FF + RGB)
                    ],
                ];

                // Aplica el estilo al rango 1
                $event->sheet->getDelegate()->getStyle($range1)->applyFromArray($styleArray);
                // Aplica el estilo al rango 2
                $event->sheet->getDelegate()->getStyle($range2)->applyFromArray($styleArray);

                $event->sheet->getDelegate()->getStyle($range3)->applyFromArray($styleArray);
            },
        ];
    }
}
