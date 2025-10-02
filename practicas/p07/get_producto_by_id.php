<?php
$servername = "localhost";
$username = "root";
$password = "Axelchivas1607";
$dbname = "marketzone";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM productos WHERE id = $id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Producto por ID - MarketZone</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .producto { border: 1px solid #ddd; padding: 20px; margin: 20px 0; }
        .imagen { max-width: 200px; }
    </style>
</head>
<body>
    <h1>Producto por ID - MarketZone</h1>
    
    <?php
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo '<div class="producto">';
        echo '<h2>' . htmlspecialchars($row["nombre"]) . '</h2>';
        echo '<p><strong>ID:</strong> ' . $row["id"] . '</p>';
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
    } else {
        echo '<p>No se encontró el producto con ID: ' . $id . '</p>';
    }

    $conn->close();
    ?>
    
    <p><a href="get_productos_xhtml.php">Ver todos los productos</a></p>
</body>
</html>