<?php

namespace App\Http\Controllers\Trabajo;


use App\Http\Controllers\Controller;
use App\Models\Parametro\Anio;
use App\Repositories\Trabajo\PeaRepositorio;
use App\Repositories\Trabajo\ProEmpleoRepositorio;

class IndicadorTrabajoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function importar()
    {
        $dataPea = PeaRepositorio:: lista_PEA_segunTipo('PEA');

        $dataPea_IPM = PeaRepositorio:: lista_PEA_segunTipo('IPM');

        // return $datos;
        return view('trabajo.Indicadores.principal',compact('dataPea','dataPea_IPM'));
    }

    public function Grafico_PEA($anio_id)
    {
        $Masculino = [];
        $Femenino = [];
        $anios = [];
      
        $dataPea = PeaRepositorio:: lista_PEA_segunTipo('PEA');

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($dataPea as $key => $lista) {
            $Masculino = array_merge($Masculino,[intval ($lista->Masculino)]);  
            $Femenino = array_merge($Femenino,[intval ($lista->Femenino)]); 
            $anios = array_merge($anios,[intval ($lista->anio)]);
        } 

        $puntos[] = [ 'name'=> 'Masculina' ,'data'=>  $Masculino];
        $puntos[] = [ 'name'=> 'Femenina' ,'data'=>  $Femenino];
      
        $titulo = 'POBLACION ECONOMICAMENTE ACTIVA - PEA';
        $subTitulo = 'Fuente: INEI - Encuesta Nacional de Hogares sobre Condiciones de Vida y Pobreza';
        $titulo_y = 'Resultado';

        $nombreGraficoLineal = 'Grafico_PEA'; // este nombre va de la mano con el nombre del DIV en la vista

        return view('graficos.Lineal', ["dataLineal" => json_encode($puntos),"categoria_nombres" => json_encode($anios)],
        compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoLineal'));
    }

    public function Grafico_PEA_IPM($anio_id)
    {
        $Masculino = [];
        $Femenino = [];
        $anios = [];
      
        $dataPea = PeaRepositorio:: lista_PEA_segunTipo('IPM');

        // array_merge concatena los valores del arreglo, mientras recorre el foreach
        foreach ($dataPea as $key => $lista) {
            $Masculino = array_merge($Masculino,[intval ($lista->Masculino)]);  
            $Femenino = array_merge($Femenino,[intval ($lista->Femenino)]); 
            $anios = array_merge($anios,[intval ($lista->anio)]);
        } 

        $puntos[] = [ 'name'=> 'Masculina' ,'data'=>  $Masculino];
        $puntos[] = [ 'name'=> 'Femenina' ,'data'=>  $Femenino];
      
        $titulo = 'PEA - INGRESO PROMEDIO MENSUAL';
        $subTitulo = 'Fuente: INEI - Encuesta Nacional de Hogares sobre Condiciones de Vida y Pobreza';
        $titulo_y = 'Monto S/';

        $nombreGraficoLineal = 'Grafico_PEA_IPM'; // este nombre va de la mano con el nombre del DIV en la vista

        return view('graficos.Lineal', ["dataLineal" => json_encode($puntos),"categoria_nombres" => json_encode($anios)],
        compact('titulo_y', 'titulo', 'subTitulo', 'nombreGraficoLineal'));
    }

    
}
