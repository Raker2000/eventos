<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once('./config.php');
require_once('./utils/verifyCaptcha.php');
require_once('./utils/setAlertSess.php');

if (isset($_POST)) {
    session_start();
    $usuario = $_POST['usuario'];
    $contra = $_POST['contraseña'];
    $email = $_POST['email'];

    $condicionToken = (string)$_POST['token'] == (string)$_SESSION['token'];

    try {
        $conexion = new mysqli(constant('DB_HOST'), constant('DB_USER'), constant('DB_PASS'), constant('DB_NAME'), constant('DB_PORT') ?? null);

        $sql = "SELECT 1 FROM usuarios WHERE nombre_usuario = ? LIMIT 1";
        $stmt = $conexion->prepare($sql);

        // Asignar parámetro y ejecutar
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $regexNumero = '/\d/';               // Verifica que tenga al menos un número
        $regexEspecial = '/[@\|\{\}\-\*\#\!\$\%\^\&\(\)\+\=]/';  // Verifica al menos un carácter especial

        // Verificar si es usuario unico, si la contraseña tiene mas de 7 caracteres, si tiene un numero, si tiene un caracter especial
        if ($resultado->num_rows == 0) {
            //if (strlen($contra) > 7 && preg_match($regexNumero, $contra) && preg_match($regexEspecial, $contra)) {
            if (strlen($contra) > 7) {
                if (verifyCaptcha()) {
                    $passwordHasheada = password_hash($contra, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO usuarios (nombre_usuario, email, contrasenia) VALUES (?, ?, ?)";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param('sss', $usuario, $email, $passwordHasheada);
                    $stmt->execute();
                    setAlertSess("loginAlert", "Registro Exitoso!", "Ingresa con tu usuario y contraseña", "success");
                    header('Location: ' . constant('ROOT_URL') . '/login.php');
                    session_write_close();
                } else {
                    setAlertSess("registerAlert", "Captcha Invalido", "Revisa e intenta nuevamente", "error");
                    throw new Error();
                }
            } else {
                setAlertSess("registerAlert", "Contraseña Invalida", "La contraseña debe tener al menos 8 caracteres, incluyendo al menos un número y un caracter especial", "error");
                throw new Error();
            }
        } else {
            setAlertSess("registerAlert", "El usuario ya existe", "Elegí otro nombre de usuario!", "error");
            throw new Error();
        }

        $stmt->close();
        $conexion->close();
    } catch (Error $e) {
        //redirecciona al la vista register.php
        header('Location: ' . constant('ROOT_URL') . '/register.php');
    }
}
