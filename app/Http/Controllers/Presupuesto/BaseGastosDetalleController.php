<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Repositories\Presupuesto\BaseGastosDetalleRepositorio;

class BaseGastosDetalleController extends Controller
{
    public function obtenerUnidadesEjecutorasParaSelect($anio)
    {
        return BaseGastosDetalleRepositorio::obtenerUnidadesEjecutorasParaSelect((int) $anio);
    }

    public function obtenerCategoriasGastoParaSelect($anio, $ue)
    {
        return BaseGastosDetalleRepositorio::obtenerCategoriasGastoParaSelect((int) $anio, (int) $ue);
    }

    public function obtenerCategoriasPresupuestalesParaSelect($anio, $ue, $cg)
    {
        return BaseGastosDetalleRepositorio::obtenerCategoriasPresupuestalesParaSelect((int) $anio, (int) $ue, (int) $cg);
    }

    public function obtenerRubrosParaSelect($anio, $ue, $ff)
    {
        return BaseGastosDetalleRepositorio::obtenerRubrosParaSelect((int) $anio, (int) $ue, (int) $ff);
    }

    public function obtenerFuenteFinanciamientoParaSelect($anio, $ue, $cg)
    {
        return BaseGastosDetalleRepositorio::obtenerFuenteFinanciamientoParaSelect((int) $anio, (int) $ue, (int) $cg);
    }

    public function obtenerGenericaParaSelect($anio, $ue, $cg)
    {
        return BaseGastosDetalleRepositorio::obtenerGenericaParaSelect((int) $anio, (int) $ue, (int) $cg);
    }

    public function obtenerCategoriasPresupuestalesParaSelect2($anio, $ue)
    {
        return BaseGastosDetalleRepositorio::obtenerCategoriasPresupuestalesParaSelect2((int) $anio, (int) $ue);
    }

    public function obtenerFuenteFinanciamientoParaSelect2($anio, $ue, $cp)
    {
        return BaseGastosDetalleRepositorio::obtenerFuenteFinanciamientoParaSelect2((int) $anio, (int) $ue, $cp);
    }
}
