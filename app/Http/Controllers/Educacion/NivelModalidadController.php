<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Area;
use App\Models\Educacion\Importacion;
use App\Models\Educacion\NivelModalidad;
use App\Models\Educacion\PLaza;
use App\Models\Parametro\Anio;
use App\Repositories\Educacion\NivelModalidadRepositorio;
use App\Repositories\Educacion\PlazaRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NivelModalidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscarnivelmodalidad($tipo)
    {
        $query = NivelModalidadRepositorio::buscarportipo($tipo);
        return $query;
    }

}
