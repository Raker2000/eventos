<?php
require_once('./config.php');
require_once('./utils/verifyCaptcha.php');
require_once('./utils/setAlertSess.php');
if (isset($_POST)) {
    session_start();
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $tipo = $_POST['tipo'];

    $condicionToken = (string)$_POST['token'] == (string)$_SESSION['token'];

    $nombre = isset($_POST['nombre']) ? (string) $_POST['nombre'] : '';
    $fecha = isset($_POST['fecha']) ? (string) $_POST['fecha'] : '';
    $hora = isset($_POST['hora']) ? (string) $_POST['hora'] : '';
    $tipo = isset($_POST['tipo']) ? (string) $_POST['tipo'] : '';

    if ($nombre && $fecha && $hora && $tipo && $condicionToken && verifyCaptcha()) {
        try {
            $conexion = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASS'), constant('DB_NAME'), constant('DB_PORT') ?? null);
            $sql = "INSERT INTO eventos (nombre, fecha, horario, tipo, id_creador, inscriptos) VALUES (?, ?, ?, ?, ?, ?)";
            $userId = $_SESSION['id_usuario'];
            $stmt = $conexion->prepare($sql);
            $UNO = 1; //ESTO ES PORQUE EL CREADOR DEL EVENTO SE INSCRIBE AUTOMATICAMENTE
            $stmt->bind_param('ssssii', $nombre, $fecha, $hora, $tipo, $userId, $UNO);
            $stmt->execute();
            $eventId = $conexion->insert_id;
            $sql = "INSERT INTO usuarios_eventos (id_usuario, id_evento) VALUES (?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param('ss', $userId, $eventId);
            $stmt->execute();
            $conexion->close();
            setAlertSess('homeAlert', "Evento Creado Correctamente!", "", "success");
            header('Location: ' . constant('ROOT_URL') . '/');
        } catch (Exception $e) {
            setAlertSess('nuevoAlert', "Error en la base de datos", "Intenta nuevamente!", "error");
            header('Location: ' . constant('ROOT_URL') . '/evento/nuevo.php');
        }
    } else {
        setAlertSess('nuevoAlert', "Datos o Captcha Incorrectos", "Intenta nuevamente!", "error");
        header('Location: ' . constant('ROOT_URL') . '/evento/nuevo.php');
    }
}
