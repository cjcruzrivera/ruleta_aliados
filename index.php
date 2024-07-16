<?php
$id_participacion = $_GET['sorteo'];

if(!isset($id_participacion)){
    header('location: pages/'); 
}

echo $id_participacion;
// AQUI RULETA