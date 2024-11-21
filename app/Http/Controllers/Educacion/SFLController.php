<?php

namespace App\Http\Controllers\Educacion;

use App\Exports\SFLExport;
use App\Http\Controllers\Controller;
use App\Imports\tablaXImport;
use App\Models\Administracion\Entidad;
use App\Models\Educacion\Area;
use App\Models\Educacion\InstitucionEducativa;
use App\Models\Educacion\PadronWeb;
use App\Models\Educacion\SFL;
use App\Models\Educacion\Ugel;
use App\Models\Parametro\IndicadorGeneral;
use App\Models\Parametro\IndicadorGeneralMeta;
use App\Models\Presupuesto\Sector;
use App\Repositories\Educacion\ImportacionRepositorio;
use App\Repositories\Educacion\UgelRepositorio;
use App\Repositories\Parametro\IndicadorGeneralRepositorio;
use App\Repositories\Parametro\UbigeoRepositorio;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\PseudoTypes\False_;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Stmt\Else_;

class SFLController extends Controller
{
    public $mesname = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'setiembre', 'octubre', 'noviembre', 'diciembre'];
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function principal()
    {
        $ugel = InstitucionEducativa::distinct()->select('uu.*')
            ->join('edu_ugel as uu', 'uu.id', '=', 'edu_institucioneducativa.Ugel_id')->where('uu.estado', 'AC')
            ->get();
        $provincia = InstitucionEducativa::distinct()->select('pv.*')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'edu_institucioneducativa.CentroPoblado_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->get();
        return view('educacion.SFL.Principal', compact('ugel', 'provincia'));
    }

    public function ListarDT(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);
        $est = ['', 'SANEADO', 'NO SANEADO', 'NO REGISTRADO', 'EN PROCESO'];
        $tip = ['', 'AFECTACION EN USO', 'TITULARIDAD', 'APORTE REGLAMENTARIO', 'OTROS'];
        //iiee.EstadoInsEdu_id = 3 and  and iiee.estado = 'AC'
        $query = DB::table(DB::raw("(
            select iiee.id, iiee.CentroPoblado_id, iiee.codLocal, iiee.Area_id, iiee.Ugel_id
	        from edu_institucionEducativa as iiee
            inner join edu_padronweb pw on pw.institucioneducativa_id=iiee.id
	        where iiee.TipoGestion_id in(4, 5, 7, 8) and iiee.NivelModalidad_id not in(14, 15) and pw.estadoinsedu_id = 3
        ) as iiee"))
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'iiee.CentroPoblado_id')
            ->join('edu_area as aa', 'aa.id', '=', 'iiee.Area_id')
            ->join('edu_ugel as uu', 'uu.id', '=', 'iiee.Ugel_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_sfl as sfl', 'sfl.institucioneducativa_id', '=', 'iiee.id');
        $query = $query->select(
            'iiee.codLocal as local',
            DB::raw('max(iiee.id) as id'),
            DB::raw('max(uu.nombre) as ugel'),
            DB::raw('max(pv.nombre) as provincia'),
            DB::raw('max(dt.nombre) as distrito'),
            DB::raw('max(aa.nombre) as area'),
        );

        if ($rq->ugel > 0) $query = $query->where('uu.id', $rq->ugel);
        if ($rq->provincia > 0) $query = $query->where('dt.dependencia', $rq->provincia);
        if ($rq->distrito > 0) $query = $query->where('dt.id', $rq->distrito);
        if ($rq->estado > 0) $query = $query->where('sfl.estado', $rq->estado);

        $query = $query->groupBy('local')->get();

        $querySFL = DB::table(DB::raw('(select id, codLocal as local, codModular as modular from edu_institucioneducativa where EstadoInsEdu_id=3)as ie'))
            ->join('edu_sfl as sfl', 'sfl.institucioneducativa_id', '=', 'ie.id', 'left')->where('ie.local', '!=', '')
            ->select('ie.*', 'sfl.estado', 'sfl.tipo', 'sfl.fecha_registro', 'sfl.fecha_inscripcion')
            ->orderBy('ie.id')->get();

        $data = [];
        foreach ($query as $key => $value) {
            $local = $value->local;
            $sflLOCAL = $querySFL->where('local', $local);

            $saneado = 0;
            $nosaneado = 0;
            $noregistrado = 0;
            $enproceso = 0;
            $blanco = 0;
            $pos = 0;
            $var0 = FALSE;
            foreach ($sflLOCAL as $item) {
                if ($item->estado == 1) {
                    $saneado++;
                }
                if ($item->estado == 2) {
                    $nosaneado++;
                }
                if ($item->estado == 3) {
                    $noregistrado++;
                }
                if ($item->estado == 4) {
                    $enproceso++;
                }
                if ($item->estado == null) {
                    $blanco++;
                }
                if ($pos == 0) {
                    $var0 = clone $item;
                }
                $pos++;
            }
            //NIURCA 941696330
            $estado = '';
            if ($sflLOCAL->count() == $saneado) {
                $estado = 'SANEADO';
            } else if ($sflLOCAL->count() == $saneado + $blanco) {
                $estado = 'SANEADO';
            } else   if ($sflLOCAL->count() == $nosaneado + $blanco) {
                $estado = 'NO SANEADO';
            } else  if ($sflLOCAL->count() == $noregistrado + $blanco) {
                $estado = 'NO REGISTRADO';
            } else  if ($sflLOCAL->count() == $enproceso + $blanco) {
                $estado = 'EN PROCESO';
            } else if ($sflLOCAL->count() == 1) {
                switch ($var0->estado) {
                    case 2:
                        $estado = 'NO SANEADO';
                        break;
                    case 3:
                        $estado = 'NO REGISTRADO';
                        break;
                    case 4:
                        $estado = 'EN PROCESO';
                        break;
                    case null:
                        $estado = 'NO REGISTRADO';
                        break;
                    default:
                        # code...
                        break;
                }
            } else {
                $estado = 'NO SANEADO';
            }
            $btn = '';
            $btn .= '&nbsp;<a href="#" class="btn btn-info btn-xs" onclick="open_modular(`' . $value->local . '`)"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            $btn .= '&nbsp;<a href="#" class="btn btn-orange-0 btn-xs" onclick="open_ver(`' . $value->local . '`)"  title="VER"> <i class="fas fa-eye"></i> </a>';

            $sfl = null;
            if ($sflLOCAL->count() > 0)
                $sfl = $var0;

            switch ($estado) {
                case 'SANEADO':
                    $estadox = '<span class="badge badge-success">' . $estado . '</span>';
                    break;
                case 'NO SANEADO':
                    $estadox = '<span class="badge badge-danger">' . $estado . '</span>';
                    break;
                case 'NO REGISTRADO':
                    $estadox = '<span class="badge badge-secondary">' . $estado . '</span>';
                    break;
                case 'EN PROCESO':
                    $estadox = '<span class="badge badge-warning">' . $estado . '</span>';
                    break;
                default:
                    $estadox = '';
                    break;
            }
            if ($rq->estado > 0) {
                if ($est[$rq->estado] == $estado) {
                    $data[] = array(
                        '<div style="text-align:center">' . ($key + 1) . '</div>',
                        '<div style="text-align:center">' . $value->local . '</div>',
                        '<div style="text-align:center">' . $sflLOCAL->count() . '</div>',
                        $value->ugel,
                        $value->provincia,
                        $value->distrito,
                        '<div style="text-align:center">' . $value->area . '</div>',
                        '<div style="text-align:center">' . ($sfl->fecha_inscripcion != null ? date('d/m/Y', strtotime($sfl->fecha_inscripcion)) : '') . '</div>',
                        '<div style="text-align:center">' . ($sfl->tipo > 0 ? ($tip[$sfl->tipo == NULL ? 0 : $sfl->tipo]) : '') . '</div>',
                        '<div style="text-align:center">' . $estadox . '</div>',
                        "<center><div class='btn-group'>" . $btn . "</div></center>",
                    );
                }
            } else {
                $data[] = array(
                    '<div style="text-align:center">' . ($key + 1) . '</div>',
                    '<div style="text-align:center">' . $value->local . '</div>',
                    '<div style="text-align:center">' . $sflLOCAL->count() . '</div>',
                    $value->ugel,
                    $value->provincia,
                    $value->distrito,
                    '<div style="text-align:center">' . $value->area . '</div>',
                    '<div style="text-align:center">' . ($sfl->fecha_inscripcion != null ? date('d/m/Y', strtotime($sfl->fecha_inscripcion)) : '') . '</div>',
                    '<div style="text-align:center">' . ($sfl->tipo > 0 ? ($tip[$sfl->tipo == NULL ? 0 : $sfl->tipo]) : '') . '</div>',
                    '<div style="text-align:center">' . $estadox . '</div>',
                    "<center><div class='btn-group'>" . $btn . "</div></center>",
                );
            }
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            "xxx1" => $query,
            "xxx2" => $querySFL,
        );
        return response()->json($result);
    }

    public function ListarDT2xx(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);
        // $est = ['', 'SANEADO', 'NO SANEADO', 'NO REGISTRADO', 'EN PROCESO'];
        $tip = ['', 'AFECTACION EN USO', 'TITULARIDAD', 'APORTE REGLAMENTARIO', 'OTROS'];

        $query = DB::table(DB::raw("(
            select iiee.id, iiee.CentroPoblado_id, iiee.codLocal, iiee.Area_id, iiee.Ugel_id
	        from edu_institucionEducativa as iiee
	        where iiee.EstadoInsEdu_id = 3 and iiee.TipoGestion_id in (4, 5, 7, 8) and iiee.estado = 'AC' and iiee.NivelModalidad_id not in (14, 15)
        ) as iiee"))
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'iiee.CentroPoblado_id')
            ->join('edu_area as aa', 'aa.id', '=', 'iiee.Area_id')
            ->join('edu_ugel as uu', 'uu.id', '=', 'iiee.Ugel_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_sfl as sfl', 'sfl.institucioneducativa_id', '=', 'iiee.id');
        $query = $query->select(
            'iiee.codLocal as local',
            DB::raw('max(iiee.id) as id'),
            DB::raw('max(uu.nombre) as ugel'),
            DB::raw('max(pv.nombre) as provincia'),
            DB::raw('max(dt.nombre) as distrito'),
            DB::raw('max(aa.nombre) as area'),
        );

        if ($rq->ugel > 0) $query = $query->where('uu.id', $rq->ugel);
        if ($rq->provincia > 0) $query = $query->where('dt.dependencia', $rq->provincia);
        if ($rq->distrito > 0) $query = $query->where('dt.id', $rq->distrito);
        if ($rq->estado > 0) $query = $query->where('sfl.estado', $rq->estado);

        $query = $query->groupBy('local')->get();

        $querySFL = DB::table(DB::raw('(select id, codLocal as local, codModular as modular from edu_institucioneducativa)as ie'))
            ->join('edu_sfl as sfl', 'sfl.institucioneducativa_id', '=', 'ie.id', 'left')->where('ie.local', '!=', '')
            ->select('ie.*', 'sfl.estado', 'sfl.tipo', 'sfl.fecha_registro', 'sfl.fecha_inscripcion')
            ->orderBy('ie.id')->get();

        $data = [];
        foreach ($query as $key => $value) {
            $local = $value->local;
            $sflLOCAL = $querySFL->where('local', $local);

            $saneado = 0;
            $nosaneado = 0;
            $noregistrado = 0;
            $enproceso = 0;
            $pos = 0;
            $var0 = FALSE;
            foreach ($sflLOCAL as $item) {
                if ($item->estado == 1) {
                    $saneado++;
                }
                if ($item->estado == 2) {
                    $nosaneado++;
                }
                if ($item->estado == 3) {
                    $noregistrado++;
                }
                if ($item->estado == 4) {
                    $enproceso++;
                }
                if ($pos == 0) {
                    $var0 = clone $item;
                }
                $pos++;
            }
            //NIURCA 941696330
            $estado = '';
            if ($sflLOCAL->count() == $saneado) {
                $estado = 'SANEADO';
            } else  if ($sflLOCAL->count() == $nosaneado) {
                $estado = 'NO SANEADO';
            } else  if ($sflLOCAL->count() == $noregistrado) {
                $estado = 'NO REGISTRADO';
            } else  if ($sflLOCAL->count() == $enproceso) {
                $estado = 'EN PROCESO';
            } else if ($sflLOCAL->count() == 1) {
                switch ($var0->estado) {
                    case 2:
                        $estado = 'NO SANEADO';
                        break;
                    case 3:
                        $estado = 'NO REGISTRADO';
                        break;
                    case 4:
                        $estado = 'EN PROCESO';
                        break;
                    default:
                        # code...
                        break;
                }
            } else {
                $estado = 'NO SANEADO';
            }
            $btn = '';
            $btn .= '&nbsp;<a href="#" class="btn btn-info btn-xs" onclick="open_modular(`' . $value->local . '`)"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            $btn .= '&nbsp;<a href="#" class="btn btn-orange-0 btn-xs" onclick="open_ver(`' . $value->local . '`)"  title="VER"> <i class="fas fa-eye"></i> </a>';

            $sfl = null;
            if ($sflLOCAL->count() > 0)
                $sfl = $var0;

            switch ($estado) {
                case 'SANEADO':
                    $estadox = '<span class="badge badge-success">' . $estado . '</span>';
                    break;
                case 'NO SANEADO':
                    $estadox = '<span class="badge badge-danger">' . $estado . '</span>';
                    break;
                case 'NO REGISTRADO':
                    $estadox = '<span class="badge badge-secondary">' . $estado . '</span>';
                    break;
                case 'EN PROCESO':
                    $estadox = '<span class="badge badge-warning">' . $estado . '</span>';
                    break;
                default:
                    $estadox = '';
                    break;
            }

            $data[] = array(
                '<div style="text-align:center">' . ($key + 1) . '</div>',
                '<div style="text-align:center">' . $value->local . '</div>',
                '<div style="text-align:center">' . $sflLOCAL->count() . '</div>',
                $value->ugel,
                $value->provincia,
                $value->distrito,
                '<div style="text-align:center">' . $value->area . '</div>',
                '<div style="text-align:center">' . ($sfl->fecha_inscripcion != null ? date('d/m/Y', strtotime($sfl->fecha_inscripcion)) : '') . '</div>',
                '<div style="text-align:center">' . ($sfl->tipo > 0 ? ($tip[$sfl->tipo == NULL ? 0 : $sfl->tipo]) : '') . '</div>',
                '<div style="text-align:center">' . $estadox . '</div>',
                "<center><div class='btn-group'>" . $btn . "</div></center>",
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            // "xxx" => $sflLOCAL->count(),
        );
        return response()->json($result);
    }

    public function ListarDTxxxx(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);
        $est = ['', 'SANEADO', 'NO SANEADO', 'NO REGISTRADO', 'EN PROCESO'];
        $tip = ['', 'AFECTACION EN USO', 'TITULARIDAD Y APORTE REGLAMENTARIO', 'OTROS'];

        $query = SFL::distinct()->select('ie.codLocal as local')
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_sfl.institucioneducativa_id')
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
            ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id');
        if ($rq->ugel > 0) $query = $query->where('uu.id', $rq->ugel);
        if ($rq->provincia > 0) $query = $query->where('dt.dependencia', $rq->provincia);
        if ($rq->distrito > 0) $query = $query->where('dt.id', $rq->distrito);
        if ($rq->estado > 0) $query = $query->where('edu_sfl.estado', $rq->estado);
        $query = $query->get();

        $data = [];
        foreach ($query as $key => $value) {
            $sfl = SFL::select(
                'edu_sfl.id',
                'edu_sfl.estado',
                'edu_sfl.tipo',
                'edu_sfl.partida_electronica',
                'edu_sfl.zona_registral',
                'edu_sfl.fecha_registro',
                'uu.nombre as ugel',
                'pv.nombre as provincia',
                'dt.nombre as distrito',
                'aa.nombre as area',
                'ie.codLocal as local',
            )
                ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_sfl.institucioneducativa_id')
                ->join('edu_area as aa', 'aa.id', '=', 'ie.Area_id')
                ->join('edu_ugel as uu', 'uu.id', '=', 'ie.Ugel_id')
                ->join('edu_centropoblado as cp', 'cp.id', '=', 'ie.CentroPoblado_id')
                ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                ->where('ie.codLocal', $value->local)
                ->first();

            $estados_sfl = SFL::select('edu_sfl.estado')->where('ie.codLocal', $value->local)
                ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_sfl.institucioneducativa_id')
                ->get();

            $saneado = 0;
            foreach ($estados_sfl as $item) {
                if ($item->estado == 1) {
                    $saneado++;
                }
            }

            $estado = '';
            if ($estados_sfl->count() == $saneado) {
                $estado = 'SANEADO';
            } else if ($saneado == 0) {
                $noregistrado = 0;
                foreach ($estados_sfl as $item) {
                    if ($item->estado == 3) {
                        $noregistrado++;
                    }
                }
                // if ($estados_sfl->count() == $noregistrado) {
                //     $estado = 'NO SANEADO';
                // } else {
                $estado = 'NO SANEADO';
                // }
            } else if ($estados_sfl->count() != $saneado) {
                // $estado = 'EN PROCESO';
                $estado = 'NO SANEADO';
            }
            $btn = '';
            $btn .= '&nbsp;<a href="#" class="btn btn-info btn-xs" onclick="open_modular(`' . $value->local . '`)"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
            $btn .= '&nbsp;<a href="#" class="btn btn-orange-0 btn-xs" onclick="open_ver(' . $value->local . ')"  title="VER"> <i class="fas fa-eye"></i> </a>';

            $data[] = array(
                '<div style="text-align:center">' . ($key + 1) . '</div>',
                '<div style="text-align:center">' . $value->local . '</div>',
                '<div style="text-align:center">' . $estados_sfl->count() . '</div>',
                $sfl->ugel,
                $sfl->provincia,
                $sfl->distrito,
                '<div style="text-align:center">' . $sfl->area . '</div>',
                '<div style="text-align:center">' . $estado . '</div>',
                '<div style="text-align:center">' . $tip[$sfl->tipo == NULL ? 0 : $sfl->tipo] . '</div>',
                '<div style="text-align:center">' . date('d/m/Y', strtotime($sfl->fecha_registro)) . '</div>',
                "<center><div class='btn-group'>" . $btn . "</div></center>",
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
        );
        return response()->json($result);
    }

    public function ListarDTModular(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);
        $est = ['ERROR', 'SANEADO', 'NO SANEADO', 'NO REGISTRADO', 'EN PROCESO'];
        $col = ['primary', 'success', 'danger', 'secondary', 'warning'];
        // $tip = ['ERROR', 'AFECTACION EN USO', 'TITULARIDAD Y APORTE REGLAMENTARIO', 'OTROS'];

        $query = SFL::select(
            'edu_sfl.*',
            'ie.codLocal as local',
            'ie.codModular as modular',
            'ie.nombreInstEduc as iiee',
            'nm.nombre as nivel',
            'ie.id as idiiee',
        )
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_sfl.institucioneducativa_id', 'right')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('ie.codLocal', $rq->local)->where('ie.EstadoInsEdu_id', 3)
            ->get();

        $data = [];

        foreach ($query as $key => $value) {
            $btn = '';
            $btn .= '<a href="#" class="btn btn-info btn-xs" onclick="edit_modular(' . ($value->id > 0 ? $value->id : 0) . ',' . $value->idiiee . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';

            $data[] = array(
                '<div style="text-align:center">' . ($key + 1) . '</div>',
                '<div style="text-align:center">' . $value->local . '</div>',
                '<div style="text-align:center">' . $value->modular . '</div>',
                $value->iiee,
                $value->nivel,
                // '<div style="text-align:center">' . ($value->estado > 0 ? $est[$value->estado] : '') . '</div>',
                '<div style="text-align:center">' . ($value->estado > 0 ? '<span class="badge badge-' . $col[$value->estado] . '">' . $est[$value->estado] . '</span>' : '') . '</div>',
                // '<div style="text-align:center"><span class="badge badge-primary . ($value->estado > 0 ? $est[$value->estado] : '') . '</span></div>',
                "<center><div class='btn-group'>" . $btn . "</div></center>",
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
        );
        return response()->json($result);
    }

    public function ListarDTModular2(Request $rq)
    {
        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);
        $est = ['ERROR', 'SANEADO', 'NO SANEADO', 'NO REGISTRADO', 'EN PROCESO'];
        $col = ['primary', 'success', 'danger', 'secondary', 'warning'];
        $tip = ['ERROR', 'AFECTACION EN USO', 'TITULARIDAD Y APORTE REGLAMENTARIO', 'OTROS'];

        $query = SFL::select(
            'edu_sfl.*',
            'ie.codLocal as local',
            'ie.codModular as modular',
            'ie.nombreInstEduc as iiee',
            'nm.nombre as nivel',
        )
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_sfl.institucioneducativa_id', 'right')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'ie.NivelModalidad_id')
            ->where('ie.codLocal', $rq->local)
            ->get();

        $data = [];
        foreach ($query as $key => $value) {
            $btn = '';
            $btn .= '<a href="#" class="btn btn-purple btn-xs" onclick="verpdf(' . $value->id . ')"  title="VER DOCUMENTO"> <i class="far fa-file-pdf"></i> </a>';

            $data[] = array(
                '<div style="text-align:center">' . ($key + 1) . '</div>',
                '<div style="text-align:center">' . $value->local . '</div>',
                '<div style="text-align:center">' . $value->modular . '</div>',
                $value->iiee,
                $value->nivel,
                // '<div style="text-align:center">' . ($value->estado > 0 ? $est[$value->estado] : '') . '</div>',
                '<div style="text-align:center">' . ($value->estado > 0 ? '<span class="badge badge-' . $col[$value->estado] . '">' . $est[$value->estado] . '</span>' : '') . '</div>',
                "<center><div class='btn-group'>" . $btn . "</div></center>",
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
            "query" => $query,
        );
        return response()->json($result);
    }

    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        $local = InstitucionEducativa::where('codLocal', $request->local)->get();
        $locales = SFL::select('edu_sfl.id')->where('ie.codLocal', $request->local)->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_sfl.institucioneducativa_id')->get();

        if ($request->local == '') {
            $data['inputerror'][] = 'local';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if ($local->count() == 0) {
            $data['inputerror'][] = 'local';
            $data['error_string'][] = 'Código Local no existe.';
            $data['status'] = FALSE;
        } else if ($locales->count() > 0) {
            $data['inputerror'][] = 'local';
            $data['error_string'][] = 'Código Local ya esta registrada.';
            $data['status'] = FALSE;
        }

        if ($request->nmodular == 0) {
            $data['inputerror'][] = 'modular';
            $data['error_string'][] = 'No hay codigos modulares.';
            $data['status'] = FALSE;
        }

        if ($request->estado == 0) {
            $data['inputerror'][] = 'estado';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->fecha == '') {
            $data['inputerror'][] = 'fecha';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function ajax_add(Request $request)
    {
        //dd($request);
        $this->_validate($request);
        $file = $request->file('documento');
        if ($file) {
            $documento = NULL;
            $name = $file->getClientOriginalName();
            $ext = \File::extension($name);
            if ($ext == 'pdf') {
                $ruta = $file->getRealPath();
                $documento = file_get_contents($ruta);
                $documento = base64_encode($documento);
            } else {
                return response()->json(array('status' => false, 'msn' => 'Solo archivos PDF'));
            }
        } else {
            $documento = NULL;
        }

        $nn = $request->nmodular;
        $mm = $request->modular;

        $info = [];
        $info['mm'] = $mm;
        $info['nn'] = $nn;
        if ($nn == 1) {
            $modulos = InstitucionEducativa::where('codLocal', $request->local)->get();
            foreach ($modulos as $item) {
                $ie = SFL::where('institucioneducativa_id', $item->id)->first();
                if ($ie) {
                    $ie->estado = $request->estado;
                    $ie->tipo = $request->tipo;
                    $ie->partida_electronica = $request->partida;
                    $ie->zona_registral = $request->zona;
                    $ie->fecha_registro = $request->fecha;
                    $ie->documento = $documento;
                    $ie->save();
                } else {
                    SFL::create([
                        'institucioneducativa_id' => $item->id,
                        'estado' => $request->estado,
                        'tipo' => ($request->tipo == 0 ? NULL : $request->tipo),
                        'partida_electronica' => $request->partida == '' ? NULL : $request->partida,
                        'zona_registral' => $request->zona == '' ? NULL : $request->zona,
                        'fecha_registro' => $request->fecha,
                        'documento' => $documento,
                    ]);
                }
            }
        } else if ($nn > 1 && $mm == 0) {
            $modulos = InstitucionEducativa::where('codLocal', $request->local)->get();
            foreach ($modulos as $item) {
                $ie = SFL::where('institucioneducativa_id', $item->id)->first();
                if ($ie) {
                    $ie->estado = $request->estado;
                    $ie->tipo = $request->tipo;
                    $ie->partida_electronica = $request->partida;
                    $ie->zona_registral = $request->zona;
                    $ie->fecha_registro = $request->fecha;
                    $ie->documento = $documento;
                    $ie->save();
                } else {
                    SFL::create([
                        'institucioneducativa_id' => $item->id,
                        'estado' => $request->estado,
                        'tipo' => $request->tipo,
                        'partida_electronica' => $request->partida,
                        'zona_registral' => $request->zona,
                        'fecha_registro' => $request->fecha,
                        'documento' => $documento,
                    ]);
                }
            }
        } else if ($nn > 1 && $mm > 0) {
            $modulos = InstitucionEducativa::where('codLocal', $request->local)->get();
            foreach ($modulos as $item) {
                $ie = SFL::where('institucioneducativa_id', $item->id)->first();
                if ($ie) {
                    if ($item->id == $mm) {
                        $ie->estado = $request->estado;
                        $ie->tipo = $request->tipo;
                        $ie->partida_electronica = $request->partida;
                        $ie->zona_registral = $request->zona;
                        $ie->fecha_registro = $request->fecha;
                        $ie->documento = $documento;
                        $ie->save();
                    }
                } else {
                    SFL::create([
                        'institucioneducativa_id' => $item->id,
                        'estado' => $item->id == $mm ? $request->estado : 2, // $request->estado,
                        'tipo' => $request->tipo,
                        'partida_electronica' => $request->partida,
                        'zona_registral' => $request->zona,
                        'fecha_registro' => $request->fecha,
                        'documento' => $documento,
                    ]);
                }
            }
        }

        return response()->json(array('status' => true, 'msn' => $info));
    }

    public function ajax_edit($id)
    {
        $sfl = SFL::find($id);
        return response()->json(compact('sfl'));
    }

    public function ajax_modular($modular)
    {
        $sfl = SFL::select('edu_sfl.*')
            ->join('edu_institucioneducativa as ie', 'ie.id', '=', 'edu_sfl.institucioneducativa_id')
            ->where('ie.id', $modular)
            ->first();
        return response()->json(compact('sfl'));
    }

    public function buscar($id)
    {
        $ie = IndicadorGeneralRepositorio::findNoFichatecnica($id);
        if ($ie) {
            if ($ie->instrumento_id > 0) {
                $inst = IndicadorGeneralRepositorio::instrumento($ie->instrumento_id);
                $ie->instrumento = $inst->nombre;
            }
            if ($ie->tipo_id > 0) {
                $inst = IndicadorGeneralRepositorio::tipo($ie->tipo_id);
                $ie->tipo = $inst->nombre;
            }
            if ($ie->oficina_id > 0) {
                $oficina = Entidad::find($ie->oficina_id);
                $entidad = Entidad::find($oficina->dependencia);
                $ie->oficinan = $oficina->nombre;
                $ie->entidad = $entidad->id;
                $ie->entidadn = $entidad->nombre;
            } else {
                $ie->oficinan = '';
                $ie->entidad = 0;
                $ie->entidadn = '';
            }
        }

        return response()->json(compact('ie'));
    }

    public function ajax_update(Request $request)
    {
        $this->_validate($request);
        $file = $request->file('fichatecnica');
        if ($file) {
            $fichatecnica = NULL;
            $name = $file->getClientOriginalName();
            $ext = \File::extension($name);
            if ($ext == 'pdf') {
                $ruta = $file->getRealPath();
                $fichatecnica = file_get_contents($ruta);
                $fichatecnica = base64_encode($fichatecnica);
            } else {
                return response()->json(array('status' => false, 'msn' => 'Solo archivos PDF'));
            }
        } else {
            $fichatecnica = NULL;
        }

        $indicador = IndicadorGeneral::find($request->id);
        $indicador->nombre = $request->nombre;
        $indicador->descripcion = $request->descripcion;
        $indicador->instrumento_id = $request->instrumento;
        $indicador->tipo_id = $request->tipo;
        $indicador->dimension_id = $request->dimension;
        $indicador->unidad_id = $request->unidad;
        $indicador->frecuencia_id = $request->frecuencia;
        $indicador->fuente_dato = $request->fuentedato;
        $indicador->anio_base = $request->aniobase;
        $indicador->valor_base = $request->valorbase;
        $indicador->sector_id = $request->sector;
        $indicador->oficina_id = $request->oficina;
        if ($file)
            $indicador->ficha_tecnica = $fichatecnica;
        $indicador->save();
        return response()->json(array('status' => true));
    }

    public function ajax_delete($id) //elimina deverdad *o*
    {
        $rer = IndicadorGeneral::find($id);
        $rer->delete();
        return response()->json(array('status' => true));
    }

    public function ajax_estado($id)
    {
        /* $rer = Lengua::find($id);
        $rer->estado = $rer->estado == 1 ? 0 : 1;
        $rer->save();
        return response()->json(array('status' => true, 'estado' => $rer->estado)); */
    }

    private function _validate_modulares($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->estadomodulares == 0) {
            $data['inputerror'][] = 'estadomodulares';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        if ($request->fechamodulares == '') {
            $data['inputerror'][] = 'fechamodulares';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        // if ($request->anotacionmodulares > 0) {
        //     $data['inputerror'][] = 'anotacionmodulares';
        //     $data['error_string'][] = 'Este campo es obligatorio.';
        //     $data['status'] = FALSE;
        // }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function ajax_update_modulares(Request $request)
    {
        $this->_validate_modulares($request);
        $file = $request->file('documentomodulares');
        if ($file) {
            $documento = NULL;
            $name = $file->getClientOriginalName();
            $ext = \File::extension($name);
            if ($ext == 'pdf') {
                $ruta = $file->getRealPath();
                $documento = file_get_contents($ruta);
                $documento = base64_encode($documento);
            } else {
                return response()->json(array('status' => false, 'msn' => 'Solo archivos PDF'));
            }
        } else {
            $documento = NULL;
        }

        if ($request->idsfl > 0) {
            $ie = SFL::find($request->idsfl);
            $ie->estado = $request->estadomodulares;
            $ie->tipo = $request->tipomodulares == 0 ? null : $request->tipomodulares;
            $ie->partida_electronica = $request->partidamodulares;
            $ie->zona_registral = 'ZONA N° VI - OFICINA REGISTRAL DE PUCALLPA';
            $ie->anotacion = $request->anotacionmodulares;
            $ie->fecha_registro = $request->fechamodulares;
            $ie->fecha_inscripcion = $request->fechainscripcion;
            if ($documento)
                $ie->documento = $documento;
            $ie->save();
        } else {
            SFL::create([
                'institucioneducativa_id' => $request->idiiee,
                'estado' => $request->estadomodulares,
                'tipo' => ($request->tipomodulares == 0 ? NULL : $request->tipomodulares),
                'partida_electronica' => $request->partidamodulares == '' ? NULL : $request->partidamodulares,
                'zona_registral' => 'ZONA N° VI - OFICINA REGISTRAL DE PUCALLPA', //$request->zonamodulares == '' ? NULL : $request->zonamodulares,
                'anotacion' => $request->anotacionmodulares,
                'fecha_registro' => $request->fechamodulares,
                'fecha_inscripcion' => $request->fechainscripcion,
                'documento' => $documento,
            ]);
        }

        return response()->json(array('status' => true));
    }

    public function exportarPDF($id)
    {
        $ind = SFL::select('documento')->where('id', $id)->first();
        if ($ind->documento) {
            header('Content-Type: application/pdf');
            echo base64_decode($ind->documento);
        } else {
            echo 'archivo PDF no encontrado';
        }
    }

    public function Download($ugel, $provincia, $distrito, $estado)
    {
        $name = 'SANEAMIENTO FÍSICO LEGAL ' . date('Y-m-d') . '.xlsx';
        return Excel::download(new SFLExport($ugel, $provincia, $distrito, $estado), $name);
    }

    public function ListarDTExport($ugel, $provincia, $distrito, $estado)
    {
        // $est = ['', 'SANEADO', 'NO SANEADO', 'NO REGISTRADO', 'EN PROCESO'];
        // $tip = ['', 'AFECTACION EN USO', 'TITULARIDAD', 'APORTE REGLAMENTARIO', 'OTROS'];

        $query = InstitucionEducativa::select(
            'edu_institucioneducativa.codLocal as local',
            'edu_institucioneducativa.codModular as modular',
            'edu_institucioneducativa.nombreInstEduc as iiee',
            'nm.nombre as nivel',
            'pv.nombre as provincia',
            'dt.nombre as distrito',
            'uu.nombre as ugel',
            'aa.nombre as area',
            'sfl.fecha_registro as fecha',
            db::raw('(case sfl.estado when 1 then "SANEADO" when 2 then "NO SANEADO" when 3 then "NO REGISTRADO" when 4 then "EN PROCESO" else "" end) as estado'),
        )
            ->join('edu_centropoblado as cp', 'cp.id', '=', 'edu_institucioneducativa.CentroPoblado_id')
            ->join('edu_nivelmodalidad as nm', 'nm.id', '=', 'edu_institucioneducativa.NivelModalidad_id')
            ->join('edu_area as aa', 'aa.id', '=', 'edu_institucioneducativa.Area_id')
            ->join('edu_ugel as uu', 'uu.id', '=', 'edu_institucioneducativa.Ugel_id')
            ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
            ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
            ->join('edu_sfl as sfl', 'sfl.institucioneducativa_id', '=', 'edu_institucioneducativa.id', 'left')
            ->where('edu_institucioneducativa.EstadoInsEdu_id', 3)->whereIn('edu_institucioneducativa.TipoGestion_id', [4, 5, 7, 8])
            ->where('edu_institucioneducativa.estado', 'AC')->whereNotIn('edu_institucioneducativa.NivelModalidad_id', [14, 15]);

        if ($ugel > 0) $query = $query->where('uu.id', $ugel);
        if ($provincia > 0) $query = $query->where('dt.dependencia', $provincia);
        if ($distrito > 0) $query = $query->where('dt.id', $distrito);
        if ($estado > 0) $query = $query->where('sfl.estado', $estado);

        $query = $query->get();

        return ["base" => $query];
    }

    public function download_plantillaxx()
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(storage_path('plantillas_excels/plantilla_sfl.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();
        $query = sfl::all();
        $fila = 2; // Comenzamos en la fila 2 (asumiendo que la fila 1 es de encabezado)
        foreach ($query as $value) {
            $ie = InstitucionEducativa::find($value->institucioneducativa_id);
            $sheet->setCellValue('A' . $fila, $ie->codModular);
            $sheet->setCellValue('B' . $fila, $value->estado);
            $sheet->setCellValue('C' . $fila, $value->tipo);
            $sheet->setCellValue('D' . $fila, $value->partida_electronica);
            $sheet->setCellValue('E' . $fila, $value->anotacion);
            $sheet->setCellValue('F' . $fila, $value->fecha_registro);
            $sheet->setCellValue('G' . $fila, $value->fecha_inscripcion);
            $fila++;
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'plantilla_sfl.xlsx';
        $path = storage_path('plantillas_excels/' . $fileName);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function download_plantilla()
    {
        $estados = [1 => 'SANEADO', 2 => 'NO SANEADO', 3 => 'NO REGISTRADO', 4 => 'EN PROCESO'];
        $tipos = [1 => 'AFECTACION EN USO', 2 => 'TITULARIDAD', 3 => 'APORTE REGLAMENTARIO', 4 => 'OTROS'];
        $anotacion = [1 => 'PREVENTIVA', 2 => 'DEFINITIVA'];

        $plantillaPath = base_path('plantillas_excels/plantilla_sfl.xlsx');

        if (!file_exists($plantillaPath)) {
            return response()->json(['error' => 'La plantilla no existe.'], 404);
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($plantillaPath);
        // $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->setActiveSheetIndex(0);

        $query = SFL::all();
        $fila = 2;
        foreach ($query as $value) {
            $ie = InstitucionEducativa::find($value->institucioneducativa_id);
            if (!$ie) {
                continue;
            }
            $sheet->setCellValueExplicit('A' . $fila, $ie->codModular, DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $fila, $estados[$value->estado] ?? '');
            $sheet->setCellValue('C' . $fila, $tipos[$value->tipo] ?? '');
            $sheet->setCellValue('D' . $fila, $value->partida_electronica);
            $sheet->setCellValue('E' . $fila, $anotacion[$value->anotacion] ?? '');
            $sheet->setCellValue('F' . $fila, $value->fecha_registro == null ? null : date('d/m/Y', strtotime($value->fecha_registro)));
            $sheet->setCellValue('G' . $fila, $value->fecha_inscripcion == null ? null : date('d/m/Y', strtotime($value->fecha_inscripcion)));
            $fila++;
        }

        $validation = $sheet->getCell('B2')->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Valor no válido');
        $validation->setError('Por favor seleccione un valor de la lista.');
        $validation->setPromptTitle('Seleccionar de la lista');
        $validation->setPrompt('Por favor seleccione un valor de la lista desplegable.');
        $validation->setFormula1('"SANEADO,NO SANEADO,NO REGISTRADO,EN PROCESO"'); // Lista de valores

        for ($i = 2; $i < $fila; $i++) {
            $sheet->getCell('B' . $i)->setDataValidation(clone $validation);
        }

        $validation = $sheet->getCell('C2')->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Valor no válido');
        $validation->setError('Por favor seleccione un valor de la lista.');
        $validation->setPromptTitle('Seleccionar de la lista');
        $validation->setPrompt('Por favor seleccione un valor de la lista desplegable.');
        $validation->setFormula1('"AFECTACION EN USO,TITULARIDAD,APORTE REGLAMENTARIO,OTROS"'); // Lista de valores

        // Aplicar la validación a toda la columna C, en las filas relevantes (desde la fila 2 hasta la última)
        for ($i = 2; $i < $fila; $i++) {
            $sheet->getCell('C' . $i)->setDataValidation(clone $validation);
        }

        $validation = $sheet->getCell('E2')->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Valor no válido');
        $validation->setError('Por favor seleccione un valor de la lista.');
        $validation->setPromptTitle('Seleccionar de la lista');
        $validation->setPrompt('Por favor seleccione un valor de la lista desplegable.');
        $validation->setFormula1('"PREVENTIVA,DEFINITIVA"'); // Lista de valores

        // Aplicar la validación a toda la columna C, en las filas relevantes (desde la fila 2 hasta la última)
        for ($i = 2; $i < $fila; $i++) {
            $sheet->getCell('E' . $i)->setDataValidation(clone $validation);
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="plantilla_sfl.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function download_plantillaoptx()
    {
        $estados = [1 => 'SANEADO', 2 => 'NO SANEADO', 3 => 'NO REGISTRADO', 4 => 'EN PROCESO'];
        $tipos = [1 => 'AFECTACION EN USO', 2 => 'TITULARIDAD', 3 => 'APORTE REGLAMENTARIO', 4 => 'OTROS'];
        $anotacion = [1 => 'PREVENTIVA', 2 => 'DEFINITIVA'];

        $plantillaPath = base_path('plantillas_excels/plantilla_sfl.xlsx');

        if (!file_exists($plantillaPath)) {
            return response()->json(['error' => 'La plantilla no existe.'], 404);
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($plantillaPath);
        $sheet = $spreadsheet->getActiveSheet();

        // Obtener la última fila con datos
        // $highestRow = $sheet->getHighestRow();

        // Limpiar todas las filas desde la 2 (asumiendo que la cabecera está en la fila 1)
        // for ($row = 2; $row <= $highestRow; $row++) {
        //     $sheet->removeRow($row);
        // }

        $query = SFL::all();
        $fila = 2; // Comenzamos en la fila 2 (cabecera en la fila 1)

        foreach ($query as $value) {
            $ie = InstitucionEducativa::find($value->institucioneducativa_id);
            if (!$ie) {
                continue;
            }
            $sheet->setCellValueExplicit('A' . $fila, $ie->codModular, DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $fila, $estados[$value->estado] ?? '');
            $sheet->setCellValue('C' . $fila, $tipos[$value->tipo] ?? '');
            $sheet->setCellValue('D' . $fila, $value->partida_electronica);
            $sheet->setCellValue('E' . $fila, $anotacion[$value->anotacion] ?? '');

            // Convertir fechas al formato de Excel si es necesario
            // $sheet->setCellValue('F' . $fila, Date::PHPToExcel($value->fecha_registro));
            $sheet->setCellValue('F' . $fila, $value->fecha_registro == null ? null : date('d/m/Y', strtotime($value->fecha_registro)));
            // $sheet->setCellValue('F' . $fila, $value->fecha_registro == null ? null : Date::PHPToExcel(strtotime($value->fecha_registro)));
            // $sheet->getStyle('F' . $fila)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
            // $sheet->setCellValue('G' . $fila, Date::PHPToExcel($value->fecha_inscripcion));
            $sheet->setCellValue('G' . $fila, $value->fecha_inscripcion == null ? null : date('d/m/Y', strtotime($value->fecha_inscripcion)));
            // $sheet->setCellValue('G' . $fila, $value->fecha_inscripcion == null ? null : Date::PHPToExcel(strtotime($value->fecha_inscripcion)));
            // $sheet->getStyle('G' . $fila)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

            $fila++;
        }

        // Enviar el archivo al navegador sin guardarlo en el servidor
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="plantilla_sfl.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function download_plantilla_opt2()
    {
        $plantillaPath = base_path('plantillas_excels/plantilla_sfl.xlsx');

        if (!file_exists($plantillaPath)) {
            return response()->json(['error' => 'La plantilla no existe.'], 404);
        }

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($plantillaPath);
        $sheet = $spreadsheet->getActiveSheet();

        $query = SFL::all();
        $fila = 2; // Comenzamos en la fila 2 (asumiendo que la fila 1 es de encabezado)

        foreach ($query as $value) {
            $ie = InstitucionEducativa::find($value->institucioneducativa_id);
            if (!$ie) {
                continue; // Si no se encuentra la institución, pasar al siguiente registro
            }

            $sheet->setCellValue('A' . $fila, $ie->codModular);
            $sheet->setCellValue('B' . $fila, $value->estado);
            $sheet->setCellValue('C' . $fila, $value->tipo);
            $sheet->setCellValue('D' . $fila, $value->partida_electronica);
            $sheet->setCellValue('E' . $fila, $value->anotacion);
            $sheet->setCellValue('F' . $fila, $value->fecha_registro);  // Verifica el formato de fecha
            $sheet->setCellValue('G' . $fila, $value->fecha_inscripcion); // Verifica el formato de fecha
            $fila++;
        }

        $fileName = 'plantilla_sfl.xlsx';
        $savePath = base_path('plantillas_excels/' . $fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($savePath);

        if (file_exists($savePath)) {
            return response()->download($savePath); //->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'No se pudo generar el archivo Excel.'], 500);
        }
    }

    public function download_plantillaasdasd()
    {
        $plantillaPath = base_path('plantillas_excels/plantilla_sfl.xlsx');

        if (!file_exists($plantillaPath)) {
            return response()->json(['error' => 'La plantilla no existe.'], 404);
        }

        // Cargar la plantilla
        $spreadsheet = IOFactory::load($plantillaPath);
        $sheet = $spreadsheet->getActiveSheet();

        $query = SFL::all();
        $fila = 2; // Comenzamos en la fila 2 (asumiendo que la fila 1 es de encabezado)

        foreach ($query as $value) {
            $ie = InstitucionEducativa::find($value->institucioneducativa_id);
            if (!$ie) {
                continue; // Si no se encuentra la institución, pasar al siguiente registro
            }

            $sheet->setCellValue('A' . $fila, $ie->codModular);
            $sheet->setCellValue('B' . $fila, $value->estado);
            $sheet->setCellValue('C' . $fila, $value->tipo);
            $sheet->setCellValue('D' . $fila, $value->partida_electronica);
            $sheet->setCellValue('E' . $fila, $value->anotacion);
            $sheet->setCellValue('F' . $fila, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($value->fecha_registro));
            $sheet->setCellValue('G' . $fila, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($value->fecha_inscripcion));
            $fila++;
        }

        $fileName = 'plantilla_sfl.xlsx';
        $savePath = base_path('plantillas_excels/' . $fileName);

        // Crear el escritor Xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        try {
            // Guardar el archivo Excel
            $writer->save($savePath);

            // Limpiar buffers y descargar el archivo
            ob_end_clean();
            return response()->download($savePath);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo generar el archivo Excel. ' . $e->getMessage()], 500);
        }
    }

    public function download_plantillasdfsdf()
    {
        $filePath = base_path('plantillas_excels/plantilla_sfl.xlsx');
        return response()->download($filePath);
    }

    public function cargar_plantilla(Request $rq)
    {
        $rq->validate([
            'archivo' => 'required|mimes:xlsx',
        ]);

        $query = Excel::toArray(new tablaXImport, $rq->file('archivo'));

        $draw = intval($rq->draw);
        $start = intval($rq->start);
        $length = intval($rq->length);

        $data = [];

        foreach ($query[0] as $key => $value) {
            $btn = '';

            $data[] = array(
                // $key + 1,
                $value['codigo_modular'],
                $value['estado_sfl'],
                $value['tipo_sfl'],
                $value['partida_electronica'],
                $value['anotacion'],
                $this->fechaExcel($value['fecha_registro']),
                $this->fechaExcel($value['fecha_inscripcion']),
            );
        }
        $result = array(
            "draw" => $draw,
            "recordsTotal" => $start,
            "recordsFiltered" => $length,
            "data" => $data,
        );
        return response()->json($result);
    }

    public function plantilla_guardar(Request $rq)
    {
        $rq->validate([
            'archivo' => 'required|mimes:xlsx',
        ]);

        $estados = ['SANEADO' => 1, 'NO SANEADO' => 2, 'NO REGISTRADO' => 3, 'EN PROCESO' => 4];
        $tipos = ['AFECTACION EN USO' => 1, 'TITULARIDAD' => 2, 'APORTE REGLAMENTARIO' => 3, 'OTROS' => 4];
        $anotacion = ['PREVENTIVA' => 1, 'DEFINITIVA' => 2];

        $query = Excel::toArray(new tablaXImport, $rq->file('archivo'));
        $notmodular = [];
        foreach ($query[0] as $key => $value) {
            $modular = str_pad($value['codigo_modular'], 7, '0', STR_PAD_LEFT);
            $iiee = InstitucionEducativa::where('codModular', $modular)->first();
            if ($iiee) {
                $sfl = SFL::where('institucioneducativa_id', $iiee->id)->first();
                if ($sfl) {
                    $sfl->estado = $estados[$value['estado_sfl']] ?? 0;
                    $sfl->tipo = $tipos[$value['tipo_sfl']] ?? 0;
                    $sfl->partida_electronica = $value['partida_electronica'];
                    $sfl->anotacion = $anotacion[$value['anotacion']] ?? 0;
                    $sfl->fecha_registro = $this->fechaExcel($value['fecha_registro']);
                    $sfl->fecha_inscripcion = $this->fechaExcel($value['fecha_inscripcion']);
                    $sfl->save();
                } else {
                    SFL::create([
                        'institucioneducativa_id' => $iiee->id,
                        'estado' => $estados[$value['estado_sfl']] ?? 0,
                        'tipo' => $tipos[$value['tipo_sfl']] ?? 0,
                        'partida_electronica' => $value['partida_electronica'],
                        'zona_registral' => 'ZONA N° VI - OFICINA REGISTRAL DE PUCALLPA',
                        'anotacion' => $anotacion[$value['anotacion']] ?? 0,
                        'fecha_registro' => $this->fechaExcel($value['fecha_registro']),
                        'fecha_inscripcion' => $this->fechaExcel($value['fecha_inscripcion']),
                    ]);
                }
            } else {
                $notmodular[] = $modular;
            }
        }
        return response()->json(['status' => TRUE, 'modular' => $notmodular]);
    }

    // public function fechaExcel($ff)
    // {
    //     if ($ff) {
    //         if ($ff != 'NULL') {
    //             $unix = (intval($ff) - 25569) * 86400;
    //             $php = new DateTime("@$unix");
    //             return $php->format('Y-m-d');
    //         }
    //     }
    //     return null;
    // }
    public function fechaExcel($ff)
    {
        if ($ff) {
            // Si la fecha es numérica (formato serial de Excel)
            if (is_numeric($ff)) {
                $unix = ($ff - 25569) * 86400; // Convertir de formato Excel a Unix timestamp
                $php = new DateTime("@$unix");
                return $php->format('Y-m-d');   // Devolver en formato Y-m-d
            }

            // Si la fecha es una cadena (formato dd/mm/yyyy)
            if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $ff)) {
                $php = DateTime::createFromFormat('d/m/Y', $ff); // Convertir de formato dd/mm/yyyy a DateTime
                if ($php) {
                    return $php->format('Y-m-d');   // Devolver en formato Y-m-d
                }
            }
        }

        return null;
    }

    public function tablerocontrol()
    {
        $ugel = UgelRepositorio::listar_opt();
        $provincia = UbigeoRepositorio::provincia_select('25');
        $area = Area::all();
        return view('educacion.SFL.TableroControl', compact('ugel', 'provincia', 'area'));
    }

    public function tablerocontrolreporte(Request $rq)
    {
        $imp = ImportacionRepositorio::ImportacionMax_porfuente(ImporPadronWebController::$FUENTE); //nexus $imp3
        switch ($rq->div) {
            case 'head':
                $query = DB::table(DB::raw("(
                    select iiee.id, iiee.CentroPoblado_id, iiee.codLocal, iiee.Area_id, iiee.Ugel_id
                    from edu_institucionEducativa as iiee
                    inner join edu_padronweb pw on pw.institucioneducativa_id=iiee.id and pw.importacion_id=" . $imp->id . "
                    where iiee.TipoGestion_id in(4, 5, 7, 8) and iiee.NivelModalidad_id not in(14, 15) and pw.estadoinsedu_id = 3
                ) as iiee"))
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'iiee.CentroPoblado_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'iiee.Area_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'iiee.Ugel_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia');

                if ($rq->ugel > 0) $query = $query->where('uu.id', $rq->ugel);
                if ($rq->provincia > 0) $query = $query->where('dt.dependencia', $rq->provincia);
                if ($rq->distrito > 0) $query = $query->where('dt.id', $rq->distrito);
                if ($rq->area > 0) $query = $query->where('aa.id', $rq->area);
                $card1 = $query = $query->count();

                $query = DB::table(DB::raw("(
                    select iiee.id, iiee.CentroPoblado_id, iiee.codLocal, iiee.Area_id, iiee.Ugel_id
                    from edu_institucionEducativa as iiee
                    inner join edu_padronweb pw on pw.institucioneducativa_id=iiee.id and pw.importacion_id=" . $imp->id . "
                    where iiee.TipoGestion_id in(4, 5, 7, 8) and iiee.NivelModalidad_id not in(14, 15) and pw.estadoinsedu_id = 3
                ) as iiee"))
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'iiee.CentroPoblado_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'iiee.Area_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'iiee.Ugel_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->select('iiee.codLocal');

                if ($rq->ugel > 0) $query = $query->where('uu.id', $rq->ugel);
                if ($rq->provincia > 0) $query = $query->where('dt.dependencia', $rq->provincia);
                if ($rq->distrito > 0) $query = $query->where('dt.id', $rq->distrito);
                if ($rq->area > 0) $query = $query->where('aa.id', $rq->area);
                $card2 = $query->groupBy('iiee.codLocal')->get()->count();



                $query = DB::table("edu_cubo_pacto02_local")->where('estado', 1);

                if ($rq->ugel > 0) $query = $query->where('ugel_id', $rq->ugel);
                if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);
                if ($rq->area > 0) $query = $query->where('area_id', $rq->area);
                $card3 = $query->count();

                $query = DB::table("edu_cubo_pacto02_local")->where('estado', '!=', 1);

                if ($rq->ugel > 0) $query = $query->where('ugel_id', $rq->ugel);
                if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);
                if ($rq->area > 0) $query = $query->where('area_id', $rq->area);
                $card4 = $query->count();

                $card1 = number_format($card1, 0);
                $card2 = number_format($card2, 0);
                $card3 = number_format($card3, 0);
                $card4 = number_format($card4, 0);
                return response()->json(compact('card1', 'card2', 'card3', 'card4'));
            case 'anal1':
                $query = DB::table("edu_cubo_pacto02_local")->join('par_ubigeo as p', 'p.id', '=', 'edu_cubo_pacto02_local.provincia_id')
                    ->select('p.nombre as provincia', DB::raw("sum(if(estado=1,1,0)) as saneado"), DB::raw("sum(if(estado!=1,1,0)) as nosaneado"));

                if ($rq->ugel > 0) $query = $query->where('ugel_id', $rq->ugel);
                if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);
                if ($rq->area > 0) $query = $query->where('area_id', $rq->area);
                $data = $query->groupBy('p.nombre')->get();

                $info['series'][0]['name'] = 'SANEADO';
                $info['series'][1]['name'] = 'NO SANEADO';
                $info['series'][0]['color'] = '#5eb9aa';
                $info['series'][1]['color'] = '#e65310';
                foreach ($data as $key => $value) {
                    $info['categoria'][] = $value->provincia;
                    $info['series'][0]['data'][] = (int)$value->saneado;
                    $info['series'][1]['data'][] = (int)$value->nosaneado;
                }

                return response()->json(compact('info', 'data'));

            case 'anal2':
                $query = DB::table("edu_cubo_pacto02_local")
                    ->select(DB::raw("sum(if(estado=1,1,0)) as saneado"), DB::raw("sum(if(estado!=1,1,0)) as nosaneado"));

                if ($rq->ugel > 0) $query = $query->where('ugel_id', $rq->ugel);
                if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);
                if ($rq->area > 0) $query = $query->where('area_id', $rq->area);
                $data = $query->get()->first();
                $info = [['name' => 'SANEADO', 'y' => (int)$data->saneado], ['name' => 'NO SANEADO', 'y' => (int)$data->nosaneado]];

                return response()->json(compact('info'));
                break;

            case 'tabla1':
                $query = DB::table(DB::raw("(
                    select iiee.id, iiee.CentroPoblado_id, iiee.codLocal, iiee.Area_id, iiee.Ugel_id
                    from edu_institucionEducativa as iiee
                    inner join edu_padronweb pw on pw.institucioneducativa_id=iiee.id and pw.importacion_id=" . $imp->id . "
                    where iiee.TipoGestion_id in(4, 5, 7, 8) and iiee.NivelModalidad_id not in(14, 15) and pw.estadoinsedu_id = 3
                ) as iiee"))
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'iiee.CentroPoblado_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'iiee.Area_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'iiee.Ugel_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->select('uu.nombre as ugel', DB::raw("count(*) as total"),);

                if ($rq->ugel > 0) $query = $query->where('uu.id', $rq->ugel);
                if ($rq->provincia > 0) $query = $query->where('dt.dependencia', $rq->provincia);
                if ($rq->distrito > 0) $query = $query->where('dt.id', $rq->distrito);
                if ($rq->area > 0) $query = $query->where('aa.id', $rq->area);
                $iiee = $query->groupBy('uu.nombre')->get();

                // $sum = $iiee->sum('total');
                //$iiee->firstWhere('provincia','ATALAYA')->total;

                $query = DB::table("edu_cubo_pacto02_local as c")->join('edu_ugel as u', 'u.id', '=', 'c.ugel_id')
                    ->select(
                        'u.nombre as ugel',
                        DB::raw("count(*) as le"),
                        DB::raw("sum(if(c.estado=1,1,0)) as e1"),
                        DB::raw("round(100*sum(if(c.estado=1,1,0))/count(*),2) as e1p"),
                        DB::raw("sum(if(c.estado=2,1,0)) as e2"),
                        DB::raw("round(100*sum(if(c.estado=2,1,0))/count(*),2) as e2p"),
                        DB::raw("sum(if(c.estado=3,1,0)) as e3"),
                        DB::raw("round(100*sum(if(c.estado=3,1,0))/count(*),2) as e3p"),
                        DB::raw("sum(if(c.estado=4,1,0)) as e4"),
                        DB::raw("round(100*sum(if(c.estado=4,1,0))/count(*),2) as e4p"),
                    );

                if ($rq->ugel > 0) $query = $query->where('ugel_id', $rq->ugel);
                if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);
                if ($rq->area > 0) $query = $query->where('area_id', $rq->area);
                $data = $query->groupBy('u.nombre')->get();

                foreach ($data as $key => $value) {
                    $value->ie = $iiee->firstWhere('ugel', $value->ugel)->total;
                    $value->iep = round(100 * $iiee->firstWhere('ugel',  $value->ugel)->total / $iiee->sum('total'), 2);
                    $value->lep = round(100 * $data->firstWhere('ugel',  $value->ugel)->le / $data->sum('le'), 2);
                }
                $base = $data;
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->ie = $data->sum('ie');
                    $foot->iep = $data->sum('iep');
                    $foot->le = $data->sum('le');
                    $foot->lep = $data->sum('lep');
                    $foot->e1 = $data->sum('e1');
                    $foot->e2 = $data->sum('e2');
                    $foot->e3 = $data->sum('e3');
                    $foot->e4 = $data->sum('e4');
                }
                $foot->e1p = round(100 * $data->sum('e1') / $data->sum('le'), 2);
                $foot->e2p = round(100 * $data->sum('e2') / $data->sum('le'), 2);
                $foot->e3p = round(100 * $data->sum('e3') / $data->sum('le'), 2);
                $foot->e4p = round(100 * $data->sum('e4') / $data->sum('le'), 2);
                $excel = view('educacion.SFL.TableroControlTabla1', compact('base', 'foot'))->render();
                return response()->json(compact('excel', 'base', 'foot'));

            case 'tabla2':
                $query = DB::table(DB::raw("(
                        select iiee.id, iiee.CentroPoblado_id, iiee.codLocal, iiee.Area_id, iiee.Ugel_id, iiee.NivelModalidad_id
                        from edu_institucionEducativa as iiee
                        inner join edu_padronweb pw on pw.institucioneducativa_id=iiee.id and pw.importacion_id=" . $imp->id . "
                        where iiee.TipoGestion_id in(4, 5, 7, 8) and iiee.NivelModalidad_id not in(14, 15) and pw.estadoinsedu_id = 3
                    ) as iiee"))
                    ->join('edu_centropoblado as cp', 'cp.id', '=', 'iiee.CentroPoblado_id')
                    ->join('edu_area as aa', 'aa.id', '=', 'iiee.Area_id')
                    ->join('edu_ugel as uu', 'uu.id', '=', 'iiee.Ugel_id')
                    ->join('par_ubigeo as dt', 'dt.id', '=', 'cp.Ubigeo_id')
                    ->join('par_ubigeo as pv', 'pv.id', '=', 'dt.dependencia')
                    ->select('uu.nombre as ugel', DB::raw("count(*) as total"),);

                if ($rq->ugel > 0) $query = $query->where('uu.id', $rq->ugel);
                if ($rq->provincia > 0) $query = $query->where('dt.dependencia', $rq->provincia);
                if ($rq->distrito > 0) $query = $query->where('dt.id', $rq->distrito);
                if ($rq->area > 0) $query = $query->where('aa.id', $rq->area);
                $iiee = $query->groupBy('uu.nombre')->get();

                // $sum = $iiee->sum('total');
                //$iiee->firstWhere('provincia','ATALAYA')->total;

                $query = DB::table("edu_cubo_pacto02_local as c")->join('edu_ugel as u', 'u.id', '=', 'c.ugel_id')
                    ->select(
                        'u.nombre as ugel',
                        DB::raw("count(*) as le"),
                        DB::raw("sum(if(c.estado=1,1,0)) as e1"),
                        DB::raw("round(100*sum(if(c.estado=1,1,0))/count(*),2) as e1p"),
                        DB::raw("sum(if(c.estado=2,1,0)) as e2"),
                        DB::raw("round(100*sum(if(c.estado=2,1,0))/count(*),2) as e2p"),
                        DB::raw("sum(if(c.estado=3,1,0)) as e3"),
                        DB::raw("round(100*sum(if(c.estado=3,1,0))/count(*),2) as e3p"),
                        DB::raw("sum(if(c.estado=4,1,0)) as e4"),
                        DB::raw("round(100*sum(if(c.estado=4,1,0))/count(*),2) as e4p"),
                    );

                if ($rq->ugel > 0) $query = $query->where('ugel_id', $rq->ugel);
                if ($rq->provincia > 0) $query = $query->where('provincia_id', $rq->provincia);
                if ($rq->distrito > 0) $query = $query->where('distrito_id', $rq->distrito);
                if ($rq->area > 0) $query = $query->where('area_id', $rq->area);
                $data = $query->groupBy('u.nombre')->get();

                foreach ($data as $key => $value) {
                    $value->ie = $iiee->firstWhere('ugel', $value->ugel)->total;
                    $value->iep = round(100 * $iiee->firstWhere('ugel',  $value->ugel)->total / $iiee->sum('total'), 2);
                    $value->lep = round(100 * $data->firstWhere('ugel',  $value->ugel)->le / $data->sum('le'), 2);
                }
                $base = $data;
                $foot = [];
                if ($base->count() > 0) {
                    $foot = clone $base[0];
                    $foot->ie = $data->sum('ie');
                    $foot->iep = $data->sum('iep');
                    $foot->le = $data->sum('le');
                    $foot->lep = $data->sum('lep');
                    $foot->e1 = $data->sum('e1');
                    $foot->e2 = $data->sum('e2');
                    $foot->e3 = $data->sum('e3');
                    $foot->e4 = $data->sum('e4');
                }
                $foot->e1p = round(100 * $data->sum('e1') / $data->sum('le'), 2);
                $foot->e2p = round(100 * $data->sum('e2') / $data->sum('le'), 2);
                $foot->e3p = round(100 * $data->sum('e3') / $data->sum('le'), 2);
                $foot->e4p = round(100 * $data->sum('e4') / $data->sum('le'), 2);
                $excel = view('educacion.SFL.TableroControlTabla1', compact('base', 'foot'))->render();
                return response()->json(compact('excel', 'base', 'foot'));
            default:
                # code...
                return [];
        }
    }
}
