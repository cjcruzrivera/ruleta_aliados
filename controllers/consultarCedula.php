<?php

require_once "../db/conexion.php";

$cedula = $_POST['cedula'];

if ($cedula == "") {
    print_r("El campo cedula no puede estar vacio");
} else {
    $registros = "";

    $query = "SELECT * FROM participaciones WHERE cedula = '$cedula'";
    // fecha, reserva, boleta, estado 

    $consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $exist = mysqli_num_rows($consulta) > 0 ? true : false;
    $table = "<table class='table'>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Reserva</th>
                        <th>Boleta</th>
                        <th>Estado</th>
                    </tr>
                </thead><tbody>";
    $registros = $exist ? $table : "No se encontraron registros";
    while ($registro = mysqli_fetch_array($consulta)) {
        $registros .= "<tr>";
        $registros .= "<td>" . $registro['fecha'] . "</td>";
        $registros .= "<td>" . $registro['reserva'] . "</td>";
        $registros .= "<td>" . str_pad($registro['boleta'], 3, '0', STR_PAD_LEFT) . "</td>";
        $registros .= "<td>" . $registro['estado'] . "</td>";
        $registros .= "</tr>";
    }
    if ($exist) {
        $registros .= "</tbody></table>";
    }

    print_r($registros);
}
