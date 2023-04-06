<?php
	// Se importa el archivo de conexión a la base de datos
	require ('../conexion.php');
	
	// Se obtiene el id de la región seleccionada mediante el método POST
	$id_region = $_POST['id_region'];
	
	// Se crea la consulta SQL para obtener las comunas que pertenecen a la región seleccionada
	$sql = "SELECT id_comuna, nombre_comuna FROM comunas WHERE id_region = '$id_region'";
	
	// Se ejecuta la consulta SQL
	$resultadoC = $mysqli->query($sql);
	
	// Se inicializa la variable $html con una opción vacía
	$html= "<option value='0'></option>";
	
	// Se recorren los resultados obtenidos de la consulta SQL y se van concatenando los valores a la variable $html
	while($rowM = $resultadoC->fetch_assoc())
	{
		$html.= "<option value='".$rowM['id_comuna']."'>".$rowM['nombre_comuna']."</option>";
	}
	
	// Se muestra el contenido de $html
	echo $html;
?>