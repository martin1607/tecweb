<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado del Registro</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; background: #d4edda; padding: 15px; border-radius: 5px; }
        .error { color: #721c24; background: #f8d7da; padding: 15px; border-radius: 5px; }
        .product-info { background: #e9ecef; padding: 15px; border-radius: 5px; margin-top: 15px; }
    </style>
</head>
<body>
    <h1>Resultado del Registro</h1>
    
    <?php
    // Recibir datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $unidades = $_POST['unidades'] ?? 0;
    $imagen = $_POST['imagen'] ?? '';

    // Validar que todos los campos estén llenos
    if (empty($nombre) || empty($descripcion) || empty($precio) || empty($unidades) || empty($imagen)) {
        echo '<div class="error">Error: Todos los campos son obligatorios.</div>';
        exit;
    }

    /** CONEXIÓN A LA BASE DE DATOS */
    @$link = new mysqli('localhost', 'root', 'Axelchivas1607', 'marketzone_fix');

    // Verificar conexión
    if ($link->connect_errno) {
        echo '<div class="error">Falló la conexión: ' . $link->connect_error . '</div>';
        exit;
    }

    /** VALIDAR SI EL PRODUCTO YA EXISTE */
    $sql_check = "SELECT id FROM productos WHERE nombre = ?";
    $stmt_check = $link->prepare($sql_check);
    
    if ($stmt_check === false) {
        echo '<div class="error">Error en consulta de validación: ' . $link->error . '</div>';
        $link->close();
        exit;
    }
    
    $stmt_check->bind_param("s", $nombre);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo '<div class="error">Error: El producto "' . $nombre . '" ya existe en la base de datos.</div>';
        $stmt_check->close();
        $link->close();
        exit;
    }
    $stmt_check->close();

    /** INSERTAR NUEVO PRODUCTO */
    $sql_insert = "INSERT INTO productos (nombre, descripcion, precio, unidades, imagen, eliminado) VALUES (?, ?, ?, ?, ?, 0)";
    $stmt_insert = $link->prepare($sql_insert);
    
    // Verificar si prepare() tuvo éxito
    if ($stmt_insert === false) {
        echo '<div class="error">Error en la consulta INSERT: ' . $link->error . '</div>';
        $link->close();
        exit;
    }

    $stmt_insert->bind_param("ssdis", $nombre, $descripcion, $precio, $unidades, $imagen);

    if ($stmt_insert->execute()) {
        echo '<div class="success">✅ Producto insertado correctamente</div>';
        echo '<div class="product-info">';
        echo '<h3>Resumen del Producto:</h3>';
        echo '<p><strong>ID:</strong> ' . $link->insert_id . '</p>';
        echo '<p><strong>Nombre:</strong> ' . htmlspecialchars($nombre) . '</p>';
        echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($descripcion) . '</p>';
        echo '<p><strong>Precio:</strong> $' . number_format($precio, 2) . '</p>';
        echo '<p><strong>Unidades:</strong> ' . $unidades . '</p>';
        echo '<p><strong>Imagen:</strong> ' . htmlspecialchars($imagen) . '</p>';
        echo '<p><strong>Estado:</strong> No eliminado (0)</p>';
        echo '</div>';
    } else {
        echo '<div class="error">Error al insertar el producto: ' . $link->error . '</div>';
    }

    // Cerrar conexiones
    $stmt_insert->close();
    $link->close();
    ?>
    
    <br>
    <a href="formulario_productos.html">← Registrar otro producto</a>
</body>
</html>