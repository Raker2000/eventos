<?php
require_once('./config.php');
require_once('./utils/setAlertSess.php');

if (isset($_POST)) {
    session_start();
    $id = $_SESSION["id_usuario"];
    $nombre = isset($_POST['usuario']) ? (string) $_POST['usuario'] : '';
    $fecha = isset($_POST['fecha']) ? (string) $_POST['fecha'] : '';
    $contrasenia = isset($_POST['contrasenia']) ? (string) $_POST['contrasenia'] : '';
    $contrasenia_rep = isset($_POST['contrasenia_rep']) ? (string) $_POST['contrasenia_rep'] : '';
    $condicionToken = (string)$_POST['token'] == (string)$_SESSION['token'];
    
    if ($id && $nombre && $fecha && $condicionToken && $contrasenia==$contrasenia_rep) {
        try {
            $conexion = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASS'), constant('DB_NAME'), constant('DB_PORT') ?? null);
            $sql = "UPDATE usuarios SET nombre_usuario = ?, contrasenia = ?, fecha_nacimiento = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $passwordHasheada = password_hash($contrasenia, PASSWORD_DEFAULT);
            $stmt->bind_param('sssi', $nombre, $passwordHasheada, $fecha, $id);
            $stmt->execute();
            $conexion->close();
            setAlertSess("creadosAlert", "Usuario editado exitosamente!", "", "success");
            header('Location: ' . constant('ROOT_URL') . '/user.php');
        } catch (Error $e) {
        }
    } else {
        setAlertSess("creadosAlert", "Ocurrio un error", "Intente Nuevamente", "error");
        header('Location: ' . constant('ROOT_URL') . '/modifyUser.php');
    }
}
