<?php

namespace App\Http\Controllers\Presupuesto;

use App\Http\Controllers\Controller;


class EntidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

}
