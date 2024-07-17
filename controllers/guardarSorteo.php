<?php
require_once "../db/conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_participacion = $_POST['id_participacion'];
    $id_premio = $_POST['id_premio'];
    
    // Obtener fecha actual
    $fecha_sorteo = date('Y-m-d');

    // Actualizar participación con el premio y fecha de sorteo
    $actualizar_participacion = "UPDATE participaciones SET id_premio = $id_premio, fecha_sorteo = '$fecha_sorteo' WHERE id = $id_participacion";
    
    if (mysqli_query($conexion, $actualizar_participacion)) {
        echo json_encode(['status' => 'success', 'message' => 'Sorteo guardado correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar el sorteo: ' . mysqli_error($conexion)]);
    }
}
?>
