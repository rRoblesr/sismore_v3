<?php

namespace App\Repositories\Presupuesto;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class BaseSiafWebDetalleRepositorio
{
    public static function obtenerUnidadesEjecutorasParaSelect(int $anio)
    {
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        return DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'sw.unidadejecutora_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->select([
                'ue.id',
                DB::raw("CONCAT(ue.codigo_ue, ' ', ue.abreviatura) as nombre")
            ])
            ->distinct()
            ->orderBy('ue.codigo_ue', 'asc')
            ->pluck('nombre', 'id');
    }

    public static function obtenerCategoriasGastoParaSelect(int $anio, int $unidadejecutoraId = 0)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $query = DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_categoriagasto as cg', 'cg.id', '=', 'sw.categoriagasto_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('sw.unidadejecutora_id', $unidadejecutoraId))
            ->select('cg.id', 'cg.nombre')
            ->distinct()
            ->orderBy('cg.nombre', 'asc');

        return $query->pluck('nombre', 'id');
    }


    public static function obtenerCategoriasPresupuestalesParaSelect(int $anio, int $unidadejecutoraId = 0, int $categoriagastoId = 0)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $query = DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('sw.unidadejecutora_id', $unidadejecutoraId))
            ->when($categoriagastoId > 0, fn($q) => $q->where('sw.categoriagasto_id', $categoriagastoId))
            ->select('cp.tipo_categoria_presupuestal as nombre')
            ->distinct()
            ->orderBy('cp.tipo_categoria_presupuestal', 'asc');
        return $query->pluck('nombre', 'nombre');
    }

    public static function obtenerCategoriasPresupuestalesParaSelect_categoria_presupuestal(int $anio, int $unidadejecutoraId = 0, int $categoriagastoId = 0)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $query = DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->when($unidadejecutoraId > 0, fn($q) => $q->where('sw.unidadejecutora_id', $unidadejecutoraId))
            ->when($categoriagastoId > 0, fn($q) => $q->where('sw.categoriagasto_id', $categoriagastoId))
            ->select('cp.id', 'cp.categoria_presupuestal as nombre')
            ->distinct()
            ->orderBy('cp.categoria_presupuestal', 'asc');
        return $query->pluck('nombre', 'id');
    }

    public static function ue_segun_sistema(int $sistema)
    {
        switch ($sistema) {
            case '3': // salud
                return [9, 10, 11, 14, 15, 20];
            case '1': // educacion
                return [8, 16, 17, 18, 19];
            default:
                return [];
        }
    }

    public static function obtenerResumenEjecucion(int $anio, int $ue, int $cg, ?string $cp = null)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $row = DB::table('pres_base_siafweb_detalle as sw')
            ->selectRaw('
            ROUND(SUM(pim), 0) AS pim,
            ROUND(SUM(certificado), 0) AS certificado,
            ROUND(SUM(compromiso_anual), 0) AS compromiso,
            ROUND(SUM(devengado), 0) AS devengado
        ')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->whereIn('sw.unidadejecutora_id', $uePermitidas)
            ->when($ue > 0, fn($q) => $q->where('sw.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('sw.categoriagasto_id', $cg))
            ->when(filled($cp), fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->first();

        // Si no hay resultados, devolver ceros (o nulls, según tu política)
        if (!$row) {
            return [
                'pim' => 0,
                'certificado' => 0,
                'compromiso' => 0,
                'devengado' => 0,
                'ejecucion1' => 0.0,
                'ejecucion2' => 0.0,
                'ejecucion3' => 0.0,
                'ejecucion4' => 0.0,
            ];
        }

        // Extraer valores (cast a float/int para evitar string de DB)
        $pim = (float) $row->pim;
        $certificado = (float) $row->certificado;
        $compromiso = (float) $row->compromiso;
        $devengado = (float) $row->devengado;

        // ✅ Manejo seguro de división por cero
        $ejecucion1 = $pim > 0 ? round(100 * $devengado / $pim, 1) : 0.0;
        $ejecucion2 = $pim > 0 ? round(100 * $certificado / $pim, 1) : 0.0;
        $ejecucion3 = $pim > 0 ? round(100 * $compromiso / $pim, 1) : 0.0;
        $ejecucion4 = $certificado > 0 ? round(100 * $devengado / $certificado, 1) : 0.0;

        return [
            'pim' => (int) $pim,
            'certificado' => (int) $certificado,
            'compromiso' => (int) $compromiso,
            'devengado' => (int) $devengado,
            'ejecucion1' => $ejecucion1, // % devengado / pim
            'ejecucion2' => $ejecucion2, // % certificado / pim
            'ejecucion3' => $ejecucion3, // % compromiso / pim
            'ejecucion4' => $ejecucion4, // % devengado / certificado
        ];
    }

    public static function obtenerResumenPorUnidadEjecutora(int $anio, int $ue, int $cg, ?string $cp = null)
    {
        $basesiafweb = BaseSiafWebRepositorio::obtenerUltimoIdPorAnio($anio);
        // $uePermitidas = [8, 16, 17, 18, 19];
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        return  DB::table('pres_base_siafweb_detalle as sw')
            ->join('pres_unidadejecutora as ue', function ($join) use ($uePermitidas) {
                $join->on('ue.id', '=', 'sw.unidadejecutora_id')
                    ->whereIn('sw.unidadejecutora_id', $uePermitidas);
            })
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'sw.categoriapresupuestal_id')
            ->where('sw.basesiafweb_id', $basesiafweb)
            ->when($ue > 0, fn($q) => $q->where('sw.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('sw.categoriagasto_id', $cg))
            ->when(filled($cp), fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->groupBy('ue.id', 'ue.codigo_ue', 'ue.abreviatura')
            ->select([
                'ue.id',
                // 'ue.abreviatura as ue',
                DB::raw('concat(ue.codigo_ue," ",ue.abreviatura) as ue'),
                DB::raw('SUM(sw.pia) as pia'),
                DB::raw('SUM(sw.pim) as pim'),
                DB::raw('ROUND(SUM(sw.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(sw.compromiso_anual), 1) as compromiso'),
                DB::raw('ROUND(SUM(sw.devengado), 1) as devengado'),
                DB::raw("
                            CASE 
                                WHEN SUM(sw.pim) > 0 
                                THEN ROUND(100 * SUM(sw.devengado) / SUM(sw.pim), 1)
                                ELSE 0.0 
                            END as avance
                        "),
                DB::raw("sum(sw.pim)-sum(sw.certificado) as saldocer"),
                DB::raw("sum(sw.pim)-sum(sw.devengado) as saldodev"),
            ])
            ->get();
        // return $results->map(function ($row) {
        //     return [
        //         'nombre' => $row->nombre,
        //         'pia' => (float) $row->pia,
        //         'pim' => (float) $row->pim,
        //         'certificado' => (float) $row->certificado,
        //         'compromiso_anual' => (float) $row->compromiso_anual,
        //         'devengado' => (float) $row->devengado,
        //         'avance' => (float) $row->avance,
        //     ];
        // });
    }

    public static function obtenerCertificadoMensual(int $anio, int $ue, int $cg, ?string $cp = null)
    {
        // $uePermitidas = [8, 16, 17, 18, 19];
        $uePermitidas = BaseSiafWebDetalleRepositorio::ue_segun_sistema(session('sistema_id'));
        $idsSubquery = DB::table('pres_base_siafweb as sw2')
            ->join('par_importacion as i2', function ($join) {
                $join->on('i2.id', '=', 'sw2.importacion_id')
                    ->where('i2.estado', '=', 'PR');
            })
            ->where('sw2.anio', $anio)
            ->whereExists(function ($query) use ($anio) {
                $query->select(DB::raw(1))
                    ->from('pres_base_siafweb as sw')
                    ->join('par_importacion as i', function ($join) {
                        $join->on('i.id', '=', 'sw.importacion_id')
                            ->where('i.estado', '=', 'PR');
                    })
                    ->whereColumn('sw.mes', 'sw2.mes')
                    ->where('sw.anio', $anio)
                    ->selectRaw('MAX(i.fechaActualizacion)')
                    ->havingRaw('i2.fechaActualizacion = MAX(i.fechaActualizacion)');
            })
            ->pluck('sw2.id');
        if ($idsSubquery->isEmpty()) {
            return [];
        }
        return DB::table('pres_base_siafweb_detalle as swd')
            ->join('pres_unidadejecutora as ue', 'ue.id', '=', 'swd.unidadejecutora_id')
            ->join('pres_base_siafweb as sw', 'sw.id', '=', 'swd.basesiafweb_id')
            ->join('par_mes as m', 'm.id', '=', 'sw.mes')
            ->join('pres_categoriapresupuestal as cp', 'cp.id', '=', 'swd.categoriapresupuestal_id')
            ->whereIn('swd.unidadejecutora_id', $uePermitidas)
            ->whereIn('swd.basesiafweb_id', $idsSubquery)
            ->where('sw.anio', $anio)
            ->when($ue > 0, fn($q) => $q->where('swd.unidadejecutora_id', $ue))
            ->when($cg > 0, fn($q) => $q->where('swd.categoriagasto_id', $cg))
            ->when(filled($cp), fn($q) => $q->where('cp.tipo_categoria_presupuestal', $cp))
            ->select([
                'm.id as pos',
                'm.abreviado as mes',
                DB::raw('ROUND(SUM(swd.pim), 1) as pim'),
                DB::raw('ROUND(SUM(swd.certificado), 1) as certificado'),
                DB::raw('ROUND(SUM(swd.devengado), 1) as devengado')
            ])
            ->groupBy('m.id', 'm.abreviado')
            ->orderBy('m.id', 'asc')
            ->get();

        // $result = [];
        // foreach ($rows as $row) {
        //     $result[] = [
        //         'pos' => (int) $row->pos,
        //         'mes' => $row->mes,
        //         'certificado' => (float) $row->certificado,
        //     ];
        // }

        // return $result;
    }
}
