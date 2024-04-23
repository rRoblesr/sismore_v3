<?php
namespace App\Exports\Salud;

use App\Models\Salud\PadronCalidad;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SaludPadronDatosExport implements FromCollection, WithHeadings
{ protected $filtro1;
  protected $campo2;
  protected $filtro2;

  public function __construct($filtro1, $campo2, $filtro2)
  {
      $this->filtro1 = $filtro1;
      $this->campo2 = $campo2;
      $this->filtro2 = $filtro2;
  }

  public function collection()
  { $query = PadronCalidad::where('codigo_calidad', $this->filtro1)
                            ->where($this->campo2, $this->filtro2);
    //$sql = $query->toSql();
    //dd($this->filtro2);

    return $query->get();
  }

  public function headings(): array
  {
      return [
        'id' => 'id',
        'cod_padron' => 'Codigo de Padron',
        'cnv' => 'CNV',
        'dni_nino' => 'DNI Niño(a)',
        'paterno_nino' => 'PATERNO',
        'materno_nino' => 'MATERNO',
        'nombre_nino' => 'NOMBRE',
        'fecha_nacimiento' => 'Fec. Nacimiento',
        'edad' => 'Edad',
        'ubigeo' => 'Cod. Ubigeo',
        'direccion' => 'Direccion',
        'distrito' => 'Distrito',
        'tipo_seguro' => 'Tipo de Seguro',
        'cod_eess_atencion' => 'Cod. RENIPRESS',
        'nom_eess_atencion' => 'EESS ATencion',
        'dni_madre' => 'DNI de Madre',
        'celular' => 'Celular',
        'codigo_calidad' => 'Cod. Calidad',
        'nombre_calidad' => 'Nom. Calidad',
        'descripcion_calidad' => 'Descripcion de Calidad',
      ];
  }
}
