<?php
require_once "db/conexion.php";

$code_participacion = $_GET['sorteo'];

if(!isset($code_participacion)){
    header('location: pages/'); 
}

$id_participacion = substr($code_participacion, 4);

$participacion_query = "SELECT
                            pa.id,
                            p.fullname as nombre_participante,
                            p.agencia as agencia_participante,
                            CASE
                                WHEN pa.id_premio IS NULL THEN ''
                                ELSE pr.nombre
                            END AS premio,
                            CASE
                                WHEN pa.id_premio IS NULL THEN ''
                                ELSE pr.url_img
                            END AS url_img
                        FROM
                            participaciones pa
                        LEFT JOIN
                            participantes p ON pa.cedula_participante = p.cedula
                        LEFT JOIN
                            premios pr ON pa.id_premio = pr.id
                        WHERE
                            pa.id = $id_participacion;";

$consulta_participacion = mysqli_query($conexion, $participacion_query) or die(mysqli_error($conexion));
$participacion = mysqli_fetch_array($consulta_participacion);

$nombre_participante = $participacion['nombre_participante'];
$agencia_participante = $participacion['agencia_participante'];
$premio = $participacion['premio'];
$url_img = $participacion['url_img'];


if($premio){
    require_once "pages/premio.php";
}else{
    require_once "pages/ruleta.php";
}

?>



