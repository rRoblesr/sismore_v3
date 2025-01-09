<?php

namespace App\Exports;

use App\Http\Controllers\Educacion\IndicadorController;
use App\Http\Controllers\Salud\IndicadoresController;
use App\Models\Parametro\Ubigeo;
use App\Models\Salud\CalidadCriterio;
use App\Models\Salud\Establecimiento;
use App\Repositories\Salud\EstablecimientoRepositorio;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TableroCalidadCriterio2Export implements FromView, ShouldAutoSize
{
    public $div;
    public $importacion;
    public $criterio;
    public $edades;
    public $provincia;
    public $distrito;

    public function __construct($div, $importacion, $criterio, $edades, $provincia, $distrito)
    {
        $this->div = $div;
        $this->importacion = $importacion;
        $this->criterio = $criterio;
        $this->edades = $edades;
        $this->provincia = $provincia;
        $this->distrito = $distrito;
    }

    public function view(): View
    {
        ini_set('memory_limit', '1024M');
        $base = CalidadCriterio::from('sal_calidad_criterio as cc')
            ->select('p.nombre as provincia', 'd.nombre as distrito', 'cc.centro_poblado_nombre as centro_poblado', DB::raw('count(cc.id) as conteo'))
            ->join('par_ubigeo as d', 'd.id', '=', 'cc.distrito_id')
            ->join('par_ubigeo as p', 'p.id', '=', 'd.dependencia')
            ->where('importacion_id', $this->importacion)->where('criterio', $this->criterio);
        if ($this->edades > 0) {
            if ($this->edades == 1) {
                $base = $base->whereIn('tipo_edad', ['D', 'M']);
            } else {
                $base = $base->where('tipo_edad', 'A')->where('edad', $this->edades - 1);
            }
        }
        if ($this->provincia > 0) $base = $base->where('provincia_id', $this->provincia);
        if ($this->distrito > 0) $base = $base->where('distrito_id', $this->distrito);


        $base = $base->groupBy('provincia', 'distrito', 'cc.centro_poblado_nombre', 'centro_poblado')->get();

        return view('salud.PadronNominal.TableroCalidadCriterioTabla2excel', ['base' => $base]);
    }
}
