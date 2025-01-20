<?php

namespace App\Repositories\Salud;

use App\Models\Parametro\Mes;
use App\Models\Salud\Establecimiento;
use App\Models\Salud\PadronActas;
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
            // ->where('es.estado', 'ACTIVO')->where('re.codigo', '!=', '00')->whereNotIn('cod_unico', [28683, 30785, 27062, 29247]);
            ->where('es.estado', 'ACTIVO')->whereIn('es.institucion', ['GOBIERNO REGIONAL', 'MINSA'])->whereIn('es.categoria', ['I-1', 'I-2', 'I-3', 'I-4'])->whereNotIn('cod_unico', [28683, 30785, 27062, 29247]);
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
            // ->where('es.estado', 'ACTIVO')->where('re.codigo', '!=', '00')->whereNotIn('cod_unico', [28683, 30785, 27062, 29247]);
            ->where('es.estado', 'ACTIVO')->whereIn('es.institucion', ['GOBIERNO REGIONAL', 'MINSA'])->whereIn('es.categoria', ['I-1', 'I-2', 'I-3', 'I-4'])->whereNotIn('cod_unico', [28683, 30785, 27062, 29247]);

        switch (session('usuario_nivel')) {
            case '1':
                break;
            case '2':
                $query = $query->where('re.codigo', session('usuario_codigo'));
                break;
            case '3':
                $query = $query->where('mi.codigo', session('usuario_codigo'));
                break;
            case '4':
                $query = $query->where('es.cod_unico', session('usuario_codigo'));
                break;
            default:

                break;
        }
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
        $query = Establecimiento::from('sal_establecimiasdasento as es')->distinct()->select('es.*')
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
            // ->where('es.estado', 'ACTIVO')->where('re.codigo', '!=', '00')->whereNotIn('cod_unico', [28683, 30785, 27062, 29247]);
            ->where('es.estado', 'ACTIVO')->whereIn('es.institucion', ['GOBIERNO REGIONAL', 'MINSA'])->whereIn('es.categoria', ['I-1', 'I-2', 'I-3', 'I-4'])->whereNotIn('cod_unico', [28683, 30785, 27062, 29247]);
        if ($municipio > 0 && $registrador > 0) $query = $query->where('ub.id', $municipio);
        if ($red > 0 && $microred > 0) $query = $query->where('mi.id', $microred);
        else if ($red > 0 && !$microred > 0) $query = $query->where('re.id', $red);
        else if (!$red > 0 && $microred > 0) $query = $query->where('mi.id', $microred);
        // if (!$red > 0 && !$microred > 0) $query = $query->where('re.id', $red);
        $query = $query->orderBy('en.nombre')->orderBy('re.codigo')->orderBy('mi.codigo')->orderBy('es.nombre_establecimiento')->get();
        return $query;
    }

    public static function otros($anio, $municipio, $red, $microred, $fechai, $fechaf, $registrador)
    {
        $mes = Mes::all();
        $anioA = date('Y');
        $mesA = $anio == date('Y') ? date('m') : 12;

        $tabla = '<table id="tabla2" class="table table-sm table-striped table-bordered font-12">
                <thead class="cabecera-dataTable table-success-0 text-white">
                    <tr>
                        <th class="text-center">Nº</th>
                        <th class="text-center">MUNICIPALIDAD</th>
                        <th class="text-center">CODIGO UNICO</th>
                        <th class="text-center">ESTABLECIMIENTO</th>';
        foreach ($mes as $key => $mm) {
            $tabla .= '<th class="text-center">' . $mm->abreviado . '</th>';
        }

        $tabla .= '     <th class="text-center">TOTAL</th>
                    </tr>
                </thead>
                <tbody>';

        $query = EstablecimientoRepositorio::listarMunicipalidades(2, $municipio, $red, $microred);

        foreach ($query as $key => $value) {
            $tabla .= '<tr>';
            $tabla .= '<td class="text-center">' . ($key + 1) . '</td>';
            $tabla .= '<td class="text-left">' . $value->muni . '</td>';
            $tabla .= '<td class="text-center">' . sprintf('%08d', $value->cod_unico) . '</td>';
            $tabla .= '<td class="text-left table-success text-dark">' . $value->eess . '</td>';
            foreach ($mes as $mm) {
                if ($mm->codigo <= $mesA) {
                    $conteo = PadronActas::from('sal_padron_actas as pa')
                        ->join('sal_establecimiento as es', 'es.id', '=', 'pa.establecimiento_id')
                        ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
                        ->join('sal_red as re', 're.id', '=', 'mi.red_id')
                        ->where('pa.establecimiento_id', $value->id)
                        ->where('pa.fecha_envio', 'like', $anio . '-' . str_pad($mm->codigo, 2, '0', STR_PAD_LEFT) . '-%');
                    $conteo = $conteo->sum('pa.nro_archivos');
                    if ($conteo == 0) $tabla .= '<td class="text-center text-danger">' . $conteo . '</td>';
                    else $tabla .= '<td class="text-center text-primary font-weight-bold">' . $conteo . '</td>';
                } else {
                    $tabla .= '<td class="text-center"></td>';
                }
            }

            $conteo = PadronActas::from('sal_padron_actas as pa')
                ->join('sal_establecimiento as es', 'es.id', '=', 'pa.establecimiento_id')
                ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')
                ->join('sal_red as re', 're.id', '=', 'mi.red_id')
                ->where('pa.establecimiento_id', $value->id)
                ->where('pa.fecha_envio', 'like', $anio . '-%');
            $conteo = $conteo->sum('pa.nro_archivos');

            if ($conteo == 0) $tabla .= '<td class="text-center text-danger table-purple">' . $conteo . '</td>';
            else $tabla .= '<td class="text-center text-primary font-weight-bold table-purple">' . $conteo . '</td>';
            // $tabla .= '<td class="text-center">' . $conteo . '</td>';
            $tabla .= '</tr>';
        }

        $tabla .= '</tbody>';
        $tabla .= '<tfoot class="table-success-0 text-white">
                    <tr>
                        <td class="text-center" colspan="4">TOTAL DE ACTAS POR MES</td>';

        foreach ($mes as $key => $mm) {
            if ($mm->codigo <= $mesA) {
                $conteo = PadronActas::where('sal_padron_actas.fecha_envio', 'like', $anio . '-' . str_pad($mm->codigo, 2, '0', STR_PAD_LEFT) . '-%');
                if ($municipio > 0) $conteo = $conteo->where('sal_padron_actas.ubigeo_id', '=', $municipio);
                $conteo = $conteo->sum('sal_padron_actas.nro_archivos');
                if ($conteo == 0) $tabla .= ' <td class="text-center text-white font-weight-bold">' . $conteo . '</td>';
                else $tabla .= ' <td class="text-center text-dark font-weight-bold">' . $conteo . '</td>';
            } else {
                $tabla .= '<td class="text-center"></td>';
            }
        }

        $conteo = PadronActas::where('sal_padron_actas.fecha_envio', 'like', $anio . '-%');
        if ($municipio > 0) $conteo = $conteo->where('sal_padron_actas.ubigeo_id', '=', $municipio);
        $conteo = $conteo->sum('sal_padron_actas.nro_archivos');
        $tabla .= '     <td class="text-center text-dark font-weight-bold">' . $conteo . '</td>
                    </tr>
                </tfoot></table>';
        return $tabla;
    }

    public static function ubicacion($establecimiento)
    {
        $query = Establecimiento::from('sal_establecimiento as es')->select('mi.id as mii', 'mi.nombre as min', 're.id as rei', 're.nombre as ren', 'es.cod_disa as dsi', 'es.disa as dsn')
            ->join('sal_microred as mi', 'mi.id', '=', 'es.microrred_id')->join('sal_red as re', 're.id', '=', 'mi.red_id')->where('es.id', $establecimiento)->first();
        return $query;
    }

    public static function queAtiendenAutocompletar($term)
    {
        $query = Establecimiento::from('sal_establecimiento as es') //->join('sal_microred as m', 'm.id', '=', 'es.microrred_id')->join('sal_red as r', 'r.id', '=', 'm.red_id')
            ->select('es.id', 'es.cod_unico', 'es.nombre_establecimiento')
            ->where('es.cod_disa', '34')->where('es.estado', 'ACTIVO')->whereIn('es.institucion', ['GOBIERNO REGIONAL', 'MINSA'])
            ->whereIn('es.categoria', ['I-1', 'I-2', 'I-3', 'I-4'])
            ->where(function ($q) use ($term) {
                $q->where('es.nombre_establecimiento', 'like', '%' . $term . '%')->orWhere('es.cod_unico', 'like', '%' . $term . '%');
            })->get();
        return $query;
    }

    public static function queAtiendenCargar()
    {
        $query = Establecimiento::from('sal_establecimiento as es') //->join('sal_microred as m', 'm.id', '=', 'es.microrred_id')->join('sal_red as r', 'r.id', '=', 'm.red_id')
            ->select('es.id', DB::raw('concat(LPAD(es.cod_unico, 8, "0")," | ",es.nombre_establecimiento) as nombrex'))
            ->where('es.cod_disa', '34')->where('es.estado', 'ACTIVO')->whereIn('es.institucion', ['GOBIERNO REGIONAL', 'MINSA'])
            ->whereIn('es.categoria', ['I-1', 'I-2', 'I-3', 'I-4'])
            ->get();
        return $query;
    }

    public static function queAtiendenIdCodunico()
    {
        $query = Establecimiento::from('sal_establecimiento as es') //->join('sal_microred as m', 'm.id', '=', 'es.microrred_id')->join('sal_red as r', 'r.id', '=', 'm.red_id')
            ->select('es.id', 'es.cod_unico')
            ->where('es.cod_disa', '34')->where('es.estado', 'ACTIVO')->whereIn('es.institucion', ['GOBIERNO REGIONAL', 'MINSA'])
            ->whereIn('es.categoria', ['I-1', 'I-2', 'I-3', 'I-4'])
            ->get();
        return $query;
    }

    public static function arrayIdCodunico()
    {
        $query = Establecimiento::from('sal_establecimiento as es')->select('es.id', 'es.cod_unico')->get();
        return $query;
    }
}
