<?php
function verifyCaptcha(): bool
{
    // Clave secreta de hCaptcha
    $secret_key = 'ES_994d2fc39e2f47699bff770ac887b4c0';

    // Verifica si hCaptcha fue completado
    if (isset($_POST['h-captcha-response'])) {
        $token = $_POST['h-captcha-response'];

        // URL de la API de verificación de hCaptcha
        $url = 'https://hcaptcha.com/siteverify';

        // Datos para la solicitud
        $data = [
            'secret' => $secret_key,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        // Inicia cURL
        $ch = curl_init($url);

        // Configuración de cURL
        curl_setopt($ch, CURLOPT_POST, true); //establece el metodo POST
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //establece que el body de la solicitud tendrá el contenido de $data

        // Envía la solicitud y guarda la respuesta
        $response = curl_exec($ch);
        curl_close($ch);

        // Decodifica la respuesta de JSON a un array
        $responseData = json_decode($response, true);

        // Verifica si el captcha es válido
        if ($responseData['success']) {
            // hCaptcha válido
            return true;
        } else {
            // hCaptcha no válido
            return false;
        }
    } else {
        // hCaptcha no completado
        return false;
    }
}
