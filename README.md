# votacionDesis
Prueba Desis.cl - formulario votación regional

Athair@hotmail.com
+569 66949444 

Se adjunta archivo SQL con las tablas y datos de todas las comunas de Chile relacionadas a cada región del país. 

Se debe importar desde MySql a través de PHPMYADMIN, la base se llama votaciondesis.
Una vez importada la base, se debe modificar el archivo conexion.php con la información respectiva al servidor local.

//servidor, usuario de base de datos, contraseña del usuario, nombre de base de datos
	$mysqli = new mysqli("localhost","root","","votaciondesis"); <-- datos a editar -->
  
  Por último se debe ejecutar el servidor php de preferencia para iniciar el intex.php

PHP Version 8.2.0

Configuración del servidor
Versión de Apache:
2.4.54.2  - Documentation Apache
Software del servidor:
Apache/2.4.54 (Win64) PHP/8.0.26 mod_fcgid/2.3.10-dev - Puerto definido para Apache: 8080
Versión de PHP:
[Apache module]  8.0.26 - Documentation PHP - Loaded PHP extensions - Use of PHP versions
 
Versión de MySQL:
8.0.31 - Puerto definido para MySQL: 3306 - default DBMS -  Documentation MySQL
Versión de MariaDB:
10.10.2 - Puerto definido para MariaDB: 3307 -  Documentation MariaDB - MySQL - MariaDB
