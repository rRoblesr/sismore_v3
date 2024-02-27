<?php

namespace App\Repositories\Educacion;

use App\Models\Educacion\Importacion;
use App\Models\Educacion\Matricula;
use App\Models\Educacion\MatriculaAnual;
use Illuminate\Support\Facades\DB;

class ImporRERRepositorio
{

    public static function listaImportada($id)
    {
        $query = DB::table(DB::raw("(
            select
                v1.id,
                v5.nombre dre,
                v4.nombre ugel,
                'UCAYALI' departamento,
                v8.nombre provincia,
                v7.nombre distrito,
                v6.nombre centropoblado,
                v3.codModular modular,
                v3.nombreInstEduc iiee,
                v9.codigo codnivel,
                v9.nombre nivel,
                v9.tipo tiponivel,
                v10.codigo codgestion,
                v10.nombre gestiondependencia,
                v11.codigo codtipogestion,
                v11.nombre tipogestion,
                v1.total_estudiantes,
                v1.matricula_definitiva,
                v1.matricula_proceso,
                v1.dni_validado,
                v1.dni_sin_validar,
                v1.registrado_sin_dni,
                v1.total_grados,
                v1.total_secciones,
                v1.nominas_generadas,
                v1.nominas_aprobadas,
                v1.nominas_por_rectificar,
                v1.tres_anios_hombre,
                v1.tres_anios_mujer,
                v1.cuatro_anios_hombre,
                v1.cuatro_anios_mujer,
                v1.cinco_anios_hombre,
                v1.cinco_anios_mujer,
                v1.primero_hombre,
                v1.primero_mujer,
                v1.segundo_hombre,
                v1.segundo_mujer,
                v1.tercero_hombre,
                v1.tercero_mujer,
                v1.cuarto_hombre,
                v1.cuarto_mujer,
                v1.quinto_hombre,
                v1.quinto_mujer,
                v1.sexto_hombre,
                v1.sexto_mujer,
                v1.cero_anios_hombre,
                v1.cero_anios_mujer,
                v1.un_anio_hombre,
                v1.un_anio_mujer,
                v1.dos_anios_hombre,
                v1.dos_anios_mujer,
                v1.mas_cinco_anios_hombre,
                v1.mas_cinco_anios_mujer,
                v1.total_hombres,
                v1.total_mujeres
            from edu_matricula_detalle v1
            inner join edu_matricula v2 on v2.id=v1.matricula_id
            inner join edu_institucioneducativa v3 on v3.id=v1.institucioneducativa_id
            inner join edu_ugel v4 on v4.id=v3.Ugel_id
            inner join edu_ugel v5 on v5.id=v4.dependencia
            inner join edu_centropoblado v6 on v6.id=v3.CentroPoblado_id
            inner join par_ubigeo v7 on v7.id=v6.Ubigeo_id
            inner join par_ubigeo v8 on v8.id=v7.dependencia
            inner join edu_nivelmodalidad v9 on v9.id=v3.NivelModalidad_id
            inner join edu_tipogestion v10 on v10.id=v3.TipoGestion_id
            inner join edu_tipogestion v11 on v11.id=v10.dependencia
            where v2.importacion_id=$id
        ) astb"))->get();
        return $query;
    }

    public static function count_matriculados($matricula)
    {
        $query = DB::table('edu_matricula_detalle as v1')
            ->where('v1.matricula_id', $matricula)
            ->select(DB::raw('SUM(IF((v1.total_hombres+v1.total_mujeres)=0,v1.total_estudiantes,v1.total_hombres+v1.total_mujeres)) as conteo'))
            ->first();
        return $query->conteo;
    }

}
