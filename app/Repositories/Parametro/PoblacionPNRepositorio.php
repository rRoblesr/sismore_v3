<?php

namespace App\Repositories\Parametro;

use App\Models\Parametro\PoblacionDetalle;
use App\Models\Parametro\PoblacionPN;
use App\Models\Parametro\Ubigeo;
use Illuminate\Support\Facades\DB;

class PoblacionPNRepositorio
{

    public static function conteo($anio, $mes, $departamento, $provincia, $distrito, $sexo)
    {
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->join('par_ubigeo as dis', 'dis.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pro', 'pro.id', '=', 'dis.dependencia')
            ->join('par_ubigeo as dep', 'dep.id', '=', 'pro.dependencia')
            ->where('pn.anio', $anio);
        if ($sexo > 0)
            $query = $query->where('pn.sexo_id', $sexo);
        if ($mes > 0)
            $query = $query->where('pn.mes_id', $mes);
        if ($departamento != '00')
            $query = $query->where('dep.codigo', $departamento);
        if ($provincia != '00')
            $query = $query->where('pro.codigo', $provincia);
        if ($distrito != '00')
            $query = $query->where('dis.id', $distrito);
        return $query->sum('pn.0a+pn.1a+pn.2a+pn.3a+pn.4a+pn.5a');
    }

    public static function conteo2($anio, $mes, $provincia, $distrito, $sexo)
    {
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->join('par_ubigeo as dis', 'dis.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pro', 'pro.id', '=', 'dis.dependencia')
            ->where('pn.anio', $anio);
        if ($sexo > 0) $query = $query->where('pn.sexo_id', $sexo);
        if ($mes > 0) $query = $query->where('pn.mes_id', $mes);
        if ($provincia > 0) $query = $query->where('pro.id', $provincia);
        if ($distrito > 0) $query = $query->where('dis.id', $distrito);
        return $query->sum(DB::raw('pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a'));
    }

    public static function conteo_cnv($anio, $mes, $provincia, $distrito, $sexo, $cnv)
    {
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->join('par_ubigeo as dis', 'dis.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pro', 'pro.id', '=', 'dis.dependencia')
            ->where('pn.anio', $anio);
        if ($sexo > 0) $query = $query->where('pn.sexo_id', $sexo);
        if ($mes > 0) $query = $query->where('pn.mes_id', $mes);
        if ($provincia > 0) $query = $query->where('pro.id', $provincia);
        if ($distrito > 0) $query = $query->where('dis.id', $distrito);
        if ($cnv > -1) $query = $query->where('pn.cnv', $cnv);
        return $query->sum(DB::raw('pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a'));
    }

    public static function conteomesmax($anio, $departamento, $provincia, $distrito, $sexo)
    {
        $mesMax = PoblacionPN::where('anio', $anio)->max('mes_id');
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->join('par_ubigeo as dis', 'dis.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pro', 'pro.id', '=', 'dis.dependencia')
            ->join('par_ubigeo as dep', 'dep.id', '=', 'pro.dependencia')
            ->where('pn.anio', $anio)->where('pn.mes_id', $mesMax);
        if ($sexo > 0)
            $query = $query->where('pn.sexo_id', $sexo);
        if ($departamento != '00')
            $query = $query->where('dep.codigo', $departamento);
        if ($provincia != '00')
            $query = $query->where('pro.codigo', $provincia);
        if ($distrito > 0)
            $query = $query->where('dis.id', $distrito);
        return $query->sum(DB::raw('pn.0a+pn.1a+pn.2a+pn.3a+pn.4a+pn.5a'));
    }

    public static function conteo3a5_acumulado($anio, $mes, $provincia, $distrito, $sexo)
    {
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->join('par_ubigeo as dis', 'dis.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pro', 'pro.id', '=', 'dis.dependencia')
            ->where('pn.anio', $anio);
        if ($sexo > 0) $query = $query->where('pn.sexo_id', $sexo);
        if ($mes > 0) $query = $query->where('pn.mes_id', $mes);
        if ($provincia > 0) $query = $query->where('pro.id', $provincia);
        if ($distrito > 0) $query = $query->where('dis.id', $distrito);
        return $query->sum(DB::raw('pn.3a+pn.4a+pn.5a'));
    }

    public static function conteo3a5_mensual($anio, $mes, $provincia, $distrito, $sexo)
    {
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->select('pn.mes_id', DB::raw('sum(pn.3a+pn.4a+pn.5a) as conteo'))
            ->join('par_ubigeo as dis', 'dis.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pro', 'pro.id', '=', 'dis.dependencia')
            ->where('pn.anio', $anio);
        if ($sexo > 0) $query = $query->where('pn.sexo_id', $sexo);
        if ($mes > 0) $query = $query->where('pn.mes_id', $mes);
        if ($provincia > 0) $query = $query->where('pro.id', $provincia);
        if ($distrito > 0) $query = $query->where('dis.id', $distrito);
        $query = $query->groupBy('mes_id')->get();
        return $query;
    }
}
