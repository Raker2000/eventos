<?php
require_once('./config.php');
require_once('./utils/setAlertSess.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    try {
        $conexion = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASS'), constant('DB_NAME'), constant('DB_PORT') ?? null);
        $id = (int)$_POST['id'];

        if (is_numeric($id)) {
            $stmt = $conexion->prepare("DELETE FROM eventos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $stmt = $conexion->prepare("DELETE FROM usuarios_eventos WHERE id_evento = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                header('Content-Type: application/json');
                if ($stmt->affected_rows > 0) {
                    echo json_encode(["success" => true]);
                    setAlertSess("creadosAlert", "Evento Eliminado!", "", "success");
                } else {
                    echo json_encode(["success" => false, "error" => "No se encontró el item."]);
                    setAlertSess("creadosAlert", "Error al eliminar el evento", "El evento no fue encontrado en la base de datos, intenta nuevamente", "error");
                }
            }
            $stmt->close();
        } else {
            setAlertSess("creadosAlert", "Error al eliminar el evento", "Ocurrio un error interno, intenta nuevamente", "error");
            echo json_encode(["success" => false, "error" => "ID inválido."]);
        }
        $conexion->close();
    } catch (Error $e) {
        setAlertSess("creadosAlert", "Error en la base de datos", "Intenta Nuevamente", "error");
    }
}
