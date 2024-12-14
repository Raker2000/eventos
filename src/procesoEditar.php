<?php
require_once('./config.php');
require_once('./utils/setAlertSess.php');

if (isset($_POST)) {
    session_start();
    $id = $_POST["event_id"];
    $nombre = isset($_POST['nombre']) ? (string) $_POST['nombre'] : '';
    $fecha = isset($_POST['fecha']) ? (string) $_POST['fecha'] : '';
    $horario = isset($_POST['hora']) ? (string) $_POST['hora'] : '';
    $tipo = isset($_POST['tipo']) ? (string) $_POST['tipo'] : '';
    $condicionToken = (string)$_POST['token'] == (string)$_SESSION['token'];

    //validacion super mega profesional anti hackers
    if ($id && $nombre && $fecha && $horario && $tipo && $condicionToken) {
        try {
            $conexion = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASS'), constant('DB_NAME'), constant('DB_PORT') ?? null);
            $sql = "UPDATE eventos SET nombre = ?, fecha = ?, horario = ?, tipo = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('ssssi', $nombre, $fecha, $horario, $tipo, $id);
            $stmt->execute();
            $conexion->close();
            setAlertSess("creadosAlert", "Evento Editado Exitosamente!", "", "success");
            header('Location: ' . constant('ROOT_URL') . '/evento/creados.php');
        } catch (Error $e) {
        }
    } else {
        setAlertSess("creadosAlert", "Ocurrio un error", "Intente Nuevamente", "error");
        header('Location: ' . constant('ROOT_URL') . '/evento/creados.php');
    }
}
