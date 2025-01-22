<?php

namespace App\Exports\Salud;

use App\Models\Parametro\IndicadorGeneralMeta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgregarMetasExport implements FromCollection, WithHeadings
{
    public $indicador;
    public function __construct($indicador)
    {
        $this->indicador = $indicador;
    }

    public function collection()
    {
        // return IndicadorGeneralMeta::from('par_indicador_general_meta igm')->select('igm.id', 'igm.indicadorgeneral', 'igm.distrito', 'u.nombre', 'igm.anio', 'igm.valor')->join('par_ubigeo as u', 'u.id', '=', 'igm.distrito')->where('indicadorgeneral', $this->indicador)->get();
        return IndicadorGeneralMeta::select(
            'par_indicador_general_meta.id',
            'par_indicador_general_meta.indicadorgeneral',
            'par_indicador_general_meta.distrito',
            'par_ubigeo.nombre',
            'par_indicador_general_meta.anio',
            'par_indicador_general_meta.valor'
        )
            ->join('par_ubigeo', 'par_ubigeo.id', '=', 'par_indicador_general_meta.distrito')
            ->where('par_indicador_general_meta.indicadorgeneral', $this->indicador)
            ->get();
    }

    public function headings(): array
    {
        return ["ID", 'INDICADOR', "DISTRITO", "DISTRITO_NOMBRE", "ANIO", "META"];
    }
}
