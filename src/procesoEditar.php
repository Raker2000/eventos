<?php
require_once('./config.php');
require_once('./utils/setAlertSess.php');

if (isset($_POST)) {
    session_start();
    $id = $_POST["event_id"];
    $nombre = isset($_POST['nombre']) ? (string) $_POST['nombre'] : '';
    $descripcion = isset($_POST['tipo']) ? (string) $_POST['descripcion'] : '';
    $fecha = isset($_POST['fecha']) ? (string) $_POST['fecha'] : '';
    $horario = isset($_POST['hora']) ? (string) $_POST['hora'] : '';
    $latitud = isset($_POST['latitud']) ? (string) $_POST['latitud'] : '';
    $longitud = isset($_POST['longitud']) ? (string) $_POST['longitud'] : '';
    $condicionToken = (string)$_POST['token'] == (string)$_SESSION['token'];

    if ($id && $nombre && $latitud && $longitud && $fecha && $horario && $descripcion && $condicionToken) {
        try {
            $conexion = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASS'), constant('DB_NAME'), constant('DB_PORT') ?? null);
            $sql = "UPDATE eventos SET nombre = ?, descripcion = ?, latitud = ?, longitud = ?, fecha = ?, horario = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('ssssssi', $nombre, $fecha, $latitud, $longitud, $horario, $descripcion, $id);
            $stmt->execute();
            $conexion->close();
            setAlertSess("creadosAlert", "Evento Editado Exitosamente!", "", "success");
            header('Location: ' . constant('ROOT_URL') . '/evento/creados.php');
        } catch (Error $e) {
        }
    } else {
        setAlertSess("creadosAlert", "Ocurrio un error", "Intente Nuevamente", "error");
        //header('Location: ' . constant('ROOT_URL') . '/evento/creados.php');
    }
}
