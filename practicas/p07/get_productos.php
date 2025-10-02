<?php
$servername = "localhost";
$username = "root";
$password = "Axelchivas1607";
$dbname = "marketzone";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tope = isset($_GET['tope']) ? intval($_GET['tope']) : 1000;
$sql = "SELECT * FROM productos WHERE unidades <= $tope";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Productos - MarketZone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .producto { border: 1px solid #ddd; padding: 15px; margin: 10px 0; }
        .imagen { max-width: 150px; }
        .info-tope { background-color: #e8f4fd; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Productos - MarketZone</h1>
    
    <div class="info-tope">
        <strong>Filtro aplicado:</strong> Productos con <?php echo $tope; ?> unidades o menos
    </div>

    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="producto">';
            echo '<h3>' . htmlspecialchars($row["nombre"]) . ' (ID: ' . $row["id"] . ')</h3>';
            echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($row["descripcion"]) . '</p>';
            echo '<p><strong>Precio:</strong> $' . number_format($row["precio"], 2) . '</p>';
            echo '<p><strong>Unidades:</strong> ' . $row["unidades"] . '</p>';
            
            if (!empty($row["imagen"])) {
                $ruta_completa = 'img/' . $row["imagen"];
                echo '<img class="imagen" src="' . htmlspecialchars($ruta_completa) . '" alt="' . htmlspecialchars($row["nombre"]) . '" />';
            } else {
                echo '<p>Sin imagen</p>';
            }
            
            echo '</div>';
        }
        
        echo '<p><strong>Total de productos encontrados:</strong> ' . $result->num_rows . '</p>';
        
    } else {
        echo '<p>No se encontraron productos con ' . $tope . ' unidades o menos.</p>';
    }

    $conn->close();
    ?>
</body>
</html>