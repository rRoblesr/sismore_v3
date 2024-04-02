<?php

namespace App\Repositories\Parametro;

use App\Models\Parametro\Clasificador;
use App\Models\Parametro\IndicadorGeneral;
use Illuminate\Support\Facades\DB;

class IndicadorGeneralRepositorio
{
    public static function find_pactoregional($sector, $instrumento)
    {
        $query = IndicadorGeneral::select(
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
        return $query;
    }

    public static function findNoFichatecnica($id)
    {
        return IndicadorGeneral::select('id', 'codigo', 'nombre', 'descripcion', 'instrumento_id', 'tipo_id', 'dimension_id', 'unidad_id', 'frecuencia_id', 'fuente_dato', 'anio_base', 'valor_base', 'sector_id', 'oficina_id', 'estado')->where('id', $id)->first();
    }

    public static function findNoFichatecnicaCodigo($codigo)
    {
        return IndicadorGeneral::select(
            'id',
            'codigo',
            'nombre',
            'descripcion',
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
        )->where('codigo', $codigo)->where('estado','0')->first();
    }

    public static function instrumento($id)
    {
        if ($id > 0)
            $data = DB::table('par_instrumento')->where('id', $id)->first();
        else
            $data = DB::table('par_instrumento')->get();
        return $data;
    }

    public static function tipo($id)
    {
        if ($id > 0)
            $data = DB::table('par_tipo')->where('id', $id)->first();
        else
            $data = DB::table('par_tipo')->get();
        return $data;
    }

    public static function dimension()
    {
        $data = DB::table('par_dimension')->get();
        return $data;
    }

    public static function unidad()
    {
        $data = DB::table('par_unidad')->get();
        return $data;
    }

    public static function frecuencia()
    {
        $data = DB::table('par_frecuencia')->get();
        return $data;
    }
}
