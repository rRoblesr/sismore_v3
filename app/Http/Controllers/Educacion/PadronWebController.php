<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Repositories\Educacion\PadronWebRepositorio;
use App\Utilities\Utilitario;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class PadronWebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function buscariiee($codigo_modular)///esta por ver
    {
        $query = PadronWebRepositorio::buscariiee($codigo_modular);
        if ($query->count() > 0) {
            $data['info'] = $query->first();
            $data['status'] = TRUE;
        } else {
            $data['inputerror'] = 'codigomodular_padronweb';
            $data['error_string'] = 'Codigo Modular no Encontrado en Padron Web';
            $data['status'] = FALSE;
        }
        return $data;
    }
}
