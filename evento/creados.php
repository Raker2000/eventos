<?php
require('../src/config.php');
require('../src/utils/protectRoute.php');
session_start();
protectRoute('/login.php');
//conexión a la DB:
$conexion = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASS'), constant('DB_NAME'), constant('DB_PORT') ?? null);
$userID = $_SESSION['id_usuario'];
$sql = "SELECT * FROM eventos WHERE id_creador = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $userID);
$stmt->execute();
$result = $stmt->get_result();
$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($events, $row);
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ba42bd5e6b.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="<?= constant('ROOT_URL'); ?>/public/assets/icono.ico" type="image/x-icon">
    <title>Crear evento</title>
    <link rel="stylesheet" href="<?= constant('ROOT_URL'); ?>/public/css/eventos-creados.css">
</head>

<body>
    <!-- cargar navbar -->
    <?php require('../src/components/navbar.component.php') ?>

    <main class="contenedor">

        <a href="<?= constant('ROOT_URL') ?>/evento/nuevo.php">Nuevo Evento</a>

        <h1>Tus eventos:</h1>

        <?php if (empty($events)) { ?>
            <h3>No creaste ningun evento</h3>
        <?php } else { ?>
            <table>
                <thead class="eventos">
                    <tr>
                        <th>Nombre del Evento</th>
                        <th>Fecha</th>
                        <th>Horario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event) { ?>
                        <tr id="item-<?= $event['id'] ?>">
                            <td><?= $event['nombre'] ?></td>
                            <td><?= $event['fecha'] ?></td>
                            <td><?= $event['horario'] ?></td>
                            <td>
                                <form method="post" action="<?= constant('ROOT_URL') ?>/evento/editar.php">
                                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                    <button type="editar">Editar</button>
                                </form>
                            </td>
                            <td><button class="darsebaja" data-id="<?= $event['id'] ?>">Eliminar</button></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php } ?>

    </main>

    <!-- cargar footer -->
    <?php require('../src/components/footer.component.php') ?>
</body>
<script src="<?= constant('ROOT_URL') ?>/public/js/deleteEvent.js"></script>

<?php
//checkea si hay errores
if (isset($_SESSION['creadosAlert'])) {
    echo "<script>
            Swal.fire({
                title: ' " . $_SESSION['creadosAlert']['title'] . "',
                text: '" . $_SESSION['creadosAlert']['text'] . "',
                icon: '" . $_SESSION['creadosAlert']['icon'] . "',
                confirmButtonText: 'Aceptar'
            });
          </script>";
    $_SESSION['creadosAlert'] = null;
    session_write_close();
}
$conexion->close();
?>

</html>