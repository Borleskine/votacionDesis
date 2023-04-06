<?php
	// Se requiere la conexión a la base de datos
	require ('../conexion.php');
	
	// Se obtiene el id de la región desde la solicitud POST
	$id_region = $_POST['id_region'];
	
	// Se construye la consulta para obtener los candidatos que corresponden a la región seleccionada
	$sql = "SELECT id_candidato, nombre_candidato FROM candidato WHERE id_region = '$id_region'";
	$resultadoC = $mysqli->query($sql);
	
	$html= "<option value='0'></option>";
	
	// Se recorre el resultado de la consulta y se construye el HTML con las opciones de candidato
	while($rowM = $resultadoC->fetch_assoc())
	{
		$html.= "<option value='".$rowM['id_candidato']."'>".$rowM['nombre_candidato']."</option>";
	}
	
	// Se retorna el HTML generado
	echo $html;
?>
