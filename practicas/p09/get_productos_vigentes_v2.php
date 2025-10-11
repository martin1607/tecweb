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
    <title>Productos Vigentes - MarketZone V2</title>
    <style type="text/css">
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .imagen-tabla { max-width: 100px; }
        .info-tope { background-color: #e8f4fd; padding: 10px; margin-bottom: 20px; }
        .btn-editar { background: #007bff; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; font-size: 12px; }
        .btn-editar:hover { background: #0056b3; }
        .vigente { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Productos Vigentes - MarketZone V2</h1>
    
    <div class="info-tope">
        <strong>Filtro aplicado:</strong> Productos con <?php echo $tope; ?> unidades o menos 
        <span class="vigente">(Solo productos NO eliminados)</span>
        <br><small><strong>NUEVO:</strong> Ahora puedes editar productos vigentes</small>
    </div>

    <?php
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Nombre</th>';
        echo '<th>Marca</th>';
        echo '<th>Modelo</th>';
        echo '<th>Descripción</th>';
        echo '<th>Precio</th>';
        echo '<th>Unidades</th>';
        echo '<th>Estado</th>';
        echo '<th>Imagen</th>';
        echo '<th>Acción</th>';
        echo '</tr>';
        
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["id"] . '</td>';
            echo '<td>' . htmlspecialchars($row["nombre"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["marca"] ?? 'N/A') . '</td>';
            echo '<td>' . htmlspecialchars($row["modelo"] ?? 'N/A') . '</td>';
            echo '<td>' . htmlspecialchars($row["descripcion"]) . '</td>';
            echo '<td>$' . number_format($row["precio"], 2) . '</td>';
            echo '<td>' . $row["unidades"] . '</td>';
            echo '<td class="vigente">VIGENTE</td>';
            echo '<td>';
            
            if (!empty($row["imagen"])) {
                $ruta_imagen = $row["imagen"];
                if (strpos($ruta_imagen, 'img/') === 0) {
                    $ruta_imagen = '../p07/' . $ruta_imagen;
                }
                echo '<img class="imagen-tabla" src="' . htmlspecialchars($ruta_imagen) . '" alt="' . htmlspecialchars($row["nombre"]) . '" />';
            } else {
                echo 'Sin imagen';
            }
            
            echo '</td>';
            // BOTÓN EDITAR
            echo '<td>';
            echo '<a href="formulario_productos_v2.php?id=' . $row["id"] . 
                  '&nombre=' . urlencode($row["nombre"]) . 
                  '&marca=' . urlencode($row["marca"] ?? '') . 
                  '&modelo=' . urlencode($row["modelo"] ?? '') . 
                  '&descripcion=' . urlencode($row["descripcion"]) . 
                  '&precio=' . $row["precio"] . 
                  '&unidades=' . $row["unidades"] . 
                  '&imagen=' . urlencode($row["imagen"]) . '" class="btn-editar">✏️ Editar</a>';
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
        <a href="get_productos_vigentes_v2.php?tope=10">Ver productos con 10 unidades o menos</a> | 
        <a href="get_productos_vigentes_v2.php?tope=1000">Ver todos los productos vigentes</a> |
        <a href="formulario_productos.html">➕ Agregar nuevo producto</a>
    </p>
</body>
</html>