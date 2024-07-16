<?php

include_once 'credentials.php';

$conexion = $mysqli = new mysqli($host, $user, $pass, $db);
$conexion->set_charset("utf8");

if (mysqli_connect_errno()) {
	echo 'Conexion Fallida : ', mysqli_connect_error();
	exit();
} 