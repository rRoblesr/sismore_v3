<?php

namespace App\Http\Controllers\Salud;

use App\Http\Controllers\Controller;
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
        return view('salud.Indicadores.PactoRegional',compact('inds'));
    }

    public function ConvenioFED()
    {
        return view('salud.Indicadores.ConvenioFED');
    }
}
