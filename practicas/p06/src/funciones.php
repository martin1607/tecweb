<?php
// practicas/p06/src/funciones.php
// Funciones para la práctica 6 (asegúrate de guardarlo como UTF-8 sin BOM)

// 1) múltiplo de 5 y 7 (boolean)
function isMultipleOf5And7($n) {
    if (!is_numeric($n)) return false;
    $n = (int)$n;
    return ($n % 5 === 0) && ($n % 7 === 0);
}

// 2) Generar filas Mx3 hasta encontrar (impar, par, impar)
function generate_until_odd_even_odd(&$matrix, &$iterations, $maxIter = 100000) {
    $matrix = array();
    $iter = 0;
    while (true) {
        $a = mt_rand(1, 999);
        $b = mt_rand(1, 999);
        $c = mt_rand(1, 999);
        $matrix[] = array($a, $b, $c);
        $iter++;
        if (($a % 2 !== 0) && ($b % 2 === 0) && ($c % 2 !== 0)) break;
        if ($iter >= $maxIter) break;
    }
    $iterations = $iter;
    return $matrix;
}

// 3a) Encontrar primer múltiplo con while
function find_first_multiple_while($target, &$value, &$tries, $maxIter = 100000) {
    $tries = 0;
    $value = null;
    if (!is_numeric($target) || (int)$target === 0) return null;
    $target = (int)$target;
    while ($tries < $maxIter) {
        $num = mt_rand(1, 100000);
        $tries++;
        if ($num % $target === 0) { $value = $num; return $num; }
    }
    return null;
}

// 3b) Usando do-while
function find_first_multiple_dowhile($target, &$value, &$tries, $maxIter = 100000) {
    $tries = 0;
    $value = null;
    if (!is_numeric($target) || (int)$target === 0) return null;
    $target = (int)$target;
    do {
        $num = mt_rand(1, 100000);
        $tries++;
        if ($num % $target === 0) { $value = $num; return $num; }
    } while ($tries < $maxIter);
    return null;
}

// 4) Arreglo ASCII 97..122 => a..z
function ascii_array() {
    $arr = array();
    for ($i = 97; $i <= 122; $i++) {
        $arr[$i] = chr($i);
    }
    return $arr;
}

// 5) Validar edad y sexo
function check_age_sex($edad, $sexo) {
    $edad = (int)$edad;
    $sexo_norm = strtolower(trim($sexo));
    $isFemale = in_array($sexo_norm, ['f', 'femenino', 'female', 'mujer']);
    if ($isFemale && ($edad >= 18 && $edad <= 35)) {
        return ['ok' => true, 'msg' => 'Bienvenida, usted está en el rango de edad permitido.'];
    } else {
        return ['ok' => false, 'msg' => 'Lo sentimos. No cumple el rango de edad/sexo solicitado.'];
    }
}

// 6) Parque vehicular con 15 autos (matrículas PUE1001..PUE1015, direcciones en Puebla)
function parqueVehicular() {
    return array(
        "PUE1001" => array(
            "Auto" => array("marca"=>"HONDA","modelo"=>2020,"tipo"=>"camioneta"),
            "Propietario" => array("nombre"=>"Alfonso Esparza","ciudad"=>"Puebla, Pue.","direccion"=>"Av. Juárez 2306, Col. La Paz")
        ),
        "PUE1002" => array(
            "Auto" => array("marca"=>"MAZDA","modelo"=>2019,"tipo"=>"sedan"),
            "Propietario" => array("nombre"=>"María del Consuelo Molina","ciudad"=>"Puebla, Pue.","direccion"=>"97 Oriente, Col. El Carmen")
        ),
        "PUE1003" => array(
            "Auto" => array("marca"=>"NISSAN","modelo"=>2021,"tipo"=>"hatchback"),
            "Propietario" => array("nombre"=>"José Luis Hernández","ciudad"=>"San Andrés Cholula, Pue.","direccion"=>"Blvd. Atlixcáyotl 5209, Reserva Territorial Atlixcáyotl")
        ),
        "PUE1004" => array(
            "Auto" => array("marca"=>"TOYOTA","modelo"=>2018,"tipo"=>"sedan"),
            "Propietario" => array("nombre"=>"Ana Gómez","ciudad"=>"Puebla, Pue.","direccion"=>"Av. Margaritas 150, Col. Bugambilias")
        ),
        "PUE1005" => array(
            "Auto" => array("marca"=>"FORD","modelo"=>2022,"tipo"=>"camioneta"),
            "Propietario" => array("nombre"=>"Luis Martínez","ciudad"=>"San Pedro Cholula, Pue.","direccion"=>"Calle 6 Norte 1203, Centro")
        ),
        "PUE1006" => array(
            "Auto" => array("marca"=>"CHEVROLET","modelo"=>2017,"tipo"=>"sedan"),
            "Propietario" => array("nombre"=>"Claudia Rivera","ciudad"=>"Puebla, Pue.","direccion"=>"Av. Reforma 1519, Col. Centro")
        ),
        "PUE1007" => array(
            "Auto" => array("marca"=>"VOLKSWAGEN","modelo"=>2015,"tipo"=>"hatchback"),
            "Propietario" => array("nombre"=>"Fernando López","ciudad"=>"Cuautlancingo, Pue.","direccion"=>"Calle Hidalgo 35, Col. Centro")
        ),
        "PUE1008" => array(
            "Auto" => array("marca"=>"KIA","modelo"=>2020,"tipo"=>"sedan"),
            "Propietario" => array("nombre"=>"Patricia Morales","ciudad"=>"Puebla, Pue.","direccion"=>"Av. San Claudio, Ciudad Universitaria")
        ),
        "PUE1009" => array(
            "Auto" => array("marca"=>"HYUNDAI","modelo"=>2021,"tipo"=>"camioneta"),
            "Propietario" => array("nombre"=>"Carlos Pérez","ciudad"=>"San Andrés Cholula, Pue.","direccion"=>"Camino Real a Cholula 1201, Col. La Carcaña")
        ),
        "PUE1010" => array(
            "Auto" => array("marca"=>"MITSUBISHI","modelo"=>2019,"tipo"=>"sedan"),
            "Propietario" => array("nombre"=>"Verónica Torres","ciudad"=>"Puebla, Pue.","direccion"=>"Av. Las Torres 560, Col. Mayorazgo")
        ),
        "PUE1011" => array(
            "Auto" => array("marca"=>"BMW","modelo"=>2022,"tipo"=>"sedan"),
            "Propietario" => array("nombre"=>"Sergio Ramírez","ciudad"=>"Puebla, Pue.","direccion"=>"Calzada Zavaleta 4308, Sta Cruz Buenavista")
        ),
        "PUE1012" => array(
            "Auto" => array("marca"=>"AUDI","modelo"=>2021,"tipo"=>"camioneta"),
            "Propietario" => array("nombre"=>"Gabriela Sánchez","ciudad"=>"Puebla, Pue.","direccion"=>"Calle 25 Sur 512, Col. La Noria")
        ),
        "PUE1013" => array(
            "Auto" => array("marca"=>"MERCEDES","modelo"=>2018,"tipo"=>"sedan"),
            "Propietario" => array("nombre"=>"Andrés Castillo","ciudad"=>"San Pedro Cholula, Pue.","direccion"=>"Av. Morelos 203, Barrio de Santiago")
        ),
        "PUE1014" => array(
            "Auto" => array("marca"=>"SEAT","modelo"=>2016,"tipo"=>"hatchback"),
            "Propietario" => array("nombre"=>"Laura Fernández","ciudad"=>"Puebla, Pue.","direccion"=>"Calle 24 Sur 1202, Col. El Ángel")
        ),
        "PUE1015" => array(
            "Auto" => array("marca"=>"TESLA","modelo"=>2023,"tipo"=>"sedan"),
            "Propietario" => array("nombre"=>"Miguel Ángel Ruiz","ciudad"=>"Puebla, Pue.","direccion"=>"Av. Osa Mayor 2902, Angelópolis")
        )
    );
}

// Buscar por matrícula (case-insensitive)
function search_vehicle($registry, $plate) {
    $plate_norm = strtoupper(trim($plate));
    if ($plate_norm === '') return null;
    if (isset($registry[$plate_norm])) return $registry[$plate_norm];
    return null;
}
