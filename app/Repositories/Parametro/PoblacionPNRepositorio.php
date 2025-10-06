<?php

namespace App\Repositories\Parametro;

use App\Models\Parametro\Mes;
use App\Models\Parametro\PoblacionDetalle;
use App\Models\Parametro\PoblacionPN;
use App\Models\Parametro\Ubigeo;
use Illuminate\Support\Facades\DB;

class PoblacionPNRepositorio
{
    public static function actualizado()
    {
        $maxAno = PoblacionPN::max('anio');
        $maxMes = PoblacionPN::where('anio', $maxAno)->max('mes');

        $query = PoblacionPN::from('par_poblacion_padron_nominal as m')
            ->join('par_mes as p', 'p.id', '=', 'm.mes')
            ->where('m.anio', $maxAno)
            ->where('m.mes', $maxMes)
            ->selectRaw('m.anio,m.mes, CONCAT(p.mes, " ", m.anio) AS fecha')
            ->first();

        if (!$query) {
            return null;
        }

        return $query;
    }

    public static function ultimoaniodisponible($list_anio, $anio_actual)
    {
        return PoblacionPN::where('anio', $list_anio->where('anio', '<=', $anio_actual)->max('anio'))->max('anio');
    }

    public static function mes($anio)
    {
        return Mes::whereIn('id', PoblacionPN::where('anio', $anio)->distinct()->pluck('mes_id'))->orderBy('id', 'asc')->get();
    }

    public static function provincia($anio, $mes)
    {
        return Ubigeo::from('par_ubigeo as p')
            ->select('p.id', 'p.codigo', 'p.nombre')
            ->join('par_ubigeo as d', 'd.dependencia', '=', 'p.id')
            ->join('par_poblacion_padron_nominal as pn', function ($join) use ($anio) {
                $join->on('d.id', '=', 'pn.ubigeo_id')->where('pn.anio', '=', $anio);
            })
            ->when($mes && $mes > 0, fn($q) => $q->where('pn.mes_id', $mes))
            ->distinct()
            ->orderBy('p.codigo', 'asc')
            ->get();
    }

    public static function distrito($anio, $mes, $provincia)
    {
        return Ubigeo::from('par_ubigeo as d')
            ->select('d.id', 'd.codigo', 'd.nombre')
            ->join('par_poblacion_padron_nominal as pn', function ($join) use ($anio) {
                $join->on('d.id', '=', 'pn.ubigeo_id')->where('pn.anio', '=', $anio);
            })
            ->when($mes && $mes > 0, fn($q) => $q->where('pn.mes_id', $mes))
            ->when($provincia && $provincia > 0, fn($q) => $q->where('d.dependencia', $provincia))
            ->distinct()
            ->orderBy('d.codigo', 'asc')
            ->get();
    }

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

    public static function conteo_anios_sexo($mes, $provincia, $distrito)
    {
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->select(
                'pn.anio',
                DB::raw('sum(pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a) as conteo'),
                DB::raw('sum(if(sexo_id=1,pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a,0)) as hconteo'),
                DB::raw('sum(if(sexo_id=2,pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a,0)) as mconteo')
            )
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')->where('anio', '>', 2018);
        if ($mes > 0) $query = $query->where('pn.mes_id', $mes);
        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('ds.id', $distrito);
        $query = $query->groupBy('anio')->get();
        return $query;
    }

    public static function conteo_edad_sexo($anio, $mes, $provincia, $distrito)
    {
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->select(
                DB::raw('case when pn.sexo_id=1 then "HOMBRE" when pn.sexo_id=2 then "MUJER" end as sexo'),
                DB::raw('sum(pn.0a) as edad0'),
                DB::raw('sum(pn.1a) as edad1'),
                DB::raw('sum(pn.2a) as edad2'),
                DB::raw('sum(pn.3a) as edad3'),
                DB::raw('sum(pn.4a) as edad4'),
                DB::raw('sum(pn.5a) as edad5'),
            )
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')
            ->where('pn.anio', $anio);
        if ($mes > 0) $query = $query->where('pn.mes_id', $mes);
        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('ds.id', $distrito);
        $query = $query->groupBy('sexo_id', 'sexo')->get();
        return $query;
    }

    public static function conteo_mes($anio, $mes, $provincia, $distrito)
    {
        $query = Mes::from('par_mes as m')
            ->select(
                'm.id',
                'm.abreviado',
                DB::raw('COALESCE(SUM(pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a), NULL) as conteo')
            )
            ->leftJoin('par_poblacion_padron_nominal as pn', function ($join) use ($anio, $provincia, $distrito) {
                $join->on('m.id', '=', 'pn.mes_id')->where('pn.anio', '=', $anio);
                if ($provincia > 0)                    $join->where('pn.provincia_id', '=', $provincia);
                if ($distrito > 0)                    $join->where('pn.distrito_id', '=', $distrito);
            })
            ->groupBy('m.id', 'm.abreviado')
            ->orderBy('m.id');
        return $query->get();
    }

    public static function conteo_seguro_edades($anio, $mes, $provincia, $distrito)
    {
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->select(
                's.codigo as seguro',
                DB::raw('sum(pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a) as conteo'),
                DB::raw('sum(if(sexo_id=1,pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a,0)) as hconteo'),
                DB::raw('sum(if(sexo_id=2,pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a,0)) as mconteo'),
                DB::raw('sum(pn.0a) as edad0'),
                DB::raw('sum(pn.1a) as edad1'),
                DB::raw('sum(pn.2a) as edad2'),
                DB::raw('sum(pn.3a) as edad3'),
                DB::raw('sum(pn.4a) as edad4'),
                DB::raw('sum(pn.5a) as edad5'),
                DB::raw('sum(pn.28dias) as edad28'),
                DB::raw('sum(pn.0_5meses) as edad05'),
                DB::raw('sum(pn.6_11meses) as edad611'),
            )
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')
            ->join('par_seguro as s', 's.id', '=', 'pn.seguro')
            ->where('pn.anio', $anio);
        if ($mes > 0) $query = $query->where('pn.mes_id', $mes);
        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('ds.id', $distrito);
        $query = $query->groupBy('s.codigo', 'seguro')->get();
        return $query;
    }

    public static function conteo_distrito_edades($anio, $mes, $provincia, $distrito)
    {
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->select(
                'ds.nombre as distrito',
                DB::raw('sum(pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a) as conteo'),
                DB::raw('sum(if(sexo_id=1,pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a,0)) as hconteo'),
                DB::raw('sum(if(sexo_id=2,pn.0a + pn.1a + pn.2a + pn.3a + pn.4a + pn.5a,0)) as mconteo'),
                DB::raw('sum(pn.0a) as edad0'),
                DB::raw('sum(pn.1a) as edad1'),
                DB::raw('sum(pn.2a) as edad2'),
                DB::raw('sum(pn.3a) as edad3'),
                DB::raw('sum(pn.4a) as edad4'),
                DB::raw('sum(pn.5a) as edad5'),
                DB::raw('sum(pn.28dias) as edad28'),
                DB::raw('sum(pn.0_5meses) as edad05'),
                DB::raw('sum(pn.6_11meses) as edad611'),
            )
            ->join('par_ubigeo as ds', 'ds.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'ds.dependencia')
            ->where('pn.anio', $anio);
        if ($mes > 0) $query = $query->where('pn.mes_id', $mes);
        if ($provincia > 0) $query = $query->where('pv.id', $provincia);
        if ($distrito > 0) $query = $query->where('ds.id', $distrito);
        $query = $query->groupBy('distrito')->get();
        return $query;
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

    public static function conteo3a5_acumuladoxx($anio, $mes, $provincia, $distrito, $sexo)
    {
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->join('par_ubigeo as dis', 'dis.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pro', 'pro.id', '=', 'dis.dependencia')
            ->where('pn.anio', $anio);
        if ($sexo > 0) $query = $query->where('pn.sexo_id', $sexo);
        if ($mes > 0) $query = $query->where('pn.mes_id', $mes);
        if ($provincia > 0) $query = $query->where('pro.id', $provincia);
        if ($distrito > 0) $query = $query->where('dis.id', $distrito);
        return $query->sum(DB::raw('pn.3a + pn.4a + pn.5a'));
    }

    public static function conteo3a5_acumulado($anio, $mes = null, $provincia = null, $distrito = null, $sexo = null)
    {
        return PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->join('par_ubigeo as dis', 'dis.id', '=', 'pn.ubigeo_id')
            ->join('par_ubigeo as pro', 'pro.id', '=', 'dis.dependencia')
            ->where('pn.anio', $anio)
            ->when($sexo && $sexo > 0, fn($q) => $q->where('pn.sexo_id', $sexo))
            ->when($mes && $mes > 0, fn($q) => $q->where('pn.mes_id', $mes))
            ->when($provincia && $provincia > 0, fn($q) => $q->where('pro.id', $provincia))
            ->when($distrito && $distrito > 0, fn($q) => $q->where('dis.id', $distrito))
            ->sum(DB::raw('`pn`.`3a` + `pn`.`4a` + `pn`.`5a`'));
    }

    public static function conteo3a5_mensual($anio, $mes, $provincia, $distrito, $sexo)
    {
        $query = PoblacionPN::from('par_poblacion_padron_nominal as pn')
            ->select('pn.mes_id', DB::raw('sum(pn.3a + pn.4a + pn.5a) as conteo'))
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
