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
        $this->middleware('auth');
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
}
