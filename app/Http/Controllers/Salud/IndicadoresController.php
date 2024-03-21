<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
use App\Models\Parametro\Anio;
use App\Models\Parametro\IndicadorGeneral;
use App\Repositories\Parametro\UbigeoRepositorio;

class IndicadoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function PactoRegional()
    {
        $sector = 14;
        $instrumento = 8;
        $inds = IndicadorGeneral::select(
            'id',
            'codigo',
            'nombre',
            'descripcion',
            'numerador',
            'denominador',
            'instrumento_id',
            'tipo_id',
            'dimension_id',
            'unidad_id',
            'frecuencia_id',
            'fuente_dato',
            'anio_base',
            'valor_base',
            'sector_id',
            'oficina_id',
            'estado'
        )->where('sector_id', $sector)->where('instrumento_id', $instrumento)->where('estado', '0')->get();
        return view('salud.Indicadores.PactoRegional', compact('inds'));
    }

    public function PactoRegionalDetalle($indicador_id)
    {
        $ind = IndicadorGeneral::find($indicador_id);
        switch ($ind->codigo) {
            case 'IND0001':
                $actualizado = 'Actualizado al 29 de febrero del 2024';
                $anio = Anio::orderBy('anio')->get();
                $provincia = UbigeoRepositorio::provincia('25');
                $aniomax = 2024;
                return view('salud.Indicadores.PactoRegionalDetalle1', compact('actualizado', 'anio', 'provincia', 'aniomax', 'ind'));

            default:
                return 'ERROR, PAGINA NO ENCONTRADA';
        }
    }

    public function ConvenioFED()
    {
        return view('salud.Indicadores.ConvenioFED');
    }
}
