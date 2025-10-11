<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado del Registro - Tienda Deportiva</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; }
        .success { color: #28a745; background: #d4edda; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb; }
        .error { color: #721c24; background: #f8d7da; padding: 15px; border-radius: 5px; border: 1px solid #f5c6cb; }
        .product-info { background: #e9ecef; padding: 15px; border-radius: 5px; margin-top: 15px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏆 Resultado del Registro - Tienda Deportiva</h1>
        
        <?php
        // Recibir datos del formulario
        $nombre = $_POST['nombre'] ?? '';
        $marca = $_POST['marca'] ?? '';
        $modelo = $_POST['modelo'] ?? '';
        $precio = $_POST['precio'] ?? 0;
        $detalles = $_POST['detalles'] ?? '';
        $unidades = $_POST['unidades'] ?? 0;
        $imagen = $_POST['imagen'] ?? 'img/producto_default.jpg';

        // Validar que todos los campos requeridos estén llenos
        if (empty($nombre) || empty($marca) || empty($modelo) || empty($precio) || empty($unidades)) {
            echo '<div class="error">Error: Todos los campos requeridos deben estar llenos.</div>';
            echo '<br><a href="formulario_productos.html" class="btn">← Volver al formulario</a>';
            exit;
        }

        /** CONEXIÓN A LA BASE DE DATOS */
        @$link = new mysqli('localhost', 'root', 'Axelchivas1607', 'marketzone_fix');

        // Verificar conexión
        if ($link->connect_errno) {
            echo '<div class="error">Falló la conexión: ' . $link->connect_error . '</div>';
            echo '<br><a href="formulario_productos.html" class="btn">← Volver al formulario</a>';
            exit;
        }

        /** VALIDAR SI EL PRODUCTO YA EXISTE */
        $sql_check = "SELECT id FROM productos WHERE nombre = ?";
        $stmt_check = $link->prepare($sql_check);
        
        if ($stmt_check === false) {
            echo '<div class="error">Error en consulta de validación: ' . $link->error . '</div>';
            $link->close();
            echo '<br><a href="formulario_productos.html" class="btn">← Volver al formulario</a>';
            exit;
        }
        
        $stmt_check->bind_param("s", $nombre);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            echo '<div class="error">Error: El producto "' . htmlspecialchars($nombre) . '" ya existe en la base de datos.</div>';
            $stmt_check->close();
            $link->close();
            echo '<br><a href="formulario_productos.html" class="btn">← Volver al formulario</a>';
            exit;
        }
        $stmt_check->close();

        /** INSERTAR NUEVO PRODUCTO - CON MARCA Y MODELO */
        $sql_insert = "INSERT INTO productos (nombre, marca, modelo, descripcion, precio, unidades, imagen, eliminado) VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt_insert = $link->prepare($sql_insert);
        
        // Verificar si prepare() tuvo éxito
        if ($stmt_insert === false) {
            echo '<div class="error">Error en la consulta INSERT: ' . $link->error . '</div>';
            $link->close();
            echo '<br><a href="formulario_productos.html" class="btn">← Volver al formulario</a>';
            exit;
        }

        $stmt_insert->bind_param("ssssdis", $nombre, $marca, $modelo, $detalles, $precio, $unidades, $imagen);

        if ($stmt_insert->execute()) {
            echo '<div class="success">✅ Producto deportivo insertado correctamente</div>';
            echo '<div class="product-info">';
            echo '<h3>📋 Resumen del Producto:</h3>';
            echo '<p><strong>ID:</strong> ' . $link->insert_id . '</p>';
            echo '<p><strong>Nombre:</strong> ' . htmlspecialchars($nombre) . '</p>';
            echo '<p><strong>Marca:</strong> ' . htmlspecialchars($marca) . '</p>';
            echo '<p><strong>Modelo:</strong> ' . htmlspecialchars($modelo) . '</p>';
            echo '<p><strong>Precio:</strong> $' . number_format($precio, 2) . '</p>';
            echo '<p><strong>Unidades:</strong> ' . $unidades . '</p>';
            echo '<p><strong>Detalles:</strong> ' . htmlspecialchars($detalles) . '</p>';
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
        <div>
            <a href="formulario_productos.html" class="btn">➕ Registrar otro producto</a>
            <a href="../p08/get_productos_vigentes.php" class="btn">📦 Ver productos vigentes</a>
        </div>
    </div>
</body>
</html>