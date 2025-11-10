<?php
// test-connection.php
header('Content-Type: text/plain');

echo "=== TEST DE CONEXIÓN ===\n";

$conexion = @mysqli_connect(
    'localhost',
    'root', 
    'Axelchivas1607',
    'marketzone_fix'
);

if(!$conexion) {
    echo "ERROR: No se pudo conectar a la base de datos\n";
    echo "Error: " . mysqli_connect_error() . "\n";
} else {
    echo "✓ Conexión exitosa a la base de datos\n";
    
    // Verificar si la tabla productos existe
    $result = $conexion->query("SHOW TABLES LIKE 'productos'");
    if($result->num_rows > 0) {
        echo "✓ Tabla 'productos' existe\n";
        
        // Verificar estructura de la tabla
        $result2 = $conexion->query("DESCRIBE productos");
        echo "✓ Estructura de la tabla:\n";
        while($row = $result2->fetch_assoc()) {
            echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
        $result2->free();
        
        // Contar productos
        $result3 = $conexion->query("SELECT COUNT(*) as total FROM productos WHERE eliminado = 0");
        $row = $result3->fetch_assoc();
        echo "✓ Productos activos: " . $row['total'] . "\n";
        $result3->free();
        
    } else {
        echo "✗ ERROR: La tabla 'productos' NO existe\n";
    }
    
    $result->free();
    $conexion->close();
}
?>