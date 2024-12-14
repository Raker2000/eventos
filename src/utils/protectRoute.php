<?php

function protectRoute(string $RedirectRoute, bool $notLogged = true)
{
    //para que funcione debe estar inciada la session.
    //$route debe ser del tipo: "/ruta.php" ej: "/login.php"
    //si $notLogged es true proteje la ruta de usuarios NO logueados (por ejemplo proteje el home de usuarios No autenticados)
    if ($notLogged) {
        if (!isset($_SESSION['userLogged']) || $_SESSION['userLogged'] !== true) {
            header('Location: ' . constant('ROOT_URL') . $RedirectRoute);
            exit;
        }
    } else {
        //si $notLogged es false proteje la ruta de usuarios LOGUEADOS (por ejemplo proteje el login de usuarios ya autenticados)
        if ($_SESSION['userLogged'] ?? false) {
            header('Location: ' . constant('ROOT_URL') . $RedirectRoute);
            exit;
        }
    }
}
