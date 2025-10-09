<?php
$servername = "localhost";
$username = "root";
$password = "Axelchivas1607";
$dbname = "marketzone_fix";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tope = isset($_GET['tope']) ? intval($_GET['tope']) : 1000;
$sql = "SELECT * FROM productos WHERE unidades <= $tope AND eliminado = 0";

$result = $conn->query($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Productos Vigentes - MarketZone</title>
    <style type="text/css">
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .imagen-tabla { max-width: 100px; max-height: 100px; }
        .info-tope { background-color: #e8f4fd; padding: 10px; margin-bottom: 20px; }
        .vigente { color: green; font-weight: bold; }
        .sin-imagen { color: #666; font-style: italic; }
    </style>
</head>
<body>
    <h1>Productos Vigentes - MarketZone</h1>
    
    <div class="info-tope">
        <strong>Filtro aplicado:</strong> Productos con <?php echo $tope; ?> unidades o menos <span class="vigente">(Solo productos NO eliminados)</span>
    </div>

    <?php
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Nombre</th>';
        echo '<th>Descripción</th>';
        echo '<th>Precio</th>';
        echo '<th>Unidades</th>';
        echo '<th>Estado</th>';
        echo '<th>Imagen</th>';
        echo '</tr>';
        
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["id"] . '</td>';
            echo '<td>' . htmlspecialchars($row["nombre"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["descripcion"]) . '</td>';
            echo '<td>$' . number_format($row["precio"], 2) . '</td>';
            echo '<td>' . $row["unidades"] . '</td>';
            echo '<td class="vigente">VIGENTE</td>';
            echo '<td>';
            
            if (!empty($row["imagen"])) {
                $ruta_imagen = $row["imagen"];
                
                // CORRECCIÓN DE RUTAS - OPCIÓN 2
                // Si la imagen tiene 'img/' al inicio, apuntar a p07
                if (strpos($ruta_imagen, 'img/') === 0) {
                    $ruta_imagen = '../p07/' . $ruta_imagen;
                }
                // Si no tiene 'img/' pero es solo el nombre del archivo
                elseif (strpos($ruta_imagen, '/') === false) {
                    $ruta_imagen = '../p07/img/' . $ruta_imagen;
                }
                
                // Verificar si la imagen existe antes de mostrarla
                $ruta_completa = $_SERVER['DOCUMENT_ROOT'] . '/tecweb/practicas/p08/' . $ruta_imagen;
                
                echo '<img class="imagen-tabla" src="' . htmlspecialchars($ruta_imagen) . '" alt="' . htmlspecialchars($row["nombre"]) . '" ';
                echo 'onerror="this.style.display=\'none\'; this.nextSibling.style.display=\'inline\'" />';
                echo '<span class="sin-imagen" style="display:none">Imagen no encontrada</span>';
                
            } else {
                echo '<span class="sin-imagen">Sin imagen</span>';
            }
            
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
        
        echo '<p><strong>Total de productos VIGENTES encontrados:</strong> ' . $result->num_rows . '</p>';
        
    } else {
        echo '<p>No se encontraron productos VIGENTES con ' . $tope . ' unidades o menos.</p>';
    }

    $conn->close();
    ?>
    
    <br>
    <p>
        <a href="formulario_productos.html">Registrar nuevo producto</a> | 
        <a href="get_productos_vigentes.php?tope=10">Ver productos con 10 unidades o menos</a> | 
        <a href="get_productos_vigentes.php?tope=1000">Ver todos los productos vigentes</a>
    </p>
</body>
</html>