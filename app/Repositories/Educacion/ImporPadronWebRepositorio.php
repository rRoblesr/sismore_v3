<?php

namespace App\Repositories\Educacion;
use App\Models\Educacion\ImporPadronWeb;

class ImporPadronWebRepositorio
{
    public static function Listar_Por_Importacion_id($importacion_id)
    {         
        $padronWebLista = ImporPadronWeb::select('id','cod_Mod','anexo','cod_Local','cen_Edu','niv_Mod','d_Niv_Mod',
        'd_Forma','cod_Car','d_Cod_Car','TipsSexo','d_TipsSexo','gestion','d_Gestion','ges_Dep','d_Ges_Dep',
                'director','telefono','email','pagWeb','dir_Cen','referencia','localidad','codcp_Inei',
                'codccpp','cen_Pob','area_Censo','d_areaCenso','codGeo','d_Dpto','d_Prov','d_Dist',
                'd_Region','codOOII','d_DreUgel','nLat_IE','nLong_IE','tipoProg','d_TipoProg','cod_Tur',
                'D_Cod_Tur','estado','d_Estado','d_Fte_Dato','tAlum_Hom','tAlum_Muj','tAlumno','tDocente',
                'tSeccion','fechaReg','fecha_Act')
        ->where("importacion_id", "=", $importacion_id)
        ->get();

        return $padronWebLista;
    }   
}