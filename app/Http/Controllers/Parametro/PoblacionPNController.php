<?php

namespace App\Http\Controllers\Parametro;

use App\Http\Controllers\Controller;
use App\Repositories\Parametro\PoblacionPNRepositorio;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function PHPUnit\Framework\isNull;

class PoblacionPNController extends Controller
{
    public function __construct() {}

    public function mes($anio)
    {
        return PoblacionPNRepositorio::mes($anio);
    }

    public function provincia($anio, $mes)
    {
        return PoblacionPNRepositorio::provincia($anio, $mes);
    }

    public function distrito($anio, $mes, $provincia)
    {
        return PoblacionPNRepositorio::distrito($anio, $mes, $provincia);
    }
}
