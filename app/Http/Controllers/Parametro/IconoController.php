<?php

namespace App\Http\Controllers\Parametro;

use App\Http\Controllers\Controller;
use App\Models\Parametro\Icono;
use App\Repositories\Parametro\UbigeoRepositorio;
use Illuminate\Http\Request;

class IconoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listarDT(Request $rq)
    {
        //$draw = intval($rq->draw);
        //$start = intval($rq->start);
        //$length = intval($rq->length);


        if ($rq->tipo > 0)
            $query = Icono::where('tipo', $rq->tipo)->get();
        else
            $query = Icono::all();

        $data = [];
        foreach ($query as $key => $value) {
            $btn = '<a href="#" class="btn btn-success btn-xs" onclick="seleccionar_icon(`' . $value->icon . '`)"  title="MODIFICAR"> <i class="fas fa-check-double"></i> </a>';
            $data[] = array(
                $key + 1,
                $value->icon,
                '<center><i class="' . $value->icon . '"></i></center>',
                '<div style="text-align:center">' . $btn . '</div>'
            );
        }
        $result = array(
            //"draw" => $draw,
            //"recordsTotal" => $start,
            //"recordsFiltered" => $length,
            "data" => $data,
        );
        return response()->json($result);
    }
}
