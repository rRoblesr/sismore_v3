<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Entidad;
use App\Models\Administracion\Perfil;
use App\Models\Administracion\Usuario;
use App\Models\Administracion\UsuarioAuditoria;
use App\Models\Administracion\UsuarioPerfil;
use App\Models\Presupuesto\Sector;
use App\Repositories\Administracion\SistemaRepositorio;
use App\Repositories\Administracion\UsuarioPerfilRepositorio;
use App\Repositories\Administracion\UsuarioRepositorio;
use App\Repositories\Administracion\EntidadRepositorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function principal()
    {
        //return filter_var('asdsad@hot', FILTER_VALIDATE_EMAIL);
        //$sistemas2 = Sistema::where('estado', '1')->orderBy('nombre')->get();
        $sistemas = SistemaRepositorio::listar_porperfil(session('perfil_administrador_id'));
        $sector = Sector::whereIn('id', [1, 2, 4, 14, 18, 22])->get();
        return view('administracion.Usuario.Principal', compact('sistemas', 'sector'));
    }
    public function Lista_DataTable()
    {
        //$data = UsuarioRepositorio::Listar_porperfil(session('perfil_administrador_id'));
        $data = UsuarioRepositorio::Listar_porperfil(session('perfil_administrador_id'));
        return  datatables()::of($data)
            ->addIndexColumn()
            ->addColumn('nombrecompleto', '{{$apellido1}} {{$apellido2}}, {{$nombre}}')
            ->editColumn('entidad', function ($data) {
                $ent = EntidadRepositorio::getEntidadOficina($data->entidad);
                return $ent ? $ent->entidad_abre : '';
            })
            ->editColumn('estado', function ($data) {
                if ($data->estado == 0) return '<span class="badge badge-danger">DESABILITADO</span>';
                else return '<span class="badge badge-success">ACTIVO</span>';
            })
            ->addColumn('perfiles', function ($data) {
                $perfiles = UsuarioPerfilRepositorio::ListarPerfilSistema($data->id);
                $datos = '';
                if ($perfiles)
                    foreach ($perfiles as $item) {
                        //$datos .='<tr><td>SISTEMA ' . $item->sistema . '</td> <td>' . $item->perfil . '</td></tr>';
                        $datos .= '<span class="badge badge-dark"><i class="' . $item->icono . '"></i> SISTEMA ' . $item->sistema . '</span>
                        <span class="badge badge-secondary"> ' . $item->perfil . '</span><br/>';
                    }
                //$datos .= '</table>';
                return $datos;
            })
            ->addColumn('cperfiles', function ($data) {
                $perfiles = UsuarioPerfilRepositorio::ListarPerfilSistema($data->id);
                return '<div style="text-align:center">' . $perfiles->count() . '</div>';
            })
            ->addColumn('action', function ($data) { // '.auth()->user()->usuario.'
                $acciones = '';

                $acciones = '<a href="#" class="btn btn-info btn-sm" onclick="edit(' . $data->id . ')"  title="MODIFICAR"> <i class="fa fa-pen"></i></a>';
                $acciones .= '&nbsp;<a href="#" class="btn btn-warning btn-sm" onclick="perfil(' . $data->id . ')" title="AGREGAR PERFIL"> <i class="fa fa-list"></i> </a>';

                if ($data->estado == '1') {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-dark" href="javascript:void(0)" title="Desactivar" onclick="estadoUsuario(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-power-off"></i></a> ';
                } else {
                    $acciones .= '&nbsp;<a class="btn btn-sm btn-default"  title="Activar" onclick="estadoUsuario(' . $data->id . ',' . $data->estado . ')"><i class="fa fa-check"></i></a> ';
                }

                //$acciones = '<a href="Editar/' . $data->id . '"   class="btn btn-info btn-sm" title="MODIFICAR"> <i class="fa fa-pen"></i> </a>';
                //$acciones .= '&nbsp<button type="button" name="delete" id = "' . $data->id . '" class="delete btn btn-danger btn-sm" title="ELIMINAR"> <i class="fa fa-trash"></i>  </button>';
                $acciones .= '&nbsp<a href="#" class="btn btn-danger btn-sm" onclick="borrar(' . $data->id . ')" title="BORRAR"> <i class="fa fa-trash"></i> </a>';
                return $acciones;
            })
            ->rawColumns(['action', 'nombrecompleto', 'perfiles', 'cperfiles', 'estado', 'entidad'])
            ->make(true);
    }
    public function listarSistemasAsignados($usuario_id)
    {
        $data = UsuarioPerfilRepositorio::ListarPerfilSistema($usuario_id);
        //return response()->json($usuario_id);
        return  datatables()::of($data)
            ->addColumn('accion', function ($data) {
                $acciones = '<a href="#" class="btn btn-danger btn-sm" onclick="borrarperfil(' . $data->usuario_id . ',' . $data->perfil_id . ')"  title="ELIMINAR"> <i class="fa fa-trash"></i></a>';
                return $acciones;
            })
            ->rawColumns(['accion'])
            ->make(true);
    }
    public function registrar()
    {
        return view('administracion.Usuario.Registrar');
    }
    public function guardar(Request $request)
    {
        $request->validate([
            'usuario' => ['required', 'string', 'max:255', 'unique:adm_usuario'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:adm_usuario'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            //'sistemas[]' => ['required'],
        ]);

        Usuario::create([
            'usuario' => $request['usuario'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'estado' => '1'
        ]);



        return redirect()->route('Usuario.principal')->with('success', 'Registro creado correctamente');
    }
    public function editar(Usuario $usuario)
    {
        return view('administracion.Usuario.Editar', compact('usuario'));
    }
    public function actualizar(Request $request, $id)
    {
        $entidad = Usuario::find($id);
        $entidad->usuario = $request['usuario'];
        $entidad->email = $request['email'];
        if ($request['password'] != '')
            $entidad->password = Hash::make($request['password']);
        $entidad->save();
        return redirect()->route('Usuario.principal')->with('success', 'Registro modificado correctamente' . count($request->sistemas));
    }
    public function eliminar($id)
    {
        $entidad = Usuario::find($id);
        $entidad->estado = 0;
        $entidad->save();
        return back();
    }
    public function cargarPerfil($sistema_id, $usuario_id)
    {
        $perfil = Perfil::where('sistema_id', $sistema_id)->where('estado', '1')->select('id', 'nombre')->get();
        $usuarioperfil = UsuarioPerfil::where('adm_usuario_perfil.usuario_id', $usuario_id)
            ->select('adm_usuario_perfil.perfil_id')
            ->join('adm_perfil as v2', 'v2.id', '=', 'adm_usuario_perfil.perfil_id')
            ->where('v2.sistema_id', $sistema_id)
            ->get();
        return response()->json(compact('perfil', 'usuarioperfil'));
    }
    private function _validateperfil($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->sistema_id == '') {
            $data['inputerror'][] = 'sistema_id';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        return $data;
    }
    public function ajax_add_perfil(Request $request)
    {
        $val = $this->_validateperfil($request);
        /* $val['sis'] = $request->sistema_id; */
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $perfiles = Perfil::where('sistema_id', $request->sistema_id)->get();
        foreach ($perfiles as $perfil) {
            if ($request->perfil) {
                if ($request->perfil == $perfil->id) {
                    $usuarioperfil = UsuarioPerfil::where('usuario_id', $request->usuario_id)->where('perfil_id', $perfil->id)->first();
                    if (!$usuarioperfil) UsuarioPerfil::Create(['usuario_id' => $request->usuario_id, 'perfil_id' => $perfil->id]);
                } else UsuarioPerfil::where('usuario_id', $request->usuario_id)->where('perfil_id', $perfil->id)->delete();
            } else UsuarioPerfil::where('usuario_id', $request->usuario_id)->where('perfil_id', $perfil->id)->delete();
        }
        return response()->json(array('status' => true, 'modulos' => $perfiles));
    }

    public function ajax_delete_perfil($usuario_id, $perfil_id) //elimina deverdad *o*
    {
        UsuarioPerfil::where('usuario_id', $usuario_id)->where('perfil_id', $perfil_id)->delete();
        return response()->json(array('status' => true));
    }

    private function _validate($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $usuarioxx = Usuario::where('dni', $request->dni)->first();

        if ($request->dni == '') {
            $data['inputerror'][] = 'dni';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if (strlen($request->dni) < 8) {
            $data['inputerror'][] = 'dni';
            $data['error_string'][] = 'Este campo necesita 8 digitos.';
            $data['status'] = FALSE;
        } else if ($usuarioxx && $request->id == '') {
            $data['inputerror'][] = 'dni';
            $data['error_string'][] = 'DNI ingresado ya existe.';
            $data['status'] = FALSE;
        }
        if ($request->nombre == '') {
            $data['inputerror'][] = 'nombre';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->apellido1 == '') {
            $data['inputerror'][] = 'apellido1';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->apellido2 == '') {
            $data['inputerror'][] = 'apellido2';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->cargo == '') {
            $data['inputerror'][] = 'cargo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->sexo == '') {
            $data['inputerror'][] = 'sexo';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->entidadgerencia == '') {
            $data['inputerror'][] = 'entidadgerencia';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        $usuarioemail = Usuario::where('email', $request->email)->first();
        if ($request->email == '') {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if (filter_var($request->email, FILTER_VALIDATE_EMAIL) == '') {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Correo electronico incorrecto.';
            $data['status'] = FALSE;
        }

        $usuarioyy = Usuario::where('usuario', $request->usuario)->first();
        if ($request->usuario == '') {
            $data['inputerror'][] = 'usuario';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if ($usuarioyy && $request->id == '') {
            $data['inputerror'][] = 'usuario';
            $data['error_string'][] = 'USUARIO ingresado ya existe.';
            $data['status'] = FALSE;
        } else if ($usuarioyy && $request->id != $usuarioyy->id) {
            $data['inputerror'][] = 'usuario';
            $data['error_string'][] = 'USUARIO ingresado ya existe.';
            $data['status'] = FALSE;
        }

        if ($request->id == '') {
            if ($request->password == '') {
                $data['inputerror'][] = 'password';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            } else if (strlen($request->password) < 8) {
                $data['inputerror'][] = 'password';
                $data['error_string'][] = 'Password necesita un minimo de 8 digitos.';
                $data['status'] = FALSE;
            }
            /* if ($request->password2 == '') {
                $data['inputerror'][] = 'password2';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            }
            if ($request->password != '' && $request->password2 != '') {
                if ($request->password != $request->password2) {
                    $data['inputerror'][] = 'password2';
                    $data['error_string'][] = 'Confirmar Password es distinto.';
                    $data['status'] = FALSE;
                }
            } */
        } else {
            if ($request->password != '' /* || $request->password2 != '' */) {
                if (strlen($request->password) < 8) {
                    $data['inputerror'][] = 'password';
                    $data['error_string'][] = 'Password necesita un minimo de 8 digitos.';
                    $data['status'] = FALSE;
                }
                /* if ($request->password != $request->password2) {
                    $data['inputerror'][] = 'password2';
                    $data['error_string'][] = 'Confirmar Password distinto.';
                    $data['status'] = FALSE;
                } */
            }
        }
        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    public function ajax_add(Request $request)
    {
        $this->_validate($request);

        $usuario = Usuario::Create([
            'usuario' => $request->usuario,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dni' => $request->dni,
            'nombre' => $request->nombre,
            'apellido1' => $request->apellido1,
            'apellido2' => $request->apellido2,
            'sexo' => $request->sexo,
            'celular' => $request->celular,
            'tipo' => $request->tipo,
            'cargo' => $request->cargo,
            'entidad' => $request->entidadgerencia,
            'estado' => '1',
        ]);

        $auditoria = UsuarioAuditoria::Create([
            'usuario_id' => $usuario->id,
            'accion' => 'CREADO',
            'datos_anteriores' => null,
            'datos_nuevos' => $usuario,
            'usuario_responsable' => auth()->user()->id,
        ]);

        return response()->json(array('status' => true, 'auditoria' => $auditoria));
    }

    public function ajax_update(Request $request)
    {
        $this->_validate($request);

        $usuario = Usuario::find($request->id);
        $usuairo_anterior = $usuario->getOriginal();
        $usuario->usuario = $request->usuario;
        $usuario->email = $request->email;
        $usuario->dni = $request->dni;
        $usuario->nombre = $request->nombre;
        $usuario->apellido1 = $request->apellido1;
        $usuario->apellido2 = $request->apellido2;
        $usuario->sexo = $request->sexo;
        $usuario->celular = $request->celular;
        $usuario->cargo = $request->cargo;
        $usuario->entidad = $request->entidadgerencia;
        if ($request->password != '')
            $usuario->password = Hash::make($request->password);
        $usuario_modificado = $usuario->getDirty();
        $usuario->save();

        $auditoria = UsuarioAuditoria::Create([
            'usuario_id' => $usuario->id,
            'accion' => 'MODIFICADO',
            'datos_anteriores' => $usuairo_anterior,
            'datos_nuevos' => $usuario_modificado,
            'usuario_responsable' => auth()->user()->id,
        ]);

        return response()->json(array('status' => true));
    }

    private function _validate2($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $usuarioxx = Usuario::where('dni', $request->dnip)->first();

        if ($request->dnip == '') {
            $data['inputerror'][] = 'dnip';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if (strlen($request->dnip) < 8) {
            $data['inputerror'][] = 'dnip';
            $data['error_string'][] = 'Este campo necesita 8 digitos.';
            $data['status'] = FALSE;
        } else if ($usuarioxx && $request->idp == '') {
            $data['inputerror'][] = 'dnip';
            $data['error_string'][] = 'DNI ingresado ya existe.';
            $data['status'] = FALSE;
        }

        if ($request->nombrep == '') {
            $data['inputerror'][] = 'nombrep';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->apellido1p == '') {
            $data['inputerror'][] = 'apellido1p';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->apellido2p == '') {
            $data['inputerror'][] = 'apellido2p';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        if ($request->sexop == '') {
            $data['inputerror'][] = 'sexop';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }
        /* if ($request->entidadoficinap == '') {
            $data['inputerror'][] = 'entidadoficinap';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } */
        $usuarioemail = Usuario::where('email', $request->emailp)->first();
        if ($request->emailp == '') {
            $data['inputerror'][] = 'emailp';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if (filter_var($request->emailp, FILTER_VALIDATE_EMAIL) == '') {
            $data['inputerror'][] = 'emailp';
            $data['error_string'][] = 'Correo electronico incorrecto.';
            $data['status'] = FALSE;
        }
        $usuarioyy = Usuario::where('usuario', $request->usuariop)->first();
        if ($request->usuariop == '') {
            $data['inputerror'][] = 'usuariop';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if ($usuarioyy && $request->idp == '') {
            $data['inputerror'][] = 'usuariop';
            $data['error_string'][] = 'USUARIO ingresado ya existe.';
            $data['status'] = FALSE;
        } else if ($usuarioyy && $request->idp != $usuarioyy->id) {
            $data['inputerror'][] = 'usuariop';
            $data['error_string'][] = 'USUARIO ingresado ya existe.';
            $data['status'] = FALSE;
        }

        if ($request->idp == '') {
            if ($request->passwordp == '') {
                $data['inputerror'][] = 'passwordp';
                $data['error_string'][] = 'Este campo es obligatorio.';
                $data['status'] = FALSE;
            } else if (strlen($request->passwordp) < 8) {
                $data['inputerror'][] = 'passwordp';
                $data['error_string'][] = 'Password necesita un minimo de 8 digitos.';
                $data['status'] = FALSE;
            }
        } else {
            if ($request->passwordp != '') {
                if (strlen($request->passwordp) < 8) {
                    $data['inputerror'][] = 'passwordp';
                    $data['error_string'][] = 'Password necesita un minimo de 8 digitos.';
                    $data['status'] = FALSE;
                }
            }
        }
        return $data;
    }

    public function ajax_updateaux(Request $request)
    {
        $val = $this->_validate2($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $usuario = Usuario::find($request->idp);
        $usuario->usuario = $request->usuariop;
        $usuario->email = $request->emailp;
        $usuario->dni = $request->dnip;
        $usuario->nombre = $request->nombrep;
        $usuario->apellido1 = $request->apellido1p;
        $usuario->apellido2 = $request->apellido2p;
        $usuario->sexo = $request->sexop;
        $usuario->celular = $request->celularp;
        $usuario->cargo = $request->cargop;
        $usuario->entidad = $request->oficinap;
        if ($request->password != '')
            $usuario->password = Hash::make($request->password);
        $usuario->save();

        return response()->json(array('status' => true/* , 'update' => $request->sistemas */));
    }

    private function _validatepassword($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        /* $usu = Usuario::find($request->cid);
        if ($request->cpassword == '') {
            $data['inputerror'][] = 'cpassword';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if (strlen($request->cpassword) < 8) {
            $data['inputerror'][] = 'cpassword';
            $data['error_string'][] = 'Password necesita un minimo de 8 digitos.';
            $data['status'] = FALSE;
        } else if (Hash::make($request->cpassword) != $usu->password) {
            $data['inputerror'][] = 'cpassword';
            $data['error_string'][] = 'Password Anterior Incorrecto.';
            $data['status'] = FALSE;
        } */
        if ($request->cpassword2 == '') {
            $data['inputerror'][] = 'cpassword2';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        } else if (strlen($request->cpassword2) < 8) {
            $data['inputerror'][] = 'cpassword2';
            $data['error_string'][] = 'Password necesita un minimo de 8 digitos.';
            $data['status'] = FALSE;
        }
        return $data;
    }

    public function ajax_updatepassword(Request $request)
    {
        $val = $this->_validatepassword($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $usuario = Usuario::find($request->cid);
        if ($request->cpassword2 != '')
            $usuario->password = Hash::make($request->cpassword2);
        $usuario->save();
        return response()->json(array('status' => true/* , 'update' => $request->sistemas */));
    }
    public function ajax_edit($usuario_id)
    {
        $usuario = Usuario::find($usuario_id);
        $entidad = Entidad::select(
            'adm_entidad.id as oficina',
            'adm_entidad.nombre as oficinan',
            'ee.id as entidad',
            'ee.nombre as entidadn',
            'ee.codigo',
            'te.id as tipo',
            'ss.id as sector',
        )
            ->join('adm_entidad as ee', 'ee.id', '=', 'adm_entidad.dependencia')
            ->join('adm_tipo_entidad as te', 'te.id', '=', 'ee.tipoentidad_id')
            ->join('pres_sector as ss', 'ss.id', '=', 'te.sector_id')
            ->where('adm_entidad.id', $usuario->entidad)
            ->first();
        return response()->json(compact('usuario', 'entidad'));
    }

    public function ajax_edit_basico($usuario_id)
    {
        $usuario = Usuario::select('id', 'usuario', 'email', 'dni', 'nombre', 'apellido1', 'apellido2', 'sexo', 'celular', 'estado')
            ->find($usuario_id);
        return response()->json(compact('usuario'));
    }

    public function ajax_delete($usuario_id) //elimina deverdad *o*
    {
        UsuarioPerfil::where('usuario_id', $usuario_id)->delete();
        $usuario = Usuario::find($usuario_id);
        $usuairo_anterior = $usuario->getOriginal();
        $usuario->delete();

        $auditoria = UsuarioAuditoria::Create([
            'usuario_id' => $usuairo_anterior['id'],
            'accion' => 'ELIMINADO',
            'datos_anteriores' => $usuairo_anterior,
            'datos_nuevos' => null,
            'usuario_responsable' => auth()->user()->id,
        ]);
        return response()->json(array('status' => true, 'usuario' => $usuario));
    }

    public function ajax_estadoUsuario($usuario_id)
    {
        $usuario = Usuario::find($usuario_id);
        $usuairo_anterior = $usuario->getOriginal();
        $usuario->estado = $usuario->estado == 1 ? 0 : 1;
        $usuario_modificado = $usuario->getDirty();
        $usuario->save();

        $auditoria = UsuarioAuditoria::Create([
            'usuario_id' => $usuario->id,
            'accion' => 'MODIFICADO',
            'datos_anteriores' => $usuairo_anterior,
            'datos_nuevos' => $usuario_modificado,
            'usuario_responsable' => auth()->user()->id,
        ]);
        return response()->json(array('status' => true, 'estado' => $usuario->estado));
    }

    public function cargarsectorx()
    {
        $sector = Sector::whereIn('id', [1, 2, 4, 14, 18, 22])->get();
        return response()->json(compact('sector'));
    }

    public function cargartipoentidadx()
    {
        $sector = Sector::whereIn('id', [1, 2, 4, 14, 18, 22])->get();
        return response()->json(compact('sector'));
    }
    /* public function cargarEntidad($tipogobierno_id)
    {
        $unidadejecutadora = UnidadEjecutora::where('tipogobierno', $tipogobierno_id)->get();
        return response()->json(compact('unidadejecutadora'));
    } */

    /* private function _validateentidad($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->gerencia == '') {
            $data['inputerror'][] = 'entidad';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        return $data;
    } */

    /* public function ajax_add_entidad(Request $request)
    {
        $val = $this->_validateentidad($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }

        $UnidadEjecutora = UnidadEjecutora::Create([
            'codigo' => $request->codigo,
            'tipogobierno' => $request->tipogobierno,
            'unidad_ejecutora' => $request->entidad,
            'abreviatura' => $request->abreviado,
        ]);

        return response()->json(array('status' => true, 'codigo' => $UnidadEjecutora->id));
    } */

    /* public function cargarGerencia($entidad_id)
    {
        $gerencias = Entidad::where('unidadejecutadora_id', $entidad_id)->where('dependencia')->get();
        return response()->json(compact('gerencias'));
    } */

    /* private function _validategerencia($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->gerencia == '') {
            $data['inputerror'][] = 'gerencia';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        return $data;
    } */

    /* public function ajax_add_gerencia(Request $request)
    {
        $val = $this->_validategerencia($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }

        $entidad = Entidad::Create([
            'entidad' => $request->gerencia,
            'unidadejecutadora_id' => $request->unidadejecutadora_id,
            'abreviado' => $request->abreviado,
            'estado' => 1,
        ]);

        return response()->json(array('status' => true, 'codigo' => $entidad->id));
    } */

    /* public function cargarOficina($entidad_id)
    {
        $oficinas = Entidad::where('dependencia', $entidad_id)->get();
        return response()->json(compact('oficinas'));
    } */

    /* private function _validateoficina($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($request->oficina == '') {
            $data['inputerror'][] = 'oficina';
            $data['error_string'][] = 'Este campo es obligatorio.';
            $data['status'] = FALSE;
        }

        return $data;
    } */

    /* public function ajax_add_oficina(Request $request)
    {
        $val = $this->_validateoficina($request);
        if ($val['status'] === FALSE) {
            return response()->json($val);
        }
        $gerencia = Entidad::where('id', $request->entidadgerencia_id)->first();
        $entidad = Entidad::Create([
            'entidad' => $request->oficina,
            'abreviado' => $request->abreviado,
            'unidadejecutadora_id' => $gerencia->unidadejecutadora_id,
            'dependencia' => $request->entidadgerencia_id,
            'estado' => 1,
        ]);

        return response()->json(array('status' => true, 'codigo' => $entidad->id));
    } */

    // public function vertical($usuari_id)
    // {
    //     $usu = Usuario::find($usuari_id);
    //     $usu->layouts = 'VERTICAL';
    //     $usu->save();

    //     return Redirect::back();
    // }

    // public function horizontal($usuari_id)
    // {
    //     $usu = Usuario::find($usuari_id);
    //     $usu->layouts = 'HORIZONTAL';
    //     $usu->save();

    //     return Redirect::back();
    // }
}
