<?php

namespace App\Http\Controllers\Educacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class MefApiController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Muestra la vista principal para consultar la API del MEF
   */
  public function index()
  {
    return view('educacion.MefApi.Index');
  }

  /**
   * Consulta la API del MEF con SQL personalizado o URL directa
   */
  public function consultar(Request $request)
  {
    $sql = $request->input('sql');

    if (empty($sql)) {
      return response()->json(['error' => 'Debe proporcionar una consulta SQL o URL'], 400);
    }

    try {
      // Detectar si es una URL completa de la API
      if (strpos($sql, 'https://api.datosabiertos.mef.gob.pe') === 0) {
        $response = Http::timeout(60)->get($sql);
      } else {
        // Es una consulta SQL
        $url = 'https://api.datosabiertos.mef.gob.pe/DatosAbiertos/v1/datastore_search_sql';
        $response = Http::timeout(60)->get($url, ['sql' => $sql]);
      }

      $data = $response->json();

      // Verificar si hay error en la respuesta
      if (isset($data['error'])) {
        return response()->json([
          'success' => false,
          'error' => 'Error en la consulta',
          'message' => isset($data['error']['message']) ? $data['error']['message'] : json_encode($data['error'])
        ], 400);
      }

      // Validar si la respuesta fue exitosa y tiene datos (result o records)
      if ($response->successful() && (isset($data['result']) || isset($data['records']))) {
        return response()->json($data);
      } else {
        return response()->json([
          'success' => false,
          'error' => 'Error al consultar la API',
          'status' => $response->status(),
          'message' => $response->body()
        ], $response->status());
      }
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => 'Error al procesar la solicitud',
        'message' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Exporta los datos de la API a Excel
   */
  public function exportarExcel(Request $request)
  {
    $sql = $request->input('sql');

    if (empty($sql)) {
      return back()->with('error', 'Debe proporcionar una consulta SQL o URL');
    }

    try {
      // Detectar si es una URL completa de la API
      if (strpos($sql, 'https://api.datosabiertos.mef.gob.pe') === 0) {
        $response = Http::timeout(60)->get($sql);
      } else {
        // Es una consulta SQL
        $url = 'https://api.datosabiertos.mef.gob.pe/DatosAbiertos/v1/datastore_search_sql';
        $response = Http::timeout(60)->get($url, ['sql' => $sql]);
      }

      if (!$response->successful()) {
        return back()->with('error', 'Error al consultar la API: ' . $response->status());
      }

      $data = $response->json();

      $records = [];
      if (isset($data['result']['records']) && !empty($data['result']['records'])) {
        $records = $data['result']['records'];
      } elseif (isset($data['records']) && !empty($data['records'])) {
        $records = $data['records'];
      }

      if (empty($records)) {
        return back()->with('error', 'No se encontraron datos para exportar');
      }

      // Crear el archivo Excel
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      // Obtener los encabezados (nombres de columnas)
      $headers = array_keys($records[0]);
      $col = 'A';
      foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', strtoupper($header));
        $sheet->getStyle($col . '1')->getFont()->setBold(true);
        $sheet->getColumnDimension($col)->setAutoSize(true);
        $col++;
      }

      // Llenar los datos
      $row = 2;
      foreach ($records as $record) {
        $col = 'A';
        foreach ($record as $value) {
          $sheet->setCellValue($col . $row, $value);
          $col++;
        }
        $row++;
      }

      // Ajustar el ancho de las columnas (Ya se hizo en el bucle de encabezados)
      // foreach (range('A', $col) as $columnID) {
      //   $sheet->getColumnDimension($columnID)->setAutoSize(true);
      // }

      // Generar el archivo
      $writer = new Xlsx($spreadsheet);
      $fileName = 'datos_mef_' . date('Y-m-d_His') . '.xlsx';
      $tempFile = tempnam(sys_get_temp_dir(), $fileName);
      $writer->save($tempFile);

      return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    } catch (\Exception $e) {
      return back()->with('error', 'Error al generar el archivo Excel: ' . $e->getMessage());
    }
  }

  /**
   * Obtiene datos de locales educativos beneficiarios
   */
  public function localesBeneficiarios(Request $request)
  {
    $anio = $request->input('anio', date('Y'));
    $limit = $request->input('limit', 100);

    // Consulta SQL para obtener locales educativos beneficiarios
    $sql = "SELECT * FROM \"locales-educativos-beneficiarios\" WHERE \"ANO_EJE\" = '{$anio}' LIMIT {$limit}";

    try {
      $url = 'https://api.datosabiertos.mef.gob.pe/DatosAbiertos/v1/datastore_search_sql';

      $response = Http::timeout(60)->get($url, [
        'sql' => $sql
      ]);

      if ($response->successful()) {
        return response()->json($response->json());
      } else {
        return response()->json([
          'error' => 'Error al consultar la API',
          'status' => $response->status()
        ], $response->status());
      }
    } catch (\Exception $e) {
      return response()->json([
        'error' => 'Error al procesar la solicitud',
        'message' => $e->getMessage()
      ], 500);
    }
  }
}
