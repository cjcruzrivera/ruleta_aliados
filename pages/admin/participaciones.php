<?php
session_start();
ini_set('display_errors', 1);

if (!$_SESSION['logged']) {
    header('location: ./login.php');
}

require_once "../../db/conexion.php";
$name = $_SESSION['fullname'];

// Obtener URL base del sitio
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$baseURL = $protocol . "://" . $host. "/aniversario";

$participantes_query = "SELECT * FROM participantes";
$consulta_participantes = mysqli_query($conexion, $participantes_query) or die(mysqli_error($conexion));
$participantes = [];
while ($registro = mysqli_fetch_array($consulta_participantes)) {
    $participantes[$registro['cedula']] = $registro;
}

$html_participaciones = "";

$query = "SELECT
            pa.id,
            pa.fecha_generacion AS generado,
            p.cedula,
            p.fullname as nombre_participante,
            p.agencia as agencia_participante,
            CASE
                WHEN pa.id_premio IS NULL THEN 'PREMIO NO SORTEADO AUN'
                ELSE pr.nombre
            END AS premio,
            CASE
                WHEN pa.id_premio IS NULL THEN ''
                ELSE pr.url_img
            END AS url_img,
            pa.fecha_sorteo AS sorteado
        FROM
            participaciones pa
        LEFT JOIN
            participantes p ON pa.cedula_participante = p.cedula
        LEFT JOIN
            premios pr ON pa.id_premio = pr.id;";

$consulta = mysqli_query($conexion, $query) or die(mysqli_error($conexion));

while ($registro = mysqli_fetch_array($consulta)) {
    $generado = $registro['generado'];
    $cedula = $registro['cedula'];
    $nombre_participante = $registro['nombre_participante'];
    $agencia_participante = $registro['agencia_participante'];
    $url_participacion = $baseURL . "?sorteo=" . strtoupper(substr($nombre_participante, 0, 2)) . strtoupper(substr($agencia_participante, 0, 2)) . $registro['id'];
    $link_participacion = "<a href='$url_participacion' target='_blank'>$url_participacion</a>";
    $premio = $registro['premio'] != 'PREMIO NO SORTEADO AUN' ? $registro['premio'] : $registro['premio'] . '<br>' . $link_participacion;
    $sorteado = $registro['sorteado'];
    $url_img = $registro['url_img'];
    $img = $url_img ? "<img src='$url_img' style='width: 100px; height: 100px;'>" : "";
    $html_participaciones .= "<tr>
                                <td>$generado</td>
                                <td>$cedula</td>
                                <td>$nombre_participante</td>
                                <td>$agencia_participante</td>
                                <td>$premio</td>
                                <td>$img</td>
                                <td>$sorteado</td>
                            </tr>";
}
$html_participaciones = $html_participaciones == "" ? "<tr><td colspan='7'>No hay participaciones</td></tr>" : $html_participaciones;
?>

<!doctype html>
<html lang="es">

<?php include "./partials/head.php"; ?>

<body>
    <?php include './partials/navbar.php'; ?>

    <div class="container pt-5" style="max-width: 1317px !important;">
        <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#generarModal">GENERAR PARTICIPACIÓN</button>
            </div>
        </div>
        <div class="row pt-3">
            <div class="col-md-12">
                <table class="table table-striped" id="table_id">
                    <thead>
                        <tr>
                            <th>Generado el</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Agencia</th>
                            <th colspan="2">Premio</th>
                            <th>Sorteado el</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $html_participaciones; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="generarModal" tabindex="-1" role="dialog" aria-labelledby="generarModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generarModalLabel">Generar Participación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formGenerar">
                        <div class="form-group">
                            <label for="cedula">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="agencia">Agencia</label>
                            <input type="text" class="form-control" id="agencia" name="agencia" required>
                        </div>
                        <button type="button" class="btn btn-primary" id="btnGenerar">Generar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var participantes = <?php echo json_encode($participantes); ?>;

        $('#cedula').on('blur', function() {
            var cedula = $(this).val();
            if (participantes[cedula]) {
                $('#nombre').val(participantes[cedula].fullname);
                $('#agencia').val(participantes[cedula].agencia);
            }
        });

        $('#btnGenerar').on('click', function() {
            var cedula = $('#cedula').val();
            var nombre = $('#nombre').val();
            var agencia = $('#agencia').val();

            $.ajax({
                url: '../../controllers/generarParticipacion.php',
                type: 'POST',
                data: {
                    cedula: cedula,
                    nombre: nombre,
                    agencia: agencia
                },
                success: function(response) {
                    response = JSON.parse(response);
                    Swal.fire({
                        title: 'Participación generada correctamente',
                        text: 'Compartir esta URL: ' + response.url,
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            $('#table_id').DataTable({
                "language": {
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "No se encuentran registros",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "No se encuentran registros",
                    "infoFiltered": "(Filtrado de _MAX_ registros totales)",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primera",
                        "last": "Última",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                },
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": 1
                }]
            });
        });
    </script>

</body>

</html>
