<?php
header('Content-Type: application/json');

$search = isset($_GET['search']) ? strtolower($_GET['search']) : '';

// DATOS DE PRUEBA - ESTOS SIEMPRE FUNCIONAN
$productos_prueba = array(
    array(
        'id' => 1,
        'nombre' => 'Laptop HP Pavilion',
        'marca' => 'HP',
        'modelo' => 'Pavilion 15',
        'precio' => 15000.00,
        'detalles' => 'Laptop con 8GB RAM, 512GB SSD',
        'unidades' => 10,
        'imagen' => 'img/laptop-hp.jpg'
    ),
    array(
        'id' => 2,
        'nombre' => 'Mouse Inalámbrico Logitech',
        'marca' => 'Logitech', 
        'modelo' => 'M185',
        'precio' => 250.50,
        'detalles' => 'Mouse ergonómico inalámbrico',
        'unidades' => 25,
        'imagen' => 'img/mouse-logitech.jpg'
    ),
    array(
        'id' => 3,
        'nombre' => 'Teclado Mecánico Redragon',
        'marca' => 'Redragon',
        'modelo' => 'Kumara',
        'precio' => 800.00,
        'detalles' => 'Teclado mecánico RGB',
        'unidades' => 15,
        'imagen' => 'img/teclado-redragon.jpg'
    ),
    array(
        'id' => 4,
        'nombre' => 'Monitor Samsung 24"',
        'marca' => 'Samsung',
        'modelo' => 'S24F350',
        'precio' => 3200.00,
        'detalles' => 'Monitor LED 24 pulgadas',
        'unidades' => 8,
        'imagen' => 'img/monitor-samsung.jpg'
    ),
    array(
        'id' => 5,
        'nombre' => 'Audífonos Sony Bluetooth',
        'marca' => 'Sony',
        'modelo' => 'WH-CH510',
        'precio' => 1200.00,
        'detalles' => 'Audífonos inalámbricos con cancelación de ruido',
        'unidades' => 20,
        'imagen' => 'img/audifonos-sony.jpg'
    )
);

$resultados = array();

if (!empty($search)) {
    foreach ($productos_prueba as $producto) {
        // Buscar en todos los campos (convertir a string para el ID)
        if (strpos(strval($producto['id']), $search) !== false ||
            strpos(strtolower($producto['nombre']), $search) !== false ||
            strpos(strtolower($producto['marca']), $search) !== false ||
            strpos(strtolower($producto['modelo']), $search) !== false ||
            strpos(strtolower($producto['detalles']), $search) !== false) {
            $resultados[] = $producto;
        }
    }
}

echo json_encode($resultados);
?>