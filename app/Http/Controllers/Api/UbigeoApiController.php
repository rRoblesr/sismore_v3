<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parametro\Ubigeo;
use Illuminate\Http\Request;

class UbigeoApiController extends Controller
{
    public function buscarPorCodigo($codigo)
    {
        // Buscamos el ubigeo cargando sus padres (Provincia -> Departamento)
        $ubigeo = Ubigeo::with(['padre.padre'])->where('codigo', $codigo)->first();

        if (!$ubigeo) {
            return response()->json([
                'mensaje' => 'Ubigeo no encontrado',
                'codigo' => $codigo
            ], 404);
        }

        // Mapeamos la respuesta según el formato SQL solicitado
        // Asumimos que el ubigeo consultado es el nivel más bajo (Distrito)
        // Estructura: Departamento -> Provincia -> Distrito (Ubigeo consultado)

        $respuesta = [
            'cod_departamento' => null,
            'departamento'     => null,
            'cod_provincia'    => null,
            'provincia'        => null,
            'ubigeo'           => $ubigeo->codigo,
            'distrito'         => $ubigeo->nombre,
        ];

        // Lógica para llenar los padres
        if ($ubigeo->padre) {
            // El padre inmediato es la Provincia
            $respuesta['cod_provincia'] = $ubigeo->padre->codigo;
            $respuesta['provincia']     = $ubigeo->padre->nombre;

            if ($ubigeo->padre->padre) {
                // El padre del padre es el Departamento
                $respuesta['cod_departamento'] = $ubigeo->padre->padre->codigo;
                $respuesta['departamento']     = $ubigeo->padre->padre->nombre;
            }
        }

        return response()->json($respuesta, 200);
    }

    public function listarHijos($codigoPadre = null)
    {
        // CASO 1: Listar Departamentos (Cuando no se envía código o es '00')
        if (!$codigoPadre || $codigoPadre == '00') {
            // Buscamos departamentos: aquellos cuya dependencia es NULL o 0
            // O alternativamente, aquellos cuyo código termina en '0000'
            $resultados = Ubigeo::whereRaw('RIGHT(codigo, 4) = "0000"')
                ->select('codigo', 'nombre')
                ->orderBy('nombre')
                ->get();

            // Si no encuentra nada con la lógica '0000', intentamos buscar por dependencia nula
            if ($resultados->isEmpty()) {
                $resultados = Ubigeo::whereNull('dependencia')
                    ->orWhere('dependencia', 0)
                    ->select('codigo', 'nombre')
                    ->orderBy('nombre')
                    ->get();
            }

            $resultados->transform(function ($item) {
                $item->codigo_navegacion = substr($item->codigo, 0, 2);
                return $item;
            });

            return response()->json($resultados);
        }

        // CASO 2: Listar Provincias (Cuando envían 2 dígitos, ej: '25')
        if (strlen($codigoPadre) == 2) {
            // Buscamos Provincias: Empiezan con '25', terminan en '00', y NO son '250000'
            $resultados = Ubigeo::where('codigo', 'like', $codigoPadre . '%')
                ->whereRaw('RIGHT(codigo, 2) = "00"')
                ->whereRaw('RIGHT(codigo, 4) != "0000"')
                ->select('codigo', 'nombre')
                ->orderBy('nombre')
                ->get();
            
            $resultados->transform(function ($item) {
                $item->codigo_navegacion = substr($item->codigo, 0, 4);
                return $item;
            });

            return response()->json($resultados);
        }

        // CASO 3: Listar Distritos (Cuando envían 4 dígitos, ej: '2501')
        if (strlen($codigoPadre) == 4) {
            // Buscamos Distritos: Empiezan con '2501' y NO terminan en '00'
            $resultados = Ubigeo::where('codigo', 'like', $codigoPadre . '%')
                 ->whereRaw('RIGHT(codigo, 2) != "00"')
                ->select('codigo', 'nombre')
                ->orderBy('nombre')
                ->get();
            
             $resultados->transform(function ($item) {
                $item->codigo_navegacion = $item->codigo;
                return $item;
            });

            return response()->json($resultados);
        }

        return response()->json(['mensaje' => 'Formato de código no válido'], 400);
    }
}
