<?php
	// Se importa el archivo de conexión a la base de datos
	require ('conexion.php');
	// Se define la consulta SQL para obtener las regiones de la base de datos
	$query = "SELECT id_region, nombre_region FROM regiones";
	// Se ejecuta la consulta y se almacena el resultado en la variable $resultado
	$resultado=$mysqli->query($query);
?>

<html>
	<head>
		<title>Votacion Desis</title>
		
		<!-- Se agrega el archivo CSS para dar estilo a la página -->
		<link rel="stylesheet" href="css/styles.css">

		<!-- Se agrega el archivo JQuery para utilizar la biblioteca de JavaScript -->
		<script language="javascript" src="js/jquery-3.6.4.min.js"></script>

		<script language="javascript">
			$(document).ready(function(){
				// Se define una función que se ejecuta cuando se cambia la opción seleccionada en el select de regiones
				$("#cbx_region").change(function () {					
					$("#cbx_region option:selected").each(function () {
						// Se obtiene el valor de la opción seleccionada
						id_region = $(this).val();
						
						// Se envía una solicitud post al archivo getComuna.php para obtener las comunas de la región seleccionada
						$.post("includes/getComuna.php", { id_region: id_region }, function(data){
							// Se actualiza el select de comunas con las opciones obtenidas de la base de datos
							$("#cbx_comuna").html(data);
						});            
					});
				})
			});

			$(document).ready(function(){
				// Se define una función que se ejecuta cuando se cambia la opción seleccionada en el select de regiones
				$("#cbx_region").change(function () {					
					$("#cbx_region option:selected").each(function () {
						// Se obtiene el valor de la opción seleccionada
						id_region = $(this).val();
						
						// Se envía una solicitud post al archivo getCandidato.php para obtener los candidatos de la región seleccionada
						$.post("includes/getCandidato.php", { id_region: id_region }, function(data){
							// Se actualiza el select de candidatos con las opciones obtenidas de la base de datos
							$("#cbx_candidato").html(data);
						});            
					});
				})
			});
		</script>
	</head>

	<body>
		<!-- Se crea un encabezado para el formulario -->
		<p><h3>FORMULARIO DE VOTACIÓN: </h3> </p>

		<!-- Se definen los campos del formulario -->
		<table class="formulario"> <!-- Inicio de la tabla del formulario -->

			<form id="combo" name="combo" action="/includes/insertar.php" method="POST"> <!-- Inicio del formulario con el atributo id, name, action y method -->

			<div><tr> 
				<td><label for="nombre">Nombre y apellido:  </label></td> 
				<td><input type="text" id="nombre" name="nombre" required></td> <!-- Campo de entrada de texto para el nombre y apellido del usuario -->
			</tr></div>

			<div><tr>
				<td><label for="alias">Alias:  </label></td>
				<td><input type="text" id="alias" name="alias" required></td> <!-- Campo de entrada de texto para el alias del usuario -->
			</tr></div>

			<div><tr> 
				<td><label for="rut">Rut:  </label></td> <!-- Etiqueta y campo "Rut" -->
				<td><input type="text" id="rut" name="rut" required></td> <!-- Campo de entrada de texto para el rut del usuario -->
			</tr></div>

			<div><tr> 
				<td><label for="email">Email:  </label></td> <!-- Etiqueta y campo "Email" -->
				<td><input type="email" id="email" name="email" required></td> <!-- Campo de entrada de texto para el email del usuario -->
			</tr></div>

			<div><tr> 
				<td><label for="region">Región:  </label></td> <!-- Etiqueta y campo "Región" -->
				<td><select name="cbx_region" id="cbx_region" required> <!-- Campo de selección para la región del usuario con el atributo name, id y required -->
				<option value="0"></option> <!-- Opción vacía para seleccionar -->
				<?php while($row = $resultado->fetch_assoc()) { ?> <!-- Ciclo while que recorre los resultados de una consulta y crea opciones de selección en la lista desplegable -->
					<option value="<?php echo $row['id_region']; ?>"><?php echo $row['nombre_region']; ?></option> <!-- Opción de selección con el valor de id_region y el texto de nombre_region -->
				<?php } ?>
				</select></td>
			</tr></div>

			<div><tr> 
				<td><label for="comuna">Comuna:</label></td> <!-- Etiqueta y campo "Comuna" -->
				<td><select name="cbx_comuna" id="cbx_comuna" required> <!-- Campo de selección para la comuna del usuario con el atributo name, id y required -->
				</select></td>
			</tr></div>

			<div><tr>			
				<td><label>¿Como se enteró de Nosotros?<td>
					<input type="checkbox" name="medios[]" value="web">Web</input>
					<input type="checkbox" name="medios[]" value="tv">Tv</input>
					<input type="checkbox" name="medios[]" value="rrss">Redes sociales</input>
					<input type="checkbox" name="medios[]" value="amigo">Amigo</input>
				</td></label>
			</tr></div>

			<div><tr><td><input type="submit" id="enviar" name="enviar" value="VOTAR" /></td></tr></div>

			</table>
		</form>

	</body>
</html>