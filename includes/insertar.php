<?php

// Función para validar RUT chileno
    function validarRut($rut) {
        // Se utiliza una expresión regular para validar el formato del RUT
        if (!preg_match("/^[0-9]+-[0-9kK]{1}$/", $rut)) {
            return false;
        }
            $rut = explode('-', $rut);
            $dv = $rut[1];
            // Se eliminan los puntos del número del RUT y se invierte su orden
            $rut = strrev(preg_replace('/\./', '', $rut[0]));
            $suma = 0;
            // Se realiza el cálculo del dígito verificador
            for($i=0;$i<strlen($rut);$i++){
                $suma += substr($rut,$i,1)*((($i%6)+2));
            }
            $resto = $suma%11;
            $dvEsperado = 11-$resto;
            $dv = ($dv == 'k' || $dv == 'K')?'10':$dv;
            // Se verifica si el dígito verificador obtenido es igual al dígito verificador ingresado
            if($dvEsperado != $dv) {
                return false;
        }
    return true;
    }

// Se incluye el archivo de conexión a la base de datos
include "../conexion.php";

// Se obtienen los datos del formulario
$nombre=$_POST['nombre'];
$alias=$_POST['alias'];
$rut=$_POST['rut'];
$email=$_POST['email'];
$id_region=$_POST['cbx_region'];
$id_comuna=$_POST['cbx_comuna'];
$id_candidato=$_POST['cbx_candidato'];
$medios=isset($_POST['medios']) ? $_POST['medios'] : array();
$medios_str = implode(",", $medios);

// Validación de datos
if(empty($nombre) || empty($alias) || empty($rut) || empty($email) || $id_region == 0 || $id_comuna == 0 || $id_candidato == 0 || count($medios) < 2){
    // Se muestra un mensaje de alerta y se redirecciona al usuario al formulario de inicio
    echo "<script> alert('Debe llenar todos los campos');
        location.href = '../index.php';
    </script>";
    exit();
}

if(strlen($alias) < 5 || !preg_match('/^[a-zA-Z0-9]+$/', $alias)){
    // Se muestra un mensaje de alerta y se redirecciona al usuario al formulario de inicio
    echo "<script> alert('El Alias debe tener al menos 5 caracteres y contener sólo letras y números');
        location.href = '../index.php';
    </script>";
    exit();
}

// Validar RUT
if(!validarRut($rut)) {
    // Se muestra un mensaje de alerta y se redirecciona al usuario al formulario de inicio
    echo "<script> alert('El rut debe tener digito verificador y sin puntos');
        location.href = '../index.php';
    </script>";
    exit;
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    // Se muestra un mensaje de alerta y se redirecciona al usuario al formulario de inicio
    echo "<script> alert('El correo electrónico ingresado no es válido, por favor ingrese un correo en formato válido');
        location.href = '../index.php';
    </script>";
    exit();
}

//Consulta para verificar si la comuna seleccionada corresponde a la región seleccionada
$query = "SELECT id_comuna FROM comunas WHERE id_comuna = $id_comuna AND id_region = $id_region";
$resultado = $mysqli->query($query);

//Si no corresponde, muestra un mensaje de alerta y redirecciona a la página principal
if($resultado->num_rows == 0){
echo "<script> alert('La comuna seleccionada no corresponde a la región seleccionada, por favor seleccione una comuna válida');
location.href = '../index.php';
</script>";
exit();
}

//Consulta para verificar si el Rut ya ha sido registrado
$query = "SELECT rut FROM votacion WHERE rut = '$rut'";
$resultado = $mysqli->query($query);

//Si ya existe un voto registrado con el Rut ingresado, muestra un mensaje de alerta y redirecciona a la página principal
if($resultado->num_rows > 0){
echo "<script> alert('Ya existe un voto registrado con el Rut ingresado, por favor revise el rut');
location.href = '../index.php';
</script>";
exit();
}

//Inserta los datos del voto en la tabla "votacion"
$sql = "INSERT INTO votacion (nombre, alias, rut, email, region, comuna, candidato, medios)
VALUES ('$nombre','$alias','$rut','$email','$id_region','$id_comuna','$id_candidato','$medios_str')";
$query = mysqli_query($mysqli, $sql);

//Si el voto es ingresado exitosamente, muestra un mensaje de alerta y redirecciona a la página principal
if($query){
echo "<script> alert('Voto registrado');
location.href = '../index.php';
</script>";
}

//Si el voto no fue ingresado, muestra un mensaje de alerta y redirecciona a la página principal
else{
echo "<script> alert('El voto no fue ingresado');
location.href = '../index.php';
</script>";
}

?>
