<?php

namespace App\Http\Controllers\Trabajo;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use Exception;

use App\Http\Controllers\Controller;
use App\Repositories\Trabajo\ActividadRepositorio;

class ActividadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Principal ()
    {
        $dataDirecciones = ActividadRepositorio:: direcciones_conActividad(8,6);

        $actividades = ActividadRepositorio:: Actividad_conMeta(8);

        $direcciones = $dataDirecciones->where('dependencia',null);
        $subDirecciones = $dataDirecciones->where('dependencia','!=',null);

        $Actividad_Resultado = ActividadRepositorio::Actividad_Resultado(8);
       
        return view('Trabajo.Actividades.Principal',compact('direcciones','subDirecciones','actividades','Actividad_Resultado' ));
    }

}
