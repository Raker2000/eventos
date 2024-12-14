<?php
//Se crea y agrega un token sencillo a la session.
require('../src/config.php');
require('../src/utils/protectRoute.php');
require('../src/utils/genToken.php');
session_start();
protectRoute('/login.php');
$token = genToken();
$_SESSION['token'] = (string)$token;

if (isset($_POST) && isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];
    if (!empty($eventId)) {
        $conexion = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASS'), constant('DB_NAME'), constant('DB_PORT') ?? null);
        $sql = "SELECT * FROM eventos WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $eventId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $event = $result->fetch_assoc();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ba42bd5e6b.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="<?= constant('ROOT_URL'); ?>/public/assets/icono.ico" type="image/x-icon">
    <title>Editar evento</title>
    <link rel="stylesheet" href="<?= constant('ROOT_URL'); ?>/public/css/evento.css">
</head>

<body>
    <!-- cargar navbar -->
    <?php require('../src/components/navbar.component.php') ?>
    <section>
        <div class="contenedor">
            <div class="formulario">
                <form name="formulario" method="post" action="<?= constant('ROOT_URL') ?>/src/procesoEditar.php">
                    <h2>Editar Evento</h2>
                    <!-- Input oculto que envía el valor del token a la solicitud POST -->
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <input type="hidden" name="event_id" value="<?= $eventId ?>">
                    <div class="input-contenedor">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="text" name="nombre" required value="<?= $event['nombre'] ?? "" ?>">
                        <label for="#">Nombre</label>
                    </div>

                    <div class="input-contenedor">
                        <i class="bi bi-calendar" id="calendar-icon"></i>
                        <input class="fecha" type="date" name="fecha" required value="<?= $event['fecha'] ?? "" ?>">
                        <!-- <label for="#">Fecha</label> -->
                    </div>

                    <div class="input-contenedor">
                        <i class="fa-solid fa-clock"></i>
                        <input type="time" name="hora" required value="<?= $event['horario'] ?? "" ?>">
                        <!-- <label for="#">Hora</label> -->
                    </div>

                    <div class="input-contenedor">
                        <i class="fas fa-medal"></i>
                        <select name="tipo">
                            <option value="1" <?= (isset($event) && $event['tipo'] == "1") ? "selected" : "" ?>>Futbol</option>
                            <option value="2" <?= (isset($event) && $event['tipo'] == "2") ? "selected" : "" ?>>Basquet</option>
                            <option value="3" <?= (isset($event) && $event['tipo'] == "3") ? "selected" : "" ?>>Handball</option>
                        </select>
                    </div>

                    <div class="button">
                        <input type="submit" value="Editar" />
                    </div>

                </form>
            </div>
        </div>
    </section>
    <!-- cargar footer -->
    <?php require('../src/components/footer.component.php') ?>
</body>
<?php
//checkea si hay errores
if (isset($_SESSION['nuevoAlert'])) {
    echo "<script>
            Swal.fire({
                title: ' " . $_SESSION['nuevoAlert']['title'] . "',
                text: '" . $_SESSION['nuevoAlert']['text'] . "',
                icon: '" . $_SESSION['nuevoAlert']['icon'] . "',
                confirmButtonText: 'Aceptar'
            });
          </script>";
    $_SESSION['nuevoAlert'] = null;
    session_write_close();
}
$conexion->close();
?>

</html>