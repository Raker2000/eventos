<?php
require_once('./config.php');
require_once('./utils/verifyCaptcha.php');
require_once('./utils/verifyPass.php');
require_once('./utils/setAlertSess.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_POST)) {
    session_start();
    $usuario = $_POST['usuario'];
    $contra = $_POST['contraseña'];

    $condicionToken = (string)$_POST['token'] == (string)$_SESSION['token'];



    $user = verifyPass($usuario, $contra);
    //if ($condicionToken && $user && verifyCaptcha()) {
    if ($condicionToken && $user) {
        $_SESSION['userLogged'] = true; //guarda true en la session para saber que hay un user logueado
        $_SESSION['username'] = $usuario;
        $_SESSION['id_usuario'] = $user['id'];
        $_SESSION['rol'] = $user['rol'];
        //redirecciona al /home añadiendo un parametro ?login=success para indicar que el usuario acaba de iniciar sesión correctamente
        setAlertSess("homeAlert", "Bienvenido!", "Iniciaste Sesión Correctamente ", "success");
        header('Location: ' . constant('ROOT_URL') . '/home.php');
        session_write_close();
    } else {
        //redirecciona al /login con la alerta de error
        setAlertSess("loginAlert", "Datos o Captcha Incorrectos", "Revisa e intenta nuevamente", "error");
        header('Location: ' . constant('ROOT_URL') . '/login.php');
    }

    $stmt->close();
    $conexion->close();
}
