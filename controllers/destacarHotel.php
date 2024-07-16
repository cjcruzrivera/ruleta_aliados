<?php

require_once "../db/conexion.php";

$id = $_POST['id'];

$query = "UPDATE hoteles SET destacado = 0";
$consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));

$query = "UPDATE hoteles SET destacado = 1 WHERE id = '$id'";
$consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));

$response = array(
    "success" => true
);

print_r(json_encode($response));
