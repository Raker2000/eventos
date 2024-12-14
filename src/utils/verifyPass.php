<?php
require_once('./config.php');
function verifyPass($usuario, $contra)
{
    $conexion = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASS'), constant('DB_NAME'), constant('DB_PORT') ?? null);
    $sql = "SELECT id, contrasenia FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        // Obtener el registro como un array asociativo solo una vez
        $user = $resultado->fetch_assoc();
        $res = password_verify($contra, $user['contrasenia']);
        $conexion->close();
        var_dump($res);
        if ($res) {
            return $user;
        } else {
            return false;
        }
    } else {
        $conexion->close();
        return false;
    }
}
