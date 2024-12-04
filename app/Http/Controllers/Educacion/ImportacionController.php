<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use App\Models\Educacion\Importacion;
use App\Repositories\Educacion\ImportacionRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function inicio()
    {
        //  $data = ImportacionRepositorio::Listar_Importaciones(session('sistema_id'));

        //  return $data;
        return view('educacion.Importacion.Inicio');
    }

    public function importacionesLista_DataTable()
    {
        // $padronWebLista = Importacion::select('id','comentario','fechaActualizacion','estado')
        //  ->get();

        $data = ImportacionRepositorio::Listar_Importaciones(session('sistema_id'));

        return  datatables()::of($data)
            ->addColumn('action', function ($data) {

                if ($data->estado == "APROBADO") {
                    switch ($data->codigo) {
                        default:
                            $acciones = '&nbsp<button type="button" name="hy" class=" btn btn-secondary btn-sm"> <i class="fa fa-check"></i>  </button>';
                            break;
                    }
                } else {
                    switch ($data->codigo) {
                        case ('COD01'):
                            $acciones = '<a href="PadronWeb/Aprobar/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;
                        case ('COD02'):
                            $acciones = '<a href="CuadroAsigPersonal/Aprobar/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;
                        case ('COD03'):
                            $acciones = '<a href="ECE/Importar/Aprobar/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;
                        case ('COD06'):
                            $acciones = '<a href="Censo/Aprobar/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;
                        case ('COD07'):
                            $acciones = '<a href="Datass/Aprobar/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;
                        case ('COD08'):
                            $acciones = '<a href="Matricula/Aprobar/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;
                        case ('COD09'):
                            $acciones = '<a href="Tableta/Aprobar/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;
                        case ('COD10'):
                            $acciones = '<a href="Matricula/AprobarConsolidadoAnual/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;
                        case ('COD11'):
                            $acciones = '<a href="PEmapacopsa/Aprobar/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;

                        case ('COD18'):
                            $acciones = '<a href="ProEmpleo/Aprobar/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;
                        case ('COD19' || 'COD20'):
                            $acciones = '<a href="AnuarioEstadistico/Aprobar/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;

                        default:
                            $acciones = '<a href="PadronWeb/AprobarNN/' . $data->id . '"   class="btn btn-info btn-sm"> <i class="fa fa-check"></i> </a>';
                            break;
                    }
                }

                $acciones .= '&nbsp<button type="button" name="delete" id = "' . $data->id . '" class="delete btn btn-danger btn-sm">  <i class="fa fa-trash"></i> </button>';
                return $acciones;
            })
            ->editColumn('fechaActualizacion', function ($data) {
                return date('d-m-Y', strtotime($data->fechaActualizacion));
            })
            /* ->editColumn('apellidos',function($data){
                return date('d-m-Y',strtotime($data->fechaActualizacion));

            }) */
            ->rawColumns(['action', 'fechaActualizacion'/* ,'apellidos' */])
            ->make(true);
        // ->toJson();
    }

    public function eliminar($id)
    {

        $entidad = Importacion::find($id);

        $entidad->estado = 'EL';
        $entidad->save();

        return back();
    }

    public function setEliminar($id)
    {
        $entidad = Importacion::find($id);
        $entidad->estado = 'EL';
        $entidad->save();
        return response()->json(array('status' => true));
    }

    public function resumenimportados()
    {
        return view('parametro.Importacion.ResumenImportados');
    }

    public function ListarDTImportFuenteTodos(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $query = ImportacionRepositorio::Listar_FuenteTodos('13');
        $data = [];
        foreach ($query as $key => $value) {
            $nom = '';
            if (strlen($value->cnombre) > 0) {
                $xx = explode(' ', $value->cnombre);
                $nom = $xx[0];
            }
            $ape = '';
            if (strlen($value->capellidos) > 0) {
                $xx = explode(' ', $value->capellidos);
                $ape = $xx[0];
            }

            if (date('Y-m-d', strtotime($value->created_at)) == date('Y-m-d') || session('perfil_administrador_id') == 3 || session('perfil_administrador_id') == 8 || session('perfil_administrador_id') == 9 || session('perfil_administrador_id') == 10 || session('perfil_administrador_id') == 11)
                $boton = '<button type="button" onclick="geteliminar(' . $value->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
            else
                $boton = '';
            $boton2 = '<button type="button" onclick="monitor(' . $value->id . ')" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> </button>';
            $data[] = array(
                $key + 1,
                date("d/m/Y", strtotime($value->fechaActualizacion)),
                $value->fuente,
                $nom . ' ' . $ape,
                date("d/m/Y", strtotime($value->created_at)),
                $value->comentario,
                $value->estado == "PR" ? "PROCESADO" : ($value->estado == "PE" ? "PENDIENTE" : "ELIMINADO"),
                $boton . '&nbsp;' . $boton2,
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data
        );
        return response()->json($result);
    }

    public function ListarImportadosDT($fuenteimportacion_id)
    {
        $data = ImportacionRepositorio::Listar_FuenteTodos($fuenteimportacion_id);
        return datatables()
            ->of($data)
            ->editColumn('fechaActualizacion', '{{date("d-m-Y",strtotime($fechaActualizacion))}}')
            ->editColumn('estado', function ($query) {
                return $query->estado == "PR" ? "PROCESADO" : ($query->estado == "PE" ? "PENDIENTE" : "ELIMINADO");
            })
            ->addColumn('accion', function ($oo) {
                return '<button type="button" onclick="geteliminar(' . $oo->id . ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> </button>';
            })
            ->rawColumns(['fechaActualizacion', 'estado', 'accion'])
            ->toJson();
    }
    public function meses_porfuente_select($fuente, $anio)
    {
        return ImportacionRepositorio::meses_porfuente_select($fuente, $anio);
    }
}
