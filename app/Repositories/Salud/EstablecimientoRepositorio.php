<?php

namespace App\Repositories\Salud;

use App\Models\Salud\Establecimiento;
use Illuminate\Support\Facades\DB;

class EstablecimientoRepositorio
{

    public static function listar($sector, $municipio, $red, $microred)
    {
        $query = Establecimiento::from('sal_establecimiento as es')
            ->select('es.id', 're.nombre as red', 'mi.nombre as microred', 'es.cod_unico', 'es.nombre_establecimiento as eess')
            ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
            ->join('sal_red as re', 're.id', '=', 'mi.red_id')
            ->join('par_ubigeo as ub', 'ub.id', '=', 'es.ubigeo_id')
            ->join('adm_entidad as en', 'en.codigo', '=', 'ub.codigo')
            ->join('adm_tipo_entidad as te', function ($join) use ($sector) {
                $join->on('te.id', '=', 'en.tipoentidad_id')
                    ->where('te.sector_id', '=', $sector);
            })
            ->where('es.estado', 'ACTIVO')->where('re.codigo', '!=', '00')->whereNotIn('cod_unico', [28683, 30785, 27062, 29247]);
        if ($municipio > 0) $query = $query->where('ub.id', $municipio);
        // if ($red > 0) $query = $query->where('re.id', $red);
        // if ($microred > 0) $query = $query->where('mi.id', $microred);
        if ($red > 0 && $microred > 0) $query = $query->where('mi.id', $microred);
        else if ($red > 0 && !$microred > 0) $query = $query->where('re.id', $red);
        else if (!$red > 0 && $microred > 0) $query = $query->where('mi.id', $microred);
        // if (!$red > 0 && !$microred > 0) $query = $query->where('re.id', $red);
        $query = $query->orderBy('re.codigo')->orderBy('mi.codigo')->orderBy('es.nombre_establecimiento')->get();
        return $query;
    }

    public static function listarMunicipalidades($sector, $municipio, $red, $microred)
    {
        $query = Establecimiento::from('sal_establecimiento as es')
            ->select('es.id', 're.nombre as red', 'mi.nombre as microred', 'es.cod_unico', 'es.nombre_establecimiento as eess', 'en.nombre as muni')
            ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
            ->join('sal_red as re', 're.id', '=', 'mi.red_id')
            ->join('par_ubigeo as ub', 'ub.id', '=', 'es.ubigeo_id')
            ->join('adm_entidad as en', 'en.codigo', '=', 'ub.codigo')
            ->join('adm_tipo_entidad as te', function ($join) use ($sector) {
                $join->on('te.id', '=', 'en.tipoentidad_id')
                    ->where('te.sector_id', '=', $sector);
            })
            ->where('es.estado', 'ACTIVO')->where('re.codigo', '!=', '00')->whereNotIn('cod_unico', [28683, 30785, 27062, 29247]);
        if ($municipio > 0) $query = $query->where('ub.id', $municipio);
        // if ($red > 0) $query = $query->where('re.id', $red);
        // if ($microred > 0) $query = $query->where('mi.id', $microred);
        if ($red > 0 && $microred > 0) $query = $query->where('mi.id', $microred);
        else if ($red > 0 && !$microred > 0) $query = $query->where('re.id', $red);
        else if (!$red > 0 && $microred > 0) $query = $query->where('mi.id', $microred);
        // if (!$red > 0 && !$microred > 0) $query = $query->where('re.id', $red);
        $query = $query->orderBy('en.nombre')->orderBy('re.codigo')->orderBy('mi.codigo')->orderBy('es.nombre_establecimiento')->get();
        return $query;
    }

    public static function listRed($sector, $municipio)
    {
        $query = Establecimiento::from('sal_establecimiento as es')->distinct()->select('re.*')
            ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
            ->join('sal_red as re', 're.id', '=', 'mi.red_id')
            ->join('par_ubigeo as ub', 'ub.id', '=', 'es.ubigeo_id')
            ->join('adm_entidad as en', 'en.codigo', '=', 'ub.codigo')
            ->join('adm_tipo_entidad as te', function ($join) use ($sector) {
                $join->on('te.id', '=', 'en.tipoentidad_id')
                    ->where('te.sector_id', '=', $sector);
            })
            ->where('es.estado', 'ACTIVO')->where('re.codigo', '!=', '00');
        if ($municipio > 0) $query = $query->where('ub.id', $municipio);
        // ->where('re.codigo', '03')
        $query = $query->orderBy('re.codigo')->orderBy('mi.codigo')->orderBy('es.nombre_establecimiento')->get();
        return $query;
    }

    public static function listMicrored($sector, $municipio, $red)
    {
        $query = Establecimiento::from('sal_establecimiento as es')->distinct()->select('mi.*')
            ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
            ->join('sal_red as re', 're.id', '=', 'mi.red_id')
            ->join('par_ubigeo as ub', 'ub.id', '=', 'es.ubigeo_id')
            ->join('adm_entidad as en', 'en.codigo', '=', 'ub.codigo')
            ->join('adm_tipo_entidad as te', function ($join) use ($sector) {
                $join->on('te.id', '=', 'en.tipoentidad_id')
                    ->where('te.sector_id', '=', $sector);
            })
            ->where('es.estado', 'ACTIVO')->where('re.codigo', '!=', '00');
        if ($municipio > 0) $query = $query->where('ub.id', $municipio);
        if ($red > 0) $query = $query->where('re.id', $red);
        // ->where('re.codigo', '03')
        $query = $query->orderBy('re.codigo')->orderBy('mi.codigo')->orderBy('es.nombre_establecimiento')->get();
        return $query;
    }

    public static function listEESS($sector, $municipio, $red, $microred)
    {
        $query = Establecimiento::from('sal_establecimiento as es')->distinct()->select('es.*')
            ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
            ->join('sal_red as re', 're.id', '=', 'mi.red_id')
            ->join('par_ubigeo as ub', 'ub.id', '=', 'es.ubigeo_id')
            ->join('adm_entidad as en', 'en.codigo', '=', 'ub.codigo')
            ->join('adm_tipo_entidad as te', function ($join) use ($sector) {
                $join->on('te.id', '=', 'en.tipoentidad_id')
                    ->where('te.sector_id', '=', $sector);
            })
            ->where('es.estado', 'ACTIVO')->where('re.codigo', '!=', '00');
        if ($municipio > 0) $query = $query->where('ub.id', $municipio);
        if ($red > 0) $query = $query->where('re.id', $red);
        if ($microred > 0) $query = $query->where('mi.id', $microred);
        // ->where('re.codigo', '03')
        $query = $query->orderBy('re.codigo')->orderBy('mi.codigo')->orderBy('es.nombre_establecimiento')->get();
        return $query;
    }

    public static function registroList($municipio, $red, $microred, $fechai, $fechaf, $registrador)
    {
        $sector = 2;
        $query = Establecimiento::from('sal_establecimiento as es')->select(
            'en.nombre as municipio',
            're.nombre as red',
            'mi.nombre as microred',
            'ub.nombre as distrito',
            'es.cod_unico',
            'es.nombre_establecimiento as eess',
            'pa.fecha_inicial',
            'pa.fecha_final',
            'pa.fecha_envio',
            'pa.nro_archivos'
        )
            ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
            ->join('sal_red as re', 're.id', '=', 'mi.red_id')
            ->join('par_ubigeo as ub', 'ub.id', '=', 'es.ubigeo_id')
            ->join('adm_entidad as en', 'en.codigo', '=', 'ub.codigo')
            ->join('adm_tipo_entidad as te', function ($join) use ($sector) {
                $join->on('te.id', '=', 'en.tipoentidad_id')
                    ->where('te.sector_id', '=', $sector);
            })
            ->leftJoin('sal_padron_actas as pa', 'pa.establecimiento_id', '=', 'es.id')
            ->where('es.estado', 'ACTIVO')->where('re.codigo', '!=', '00')->whereNotIn('cod_unico', [28683, 30785, 27062, 29247]);
        if ($municipio > 0 && $registrador > 0) $query = $query->where('ub.id', $municipio);
        if ($red > 0 && $microred > 0) $query = $query->where('mi.id', $microred);
        else if ($red > 0 && !$microred > 0) $query = $query->where('re.id', $red);
        else if (!$red > 0 && $microred > 0) $query = $query->where('mi.id', $microred);
        // if (!$red > 0 && !$microred > 0) $query = $query->where('re.id', $red);
        $query = $query->orderBy('en.nombre')->orderBy('re.codigo')->orderBy('mi.codigo')->orderBy('es.nombre_establecimiento')->get();
        return $query;
    }
}
