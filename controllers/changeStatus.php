<?php

require_once "../db/conexion.php";

$id = $_POST['id'];
$new_status = $_POST['new_status'];

$query = "UPDATE participaciones SET estado = '$new_status' WHERE id = '$id'";
$consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));

$response = array(
    "success" => true
);

print_r(json_encode($response));