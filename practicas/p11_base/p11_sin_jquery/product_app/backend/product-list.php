<?php
header('Content-Type: application/json');

// DATOS DE PRUEBA - MISMO QUE EN PRODUCT-SEARCH
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
    )
);

echo json_encode($productos_prueba);
?>