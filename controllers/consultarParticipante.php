<?php

require_once "../db/conexion.php";

$cedula = $_POST['cedula'];

if ($cedula == "") {
    print_r("El campo cedula no puede estar vacio");
} else {

    $query = "SELECT * FROM participantes WHERE cedula = '$cedula'";
    $consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    
    if(mysqli_num_rows($consulta) == 0){
        $response = array(
            "success" => false,
            "message" => "No se encontraron registros"
        );
        print_r(json_encode($response));
        exit;
    }

    $registro = mysqli_fetch_array($consulta);
    $response = array(
        "success" => true,
        "participante" => $registro
    );
    print_r(json_encode($response));
    exit;

}
