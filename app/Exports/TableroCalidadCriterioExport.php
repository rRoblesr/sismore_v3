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

class TableroCalidadCriterioExport implements FromView, ShouldAutoSize
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
        $query = CalidadCriterio::where('importacion_id', $this->importacion)->where('criterio', $this->criterio);
        if ($this->edades > 0) {
            if ($this->edades == 1) {
                $query = $query->whereIn('tipo_edad', ['D', 'M']);
            } else {
                $query = $query->where('tipo_edad', 'A')->where('edad', $this->edades - 1);
            }
        }
        if ($this->provincia > 0) $query = $query->where('provincia_id', $this->provincia);
        if ($this->distrito > 0) $query = $query->where('distrito_id', $this->distrito);

        $query = $query->get();

        $sim = ['D' => 'DÍAS', 'M' => 'MESES', 'A' => 'AÑOS'];
        $seguro = [0 => 'NINGUNO', 1 => 'SIS', 2 => 'ESSALUD', 3 => 'SANIDAD', 4 => 'PRIVADO'];
        $programa = [0 => 'NINGUNO', 1 => 'PIN', 2 => 'PVL', 4 => 'JUNTOS', 5 => 'QALIWARMA', 7 => 'CUNA+ SCD', 8 => 'CUNA+ SAF'];

        $ubigeos = Ubigeo::whereIn('codigo', $query->pluck('ubigeo')->toArray())->get()->keyBy('codigo');
        $establecimientos = Establecimiento::whereIn('cod_unico', $query->pluck('cui_atencion')->toArray())->get()->keyBy('cod_unico');

        foreach ($query as $key => $value) {
            $dis = $ubigeos[$value->ubigeo] ?? null;
            $eess = $establecimientos[$value->cui_atencion] ?? null;

            $value->edadx = $value->edad . ' ' . ($sim[$value->tipo_edad] ?? '');
            $value->segurox = $seguro[$value->seguro_id] ?? '';

            $value->distritox = $dis ? $dis->nombre : '';
            $value->cuix = $eess ? str_pad($value->cui_atencion, 8, '0', STR_PAD_LEFT) : '';
            $value->eessx = $eess ? $eess->nombre_establecimiento : '';

            $value->disax = '';
            $value->redx = '';
            $value->microx = '';
            if ($value->establecimiento_id > 0) {
                $ubica1 = EstablecimientoRepositorio::ubicacion($value->establecimiento_id);
                $value->disax = $ubica1->dsn;
                $value->redx = $ubica1->ren;
                $value->microx = $ubica1->min;
            }
        }

        return view('salud.PadronNominal.TableroCalidadCriterioTabla1excel', ['base' => $query]);
    }
}
