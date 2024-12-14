<?php
//Se crea y agrega un token sencillo a la session.
require('src/config.php');
require('src/utils/protectRoute.php');
require('src/utils/genToken.php');

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
    <title>Tu usuario</title>
    <link rel="stylesheet" href="<?= constant('ROOT_URL'); ?>/public/css/user.css">
</head>

<body>
    <!-- cargar navbar -->
    <?php require('src/components/navbar.component.php') ?>
    <section>
        <div class="contenedor">
            <div class="formulario">
                <form name="formulario" method="post" action="src/procesoLoginInterno.php">
                    <h2>Tu usuario</h2>
                    <h3>Para modificar los datos de tu usuario debes introducir tus credenciales</h3>
                    <!-- Input oculto que envía el valor del token a la solicitud POST -->
                    <input type="hidden" name="token" value="<?= $token ?>">

                    <div class="input-contenedor">
                        <h3>Nombre de usuario</h3>
                        <i class="fa-solid fa-envelope"></i>
                        <input type="text" name="usuario" required>
                        <label for="#"></label>
                    </div>

                    <div class="input-contenedor">
                        <h3>Contraseña</h3>
                        <i class="fa-solid fa-envelope"></i>
                        <input type="password" name="contrasenia" required>
                        <label for="#"></label>
                    </div>
                    
                    <div class="h-captcha" data-sitekey="ca1cb826-038a-462c-afba-8f88601e6002"></div>

                    <div class="button">
                        <input type="submit" value="Ingresar" />
                    </div>
                    
                </form>
            </div>
                <div class="button">
                    <a href="<?= constant('ROOT_URL') ?>/src/utils/logout.php">Cerrar sesion</a>
                </div>
        </div>
    </section>
    <!-- cargar footer -->
    <?php require('src/components/footer.component.php') ?>
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