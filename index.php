<?php
//borrar la session (descomentar el codigo de abajo, recargar la web y volver a comentarlo)
//session_start();
//unset($_SESSION['userLogged']);

require_once('./src/config.php'); //importa la config
session_start();
if ($_SESSION['userLogged'] ?? false) {
    header('Location: ' . constant('ROOT_URL') . '/home.php');
    exit;
} else {
    header('Location: ' . constant('ROOT_URL') . '/login.php');
    exit;
}
