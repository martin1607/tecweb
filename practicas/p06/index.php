<?php
// mostrar errores en pantalla mientras depuramos (quita en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Práctica 6 - Test</title>
</head>
<body>
<h1>Práctica 6 - Pruebas</h1>

<?php
// include con ruta segura
require_once __DIR__ . '/src/funciones.php';

// Ejercicio 1 (GET)
echo "<h2>Ejercicio 1 (GET)</h2>";
if (isset($_GET['numero'])) {
    echo "<p>" . htmlspecialchars(isMultipleOf5And7($_GET['numero']) ? "Es múltiplo" : "No es múltiplo") . "</p>";
} else {
    echo '<p>Prueba con: ?numero=35</p>';
}

// Ejercicio 2
echo "<h2>Ejercicio 2</h2>";
$matrix = null; $iters = 0;
generate_until_odd_even_odd($matrix, $iters);
echo "<p>Iteraciones: $iters — Números generados: " . ($iters*3) . "</p>";
echo "<table border='1'><tr><th>fila</th><th>n1</th><th>n2</th><th>n3</th></tr>";
foreach ($matrix as $i => $row) {
    echo "<tr><td>".($i+1)."</td><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></tr>";
}
echo "</table>";

// Ejercicio 3 (GET target)
echo "<h2>Ejercicio 3 (GET target)</h2>";
if (isset($_GET['target'])) {
    $valueW = null; $triesW = 0;
    find_first_multiple_while($_GET['target'], $valueW, $triesW);
    $valueD = null; $triesD = 0;
    find_first_multiple_dowhile($_GET['target'], $valueD, $triesD);
    echo "<p>while: $valueW en $triesW intentos — do-while: $valueD en $triesD intentos</p>";
} else {
    echo "<p>Prueba con ?target=7</p>";
}

// Ejercicio 4
echo "<h2>Ejercicio 4</h2>";
$arr = ascii_array();
echo "<table border='1'><tr><th>código</th><th>letra</th></tr>";
foreach ($arr as $c => $ch) {
    echo "<tr><td>$c</td><td>$ch</td></tr>";
}
echo "</table>";

// Ejercicio 5 (POST)
echo "<h2>Ejercicio 5 (POST)</h2>";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form']) && $_POST['form'] === 'ej5') {
    $edad = isset($_POST['edad']) ? (int)$_POST['edad'] : 0;
    $sexo = isset($_POST['sexo']) ? $_POST['sexo'] : '';
    $res = check_age_sex($edad, $sexo);
    echo "<p>" . htmlspecialchars($res['msg']) . "</p>";
}
echo '<form method="post"><input type="hidden" name="form" value="ej5" />Edad: <input type="number" name="edad" /> Sexo: <select name="sexo"><option value="f">F</option><option value="m">M</option></select> <button type="submit">Enviar</button></form>';

// Ejercicio 6
echo "<h2>Ejercicio 6</h2>";
$registry = parqueVehicular();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form']) && $_POST['form'] === 'ej6') {
    $action = $_POST['action'] ?? '';
    if ($action === 'buscar') {
        $plate = strtoupper(trim($_POST['plate'] ?? ''));
        $result = search_vehicle($registry, $plate);
        if ($result !== null) {
            echo "<h3>Resultado:</h3><pre>" . print_r($result, true) . "</pre>";
        } else {
            echo "<p>No se encontró la matrícula $plate</p>";
        }
    } elseif ($action === 'todos') {
        echo "<h3>Todos los autos:</h3><pre>" . print_r($registry, true) . "</pre>";
    }
}
echo '<form method="post"><input type="hidden" name="form" value="ej6" />Matrícula: <input type="text" name="plate" /> <button name="action" value="buscar" type="submit">Buscar</button> <button name="action" value="todos" type="submit">Mostrar todos</button></form>';
?>
</body>
</html>
