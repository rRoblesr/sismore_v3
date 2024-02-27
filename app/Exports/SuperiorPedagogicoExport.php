<?php

namespace App\Exports;

use App\Models\Parametro\Ubigeo;
use App\Repositories\Educacion\ImporCensoMatriculaRepositorio;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuperiorPedagogicoExport implements FromView, ShouldAutoSize
{
    public $ano;
    public $ugel;
    public $area;
    public $gestion;

    public function __construct($ano, $ugel, $area, $gestion)
    {
        $this->ano = $ano;
        $this->ugel = $ugel;
        $this->area = $area;
        $this->gestion = $gestion;
    }

    public function view(): View
    {
        $base = ImporCensoMatriculaRepositorio::_5AReportes($this->ano, 0, 0, $this->ugel, $this->area, $this->gestion, 5);
        $distrito = Ubigeo::where('codigo', 'like', '25%')->where(DB::raw('length(codigo)'), 6)->get();
        $gestion = DB::table('censo_gestion')->get();
        $area = DB::table('censo_area')->get();
        $docentes = ImporCensoMatriculaRepositorio::_5ATotalDocentesAnioModular($this->ano, 0, 0, $this->ugel, $this->area, $this->gestion, 1);
        $meta = ImporCensoMatriculaRepositorio::_5ATotalEstudiantesAnioMeta($this->ano, 0, 0, $this->ugel, $this->area, $this->gestion);
        foreach ($base as $key => $bb) {
            foreach ($gestion as $key => $gg) {
                if ($bb->gestion == $gg->codigo) {
                    $bb->gestion = $gg->nombre;
                    break;
                }
            }
            foreach ($area as $key => $aa) {
                if ($bb->area == $aa->codigo) {
                    $bb->area = $aa->nombre;
                    break;
                }
            }
            foreach ($meta as $key => $mm) {
                if ($bb->modular == $mm->modular) {
                    $bb->meta = $mm->meta;
                    break;
                }
            }
            foreach ($distrito as $key => $di) {
                if ($bb->distrito == $di->codigo) {
                    $bb->distrito = $di->nombre;
                    break;
                }
            }
            $bb->indicador = $bb->meta > 0 ? 100 * ($bb->at + $bb->t) / $bb->meta : 100;
            $n = 0;
            $c = 0;
            $existe = false;
            foreach ($docentes as $key => $dd) {
                if ($bb->modular == $dd->modular) {
                    $n = $dd->n;
                    $c = $dd->c;
                    $existe = true;
                    break;
                }
            }
            if (!$existe) {
                $n = 0;
                $c = 0;
            }
            $bb->n = $n;
            $bb->c = $c;
        }

        $foot = clone $base[0];
        $foot->meta = 0;
        $foot->at = 0;
        $foot->t = 0;
        $foot->indicador = 0;
        $foot->c = 0;
        $foot->n = 0;
        foreach ($base as $key => $value) {
            $foot->meta += $value->meta;
            $foot->at += $value->at;
            $foot->t += $value->t;
            $foot->c += $value->c;
            $foot->n += $value->n;
        }
        $foot->indicador = $foot->meta > 0 ? 100 * ($foot->at + $foot->t) / $foot->meta : 100;
        //$excel = view('educacion.TecnicoProductiva.PrincipalTabla1excel', compact('base', 'foot'))->render();
        return view("educacion.TecnicoProductiva.PrincipalTabla1excelExport", compact('base', 'foot'));
    }
}
