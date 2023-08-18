<?php

namespace App\Http\Controllers\Vivienda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\tablaXImport;
use App\Models\Vivienda\Emapacopsa;
use App\Repositories\Vivienda\EmapacopsaRepositorio;
use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EmapacopsaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cargardistritos($provincia)
    {
        $distritos = EmapacopsaRepositorio::listarDistrito($provincia);
        return response()->json(compact('distritos'));
    }
 
}
