<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto Deportivo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .form-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input, select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 12px 25px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin: 5px; }
        button:hover { background: #0056b3; }
        .error { color: #d9534f; font-size: 12px; margin-top: 5px; display: none; }
        .btn-cancelar { background: #6c757d; }
        .btn-cancelar:hover { background: #545b62; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>🏆 <?php echo isset($_GET['id']) ? 'Editar' : 'Registrar'; ?> Producto Deportivo</h1>
        
        <?php
        // Recibir datos del producto a editar (si existen)
        $id = $_GET['id'] ?? '';
        $nombre = $_GET['nombre'] ?? '';
        $marca = $_GET['marca'] ?? '';
        $modelo = $_GET['modelo'] ?? '';
        $descripcion = $_GET['descripcion'] ?? '';
        $precio = $_GET['precio'] ?? '';
        $unidades = $_GET['unidades'] ?? '';
        $imagen = $_GET['imagen'] ?? 'img/producto_default.jpg';
        ?>

        <form id="formProducto" action="update_producto.php" method="post" onsubmit="return validarFormulario()">
            <!-- Campo oculto para el ID -->
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            
            <!-- NOMBRE -->
            <div class="form-group">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" id="nombre" name="nombre" maxlength="100" value="<?php echo htmlspecialchars($nombre); ?>">
                <div class="error" id="errorNombre"></div>
            </div>
            
            <!-- MARCA -->
            <div class="form-group">
                <label for="marca">Marca:</label>
                <select id="marca" name="marca">
                    <option value="">Selecciona una marca</option>
                    <option value="Nike" <?php echo ($marca == 'Nike') ? 'selected' : ''; ?>>Nike</option>
                    <option value="Adidas" <?php echo ($marca == 'Adidas') ? 'selected' : ''; ?>>Adidas</option>
                    <option value="Puma" <?php echo ($marca == 'Puma') ? 'selected' : ''; ?>>Puma</option>
                    <option value="Under Armour" <?php echo ($marca == 'Under Armour') ? 'selected' : ''; ?>>Under Armour</option>
                    <option value="Reebok" <?php echo ($marca == 'Reebok') ? 'selected' : ''; ?>>Reebok</option>
                    <option value="New Balance" <?php echo ($marca == 'New Balance') ? 'selected' : ''; ?>>New Balance</option>
                    <option value="Wilson" <?php echo ($marca == 'Wilson') ? 'selected' : ''; ?>>Wilson</option>
                    <option value="Spalding" <?php echo ($marca == 'Spalding') ? 'selected' : ''; ?>>Spalding</option>
                    <option value="Otra" <?php echo ($marca == 'Otra') ? 'selected' : ''; ?>>Otra</option>
                </select>
                <div class="error" id="errorMarca"></div>
            </div>
            
            <!-- MODELO -->
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" id="modelo" name="modelo" maxlength="25" value="<?php echo htmlspecialchars($modelo); ?>" placeholder="Ej: Air Max 90, Ultraboost">
                <div class="error" id="errorModelo"></div>
            </div>
            
            <!-- PRECIO -->
            <div class="form-group">
                <label for="precio">Precio ($):</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" value="<?php echo htmlspecialchars($precio); ?>" placeholder="100.00 o más">
                <div class="error" id="errorPrecio"></div>
            </div>
            
            <!-- DETALLES -->
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="3" maxlength="250" placeholder="Descripción del producto deportivo..."><?php echo htmlspecialchars($descripcion); ?></textarea>
                <div class="error" id="errorDescripcion"></div>
            </div>
            
            <!-- UNIDADES -->
            <div class="form-group">
                <label for="unidades">Unidades en Stock:</label>
                <input type="number" id="unidades" name="unidades" min="0" value="<?php echo htmlspecialchars($unidades); ?>">
                <div class="error" id="errorUnidades"></div>
            </div>
            
            <!-- IMAGEN -->
            <div class="form-group">
                <label for="imagen">Imagen (opcional):</label>
                <input type="text" id="imagen" name="imagen" value="<?php echo htmlspecialchars($imagen); ?>" placeholder="ruta/imagen.jpg">
                <small>Si no se especifica, se usará: img/producto_default.jpg</small>
                <div class="error" id="errorImagen"></div>
            </div>
            
            <div>
                <button type="submit">💾 <?php echo isset($_GET['id']) ? 'Actualizar' : 'Registrar'; ?> Producto</button>
                <a href="get_productos_xhtml_v2.php" class="btn-cancelar" style="text-decoration: none; padding: 12px 25px; display: inline-block;">❌ Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        function validarFormulario() {
            let isValid = true;
            
            // Limpiar errores anteriores
            document.querySelectorAll('.error').forEach(error => {
                error.style.display = 'none';
                error.textContent = '';
            });
            
            // Validar NOMBRE
            const nombre = document.getElementById('nombre').value.trim();
            if (nombre === '') {
                mostrarError('errorNombre', 'El nombre del producto es requerido');
                isValid = false;
            } else if (nombre.length > 100) {
                mostrarError('errorNombre', 'El nombre no puede tener más de 100 caracteres');
                isValid = false;
            }
            
            // Validar MARCA
            const marca = document.getElementById('marca').value;
            if (marca === '') {
                mostrarError('errorMarca', 'Debes seleccionar una marca');
                isValid = false;
            }
            
            // Validar MODELO
            const modelo = document.getElementById('modelo').value.trim();
            if (modelo === '') {
                mostrarError('errorModelo', 'El modelo es requerido');
                isValid = false;
            } else if (modelo.length > 25) {
                mostrarError('errorModelo', 'El modelo no puede tener más de 25 caracteres');
                isValid = false;
            } else if (!/^[a-zA-Z0-9\s\-]+$/.test(modelo)) {
                mostrarError('errorModelo', 'El modelo solo puede contener letras, números, espacios y guiones');
                isValid = false;
            }
            
            // Validar PRECIO
            const precio = parseFloat(document.getElementById('precio').value);
            if (isNaN(precio)) {
                mostrarError('errorPrecio', 'El precio es requerido');
                isValid = false;
            } else if (precio <= 99.99) {
                mostrarError('errorPrecio', 'El precio debe ser mayor a $99.99');
                isValid = false;
            }
            
            // Validar UNIDADES
            const unidades = parseInt(document.getElementById('unidades').value);
            if (isNaN(unidades) || unidades < 0) {
                mostrarError('errorUnidades', 'Las unidades deben ser un número mayor o igual a 0');
                isValid = false;
            }
            
            // Asignar imagen por defecto si está vacía
            const imagenInput = document.getElementById('imagen');
            if (imagenInput.value.trim() === '') {
                imagenInput.value = 'img/producto_default.jpg';
            }
            
            return isValid;
        }
        
        function mostrarError(elementId, mensaje) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = mensaje;
            errorElement.style.display = 'block';
        }
    </script>
</body>
</html>