<?php
$hostname = 'localhost';
$usuario  = 'root';
$clave    = '';
$base     = 'bdsismore';
// Crear conexión
$conexion = new mysqli($hostname, $usuario, $clave, $base);

// Verificar si hubo error en la conexión
if ($conexion->connect_error) {
    die("<h2>❌ Error de conexión:</h2> " . htmlspecialchars($conexion->connect_error));
}

// Establecer charset UTF-8
$conexion->set_charset("utf8");

// Consulta para obtener todas las tablas de la base de datos actual
$sql = "SHOW TABLES";
$resultado = $conexion->query($sql);

echo "<h2>📋 Tablas en la base de datos: <code>" . htmlspecialchars($base) . "</code></h2>";

if ($resultado && $resultado->num_rows > 0) {
    echo "<ul style='font-family: monospace;'>";
    while ($fila = $resultado->fetch_row()) {
        // $fila[0] contiene el nombre de la tabla
        echo "<li>• " . htmlspecialchars($fila[0]) . "</li>";
    }
    echo "</ul>";
    echo "<p><strong>Total de tablas:</strong> " . $resultado->num_rows . "</p>";
} else {
    echo "<p>⚠️ No se encontraron tablas en la base de datos.</p>";
}

// Cerrar conexión
$conexion->close();
?>