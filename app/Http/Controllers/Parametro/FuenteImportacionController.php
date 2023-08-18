<?php

namespace App\Http\Controllers\Parametro;

use App\Http\Controllers\Controller;
use App\Models\Parametro\Icono;
use App\Models\Administracion\Sistema;
use App\Models\Parametro\FuenteImportacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FuenteImportacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function principal()
    {
        return 'falta implementar';
    }

    public function cargar($sistema_id)
    {
        $query = FuenteImportacion::where('sistema_id', $sistema_id)->get();
        return response()->json(array('fuenteimportacions' => $query));
    }
}
