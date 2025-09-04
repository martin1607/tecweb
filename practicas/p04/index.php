<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Práctica 4 - Variables PHP</title>
</head>
<body>
    <!-- Ejercicio 1 -->
    <h2>Ejercicio 1</h2>
    <p>Determina cuál de las siguientes variables son válidas y explica por qué:</p>
    <p>$_myvar,  $_7var,  myvar,  $myvar,  $var7,  $_element1, $house*5</p>
    <?php
        $_myvar;
        $_7var;
        //myvar;       // Inválida
        $myvar;
        $var7;
        $_element1;
        //$house*5;     // Invalida
        
        echo '<h4>Respuesta:</h4>';   
        echo '<ul>';
        echo '<li>$_myvar es válida porque inicia con guión bajo.</li>';
        echo '<li>$_7var es válida porque inicia con guión bajo.</li>';
        echo '<li>myvar es inválida porque no tiene el signo de dolar ($).</li>';
        echo '<li>$myvar es válida porque inicia con una letra.</li>';
        echo '<li>$var7 es válida porque inicia con una letra.</li>';
        echo '<li>$_element1 es válida porque inicia con guión bajo.</li>';
        echo '<li>$house*5 es inválida porque el símbolo * no está permitido.</li>';
        echo '</ul>';
    ?>

    <!-- Ejercicio 2 -->
    <h2>Ejercicio 2</h2>
    <p>Proporcionar los valores de $a, $b, $c y sus cambios:</p>
    <?php
        $a = "ManejadorSQL";
        $b = 'MySQL';
        $c = &$a;

        echo "<p>Valores iniciales:</p>";
        echo "<ul>";
        echo "<li>a = $a</li>";
        echo "<li>b = $b</li>";
        echo "<li>c = $c</li>";
        echo "</ul>";

        $a = "PHP server";
        $b = &$a;

        echo "<p>Después de las nuevas asignaciones:</p>";
        echo "<ul>";
        echo "<li>a = $a</li>";
        echo "<li>b = $b</li>";
        echo "<li>c = $c</li>";
        echo "</ul>";
    ?>

    <!-- Ejercicio 3 -->
    <h2>Ejercicio 3</h2>
    <?php
        $a = "PHP5";
        $z[] = &$a;
        $b = "5a version de PHP";
        $c = $b * 10;
        $a .= $b;
        $b *= $c;
        $z[0] = "MySQL";

        echo "<pre>";
        echo "a: "; var_dump($a);
        echo "b: "; var_dump($b);
        echo "c: "; var_dump($c);
        echo "z: "; print_r($z);
        echo "</pre>";
    ?>

    <!-- Ejercicio 4 -->
    <h2>Ejercicio 4</h2>
    <?php
        $GLOBALS['x'] = $a;
        $GLOBALS['y'] = $b;
        $GLOBALS['z'] = $c;

        echo "<pre>";
        echo "x: "; var_dump($GLOBALS['x']);
        echo "y: "; var_dump($GLOBALS['y']);
        echo "z: "; var_dump($GLOBALS['z']);
        echo "</pre>";
    ?>

    <!-- Ejercicio 5 -->
    <h2>Ejercicio 5</h2>
    <?php
        $a = "7 personas";
        $b = (integer) $a;
        $a = "9E3";
        $c = (double) $a;

        echo "<ul>";
        echo "<li>a = $a</li>";
        echo "<li>b = $b</li>";
        echo "<li>c = $c</li>";
        echo "</ul>";
    ?>

    <!-- Ejercicio 6 -->
    <h2>Ejercicio 6</h2>
    <?php
        $a = "0";
        $b = "TRUE";
        $c = FALSE;
        $d = ($a OR $b);
        $e = ($a AND $c);
        $f = ($a XOR $b);

        echo "<pre>";
        var_dump($a, $b, $c, $d, $e, $f);
        echo "</pre>";

        echo "<p>Transformar booleanos a valores legibles:</p>";
        echo "c = " . ($c ? "true" : "false") . "<br>";
        echo "e = " . ($e ? "true" : "false") . "<br>";
    ?>

    <!-- Ejercicio 7 -->
    <h2>Ejercicio 7</h2>
    <?php
        echo "<ul>";
        echo "<li>Versión Apache y PHP: " . $_SERVER['SERVER_SOFTWARE'] . "</li>";
        echo "<li>Nombre del sistema operativo (servidor): " . PHP_OS . "</li>";
        echo "<li>Idioma del navegador (cliente): " . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "</li>";
        echo "</ul>";
    ?>
</body>
</html>
