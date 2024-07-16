<?php

ini_set("display_errors", true);
require_once "../db/conexion.php";

function get_boleta()
{
    global $conexion;

    $query = "SELECT random.RandomValue
    FROM (SELECT FLOOR( RAND() * (999-0) + 0) as RandomValue) as random
    WHERE random.RandomValue NOT IN (SELECT boleta FROM participaciones)
    LIMIT 1";
    $consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $boleta = mysqli_fetch_row($consulta);
    
    while(!$boleta){
        $consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
        $boleta = mysqli_fetch_row($consulta);
    }

    return $boleta[0];
}

$data = json_decode($_POST['data']);
$reserva = $data->reserva;

$query = "SELECT * FROM participaciones WHERE reserva = '$reserva'";

$consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
if (mysqli_num_rows($consulta) > 0) {
    $response = array(
        "success" => false,
        "error" => "Ya hay una participación registrada con esta reserva"
    );
    print_r(json_encode($response));
    exit;
}


if ($_FILES['file']['size'] != 0) {
    $rutaArchivo = "../pages/files/";

    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $rutaArchivo .= $reserva . "." . $extension;
    if ($extension != "pdf" && $extension != "PDF") {
        $response = array(
            "success" => false,
            "error" => "Debe cargar archivos con extensión PDF (.pdf, .PDF)."
        );
        print_r(json_encode($response));
        exit;
    } else {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $rutaArchivo)) {
            $carga = true;
        } else {
            $response = array(
                "success" => false,
                "error" => "Error al subir archivo PDF al servidor."
            );
            print_r(json_encode($response));
            exit;
        }
    }
} else {
    $response = array(
        "success" => false,
        "error" => "Error con archivo cargado.",
    );
    print_r(json_encode($response));
    exit;
}

$liquidacion = str_replace("/pages", "", $rutaArchivo);

$cedula = $data->cedula;
$query_validation = "SELECT * FROM participantes WHERE cedula = '$cedula'";

$consulta = mysqli_query($conexion, $query_validation) or die(mysqli_error($conexion));
if (mysqli_num_rows($consulta) == 0) {
    $query_insert_participante = "INSERT INTO `participantes` (`cedula`, `fullname`, `email`, `telefono`, `agencia`)
    VALUES ('$cedula', '$data->fullname', '$data->email', '$data->telefono', '$data->agencia')";
    $consulta = mysqli_query($conexion, $query_insert_participante) or die(mysqli_error($conexion));
}

$message = "";
$boleta = get_boleta();
$query_insert_participacion = "INSERT INTO `participaciones` (`cedula`, `id_hotel`, `id_asesor`, `fecha`, `reserva`, `boleta`, `url_liquidacion`, `estado`)
                                                      VALUES ('$cedula', '$data->hotel',  '$data->asesor',CURDATE(), '$reserva',$boleta, '$liquidacion', 'Pendiente de Verificar')";

$consulta = mysqli_query($conexion, $query_insert_participacion) or die(mysqli_error($conexion));

$message .= "Participación registrada con éxito. Boleta #" . str_pad($boleta, 3, '0', STR_PAD_LEFT). "\n";
if ($data->destacado) {
    $boleta2 = get_boleta();
    $query_insert_participacion2 = "INSERT INTO `participaciones` (`cedula`, `id_hotel`, `id_asesor`, `fecha`, `reserva`, `boleta`, `url_liquidacion`, `estado`)
                                                      VALUES ('$cedula', '$data->hotel',  '$data->asesor',CURDATE(), '$reserva',$boleta2, '$liquidacion', 'Pendiente de Verificar')";
    $consulta = mysqli_query($conexion, $query_insert_participacion2) or die(mysqli_error($conexion));
    
    $message .= "Participación adicional registrada con éxito. Boleta #" . str_pad($boleta2, 3, '0', STR_PAD_LEFT);
}


$response = array(
    "success" => true,
    "message" => $message
);

print_r(json_encode($response));
