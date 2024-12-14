<?php
require_once('config.php');
require_once('utils/setAlertSess.php');
function inscribirse($id_usuario, $id_evento)
{
    $conexion = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASS'), constant('DB_NAME'), constant('DB_PORT') ?? null);

    // Verificar conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    $sql = "INSERT INTO usuarios_eventos (id_usuario, id_evento) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ii', $id_usuario, $id_evento);


    session_start();
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Inscripción añadida."]);
        $stmt = $conexion->prepare("UPDATE eventos SET inscriptos = inscriptos + 1 WHERE id = ?");
        $stmt->bind_param("i", $id_evento);
        $stmt->execute();
        setAlertSess("homeAlert", "Inscripción añadida correctamente!", "", "success");
        die();
    } else {
        echo json_encode(["status" => "error", "message" => "Error al inscribirse."]);
        setAlertSess("homeAlert", "Error al inscribirse", "Intenta Nuevamente", "error");
        die();
    }
    session_write_close();

    $stmt->close();
    $conexion->close();
}

// Verificar si la solicitud es una llamada AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario']) && isset($_POST['id_evento'])) {
    header('Content-Type: application/json');
    inscribirse($_POST['id_usuario'], $_POST['id_evento']);
    exit;
}
