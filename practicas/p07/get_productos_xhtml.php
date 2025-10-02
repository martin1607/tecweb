<?php
$servername = "localhost";
$username = "root";
$password = "Axelchivas1607";
$dbname = "marketzone";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tope = isset($_GET['tope']) ? intval($_GET['tope']) : 0;
$sql = "SELECT * FROM productos WHERE unidades <= $tope";
$result = $conn->query($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Productos - MarketZone</title>
    <style type="text/css">
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .imagen-tabla { max-width: 100px; }
        .info-tope { background-color: #e8f4fd; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Productos en Stock - MarketZone</h1>
    
    <div class="info-tope">
        <strong>Filtro aplicado:</strong> Productos con <?php echo $tope; ?> unidades o menos
    </div>

    <?php
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Nombre</th>';
        echo '<th>Descripcion</th>';
        echo '<th>Precio</th>';
        echo '<th>Unidades</th>';
        echo '<th>Imagen</th>';
        echo '</tr>';
        
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["id"] . '</td>';
            echo '<td>' . htmlspecialchars($row["nombre"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["descripcion"]) . '</td>';
            echo '<td>$' . number_format($row["precio"], 2) . '</td>';
            echo '<td>' . $row["unidades"] . '</td>';
            echo '<td>';
            
            if (!empty($row["imagen"])) {
                // AGREGAR 'img/' antes del nombre de la imagen
                $ruta_completa = 'img/' . $row["imagen"];
                echo '<img class="imagen-tabla" src="' . htmlspecialchars($ruta_completa) . '" alt="' . htmlspecialchars($row["nombre"]) . '" />';
            } else {
                echo 'Sin imagen';
            }
            
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
        
        echo '<p><strong>Total de productos encontrados:</strong> ' . $result->num_rows . '</p>';
        
    } else {
        echo '<p>No se encontraron productos con ' . $tope . ' unidades o menos.</p>';
    }

    $conn->close();
    ?>
</body>
</html>