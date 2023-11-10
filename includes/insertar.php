<?php

// Función para validar RUT chileno
function validarRut($rut) {
    if (!preg_match("/^[0-9]+-[0-9kK]{1}$/", $rut)) {
        return false;
    }
    $rut = explode('-', $rut);
    $dv = $rut[1];
    $rut = strrev(preg_replace('/\./', '', $rut[0]));
    $suma = 0;
    for ($i = 0; $i < strlen($rut); $i++) {
        $suma += substr($rut, $i, 1) * ((($i % 6) + 2));
    }
    $resto = $suma % 11;
    $dvEsperado = 11 - $resto;
    $dv = ($dv == 'k' || $dv == 'K') ? '10' : $dv;
    if ($dvEsperado != $dv) {
        return false;
    }
    return true;
}

include "../conexion.php";

$nombre = $_POST['nombre'];
$alias = $_POST['alias'];
$rut = $_POST['rut'];
$email = $_POST['email'];
$id_region = $_POST['cbx_region'];
$id_comuna = $_POST['cbx_comuna'];
$id_candidato = $_POST['cbx_candidato'];
$medios = isset($_POST['medios']) ? $_POST['medios'] : array();
$medios_str = implode(",", $medios);

// Validación de datos
if (empty($nombre) || empty($alias) || empty($rut) || empty($email) || $id_region == 0 || $id_comuna == 0 || $id_candidato == 0 || count($medios) < 2) {
    echo "<script> alert('Debe llenar todos los campos');
        location.href = '../index.php';
    </script>";
    exit();
}

if (strlen($alias) < 5 || !preg_match('/^[a-zA-Z0-9]+$/', $alias)) {
    echo "<script> alert('El Alias debe tener al menos 5 caracteres y contener sólo letras y números');
        location.href = '../index.php';
    </script>";
    exit();
}

// Validar RUT
if (!validarRut($rut)) {
    echo "<script> alert('El rut debe tener digito verificador y sin puntos');
        location.href = '../index.php';
    </script>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script> alert('El correo electrónico ingresado no es válido, por favor ingrese un correo en formato válido');
        location.href = '../index.php';
    </script>";
    exit();
}

// Preaparo la consulta sql para evitar inyección utilizando consultas preparadas
$query = "INSERT INTO votacion (nombre, alias, rut, email, region, comuna, candidato, medios) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($query);

if (!$stmt) {
    die("Error en la preparación de la consulta: " . $mysqli->error);
}

// Enlazar parámetros
$stmt->bind_param("ssssiiis", $nombre, $alias, $rut, $email, $id_region, $id_comuna, $id_candidato, $medios_str);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo "<script> alert('Voto registrado');
        location.href = '../index.php';
    </script>";
} else {
    echo "<script> alert('El voto no fue ingresado');
        location.href = '../index.php';
    </script>";
}

// Cerrar la declaración y la conexión
$stmt->close();
$mysqli->close();
?>
