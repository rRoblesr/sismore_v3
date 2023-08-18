<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;
use App\Models\Presupuesto\Meta;
use App\Models\Presupuesto\TipoGobierno;
use App\Models\Presupuesto\UnidadOrganica;
use Illuminate\Http\Request;

class UEMetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
