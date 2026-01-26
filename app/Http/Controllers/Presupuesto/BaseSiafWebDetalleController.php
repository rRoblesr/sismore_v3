<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Repositories\Presupuesto\BaseSiafWebDetalleRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BaseSiafWebDetalleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function obtenerUnidadesEjecutorasParaSelect($anio)
    {
        return BaseSiafWebDetalleRepositorio::obtenerUnidadesEjecutorasParaSelect($anio);
    }

    public function obtenerCategoriasGastoParaSelect($anio, $ue)
    {
        return BaseSiafWebDetalleRepositorio::obtenerCategoriasGastoParaSelect($anio, $ue);
    }

    public function obtenerCategoriasPresupuestalesParaSelect($anio, $ue, $cg)
    {
        return BaseSiafWebDetalleRepositorio::obtenerCategoriasPresupuestalesParaSelect($anio, $ue, $cg);
    }

    public function obtenerFuenteFinanciamientoParaSelect($anio, $ue, $cg)
    {
        return BaseSiafWebDetalleRepositorio::obtenerFuenteFinanciamientoParaSelect($anio, $ue, $cg);
    }

    public function obtenerGenericaParaSelect($anio, $ue, $cg)
    {
        return BaseSiafWebDetalleRepositorio::obtenerGenericaParaSelect($anio, $ue, $cg);
    }

    public function obtenerCategoriasPresupuestalesParaSelect2($anio, $ue)
    {
        return BaseSiafWebDetalleRepositorio::obtenerCategoriasPresupuestalesParaSelect2($anio, $ue);
    }

    public function obtenerFuenteFinanciamientoParaSelect2($anio, $ue, $cp)
    {
        return BaseSiafWebDetalleRepositorio::obtenerFuenteFinanciamientoParaSelect2($anio, $ue, $cp);
    }
}
