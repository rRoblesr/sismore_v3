<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class LogController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs');
        $logFiles = [];

        // Obtener todos los archivos .log
        $files = File::files($logPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'log') {
                $logFiles[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'last_modified' => date('Y-m-d H:i:s', $file->getCTime()),
                    'path' => $file->getPathname()
                ];
            }
        }

        // Ordenar por fecha de modificación (más reciente primero)
        usort($logFiles, function ($a, $b) {
            return strtotime($b['last_modified']) - strtotime($a['last_modified']);
        });

        return view('logs.index', compact('logFiles'));
    }

    public function showx($filename)
    {
        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            return redirect()->route('logs.index')->with('error', 'Archivo de log no encontrado.');
        }

        // Validar que sea un archivo .log
        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'log') {
            return redirect()->route('logs.index')->with('error', 'Tipo de archivo no válido.');
        }

        $content = File::get($logPath);
        $lines = explode("\n", $content);
        $lines = array_reverse($lines); // Más recientes primero

        return view('logs.show', compact('filename', 'lines'));
    }

    public function show($filename)
    {
        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            return redirect()->route('logs.index')->with('error', 'Archivo de log no encontrado.');
        }

        // Validar que sea un archivo .log
        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'log') {
            return redirect()->route('logs.index')->with('error', 'Tipo de archivo no válido.');
        }

        $content = File::get($logPath);
        $lines = explode("\n", $content);

        // Procesar líneas y organizar por fecha/hora
        $organizedLogs = [];
        $currentDate = '';
        $currentHour = '';

        foreach (array_reverse($lines) as $line) {
            if (!empty(trim($line))) {
                // Extraer fecha y hora del log (formato Laravel típico)
                $dateTime = $this->extractDateTime($line);
                $logLevel = $this->extractLogLevel($line);

                if ($dateTime) {
                    $date = $dateTime->format('Y-m-d');
                    $hour = $dateTime->format('H:00'); // Agrupar por hora

                    if ($date !== $currentDate) {
                        $currentDate = $date;
                        $currentHour = '';
                    }

                    if ($hour !== $currentHour) {
                        $currentHour = $hour;
                    }

                    if (!isset($organizedLogs[$date])) {
                        $organizedLogs[$date] = [];
                    }

                    if (!isset($organizedLogs[$date][$currentHour])) {
                        $organizedLogs[$date][$currentHour] = [];
                    }

                    $organizedLogs[$date][$currentHour][] = [
                        'time' => $dateTime->format('H:i:s'),
                        'level' => $logLevel,
                        'message' => $line,
                        'full_time' => $dateTime->format('Y-m-d H:i:s')
                    ];
                } else {
                    // Si no se puede extraer fecha/hora, agregar a "Sin fecha"
                    if (!isset($organizedLogs['sin_fecha'])) {
                        $organizedLogs['sin_fecha'] = [];
                    }
                    if (!isset($organizedLogs['sin_fecha']['sin_hora'])) {
                        $organizedLogs['sin_fecha']['sin_hora'] = [];
                    }

                    $organizedLogs['sin_fecha']['sin_hora'][] = [
                        'time' => '--:--:--',
                        'level' => 'unknown',
                        'message' => $line,
                        'full_time' => ''
                    ];
                }
            }
        }

        return view('logs.show', compact('filename', 'organizedLogs'));
    }

    private function extractDateTime($line)
    {
        // Patrones comunes de fecha/hora en logs Laravel
        $patterns = [
            '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})]/', // [2024-01-15 10:30:25]
            '/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/',    // 2024-01-15 10:30:25
            '/^\[(\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2})]/', // [15-01-2024 10:30:25]
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line, $matches)) {
                try {
                    return \Carbon\Carbon::createFromFormat(
                        str_contains($matches[1], '-') ? 'Y-m-d H:i:s' : 'd-m-Y H:i:s',
                        $matches[1]
                    );
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return null;
    }

    private function extractLogLevel($line)
    {
        $levels = [
            'EMERGENCY' => 'emergency',
            'ALERT' => 'alert',
            'CRITICAL' => 'critical',
            'ERROR' => 'error',
            'WARNING' => 'warning',
            'NOTICE' => 'notice',
            'INFO' => 'info',
            'DEBUG' => 'debug'
        ];

        foreach ($levels as $level => $class) {
            if (stripos($line, $level) !== false) {
                return $class;
            }
        }

        return 'unknown';
    }
    //-->
    public function destroy($filename)
    {
        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            return redirect()->route('logs.index')->with('error', 'Archivo de log no encontrado.');
        }

        // Validar que sea un archivo .log
        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'log') {
            return redirect()->route('logs.index')->with('error', 'Tipo de archivo no válido.');
        }

        try {
            File::delete($logPath);
            return redirect()->route('logs.index')->with('success', 'Log eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('logs.index')->with('error', 'Error al eliminar el log: ' . $e->getMessage());
        }
    }

    public function clear($filename)
    {
        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            return redirect()->route('logs.index')->with('error', 'Archivo de log no encontrado.');
        }

        // Validar que sea un archivo .log
        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'log') {
            return redirect()->route('logs.index')->with('error', 'Tipo de archivo no válido.');
        }

        try {
            File::put($logPath, '');
            return redirect()->route('logs.index')->with('success', 'Log vaciado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('logs.index')->with('error', 'Error al vaciar el log: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            return redirect()->route('logs.index')->with('error', 'Archivo de log no encontrado.');
        }

        // Validar que sea un archivo .log
        if (pathinfo($filename, PATHINFO_EXTENSION) !== 'log') {
            return redirect()->route('logs.index')->with('error', 'Tipo de archivo no válido.');
        }

        return Response::download($logPath);
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $size > 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . ' ' . $units[$i];
    }

    // Método para contar entradas (usado en la vista)
    private function countEntries($hours)
    {
        $count = 0;
        foreach ($hours as $entries) {
            $count += count($entries);
        }
        return $count;
    }

    // Método para clases de badges
    private function getLevelBadgeClass($level)
    {
        $classes = [
            'emergency' => 'emergency',
            'alert' => 'alert',
            'critical' => 'critical',
            'error' => 'error',
            'warning' => 'warning',
            'notice' => 'notice',
            'info' => 'info',
            'debug' => 'debug',
            'unknown' => 'unknown'
        ];

        return $classes[$level] ?? 'secondary';
    }
}
