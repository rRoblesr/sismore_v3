<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

use App\Http\Controllers\Controller;
use App\Models\Administracion\Menu;

class PowerBiController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
    }

    public function trabajoPlanillaElectronica()
    {
        return view('PowerBi.Trabajo.PlantillaElectronica');
    }

    public function trabajoEmpleoFormal()
    {
        return view('PowerBi.Trabajo.EmpleoFormal');
    }

    public function trabajoEmpleoInformal()
    {
        return view('PowerBi.Trabajo.EmpleoInformal');
    }

    public function saludCovid19()
    {
        return view('PowerBi.Salud.Covid19');
    }

    public function saludMenu($id)
    {
        $link = Menu::find($id)->link;
        return view('PowerBi.Salud.Menu', compact('link'));
    }

    public function verReporteAmigable($sistema, $nombre_menu, $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            abort(404, 'Reporte no encontrado');
        }

        $link = $menu->link;

        // Retornamos la vista correspondiente según el sistema
        // Convertimos a minúsculas para evitar problemas de mayúsculas/minúsculas
        $sistema = strtolower($sistema);

        if ($sistema == 'trabajo') {
            // Puedes crear una vista genérica para Trabajo si no existe,
            // o reutilizar la de Salud si solo cambia el iframe.
            // Por ahora usaré una vista genérica basada en la lógica de Salud
            return view('PowerBi.Salud.Menu', compact('link', 'sistema', 'nombre_menu'));
        }
        
        if ($sistema == 'salud') {
            return view('PowerBi.Salud.Menu', compact('link', 'sistema', 'nombre_menu'));
        }

        // Default: Reutilizamos la vista de Salud que ya es dinámica
        return view('PowerBi.Salud.Menu', compact('link', 'sistema', 'nombre_menu'));
    }
}
