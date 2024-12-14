<?php
//Se crea y agrega un token sencillo a la session.
require('../src/config.php');
require('../src/utils/protectRoute.php');
require('../src/utils/genToken.php');

session_start();
protectRoute('/login.php');
$token = genToken();
$_SESSION['token'] = (string)$token;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/ba42bd5e6b.js" crossorigin="anonymous"></script>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="<?= constant('ROOT_URL'); ?>/public/assets/icono.ico" type="image/x-icon">
    <title>Nuevo evento</title>
    <link rel="stylesheet" href="<?= constant('ROOT_URL'); ?>/public/css/evento.css">
</head>

<body>
    <!-- cargar navbar -->
    <?php require('../src/components/navbar.component.php') ?>
    <section>
        <div class="contenedor">
            <div class="formulario">
                <form name="formulario" method="post" action="../src/procesoNuevo.php">
                    <?php
                    //Si existe un error (se fija en la url)..
                    if (isset($_GET['error']) && $_GET['error'] === 'true') { ?>
                        <div class="error-contenedor">
                            <p>Error al crear el evento, intenta nuevamente</p>
                        </div>
                    <?php } ?>
                    <h2>Crear Nuevo Evento</h2>
                    <!-- Input oculto que envía el valor del token a la solicitud POST -->
                    <input type="hidden" name="token" value="<?= $token ?>">

                    <div class="input-contenedor">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="text" name="nombre" required>
                        <label for="#">Nombre</label>
                    </div>

                    <div class="input-contenedor">
                        <i class="bi bi-calendar" id="calendar-icon"></i>
                        <input class="fecha" type="date" name="fecha" required>
                        <!-- <label for="#">Fecha</label> -->
                    </div>

                    <div class="input-contenedor">
                        <i class="fa-solid fa-clock"></i>
                        <input type="time" name="hora" required>
                        <!-- <label for="#">Hora</label> -->
                    </div>

                    <div class="input-contenedor">
                        <i class="fas fa-medal"></i>
                        <select name="tipo">
                            <option value="Futbol">Futbol</option>
                            <option value="Basquet">Basquet</option>
                            <option value="Handball">Handball</option>
                        </select>
                        <!-- <label for="#">Disciplina</label> -->
                    </div>

                    <!-- hCaptcha widget -->
                    <div class="h-captcha" data-sitekey="ca1cb826-038a-462c-afba-8f88601e6002"></div>

                    <div class="button">
                        <input type="submit" value="Crear" />
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
?>

</html>