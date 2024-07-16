<?php
require_once "../db/conexion.php";

function generarCodigo($nombre, $agencia, $idParticipacion) {
    $nombreParte = strtoupper(substr($nombre, 0, 2));
    $agenciaParte = strtoupper(substr($agencia, 0, 2));
    return $nombreParte . $agenciaParte . $idParticipacion;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $agencia = $_POST['agencia'];

    // Comprobar si el participante ya existe
    $consulta_participante = mysqli_query($conexion, "SELECT * FROM participantes WHERE cedula = '$cedula'");
    if (mysqli_num_rows($consulta_participante) === 0) {
        // Insertar nuevo participante
        $insertar_participante = "INSERT INTO participantes (cedula, fullname, agencia) VALUES ('$cedula', '$nombre', '$agencia')";
        mysqli_query($conexion, $insertar_participante);
    }

    // Insertar nueva participación
    $fechaGeneracion = date('Y-m-d');
    $insertar_participacion = "INSERT INTO participaciones (cedula_participante, fecha_generacion) VALUES ('$cedula', '$fechaGeneracion')";
    mysqli_query($conexion, $insertar_participacion);

    $idParticipacion = mysqli_insert_id($conexion);

    // Obtener URL base del sitio
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $baseURL = $protocol . "://" . $host;

    // Generar URL
    $code = generarCodigo($nombre, $agencia, $idParticipacion);
    $url = $baseURL . "/aniversario?sorteo=" . $code;

    // Retornar URL como respuesta JSON
    echo json_encode(['url' => $url]);
}
?>
