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
		<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

		<script language="javascript">
			$(document).ready(function() {
				function validarFormulario() {
					var nombre = $("#nombre").val();
					var alias = $("#alias").val();
					var rut = $("#rut").val();
					var email = $("#email").val();
					var cbx_region = $("#cbx_region").val();
					var cbx_comuna = $("#cbx_comuna").val();
					var cbx_candidato = $("#cbx_candidato").val();
					var medios = $("input[name='medios[]']:checked").length;

					// Validación del nombre y apellido
					if (nombre === "") {
						$("#nombreError").html("El campo 'Nombre y Apellido' no debe quedar en blanco.");
						return false;
					} else {
						$("#nombreError").html(""); // Limpia el mensaje de error
					}

					// Validación del alias
					if (alias === "" || alias.length < 6 || !/^[a-zA-Z0-9]+$/.test(alias)) {
						$("#aliasError").html("El Alias debe tener al menos 6 caracteres y contener solo letras y números.");
						return false;
					} else {
						$("#aliasError").html(""); // Limpia el mensaje de error
					}

					// Validación del correo electrónico
					if (!validarEmail(email)) {
						$("#emailError").html("El correo electrónico ingresado no es válido, por favor ingrese un correo en formato válido.");
						return false;
					} else {
						$("#emailError").html(""); // Limpia el mensaje de error
					}

					// Validación del RUT con la función validarRutChileno
					if (!validarRutChileno(rut)) {
						$("#rutError").html("El RUT ingresado no es válido, por favor ingrese un RUT en formato chileno.");
						return false;
					} else {
						$("#rutError").html(""); // Limpia el mensaje de error
					}

					// Validación de los combos (Región y Comuna)
					if (cbx_region === "0" || cbx_comuna === "0") {
						$("#regionComunaError").html("Debe seleccionar una Región y una Comuna válidas.");
						return false;
					} else {
						$("#regionComunaError").html(""); // Limpia el mensaje de error
					}

					// Validación del combo de Candidato
					if (cbx_candidato === "0") {
						$("#candidatoError").html("Debe seleccionar un Candidato.");
						return false;
					} else {
						$("#candidatoError").html(""); // Limpia el mensaje de error
					}

					// Validación de los checkboxes
					if (medios < 2) {
						$("#mediosError").html("Debe seleccionar al menos dos opciones en '¿Cómo se enteró de Nosotros?'");
						return false;
					} else {
						$("#mediosError").html(""); // Limpia el mensaje de error
					}

					// Agrega más validaciones si es necesario

					return true;
				}

				function validarRutChileno(rut) {
					// Elimina puntos, guiones y espacios en blanco del RUT y lo convierte a mayúsculas
					rut = rut.replace(/[.-\s]/g, '').toUpperCase();

					// Verifica que el RUT tenga el formato adecuado
					if (!/^[0-9]{7,8}[0-9Kk]$/.test(rut)) {
						return false;
					}

					// Divide el RUT en su parte numérica y el dígito verificador
					const rutNumerico = rut.slice(0, -1);
					const digitoVerificador = rut.slice(-1);

					// Calcula el dígito verificador esperado
					let suma = 0;
					let multiplicador = 2;

					for (let i = rutNumerico.length - 1; i >= 0; i--) {
						suma += parseInt(rutNumerico[i]) * multiplicador;
						multiplicador = multiplicador === 7 ? 2 : multiplicador + 1;
					}

					const resto = suma % 11;
					const digitoEsperado = resto === 1 ? 'K' : resto === 0 ? '0' : 11 - resto;

					return digitoVerificador === digitoEsperado.toString();
				}



				// Función para validar el correo electrónico
				function validarEmail(email) {
					// Utiliza una expresión regular para validar el formato del correo electrónico.
					// Devuelve true si es válido, false si no.
					return /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email);
				}
				// Cuando se envía el formulario
				$("#combo").submit(function(event) {
					event.preventDefault(); // Evitar el envío del formulario por defecto

					// Realizar las validaciones
					if (!validarFormulario()) {
						return; // Salir de la función si las validaciones no pasan
					}

					// Obtener los datos del formulario
					var formData = $(this).serialize();

					// Realizar una solicitud AJAX para insertar los datos en insertar.php
					$.ajax({
						type: "POST",
						url: "includes/insertar.php",
						data: formData,
						success: function(response) {
							// Mostrar la respuesta del servidor en el div de mensajes
							$("#mensaje").html(response);
							
							// Redirigir a index.php después de un cierto tiempo (por ejemplo, 3 segundos)
							setTimeout(function() {
								window.location.href = "http://localhost/votacionDesis-main/";
							}, 3000); // 3000 milisegundos (3 segundos)
						}
					});
				});

				// Se define una función que se ejecuta cuando se cambia la opción seleccionada en el select de regiones
				$("#cbx_region").change(function() {
					$("#cbx_region option:selected").each(function() {
						// Se obtiene el valor de la opción seleccionada
						id_region = $(this).val();

						// Se envía una solicitud post al archivo getComuna.php para obtener las comunas de la región seleccionada
						$.post("includes/getComuna.php", { id_region: id_region }, function(data) {
							// Se actualiza el select de comunas con las opciones obtenidas de la base de datos
							$("#cbx_comuna").html(data);
						});
					});
				});

				// Se define una función que se ejecuta cuando se cambia la opción seleccionada en el select de regiones
				$("#cbx_region").change(function() {
					$("#cbx_region option:selected").each(function() {
						// Se obtiene el valor de la opción seleccionada
						id_region = $(this).val();

						// Se envía una solicitud post al archivo getCandidato.php para obtener los candidatos de la región seleccionada
						$.post("includes/getCandidato.php", { id_region: id_region }, function(data) {
							// Se actualiza el select de candidatos con las opciones obtenidas de la base de datos
							$("#cbx_candidato").html(data);
						});
					});
				});
			});
		</script>
	</head>

	<body>
		<!-- Se crea un encabezado para el formulario -->
		<p><h3>FORMULARIO DE VOTACIÓN: </h3> </p>

		<!-- Se definen los campos del formulario -->
		<table class="formulario"> <!-- Inicio de la tabla del formulario -->

			<form id="combo" name="combo" action="includes/insertar.php" method="POST"> <!-- Inicio del formulario con el atributo id, name, action y method -->

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
				<td><label for="comuna">Candidato:</label></td> <!-- Etiqueta y campo "Candidadto" -->
				<td><select name="cbx_candidato" id="cbx_candidato" required> <!-- Campo de selección para la comuna del usuario con el atributo name, id y required -->
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
			
			</form>
		</table>	

		<div id="mensaje"></div>
		<div id="nombreError" class="error"></div>
		<div id="aliasError" class="error"></div>
		<div id="emailError" class="error"></div>
		<div id="rutError" class="error"></div>
		<div id="regionComunaError" class="error"></div>
		<div id="candidatoError" class="error"></div>
		<div id="mediosError" class="error"></div>

	</body>
</html>