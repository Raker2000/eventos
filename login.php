<?php
//Se crea y agrega un token sencillo a la session.
require('./src/config.php');
require('./src/utils/protectRoute.php');
require('./src/utils/genToken.php');
session_start();
protectRoute('/home.php', false);
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
    <title>Iniciar sesion</title>
    <link rel="stylesheet" href="<?= constant('ROOT_URL'); ?>/public/css/login.css">
</head>

<body>
    <!-- cargar navbar -->
    <?php require('./src/components/navbar.component.php') ?>
    <section>
        <div class="contenedor">
            <div class="formulario">
                <form name="formulario" method="post" action="./src/procesoLogin.php">
                    <h2>Iniciar Sesion</h2>
                    <!-- Input oculto que envía el valor del token a la solicitud POST -->
                    <input type="hidden" name="token" value="<?= $token ?>">

                    <div class="input-contenedor">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="text" name="usuario" required>
                        <label for="#">Usuario</label>
                    </div>

                    <div class="input-contenedor">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" name="contraseña" required>
                        <label for="#">Contraseña</label>
                    </div>

                    <!-- hCaptcha widget -->
                     <?php
                    //<div class="h-captcha" data-sitekey="ca1cb826-038a-462c-afba-8f88601e6002"></div>?>

                    
                    <div class="button">
                        <input type="submit" value="Acceder" />
                    </div>

                </form>

                <div>
                    <div class="registrar">
                        <a href="<?= constant('ROOT_URL'); ?>/register.php">Registrarse</a>
                    </div>

                    <div class="registrar">
                        <a href="<?= constant('ROOT_URL'); ?>/register.php">Olvidé mi contraseña</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- cargar footer -->
    <?php require('./src/components/footer.component.php') ?>
</body>

<?php
//checkea si hay errores
if (isset($_SESSION['loginAlert'])) {
    echo "<script>
            Swal.fire({
                title: ' " . $_SESSION['loginAlert']['title'] . "',
                text: '" . $_SESSION['loginAlert']['text'] . "',
                icon: '" . $_SESSION['loginAlert']['icon'] . "',
                confirmButtonText: 'Aceptar'
            });
          </script>";
    $_SESSION['loginAlert'] = null;
}
?>

</html>